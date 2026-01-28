<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoverArticle extends Model
{
    use SoftDeletes;

    /**
     * Roles allowed to publish and activate covers.
     */
    public const ALLOWED_ROLES = ['editor_chief', 'administrator', 'moderator'];

    protected $table = 'cover_articles';

    protected $fillable = [
        'parent_id',
        'name',
        'main_articles',
        'mid_articles',
        'latest_articles',
        'scheduled_at',
        'ends_at',
        'status',
        'visibility',
        'is_active',
        'activated_by',
        'activated_at',
        'notes',
        'created_by',
        'edited_by',
        'published_at',
    ];

    protected $casts = [
        'main_articles' => 'array',
        'mid_articles' => 'array',
        'latest_articles' => 'array',
        'scheduled_at' => 'datetime',
        'ends_at' => 'datetime',
        'published_at' => 'datetime',
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function activator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'activated_by');
    }

    /**
     * Get the parent cover (if this is a pending version).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get all pending versions of this cover (multiple change requests).
     * Newest first.
     */
    public function pendingVersions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderByDesc('created_at');
    }

    // -------------------------------------------------------------------------
    // Accessors - Ordered Articles
    // -------------------------------------------------------------------------

    /**
     * Get main articles in the order defined in main_articles.
     */
    public function getOrderedMainArticlesAttribute()
    {
        return $this->orderedArticlesFromIds($this->main_articles ?? []);
    }

    /**
     * Get mid articles in the order defined in mid_articles.
     */
    public function getOrderedMidArticlesAttribute()
    {
        return $this->orderedArticlesFromIds($this->mid_articles ?? []);
    }

    /**
     * Get latest articles in the order defined in latest_articles.
     */
    public function getOrderedLatestArticlesAttribute()
    {
        return $this->orderedArticlesFromIds($this->latest_articles ?? []);
    }

    protected function orderedArticlesFromIds(array $ids): \Illuminate\Support\Collection
    {
        if (empty($ids)) {
            return collect();
        }

        $items = Article::query()
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return collect($ids)
            ->map(fn ($id) => $items->get($id))
            ->filter()
            ->values();
    }

    // -------------------------------------------------------------------------
    // Accessors - Display Names (Spanish for views)
    // -------------------------------------------------------------------------

    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Borrador',
            'pending_review' => 'Pendiente de revisión',
            'published' => 'Publicada',
            'archived' => 'Archivada',
            default => $this->status ?? '—',
        };
    }

    public function getVisibilityNameAttribute(): string
    {
        return match ($this->visibility) {
            'public' => 'Público',
            'private' => 'Privado',
            default => $this->visibility ?? '—',
        };
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Only main covers (not pending versions).
     */
    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Only pending versions.
     */
    public function scopePendingVersions($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // -------------------------------------------------------------------------
    // Activation Logic
    // -------------------------------------------------------------------------

    /**
     * Check if the given user can activate covers.
     */
    public static function userCanActivate(User $user): bool
    {
        return in_array($user->rol, self::ALLOWED_ROLES, true);
    }

    /**
     * Activate this cover. If not published and user has permission, publish it first.
     * Also sets visibility to public. Deactivates all other covers.
     *
     * @return bool True if activated successfully, false if user lacks permission.
     */
    public function activate(User $user): bool
    {
        // Check if user has permission to activate
        if (! self::userCanActivate($user)) {
            return false;
        }

        // If not published, publish it first
        if ($this->status !== 'published') {
            $this->status = 'published';
            $this->published_at = now();
        }

        // Deactivate all other covers
        static::where('is_active', true)
            ->where('id', '!=', $this->id)
            ->update(['is_active' => false]);

        // Activate this cover (also set visibility to public)
        return $this->update([
            'is_active' => true,
            'visibility' => 'public',
            'activated_by' => $user->id,
            'activated_at' => now(),
            'edited_by' => $user->id,
        ]);
    }

    /**
     * Deactivate this cover.
     */
    public function deactivate(): bool
    {
        return $this->update([
            'is_active' => false,
            'activated_by' => null,
            'activated_at' => null,
        ]);
    }

    /**
     * Get the currently active cover.
     */
    public static function getActive(): ?self
    {
        return static::active()->first();
    }

    // -------------------------------------------------------------------------
    // Pending Version Logic
    // -------------------------------------------------------------------------

    /**
     * Check if this cover is a pending version of another cover.
     */
    public function isPendingVersion(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Check if this cover has any pending versions.
     */
    public function hasPendingVersions(): bool
    {
        return $this->pendingVersions()->exists();
    }

    /**
     * Merge a specific pending version into this (parent) cover and delete that pending version.
     * Returns true on success, false if user lacks permission or pending is not a child.
     */
    public function mergePendingVersion(self $pendingVersion, User $user): bool
    {
        if (! self::userCanActivate($user)) {
            return false;
        }

        if ($pendingVersion->parent_id !== $this->id) {
            return false;
        }

        $this->update([
            'main_articles' => $pendingVersion->main_articles,
            'mid_articles' => $pendingVersion->mid_articles,
            'latest_articles' => $pendingVersion->latest_articles,
            'notes' => $pendingVersion->notes,
            'scheduled_at' => $pendingVersion->scheduled_at,
            'ends_at' => $pendingVersion->ends_at,
            'visibility' => $pendingVersion->visibility,
            'edited_by' => $user->id,
        ]);

        $pendingVersion->delete();

        return true;
    }

    /**
     * Create a new pending version of this cover. Multiple pending versions are allowed.
     */
    public function createPendingVersion(array $data, User $creator): self
    {
        $data['parent_id'] = $this->id;
        $data['created_by'] = $creator->id;
        $data['edited_by'] = $creator->id;

        return static::create($data);
    }
}

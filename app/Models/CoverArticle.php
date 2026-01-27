<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoverArticle extends Model
{
    use SoftDeletes;

    protected $table = 'cover_articles';

    protected $fillable = [
        'name',
        'main_articles',
        'mid_articles',
        'latest_articles',
        'scheduled_at',
        'ends_at',
        'status',
        'visibility',
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
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    /**
     * Artículos principales en el orden definido en main_articles.
     */
    public function getOrderedMainArticlesAttribute()
    {
        return $this->orderedArticlesFromIds($this->main_articles ?? []);
    }

    /**
     * Artículos medios en el orden definido en mid_articles.
     */
    public function getOrderedMidArticlesAttribute()
    {
        return $this->orderedArticlesFromIds($this->mid_articles ?? []);
    }

    /**
     * Artículos de "últimos" en el orden definido en latest_articles.
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'redirect_url',
        'status',
        'visibility',
        'user_id',
        'advertiser_id',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    /**
     * Route model binding uses slug instead of id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function getStatusNameAttribute(): string
    {
        $statuses = [
            'draft' => 'Borrador',
            'review' => 'En Revisión',
            'published' => 'Publicado',
            'denied' => 'Rechazado',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * True si algún artículo (no eliminado) usa este anuncio en su contenido (bloque tipo "ad").
     */
    public function isInUse(): bool
    {
        $adId = $this->id;

        return Article::query()
            ->whereNotNull('content')
            ->get()
            ->contains(function (Article $article) use ($adId) {
                $blocks = $article->content;
                if (! is_array($blocks)) {
                    return false;
                }
                foreach ($blocks as $block) {
                    if (($block['type'] ?? '') === 'ad' && (int) ($block['ad_id'] ?? 0) === $adId) {
                        return true;
                    }
                }

                return false;
            });
    }
}

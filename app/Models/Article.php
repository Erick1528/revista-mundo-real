<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'attribution',
        'summary',
        'slug',
        'image_path',
        'image_credits',
        'image_alt_text',
        'image_caption',
        'visibility',
        'status',
        'published_at',
        'section',
        'tags',
        'related_articles',
        'content',
        'view_count',
        'reading_time',
        'meta_description',
        'user_id'
    ];

    protected $casts = [
        'tags' => 'array',
        'related_articles' => 'array',
        'content' => 'array',
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'reading_time' => 'integer',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getSectionNameAttribute()
    {
        $sections = [
            'destinations' => 'Destinos',
            'inspiring_stories' => 'Historias que Inspiran',
            'social_events' => 'Eventos Sociales',
            'health_wellness' => 'Salud y Bienestar',
            'gastronomy' => 'Gastronomía con Identidad',
            'living_culture' => 'Cultura Viva'
        ];

        return $sections[$this->section] ?? $this->section;
    }

    public function getStatusNameAttribute()
    {
        $statuses = [
            'draft' => 'Borrador',
            'review' => 'En Revisión',
            'published' => 'Publicado',
            'denied' => 'Rechazado'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getVisibilityNameAttribute()
    {
        $visibilities = [
            'public' => 'Público',
            'private' => 'Privado'
        ];

        return $visibilities[$this->visibility] ?? $this->visibility;
    }
}

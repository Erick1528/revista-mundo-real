<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Elimina del disco la imagen principal del artículo (solo si está en storage público).
     * Útil antes de forceDelete() para no dejar archivos huérfanos.
     */
    public function deleteMainImageFromStorage(): void
    {
        if (! $this->image_path) {
            return;
        }
        $this->deleteStorageFileByUrl($this->image_path);
    }

    /**
     * Elimina del disco todas las imágenes referenciadas en $this->content.
     * Se usa al eliminar permanentemente desde la papelera (force delete), para no dejar archivos huérfanos.
     *
     * Bloques del Content Editor que tienen imágenes en storage:
     * - image: una imagen en block['url']
     * - gallery: varias imágenes en block['images'] (cada elemento puede ser string URL o array con 'url')
     * - review: foto por reseña en block['reviews'][]['photo']
     * Solo se borran URLs que empiezan por /storage/ (nuestro disco público).
     */
    public function deleteContentImagesFromStorage(): void
    {
        $blocks = $this->content;
        if (! is_array($blocks)) {
            return;
        }

        foreach ($blocks as $block) {
            $type = $block['type'] ?? null;

            // Bloque imagen: una sola URL
            if ($type === 'image' && ! empty($block['url'])) {
                $this->deleteStorageFileByUrl($block['url']);
            }

            // Bloque galería: array de imágenes (guardadas como string o como array con 'url', 'alt_text', 'credits')
            if ($type === 'gallery' && ! empty($block['images']) && is_array($block['images'])) {
                foreach ($block['images'] as $item) {
                    $url = is_string($item) ? $item : ($item['url'] ?? null);
                    if ($url !== null && $url !== '') {
                        $this->deleteStorageFileByUrl($url);
                    }
                }
            }

            // Bloque reseñas: cada reseña puede tener 'photo'
            if ($type === 'review' && ! empty($block['reviews']) && is_array($block['reviews'])) {
                foreach ($block['reviews'] as $review) {
                    if (! empty($review['photo'])) {
                        $this->deleteStorageFileByUrl($review['photo']);
                    }
                }
            }
        }
    }

    /**
     * Borra un archivo del disco público si la URL es de nuestro storage (/storage/...).
     */
    protected function deleteStorageFileByUrl(string $url): void
    {
        if ($url === '' || ! str_starts_with($url, '/storage/')) {
            return;
        }
        $path = str_replace('/storage/', '', $url);
        if ($path !== '') {
            try {
                Storage::disk('public')->delete($path);
            } catch (\Throwable) {
                // Ignorar errores (archivo ya borrado, permisos, etc.)
            }
        }
    }
}

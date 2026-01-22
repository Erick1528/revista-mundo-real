<?php

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ShowArticle extends Component
{

    public $article;
    public $section;
    public $fecha;
    public $authorName;
    public $relatedArticles;

    public function mount()
    {
        $allowedRoles = ['editor_chief', 'moderator', 'administrator'];
        $user = Auth::user();
        if ($user) {
            if (!in_array($user->rol, $allowedRoles) && $this->article->status !== 'published') {
                abort(404, 'Pagina no encontrada');
            }
        } else {
            if ($this->article->status !== 'published' || $this->article->visibility !== 'public') {
                abort(404, 'Pagina no encontrada');
            }
        }

        if ($this->article->section == 'destinations') {
            $this->section = 'Destinos';
        } elseif ($this->article->section == 'inspiring_stories') {
            $this->section = 'Historias que inspiran';
        } elseif ($this->article->section == 'social_events') {
            $this->section = 'Eventos sociales';
        } elseif ($this->article->section == 'health_wellness') {
            $this->section = 'Salud y Bienestar';
        } elseif ($this->article->section == 'gastronomy') {
            $this->section = 'Gastronomía';
        } elseif ($this->article->section == 'living_culture') {
            $this->section = 'Cultura viva';
        } else {
            $this->section = 'Sección desconocida';
        }

        if (!empty(trim($this->article->attribution))) {
            $this->authorName = $this->article->attribution;
        } else {
            $this->authorName = $this->article->user->name ?? 'Autor desconocido';
        }

        $meses = [
            'ene' => 'Ene',
            'feb' => 'Feb',
            'mar' => 'Mar',
            'abr' => 'Abr',
            'may' => 'May',
            'jun' => 'Jun',
            'jul' => 'Jul',
            'ago' => 'Ago',
            'sep' => 'Sep',
            'oct' => 'Oct',
            'nov' => 'Nov',
            'dic' => 'Dic',
        ];
        $fecha = $this->article->created_at->locale('es')->translatedFormat('j M Y');
        $fecha = str_replace('.', '', $fecha);
        $fecha = preg_replace_callback(
            '/\b([a-z]{3})\b/i',
            function ($matches) use ($meses) {
                $mes = strtolower($matches[1]);
                return $meses[$mes] ?? $matches[1];
            },
            $fecha,
        );

        $this->fecha = $fecha;

        // Obtener artículos relacionados
        $this->relatedArticles = $this->getRelatedArticles();
    }

    private function getRelatedArticles()
    {
        // Si hay artículos relacionados en el array, usarlos
        if (!empty($this->article->related_articles) && is_array($this->article->related_articles)) {
            $relatedIds = $this->article->related_articles;
            $related = Article::whereIn('id', $relatedIds)
                ->where('status', 'published')
                ->where('visibility', 'public')
                ->where('id', '!=', $this->article->id)
                ->orderBy('published_at', 'desc')
                ->limit(5)
                ->get();

            // Si encontramos los artículos del array, los retornamos
            if ($related->count() > 0) {
                return $related->take(5);
            }
        }

        // Si no hay artículos relacionados o no se encontraron, generar según sección, fecha, visibilidad y status
        $related = Article::where('section', $this->article->section)
            ->where('status', 'published')
            ->where('visibility', 'public')
            ->where('id', '!=', $this->article->id)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return $related;
    }

    public function render()
    {
        return view('livewire.show-article');
    }
}

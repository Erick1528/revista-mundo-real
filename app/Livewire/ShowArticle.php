<?php

namespace App\Livewire;

use App\Models\Article;
use App\Notifications\ArticleNotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowArticle extends Component
{

    public $article;
    public $section;
    public $fecha;
    public $authorName;
    public $relatedArticles;

    /** Estado seleccionado para el formulario de cambio (solo para roles con permiso). */
    public string $newStatus = '';

    public function mount()
    {
        $allowedRoles = ['editor_chief', 'moderator', 'administrator'];
        $user = Auth::user();

        if (!$user) {
            // No logueado: solo artículos publicados y públicos
            if ($this->article->status !== 'published' || $this->article->visibility !== 'public') {
                abort(404, 'Pagina no encontrada');
            }
        } else {
            // Logueado: puede ver si está publicado, si tiene rol permitido o si es el autor
            $isPublished = $this->article->status === 'published';
            $hasRole = in_array($user->rol, $allowedRoles, true);
            $isAuthor = (int) $this->article->user_id === (int) $user->id;

            if (!$isPublished && !$hasRole && !$isAuthor) {
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

        $this->newStatus = $this->article->status;
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

    public function canChangeStatus(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        return in_array($user->rol, ['editor_chief', 'moderator', 'administrator'], true);
    }

    public static function getAllowedStatuses(): array
    {
        return [
            'draft' => 'Borrador',
            'review' => 'En Revisión',
            'published' => 'Publicado',
            'denied' => 'Rechazado',
        ];
    }

    public function updateStatusFromSelect(): void
    {
        $this->updateStatus($this->newStatus);
    }

    public function updateStatus(string $newStatus): void
    {
        if (!$this->canChangeStatus()) {
            session()->flash('error', 'No tienes permiso para cambiar el estado del artículo.');
            $this->redirect(route('dashboard'), navigate: true);
            return;
        }
        $allowed = array_keys(self::getAllowedStatuses());
        if (!in_array($newStatus, $allowed, true)) {
            session()->flash('error', 'No se pudo actualizar el estado.');
            $this->redirect(route('dashboard'), navigate: true);
            return;
        }
        try {
            $this->article->update(['status' => $newStatus]);
            if ($newStatus === 'published' && !$this->article->published_at) {
                $this->article->update(['published_at' => now()]);
            }
            $this->article->refresh();

            if ($newStatus === 'published') {
                ArticleNotificationService::notifyAuthorArticlePublished($this->article);
            }
            if ($newStatus === 'denied') {
                ArticleNotificationService::notifyAuthorArticleDenied($this->article);
            }

            session()->flash('message', 'El estado del artículo se actualizó correctamente.');
        } catch (\Throwable $e) {
            session()->flash('error', 'No se pudo actualizar el estado.');
        }
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.show-article');
    }
}

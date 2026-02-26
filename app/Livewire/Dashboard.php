<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\CoverArticle;
use App\Notifications\ArticleNotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'vendor.pagination.revista-livewire';
    }

    public $statusFilter = '';
    public $sectionFilter = '';
    public $visibilityFilter = '';
    public $search = '';

    public bool $showDeleteModal = false;
    public ?int $selectedArticleId = null;
    public string $selectedArticleTitle = '';
    /** Se establece al abrir el modal; no se limpia al cerrar para evitar parpadeo del contenido. */
    public bool $deleteModalBlockedByCover = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSectionFilter()
    {
        $this->resetPage();
    }

    public function updatingVisibilityFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->statusFilter = '';
        $this->sectionFilter = '';
        $this->visibilityFilter = '';
        $this->search = '';
        $this->resetPage();
    }

    public function showInDevelopment($action)
    {
        $this->dispatch('openDevelopModal', action: $action);
    }

    public function getFilteredArticles()
    {

        $user = Auth::user();
        $query = Article::with('user')
            ->orderBy('updated_at', 'desc');

        // Solo filtra por usuario si NO tiene uno de los roles especiales
        if (!in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            $query->where('user_id', $user->id);
        }

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                    ->orWhere('subtitle', 'like', $searchTerm)
                    ->orWhereHas('user', function ($qUser) use ($searchTerm) {
                        $qUser->where('name', 'like', $searchTerm);
                    });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->sectionFilter) {
            $query->where('section', $this->sectionFilter);
        }

        if ($this->visibilityFilter) {
            $query->where('visibility', $this->visibilityFilter);
        }

        return $query->paginate(10);
    }

    public function openDeleteModal(int $articleId): void
    {
        $article = Article::find($articleId);
        if (! $article) {
            return;
        }
        $user = Auth::user();
        if ($article->user_id !== $user->id && ! in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            session()->flash('error', 'No tienes permisos para eliminar este artículo.');

            return;
        }
        $this->selectedArticleId = $articleId;
        $this->selectedArticleTitle = $article->title;
        $this->deleteModalBlockedByCover = $this->isArticleInActiveCover($article);
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->selectedArticleId = null;
        $this->selectedArticleTitle = '';
    }

    public function confirmDeleteArticle(): void
    {
        if (! $this->selectedArticleId) {
            $this->closeDeleteModal();

            return;
        }
        $article = Article::find($this->selectedArticleId);
        $user = Auth::user();
        if (! $article) {
            session()->flash('error', 'Artículo no encontrado.');
            $this->closeDeleteModal();

            return;
        }
        if ($article->user_id !== $user->id && ! in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            session()->flash('error', 'No tienes permisos para eliminar este artículo.');
            $this->closeDeleteModal();

            return;
        }
        if ($this->isArticleInActiveCover($article)) {
            session()->flash('error', 'No se puede mover a la papelera: el artículo está en la portada activa.');
            $this->closeDeleteModal();

            return;
        }
        ArticleNotificationService::notifyAuthorArticleDeleted($article);
        $article->delete();
        session()->flash('message', 'Artículo movido a la papelera. Puedes restaurarlo desde Papelera.');
        $this->closeDeleteModal();
    }

    /**
     * Comprueba si el artículo está en alguna sección de la portada activa.
     */
    public function isArticleInActiveCover(Article $article): bool
    {
        $cover = CoverArticle::getActive();
        if (! $cover) {
            return false;
        }
        $ids = array_merge(
            $cover->main_articles ?? [],
            $cover->mid_articles ?? [],
            $cover->latest_articles ?? []
        );

        return in_array((int) $article->id, array_map('intval', $ids), true);
    }

    public function canDeleteArticle(Article $article): bool
    {
        $user = Auth::user();

        return $article->user_id === $user->id || in_array($user->rol, ['editor_chief', 'moderator', 'administrator']);
    }

    /**
     * True si el artículo actualmente seleccionado en el modal está en la portada activa.
     */
    public function getSelectedArticleInActiveCoverProperty(): bool
    {
        if (! $this->selectedArticleId) {
            return false;
        }
        $article = Article::find($this->selectedArticleId);

        return $article ? $this->isArticleInActiveCover($article) : false;
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'articles' => $this->getFilteredArticles()
        ]);
    }
}

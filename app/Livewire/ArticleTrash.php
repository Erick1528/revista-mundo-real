<?php

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleTrash extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'vendor.pagination.revista-livewire';
    }

    public bool $showForceDeleteModal = false;
    public ?int $selectedArticleId = null;
    public string $selectedArticleTitle = '';

    public function openForceDeleteModal(int $articleId): void
    {
        $article = Article::onlyTrashed()->find($articleId);
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
        $this->showForceDeleteModal = true;
    }

    public function closeForceDeleteModal(): void
    {
        $this->showForceDeleteModal = false;
        $this->selectedArticleId = null;
        $this->selectedArticleTitle = '';
    }

    public function restoreArticle(int $articleId): void
    {
        $article = Article::onlyTrashed()->find($articleId);
        if (! $article) {
            session()->flash('error', 'Artículo no encontrado.');

            return;
        }
        $user = Auth::user();
        if ($article->user_id !== $user->id && ! in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            session()->flash('error', 'No tienes permisos para restaurar este artículo.');

            return;
        }
        $article->restore();
        session()->flash('message', 'Artículo restaurado correctamente.');
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function confirmForceDeleteArticle(): void
    {
        if (! $this->selectedArticleId) {
            $this->closeForceDeleteModal();

            return;
        }
        $article = Article::onlyTrashed()->find($this->selectedArticleId);
        $user = Auth::user();
        if (! $article) {
            session()->flash('error', 'Artículo no encontrado.');
            $this->closeForceDeleteModal();

            return;
        }
        if ($article->user_id !== $user->id && ! in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            session()->flash('error', 'No tienes permisos para eliminar este artículo.');
            $this->closeForceDeleteModal();

            return;
        }
        $article->deleteMainImageFromStorage();
        $article->deleteContentImagesFromStorage();
        $article->forceDelete();
        session()->flash('message', 'Artículo eliminado permanentemente.');
        $this->closeForceDeleteModal();
    }

    protected function getTrashedArticles()
    {
        $user = Auth::user();
        $query = Article::onlyTrashed()
            ->with('user')
            ->orderByDesc('deleted_at');

        if (! in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            $query->where('user_id', $user->id);
        }

        return $query->paginate(10);
    }

    /** Si el usuario es editor (ve todos los artículos eliminados). */
    public function getCanSeeAllTrashedProperty(): bool
    {
        $user = Auth::user();

        return in_array($user->rol, ['editor_chief', 'moderator', 'administrator']);
    }

    public function render()
    {
        return view('livewire.article-trash', [
            'articles' => $this->getTrashedArticles(),
            'canSeeAllTrashed' => $this->canSeeAllTrashed,
        ]);
    }
}

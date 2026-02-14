<?php

namespace App\Livewire;

use App\Models\CoverArticle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CoverList extends Component
{
    public string $search = '';

    public string $statusFilter = '';

    public string $visibilityFilter = '';

    public string $activeFilter = '';

    public bool $showDeleteModal = false;

    public ?int $selectedCoverId = null;

    public string $selectedCoverName = '';

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->visibilityFilter = '';
        $this->activeFilter = '';
    }

    protected function getFilteredCovers()
    {
        $userId = Auth::id();

        $query = CoverArticle::query()
            ->main()
            ->with(['creator', 'editor', 'activator', 'pendingVersions.editor'])
            ->where(function ($q) use ($userId) {
                $q->where('is_active', true)
                    ->orWhere('created_by', $userId);
            })
            ->orderByDesc('is_active')
            ->latest('updated_at');

        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->visibilityFilter !== '') {
            $query->where('visibility', $this->visibilityFilter);
        }

        if ($this->activeFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->activeFilter === 'inactive') {
            $query->where('is_active', false);
        }

        return $query->get();
    }

    public function openDeleteModal(int $coverId): void
    {
        $cover = CoverArticle::query()->main()->find($coverId);
        if (! $cover) {
            return;
        }
        $user = Auth::user();
        if ($cover->is_active) {
            session()->flash('error', 'No se puede eliminar la portada activa. Desactívala antes.');

            return;
        }
        if ($cover->created_by !== $user->id && ! CoverArticle::userCanActivate($user)) {
            session()->flash('error', 'No tienes permisos para eliminar esta portada.');

            return;
        }
        $this->selectedCoverId = $coverId;
        $this->selectedCoverName = $cover->name ?: 'Sin nombre';
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->selectedCoverId = null;
        $this->selectedCoverName = '';
    }

    public function confirmDeleteCover(): void
    {
        if (! $this->selectedCoverId) {
            $this->closeDeleteModal();

            return;
        }
        $cover = CoverArticle::query()->main()->find($this->selectedCoverId);
        $user = Auth::user();
        if (! $cover) {
            session()->flash('error', 'Portada no encontrada.');
            $this->closeDeleteModal();

            return;
        }
        if ($cover->is_active) {
            session()->flash('error', 'No se puede eliminar la portada activa.');
            $this->closeDeleteModal();

            return;
        }
        if ($cover->created_by !== $user->id && ! CoverArticle::userCanActivate($user)) {
            session()->flash('error', 'No tienes permisos para eliminar esta portada.');
            $this->closeDeleteModal();

            return;
        }
        $cover->delete();
        session()->flash('message', 'Portada eliminada correctamente.');
        $this->closeDeleteModal();
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.cover-list', [
            'covers' => $this->getFilteredCovers(),
            'hasActiveFilters' => $this->search !== '' || $this->statusFilter !== '' || $this->visibilityFilter !== '' || $this->activeFilter !== '',
            'canActivate' => $user && CoverArticle::userCanActivate($user),
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\CoverArticle;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CoverTrash extends Component
{
    use WithPagination;

    public bool $showForceDeleteModal = false;

    public ?int $selectedCoverId = null;

    public string $selectedCoverName = '';

    public function paginationView(): string
    {
        return 'vendor.pagination.revista-livewire';
    }

    public function mount(): void
    {
        if (! Auth::check()) {
            abort(403, 'Debes iniciar sesión para ver la papelera.');
        }
    }

    public function restoreCover(int $id): void
    {
        $cover = CoverArticle::onlyTrashed()->main()->find($id);
        if (! $cover) {
            session()->flash('error', 'Portada no encontrada.');

            return;
        }
        if (! $this->canManageCover($cover)) {
            session()->flash('error', 'No tienes permiso para restaurar esta portada.');

            return;
        }
        $cover->restore();
        session()->flash('message', 'Portada restaurada correctamente.');
    }

    public function openForceDeleteModal(int $id): void
    {
        $cover = CoverArticle::onlyTrashed()->main()->find($id);
        if (! $cover || ! $this->canManageCover($cover)) {
            return;
        }
        $this->selectedCoverId = $id;
        $this->selectedCoverName = $cover->name ?: 'Sin nombre';
        $this->showForceDeleteModal = true;
    }

    public function closeForceDeleteModal(): void
    {
        $this->showForceDeleteModal = false;
        $this->selectedCoverId = null;
        $this->selectedCoverName = '';
    }

    public function confirmForceDelete(): void
    {
        if (! $this->selectedCoverId) {
            $this->closeForceDeleteModal();

            return;
        }
        $cover = CoverArticle::onlyTrashed()->main()->find($this->selectedCoverId);
        if (! $cover) {
            session()->flash('error', 'Portada no encontrada.');
            $this->closeForceDeleteModal();

            return;
        }
        if (! $this->canManageCover($cover)) {
            session()->flash('error', 'No tienes permiso para eliminar esta portada.');
            $this->closeForceDeleteModal();

            return;
        }
        $cover->forceDelete();
        session()->flash('message', 'Portada eliminada permanentemente.');
        $this->closeForceDeleteModal();
    }

    /**
     * Solo el creador o editor_chief/moderator/administrator pueden gestionar la portada.
     */
    protected function canManageCover(CoverArticle $cover): bool
    {
        $user = Auth::user();

        return $cover->created_by === $user->id
            || in_array($user->rol, ['editor_chief', 'administrator', 'moderator'], true);
    }

    protected function getTrashedCovers()
    {
        $user = Auth::user();
        $query = CoverArticle::onlyTrashed()
            ->main()
            ->with(['creator'])
            ->orderByDesc('deleted_at');

        if (! in_array($user->rol, ['editor_chief', 'administrator', 'moderator'], true)) {
            $query->where('created_by', $user->id);
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.cover-trash', [
            'covers' => $this->getTrashedCovers(),
        ]);
    }
}

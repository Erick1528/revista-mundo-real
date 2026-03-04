<?php

namespace App\Livewire;

use App\Models\SuggestedTopic;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SuggestedTopicTrash extends Component
{
    use WithPagination;

    public bool $showForceDeleteModal = false;

    public ?int $selectedTopicId = null;

    public string $selectedTopicTitle = '';

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

    public function restoreTopic(int $id): void
    {
        $topic = SuggestedTopic::onlyTrashed()->find($id);
        if (! $topic) {
            session()->flash('error', 'Tema no encontrado.');

            return;
        }
        if (! $this->canManageTopic($topic)) {
            session()->flash('error', 'No tienes permiso para restaurar este tema.');

            return;
        }
        $topic->restore();
        session()->flash('message', 'Tema restaurado correctamente.');
    }

    public function openForceDeleteModal(int $id): void
    {
        $topic = SuggestedTopic::onlyTrashed()->find($id);
        if (! $topic || ! $this->canManageTopic($topic)) {
            return;
        }
        $this->selectedTopicId = $id;
        $this->selectedTopicTitle = $topic->title;
        $this->showForceDeleteModal = true;
    }

    public function closeForceDeleteModal(): void
    {
        $this->showForceDeleteModal = false;
        $this->selectedTopicId = null;
        $this->selectedTopicTitle = '';
    }

    public function confirmForceDelete(): void
    {
        if (! $this->selectedTopicId) {
            $this->closeForceDeleteModal();

            return;
        }
        $topic = SuggestedTopic::onlyTrashed()->find($this->selectedTopicId);
        if (! $topic) {
            session()->flash('error', 'Tema no encontrado.');
            $this->closeForceDeleteModal();

            return;
        }
        if (! $this->canManageTopic($topic)) {
            session()->flash('error', 'No tienes permiso para eliminar este tema.');
            $this->closeForceDeleteModal();

            return;
        }
        $topic->deleteResourcesImagesFromStorage();
        $topic->forceDelete();
        session()->flash('message', 'Tema eliminado permanentemente.');
        $this->closeForceDeleteModal();
    }

    /**
     * Solo editor_chief, administrator y moderator pueden gestionar cualquier tema;
     * el resto solo los que ellos crearon (created_by).
     */
    protected function canManageTopic(SuggestedTopic $topic): bool
    {
        $user = Auth::user();

        return in_array($user->rol, ['editor_chief', 'administrator', 'moderator'], true)
            || $topic->created_by === $user->id;
    }

    protected function getTrashedTopics()
    {
        $user = Auth::user();
        $query = SuggestedTopic::onlyTrashed()
            ->with(['creator', 'assignedUser'])
            ->orderByDesc('deleted_at');

        // Solo editor_chief, administrator y moderator ven todos los eliminados; el resto solo los suyos
        if (! in_array($user->rol, ['editor_chief', 'administrator', 'moderator'], true)) {
            $query->where('created_by', $user->id);
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.suggested-topic-trash', [
            'topics' => $this->getTrashedTopics(),
        ]);
    }
}

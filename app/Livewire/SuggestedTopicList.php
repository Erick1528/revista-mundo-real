<?php

namespace App\Livewire;

use App\Models\SuggestedTopic;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SuggestedTopicList extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $sectionFilter = '';
    public $search = '';
    public $assignedToFilter = '';
    public $createdByFilter = '';

    // Modales de confirmación
    public $showReleaseModal = false;
    public $showDeleteModal = false;
    public $selectedTopicId = null;
    public $selectedTopicTitle = '';

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

    public function updatingAssignedToFilter()
    {
        $this->resetPage();
    }

    public function updatingCreatedByFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->statusFilter = '';
        $this->sectionFilter = '';
        $this->search = '';
        $this->assignedToFilter = '';
        $this->createdByFilter = '';
        $this->resetPage();
    }


    public function getFilteredTopics()
    {
        $user = Auth::user();
        $query = SuggestedTopic::with(['creator', 'assignedUser', 'requester', 'topicRequests.user'])
            ->orderBy('created_at', 'desc');

        // Búsqueda por título o descripción
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por estado
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Filtro por sección
        if ($this->sectionFilter) {
            $query->where('section', $this->sectionFilter);
        }

        // Filtro por usuario asignado
        if ($this->assignedToFilter) {
            $query->where('assigned_to', $this->assignedToFilter);
        }

        // Filtro por creador
        if ($this->createdByFilter) {
            $query->where('created_by', $this->createdByFilter);
        }

        return $query->paginate(10);
    }

    public function takeTopic($topicId)
    {
        $topic = SuggestedTopic::findOrFail($topicId);
        $user = Auth::user();

        if ($topic->takeTopic($user)) {
            session()->flash('message', 'Tema tomado exitosamente.');
        } else {
            session()->flash('error', 'No se pudo tomar el tema. Puede que ya esté tomado.');
        }
    }

    public function requestTopic($topicId)
    {
        $topic = SuggestedTopic::findOrFail($topicId);
        $user = Auth::user();

        if ($topic->requestTopic($user)) {
            session()->flash('message', 'Solicitud enviada exitosamente.');
        } else {
            session()->flash('error', 'No se pudo solicitar el tema. Puede que no esté disponible o ya lo tengas asignado.');
        }
    }

    public function openReleaseModal($topicId)
    {
        $topic = SuggestedTopic::findOrFail($topicId);
        $this->selectedTopicId = $topicId;
        $this->selectedTopicTitle = $topic->title;
        $this->showReleaseModal = true;
    }

    public function closeReleaseModal()
    {
        $this->showReleaseModal = false;
        $this->selectedTopicId = null;
        $this->selectedTopicTitle = '';
    }

    public function confirmReleaseTopic()
    {
        if ($this->selectedTopicId) {
            $topic = SuggestedTopic::findOrFail($this->selectedTopicId);
            $user = Auth::user();

            if ($topic->releaseTopic($user)) {
                session()->flash('message', 'Tema liberado exitosamente.');
            } else {
                session()->flash('error', 'No tienes permisos para liberar este tema.');
            }
        }

        $this->closeReleaseModal();
    }

    public function openDeleteModal($topicId)
    {
        $topic = SuggestedTopic::findOrFail($topicId);
        $this->selectedTopicId = $topicId;
        $this->selectedTopicTitle = $topic->title;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->selectedTopicId = null;
        $this->selectedTopicTitle = '';
    }

    public function confirmDeleteTopic()
    {
        if ($this->selectedTopicId) {
            $topic = SuggestedTopic::findOrFail($this->selectedTopicId);
            $user = Auth::user();

            // Solo admin/editor o el creador puede eliminar
            if ($topic->created_by !== $user->id && !in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
                session()->flash('error', 'No tienes permisos para eliminar este tema.');
                $this->closeDeleteModal();
                return;
            }

            $topic->delete();
            session()->flash('message', 'Tema eliminado exitosamente.');
        }

        $this->closeDeleteModal();
    }

    public function getUsersProperty()
    {
        return User::orderBy('name')->get();
    }

    /**
     * Verificar si el usuario actual puede editar un tema.
     * 
     * @param SuggestedTopic $topic
     * @return bool
     */
    public function canEdit($topic)
    {
        $user = Auth::user();
        
        // Creador siempre puede editar
        if ($topic->created_by === $user->id) {
            return true;
        }
        
        // Usuario asignado puede editar si está en estado taken o requested
        if ($topic->assigned_to === $user->id && in_array($topic->status, ['taken', 'requested'])) {
            return true;
        }
        
        // Admin/Editor siempre puede editar
        if (in_array($user->rol, ['editor_chief', 'moderator', 'administrator'])) {
            return true;
        }
        
        return false;
    }

    public function render()
    {
        $hasActiveFilters = $this->search || $this->statusFilter || $this->sectionFilter || $this->assignedToFilter || $this->createdByFilter;
        
        return view('livewire.suggested-topic-list', [
            'topics' => $this->getFilteredTopics(),
            'users' => $this->users,
            'hasActiveFilters' => $hasActiveFilters,
        ]);
    }
}

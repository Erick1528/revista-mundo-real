<?php

namespace App\Livewire;

use App\Models\SuggestedTopic;
use App\Models\User;
use App\Notifications\SuggestedTopicNotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShowSuggestedTopic extends Component
{
    public $topic;

    // Modales de confirmación
    public $showRequestModal = false;
    public $showAssignModal = false;
    public $showRejectModal = false;
    public $showReleaseModal = false;
    /** ID del usuario seleccionado para asignar o rechazar. */
    public $assignModalUserId = null;
    public $rejectModalUserId = null;

    public function mount(SuggestedTopic $topic)
    {
        $this->topic = $topic;
        $this->topic->load(['creator', 'assignedUser', 'requester', 'topicRequests.user']);
    }

    public function takeTopic()
    {
        $user = Auth::user();
        
        if ($this->topic->takeTopic($user)) {
            // Recargar el tema para obtener los datos actualizados
            $this->topic->refresh();
            session()->flash('message', 'Tema tomado exitosamente.');
        } else {
            session()->flash('error', 'No se pudo tomar el tema. Puede que ya esté tomado.');
        }
    }

    public function openRequestModal()
    {
        $this->showRequestModal = true;
    }

    public function closeRequestModal()
    {
        $this->showRequestModal = false;
    }

    public function confirmRequestTopic()
    {
        $user = Auth::user();
        
        if ($this->topic->requestTopic($user)) {
            $this->topic->refresh();
            SuggestedTopicNotificationService::notifyAssigneeTopicRequested($this->topic, $user);
            session()->flash('message', 'Solicitud de tema enviada exitosamente.');
        } else {
            session()->flash('error', 'No se pudo solicitar el tema.');
        }

        $this->closeRequestModal();
    }

    public function getIsOwnerProperty()
    {
        $user = Auth::user();
        return $this->topic->created_by === $user->id;
    }

    /** Si el usuario actual tiene el tema asignado. */
    public function getIsAssignedProperty()
    {
        $user = Auth::user();
        return $this->topic->assigned_to === $user->id;
    }

    public function getCanTakeProperty()
    {
        return $this->topic->status === 'available';
    }

    public function getCanRequestProperty()
    {
        // Cualquiera puede solicitar si está tomado por otro (incluido el dueño)
        return $this->topic->status === 'taken' && $this->topic->assigned_to !== Auth::user()->id;
    }

    /** Si hay al menos una solicitud pendiente. */
    public function getHasRequestProperty()
    {
        return $this->topic->hasPendingRequests();
    }

    /** Si el usuario puede liberar el tema (asignado o editor_chief/moderator/administrator). */
    public function getCanReleaseProperty()
    {
        $user = Auth::user();
        if (! $user) {
            return false;
        }
        if (! in_array($this->topic->status, ['taken', 'requested'])) {
            return false;
        }
        return $this->topic->assigned_to === $user->id
            || in_array($user->rol, ['editor_chief', 'moderator', 'administrator']);
    }

    public function openReleaseModal()
    {
        $this->showReleaseModal = true;
    }

    public function closeReleaseModal()
    {
        $this->showReleaseModal = false;
    }

    public function releaseTopic()
    {
        $user = Auth::user();
        if ($this->topic->releaseTopic($user)) {
            $this->topic->refresh();
            $this->topic->load(['creator', 'assignedUser', 'requester', 'topicRequests.user']);
            session()->flash('message', 'Tema liberado correctamente.');
        } else {
            session()->flash('error', 'No tienes permisos para liberar este tema.');
        }
        $this->closeReleaseModal();
    }

    public function openAssignModal($userId)
    {
        $this->assignModalUserId = $userId;
        $this->showAssignModal = true;
    }

    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->assignModalUserId = null;
    }

    public function openRejectModal($userId)
    {
        $this->rejectModalUserId = $userId;
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectModalUserId = null;
    }

    public function assignToRequester()
    {
        $user = Auth::user();

        if (!$this->isAssigned) {
            session()->flash('error', 'No tienes permisos para asignar este tema.');
            $this->closeAssignModal();
            return;
        }

        $userIdToAssign = (int) $this->assignModalUserId;
        if (!$userIdToAssign) {
            session()->flash('error', 'Usuario no válido.');
            $this->closeAssignModal();
            return;
        }

        $requester = \App\Models\User::find($userIdToAssign);
        $requesterName = $requester ? $requester->name : 'el solicitante';

        if ($this->topic->assignToRequester($user, $userIdToAssign)) {
            $this->topic->refresh();
            $this->topic->load(['creator', 'assignedUser', 'requester', 'topicRequests.user']);
            if ($requester && $requester->email) {
                \App\Notifications\SuggestedTopicNotificationService::notifyUserAssignedToTopic($this->topic, $requester);
            }
            session()->flash('message', 'Tema asignado exitosamente a ' . $requesterName . '.');
        } else {
            session()->flash('error', 'No se pudo asignar el tema.');
        }
        $this->closeAssignModal();
    }

    public function rejectRequest()
    {
        $user = Auth::user();

        if (!$this->isAssigned) {
            session()->flash('error', 'No tienes permisos para rechazar esta solicitud.');
            $this->closeRejectModal();
            return;
        }

        $userIdToReject = (int) $this->rejectModalUserId;
        if (!$userIdToReject) {
            session()->flash('error', 'Usuario no válido.');
            $this->closeRejectModal();
            return;
        }

        if ($this->topic->rejectRequest($user, $userIdToReject)) {
            $rejectedUser = User::find($userIdToReject);
            if ($rejectedUser) {
                SuggestedTopicNotificationService::notifyUserRequestRejected($this->topic, $rejectedUser);
            }
            $this->topic->refresh();
            $this->topic->load(['creator', 'assignedUser', 'requester', 'topicRequests.user']);
            session()->flash('message', 'Solicitud rechazada.');
        } else {
            session()->flash('error', 'No se pudo rechazar la solicitud.');
        }
        $this->closeRejectModal();
    }

    /** Usuario seleccionado para el modal de asignar (para mostrar nombre). */
    public function getAssignModalUserProperty()
    {
        if (!$this->assignModalUserId) {
            return null;
        }
        return \App\Models\User::find($this->assignModalUserId);
    }

    /** Usuario seleccionado para el modal de rechazar (para mostrar nombre). */
    public function getRejectModalUserProperty()
    {
        if (!$this->rejectModalUserId) {
            return null;
        }
        return \App\Models\User::find($this->rejectModalUserId);
    }

    public function render()
    {
        return view('livewire.show-suggested-topic', [
            'isOwner' => $this->isOwner,
            'isAssigned' => $this->isAssigned,
            'canTake' => $this->canTake,
            'canRequest' => $this->canRequest,
            'canRelease' => $this->canRelease,
            'hasRequest' => $this->hasRequest,
            'assignModalUser' => $this->assignModalUser,
            'rejectModalUser' => $this->rejectModalUser,
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\User;
use App\Notifications\UserNotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public function paginationView(): string
    {
        return 'vendor.pagination.revista-livewire';
    }

    public $search = '';
    public $rolFilter = '';

    public function mount(): void
    {
        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->rol !== 'administrator') {
            abort(404);
        }
    }

    // Modal de confirmación para eliminar
    public $showDeleteModal = false;
    public $selectedUserId = null;
    public $selectedUserName = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRolFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->rolFilter = '';
        $this->resetPage();
    }

    public function getFilteredUsers()
    {
        $query = User::query()->orderBy('name');

        // Búsqueda por nombre o email
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por rol
        if ($this->rolFilter) {
            $query->where('rol', $this->rolFilter);
        }

        return $query->paginate(10);
    }

    public function openDeleteModal($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $currentUser = Auth::user();
        
        // No permitir eliminar a sí mismo
        if ($user->id === $currentUser->id) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta.');
            return;
        }

        $this->selectedUserId = $userId;
        $this->selectedUserName = $user->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->selectedUserId = null;
        $this->selectedUserName = '';
    }

    public function confirmDeleteUser()
    {
        if (!$this->selectedUserId) {
            $this->closeDeleteModal();
            return;
        }

        $user = User::find($this->selectedUserId);
        $currentUser = Auth::user();

        if (!$user) {
            session()->flash('error', 'Usuario no encontrado.');
            $this->closeDeleteModal();
            return;
        }

        // No permitir eliminar a sí mismo
        if ($user->id === $currentUser->id) {
            session()->flash('error', 'No puedes eliminar tu propia cuenta.');
            $this->closeDeleteModal();
            return;
        }

        $userName = $user->name;
        UserNotificationService::notifyUserDeleted($user);
        $user->delete();

        session()->flash('message', "Usuario {$userName} eliminado correctamente.");
        $this->closeDeleteModal();
    }

    public function showInDevelopment($action)
    {
        $this->dispatch('openDevelopModal', action: $action);
    }

    public function render()
    {
        $hasActiveFilters = $this->search || $this->rolFilter;
        
        $roles = [
            'writer_junior' => 'Escritor Junior',
            'writer_senior' => 'Escritor Senior',
            'editor_junior' => 'Editor Junior',
            'editor_senior' => 'Editor Senior',
            'editor_chief' => 'Editor Jefe',
            'moderator' => 'Moderador',
            'administrator' => 'Administrador',
        ];

        return view('livewire.user-list', [
            'users' => $this->getFilteredUsers(),
            'hasActiveFilters' => $hasActiveFilters,
            'roles' => $roles,
        ]);
    }
}

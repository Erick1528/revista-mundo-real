<?php

namespace App\Livewire;

use App\Models\User;
use App\Notifications\UserNotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserTrash extends Component
{
    use WithPagination;

    public bool $showForceDeleteModal = false;
    public ?int $selectedUserId = null;
    public string $selectedUserName = '';

    public function paginationView(): string
    {
        return 'vendor.pagination.revista-livewire';
    }

    public function mount(): void
    {
        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->rol !== 'administrator') {
            abort(404);
        }
    }

    public function restoreUser(int $userId): void
    {
        $user = User::onlyTrashed()->find($userId);
        if (!$user) {
            session()->flash('error', 'Usuario no encontrado.');
            return;
        }

        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->rol !== 'administrator') {
            session()->flash('error', 'No tienes permisos para restaurar usuarios.');
            return;
        }

        $user->restore();
        UserNotificationService::notifyUserRestored($user);
        session()->flash('message', 'Usuario restaurado correctamente.');
    }

    public function openForceDeleteModal(int $userId): void
    {
        $user = User::onlyTrashed()->find($userId);
        if (!$user) {
            return;
        }
        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->rol !== 'administrator') {
            return;
        }
        $this->selectedUserId = $userId;
        $this->selectedUserName = $user->name;
        $this->showForceDeleteModal = true;
    }

    public function closeForceDeleteModal(): void
    {
        $this->showForceDeleteModal = false;
        $this->selectedUserId = null;
        $this->selectedUserName = '';
    }

    public function confirmForceDeleteUser(): void
    {
        if (!$this->selectedUserId) {
            $this->closeForceDeleteModal();
            return;
        }
        $user = User::onlyTrashed()->find($this->selectedUserId);
        $currentUser = Auth::user();
        if (!$user) {
            session()->flash('error', 'Usuario no encontrado.');
            $this->closeForceDeleteModal();
            return;
        }
        if (!$currentUser || $currentUser->rol !== 'administrator') {
            session()->flash('error', 'No tienes permisos para eliminar usuarios.');
            $this->closeForceDeleteModal();
            return;
        }
        $user->deleteAvatarFromStorage();
        $user->forceDelete();
        session()->flash('message', 'Usuario eliminado permanentemente.');
        $this->closeForceDeleteModal();
    }

    protected function getTrashedUsers()
    {
        return User::onlyTrashed()
            ->orderByDesc('deleted_at')
            ->paginate(10);
    }

    public function render()
    {
        $roles = [
            'writer_junior' => 'Escritor Junior',
            'writer_senior' => 'Escritor Senior',
            'editor_junior' => 'Editor Junior',
            'editor_senior' => 'Editor Senior',
            'editor_chief' => 'Editor Jefe',
            'moderator' => 'Moderador',
            'administrator' => 'Administrador',
        ];

        return view('livewire.user-trash', [
            'users' => $this->getTrashedUsers(),
            'roles' => $roles,
        ]);
    }
}

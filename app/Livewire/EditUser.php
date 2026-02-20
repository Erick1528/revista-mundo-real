<?php

namespace App\Livewire;

use App\Models\User;
use App\Notifications\UserNotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditUser extends Component
{
    public User $user;

    public $name;
    public $rol;

    protected $rules = [
        'name' => 'required|string|max:255',
        'rol' => 'required|in:writer_junior,writer_senior,editor_junior,editor_senior,editor_chief,moderator,administrator',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'rol.required' => 'Debe seleccionar un rol.',
        'rol.in' => 'El rol seleccionado no es válido.',
    ];

    public function mount(User $user)
    {
        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->rol !== 'administrator') {
            abort(404);
        }

        $this->user = $user;
        $this->name = $user->name;
        $this->rol = $user->rol;
    }

    public function updateUser()
    {
        $this->validate();

        $oldRol = $this->user->rol;
        $this->user->update([
            'name' => $this->name,
            'rol' => $this->rol,
        ]);

        if ($oldRol !== $this->rol) {
            UserNotificationService::notifyUserRoleChanged(
                $this->user->fresh(),
                rol_label($oldRol),
                rol_label($this->rol)
            );
        }

        session()->flash('message', 'Usuario actualizado correctamente.');
        return $this->redirect(route('users.index'));
    }

    public function getRolesOptions(): array
    {
        return [
            'writer_junior' => 'Escritor Junior',
            'writer_senior' => 'Escritor Senior',
            'editor_junior' => 'Editor Junior',
            'editor_senior' => 'Editor Senior',
            'editor_chief' => 'Editor Jefe',
            'moderator' => 'Moderador',
            'administrator' => 'Administrador',
        ];
    }

    public function render()
    {
        return view('livewire.edit-user', [
            'rolesOptions' => $this->getRolesOptions(),
        ]);
    }
}

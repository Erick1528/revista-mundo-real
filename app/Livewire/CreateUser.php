<?php

namespace App\Livewire;

use App\Models\User;
use App\Notifications\UserNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateUser extends Component
{
    public $name = '';
    public $email = '';
    public $rol = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'rol' => 'required|in:writer_junior,writer_senior,editor_junior,editor_senior,editor_chief,moderator,administrator',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'email.required' => 'El correo es obligatorio.',
        'email.email' => 'El correo no es válido.',
        'email.unique' => 'Ya existe un usuario con este correo.',
        'rol.required' => 'Debe seleccionar un rol.',
        'rol.in' => 'El rol seleccionado no es válido.',
    ];

    public function mount()
    {
        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->rol !== 'administrator') {
            abort(404);
        }
    }

    public function createUser()
    {
        $this->validate();

        $temporaryPassword = Str::random(12);
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($temporaryPassword),
            'rol' => $this->rol,
        ]);

        UserNotificationService::notifyUserCreated($user, $temporaryPassword);

        session()->flash('message', 'Usuario creado correctamente. Se ha enviado un correo con la contraseña temporal.');
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
        return view('livewire.create-user', [
            'rolesOptions' => $this->getRolesOptions(),
        ]);
    }
}

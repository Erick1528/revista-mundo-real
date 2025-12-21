<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Login extends Component
{
    public $showModal = false;
    public $email = '';
    public $password = '';

    protected $rules = [
        'email' => 'required|email|max:255|exists:users,email',
        'password' => 'required|min:6|max:255',
    ];

    protected $messages = [
        'email.required' => 'El correo electrónico es obligatorio.',
        'email.email' => 'El correo electrónico no es válido.',
        'email.exists' => 'Error al iniciar sesión. Verifica tus credenciales.',
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
    ];

    protected $listeners = ['openLoginModal' => 'openModal'];

    public function login()
    {
        $this->validate();

        // Limpiar mensajes previos
        session()->forget(['error', 'success']);

        $rateLimiterKey = $this->email . '|' . request()->ip();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            session()->flash('error', 'Demasiados intentos fallidos. Inténtalo de nuevo más tarde.');
            $this->password = ''; // Solo limpiar contraseña
            return;
        }

        // Intentar autenticación
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            RateLimiter::clear($rateLimiterKey);
            $this->password = '';

            // Cerrar Modal y redireccionar a dashboard
            $this->closeModal();
            redirect()->route('dashboard');
        }

        // Solo se ejecuta si el login falló
        RateLimiter::hit($rateLimiterKey);
        $this->password = '';
        session()->flash('error', 'El correo o la contraseña no son correctos.');
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->dispatch('loginModalToggled', true);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('loginModalToggled', false);
        $this->reset(['email', 'password']);
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.login');
    }
}

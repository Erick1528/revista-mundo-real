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

        $rateLimiterKey = 'login:' . $this->email . '|' . request()->ip();
        $maxAttempts = 5;
        $decaySeconds = 60;

        if (RateLimiter::tooManyAttempts($rateLimiterKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($rateLimiterKey);
            session()->flash('error', "Demasiados intentos fallidos. Espera {$seconds} segundos e inténtalo de nuevo.");
            $this->password = '';
            return;
        }

        // Intentar autenticación
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            RateLimiter::clear($rateLimiterKey);
            $this->password = '';

            \App\Notifications\AuthNotificationService::notifyLogin(Auth::user(), request()->ip());

            // Cerrar Modal y redireccionar a dashboard
            $this->closeModal();
            return $this->redirect(route('dashboard'));
        }

        // Solo se ejecuta si el login falló
        RateLimiter::hit($rateLimiterKey, $decaySeconds);
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

<?php

namespace App\Livewire;

use Livewire\Component;

class Login extends Component
{
    public $showModal = false;
    public $email = '';
    public $password = '';

    protected $listeners = ['openLoginModal' => 'openModal'];

    public function login()
    {
        // Aquí iría la lógica de autenticación
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Ejemplo básico - necesitarás implementar tu lógica de autenticación
        dd('Login attempt', $this->email, $this->password);
        
        // Después del login exitoso:
        // $this->closeModal();
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
    }

    public function render()
    {
        return view('livewire.login');
    }
}

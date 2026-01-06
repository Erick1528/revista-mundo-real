<?php

namespace App\Livewire;

use Livewire\Component;

class DevelopModal extends Component
{
    public $isOpen = false;
    public $action = '';

    protected $listeners = ['openDevelopModal' => 'openModal'];

    public function openModal($action)
    {
        $this->action = $action;
        $this->isOpen = true;
        
        // Bloquear scroll
        $this->dispatch('block-scroll');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->action = '';
        
        // Desbloquear scroll
        $this->dispatch('unblock-scroll');
    }

    public function render()
    {
        return view('livewire.develop-modal');
    }
}

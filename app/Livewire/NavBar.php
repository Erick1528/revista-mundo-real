<?php

namespace App\Livewire;

use Livewire\Component;

class NavBar extends Component
{

    protected $listeners = ['openLoginModal' => 'toggleMenuState'];

    public $toggleMenu = false;

    public function toggleMenuState()
    {
        $this->toggleMenu = !$this->toggleMenu;
        $this->dispatch('toggleMenu', $this->toggleMenu);
    }

    public function render()
    {
        return view('livewire.nav-bar');
    }
}

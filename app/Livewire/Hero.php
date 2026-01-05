<?php

namespace App\Livewire;

use Livewire\Component;

class Hero extends Component
{

    public $cancelButton = false;
    public $showCreateArticleView = false;

    public function mount()
    {
        $this->showCreateArticleView = request()->is('articles/create');
    }

    public function cancelCreateArticle()
    {
        $this->dispatch('cancelCreateArticle');
    }

    public function render()
    {
        return view('livewire.hero');
    }
}

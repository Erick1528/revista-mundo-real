<?php

namespace App\Livewire;

use Livewire\Component;

class Hero extends Component
{

    public $cancelButton = false;
    public $showCreateArticleView = false;
    public $showEditArticleView = false;

    public function mount()
    {
        $this->showCreateArticleView = request()->is('articles/create');
        $this->showEditArticleView = request()->is('articles/*/edit');
    }

    public function cancelCreateArticle()
    {
        $this->dispatch('cancelCreateArticle');
    }

    public function cancelEditArticle()
    {
        $this->dispatch('cancelEditArticle');
    }

    public function render()
    {
        return view('livewire.hero');
    }
}

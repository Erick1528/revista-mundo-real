<?php

namespace App\Livewire;

use Livewire\Component;

class ShowArticle extends Component
{

    public $article;

    public function render()
    {
        return view('livewire.show-article');
    }
}

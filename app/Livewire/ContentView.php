<?php

namespace App\Livewire;

use Livewire\Component;

class ContentView extends Component
{

    public $content;
        public $blocks = [];

    public function mount()
    {
            if (is_string($this->content)) {
                $this->blocks = json_decode($this->content, true) ?? [];
            } else {
                $this->blocks = $this->content ?? [];
            }
    }

    public function render()
    {
        return view('livewire.content-view');
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;

class Hero extends Component
{

    public $cancelButton = false;
    public $showCreateArticleView = false;
    public $showEditArticleView = false;
    public $showCreateTopicView = false;
    public $showEditTopicView = false;
    public $showViewTopicView = false;
    public $showViewUserView = false;
    public $showEditUserView = false;
    public $showUserTrashView = false;
    public $showCreateUserView = false;

    public function mount()
    {
        $this->showCreateArticleView = request()->is('articles/create');
        $this->showEditArticleView = request()->is('articles/*/edit');
        $this->showCreateTopicView = request()->is('temas-sugeridos/crear');
        $this->showEditTopicView = request()->is('temas-sugeridos/*/editar');
        $this->showViewTopicView = request()->is('temas-sugeridos/*') && !request()->is('temas-sugeridos') && !request()->is('temas-sugeridos/crear') && !request()->is('temas-sugeridos/*/editar');
        $this->showCreateUserView = request()->is('usuarios/crear');
        $this->showUserTrashView = request()->is('usuarios/eliminados');
        $this->showEditUserView = request()->is('usuarios/*/editar');
        $this->showViewUserView = request()->is('usuarios/*') && !request()->is('usuarios') && !request()->is('usuarios/crear') && !request()->is('usuarios/eliminados') && !request()->is('usuarios/*/editar');
    }

    public function cancelCreateArticle()
    {
        $this->dispatch('cancelCreateArticle');
    }

    public function cancelEditArticle()
    {
        $this->dispatch('cancelEditArticle');
    }

    public function cancelCreateTopic()
    {
        $this->dispatch('cancelCreateTopic');
    }

    public function cancelEditTopic()
    {
        $this->dispatch('cancelEditTopic');
    }

    public function cancelViewTopic()
    {
        return redirect()->route('suggested-topics.index');
    }

    public function cancelViewUser()
    {
        return redirect()->route('users.index');
    }

    public function cancelEditUser()
    {
        return redirect()->route('users.index');
    }

    public function cancelUserTrash()
    {
        return redirect()->route('users.index');
    }

    public function cancelCreateUser()
    {
        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.hero');
    }
}

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
    public $showCreateAdvertiserView = false;
    public $showEditAdvertiserView = false;
    public $showCreateAdView = false;
    public $showEditAdView = false;
    public $showAdView = false;
    public $showAdTrashView = false;
    public $showArticleTrashView = false;
    public $showAdvertiserTrashView = false;

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
        $this->showCreateAdvertiserView = request()->is('anunciantes/crear');
        $this->showEditAdvertiserView = request()->is('anunciantes/*/editar');
        $this->showCreateAdView = request()->is('anuncios/crear');
        $this->showEditAdView = request()->is('anuncios/*/editar');
        $this->showAdView = request()->routeIs('ads.show');
        $this->showAdTrashView = request()->is('anuncios/eliminados');
        $this->showArticleTrashView = request()->is('dashboard/papelera');
        $this->showAdvertiserTrashView = request()->is('anunciantes/eliminados');
    }

    public function cancelArticleTrash()
    {
        return redirect()->route('dashboard');
    }

    public function cancelAdvertiserTrash()
    {
        return redirect()->route('advertisers.index');
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

    public function cancelCreateAdvertiser()
    {
        return redirect()->route('advertisers.index');
    }

    public function cancelEditAdvertiser()
    {
        return redirect()->route('advertisers.index');
    }

    public function cancelCreateAd()
    {
        $this->dispatch('cancelCreateAd', ['redirectUrl' => route('ads.index')]);
    }

    public function cancelEditAd()
    {
        $this->dispatch('cancelEditAd', ['redirectUrl' => route('ads.index')]);
    }

    public function cancelAdTrash()
    {
        return redirect()->route('ads.index');
    }

    public function render()
    {
        return view('livewire.hero');
    }
}

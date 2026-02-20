<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowUser extends Component
{
    public User $user;
    public $articles;
    public $totalArticles;
    public $createdAt;
    public $updatedAt;
    public $lastSession;

    public function mount($user)
    {
        // Verificar que el usuario actual es administrador (404 genérico para no revelar la ruta)
        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->rol !== 'administrator') {
            abort(404);
        }

        // Si se pasa un ID, buscar el usuario (excluir eliminados para no mostrar papelera aquí)
        if (is_numeric($user)) {
            $this->user = User::withoutTrashed()->findOrFail($user);
        } elseif ($user instanceof User) {
            $this->user = $user;
        } else {
            abort(404);
        }

        $this->createdAt = $this->user->created_at;
        $this->updatedAt = $this->user->updated_at;
        $this->loadLastSession();
        $this->loadArticles();
    }

    public function loadLastSession()
    {
        $session = DB::table('sessions')
            ->where('user_id', $this->user->id)
            ->orderBy('last_activity', 'desc')
            ->first();
        
        if ($session) {
            $this->lastSession = \Carbon\Carbon::createFromTimestamp($session->last_activity);
        } else {
            $this->lastSession = null;
        }
    }

    public function loadArticles()
    {
        $this->totalArticles = Article::where('user_id', $this->user->id)->count();
        $this->articles = Article::where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    }

    public function formatDate($date)
    {
        if (!$date) {
            return 'No disponible';
        }

        if ($date->isAfter(now()->subDays(30))) {
            return $date->locale('es')->diffForHumans();
        }

        return $date->locale('es')->translatedFormat('d \d\e F \d\e Y');
    }

    public function getRolName()
    {
        $roles = [
            'writer_junior' => 'Escritor Junior',
            'writer_senior' => 'Escritor Senior',
            'editor_junior' => 'Editor Junior',
            'editor_senior' => 'Editor Senior',
            'editor_chief' => 'Editor Jefe',
            'moderator' => 'Moderador',
            'administrator' => 'Administrador',
        ];

        return $roles[$this->user->rol] ?? $this->user->rol;
    }

    public function render()
    {
        return view('livewire.show-user');
    }
}

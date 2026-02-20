<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     * Solo visible para usuarios con rol 'administrator'.
     * Filters and list are handled by the UserList Livewire component.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user || $user->rol !== 'administrator') {
            abort(404);
        }

        return view('dashboard.users.index');
    }

    /**
     * Show the form for creating a new user. Solo administradores.
     * El formulario y la validación están en el componente Livewire CreateUser.
     */
    public function create()
    {
        $currentUser = Auth::user();
        if (!$currentUser || $currentUser->rol !== 'administrator') {
            abort(404);
        }
        return view('dashboard.users.create');
    }

    /**
     * Display the specified user profile.
     * Solo visible para administradores. Si es el propio usuario, redirige a su perfil.
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser || $currentUser->rol !== 'administrator') {
            abort(404);
        }

        if ((int) $currentUser->id === (int) $user->id) {
            return redirect()->route('profile');
        }

        return view('dashboard.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     * Solo administradores. Si es el propio usuario, redirige a su perfil (edición desde ahí).
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser || $currentUser->rol !== 'administrator') {
            abort(404);
        }

        if ((int) $currentUser->id === (int) $user->id) {
            return redirect()->route('profile', ['editar' => 1]);
        }

        return view('dashboard.users.edit', compact('user'));
    }

    /**
     * Lista de usuarios eliminados (soft deleted). Solo administradores.
     */
    public function trash()
    {
        $currentUser = Auth::user();

        if (!$currentUser || $currentUser->rol !== 'administrator') {
            abort(404);
        }

        return view('dashboard.users.trash');
    }
}

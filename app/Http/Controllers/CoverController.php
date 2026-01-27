<?php

namespace App\Http\Controllers;

use App\Models\CoverArticle;
use Illuminate\Support\Facades\Auth;

class CoverController extends Controller
{
    /**
     * Roles allowed to manage the magazine cover.
     */
    private const ALLOWED_ROLES = ['editor_chief', 'administrator', 'moderator'];

    private function authorizeCover(): void
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->rol, self::ALLOWED_ROLES, true)) {
            abort(403, 'No tienes permiso para gestionar la portada.');
        }
    }

    /**
     * Listado de portadas (solo roles permitidos).
     */
    public function index()
    {
        $this->authorizeCover();

        $covers = CoverArticle::query()
            ->with(['creator', 'editor'])
            ->latest('updated_at')
            ->get();

        return view('cover.index', ['covers' => $covers]);
    }

    /**
     * Vista para crear o editar una portada (solo roles permitidos).
     */
    public function manage()
    {
        $this->authorizeCover();

        return view('cover.manage');
    }
}

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

    /**
     * Check if current user can manage covers.
     */
    private function authorizeCover(): void
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->rol, self::ALLOWED_ROLES, true)) {
            abort(403, 'No tienes permiso para gestionar la portada.');
        }
    }

    /**
     * Display a listing of covers (allowed roles only).
     * Filters and list are handled by the CoverList Livewire component.
     */
    public function index()
    {
        $this->authorizeCover();

        return view('cover.index');
    }

    /**
     * Show the form for creating a new cover (allowed roles only).
     */
    public function manage()
    {
        $this->authorizeCover();

        return view('cover.manage');
    }

    /**
     * Show the form for editing an existing cover (allowed roles only).
     */
    public function edit(CoverArticle $cover)
    {
        $this->authorizeCover();

        return view('cover.edit', ['cover' => $cover]);
    }

    /**
     * Activate a cover (and publish if needed). Allowed roles only.
     */
    public function activate(CoverArticle $cover)
    {
        $this->authorizeCover();

        $user = Auth::user();

        if (! CoverArticle::userCanActivate($user)) {
            return redirect()->route('cover.index')
                ->with('error', 'No tienes permisos para activar portadas.');
        }

        $result = $cover->activate($user);

        if ($result) {
            return redirect()->route('cover.index')
                ->with('message', 'Portada activada correctamente. Ahora es la portada visible en el sitio.');
        }

        return redirect()->route('cover.index')
            ->with('error', 'No se pudo activar la portada.');
    }

    /**
     * Approve and merge a specific pending version into its parent cover.
     */
    public function approvePending(CoverArticle $cover)
    {
        $this->authorizeCover();

        $user = Auth::user();

        if (! CoverArticle::userCanActivate($user)) {
            return redirect()->route('cover.index')
                ->with('error', 'No tienes permisos para aprobar cambios.');
        }

        if (! $cover->isPendingVersion()) {
            return redirect()->route('cover.index')
                ->with('error', 'No hay cambios pendientes para aprobar.');
        }

        $parent = $cover->parent;
        if (! $parent) {
            return redirect()->route('cover.index')
                ->with('error', 'No se pudieron aplicar los cambios.');
        }

        $result = $parent->mergePendingVersion($cover, $user);

        if ($result) {
            return redirect()->route('cover.index')
                ->with('message', 'Cambios aprobados y aplicados a la portada.');
        }

        return redirect()->route('cover.index')
            ->with('error', 'No se pudieron aplicar los cambios.');
    }

    /**
     * Reject and delete a specific pending version.
     */
    public function rejectPending(CoverArticle $cover)
    {
        $this->authorizeCover();

        $user = Auth::user();

        if (! CoverArticle::userCanActivate($user)) {
            return redirect()->route('cover.index')
                ->with('error', 'No tienes permisos para rechazar cambios.');
        }

        if (! $cover->isPendingVersion()) {
            return redirect()->route('cover.index')
                ->with('error', 'No hay cambios pendientes para rechazar.');
        }

        $cover->delete();

        return redirect()->route('cover.index')
            ->with('message', 'Cambios pendientes rechazados y eliminados.');
    }
}

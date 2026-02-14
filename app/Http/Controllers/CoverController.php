<?php

namespace App\Http\Controllers;

use App\Models\CoverArticle;
use Illuminate\Support\Facades\Auth;

class CoverController extends Controller
{
    /**
     * Check if current user can publish/activate covers (editor_chief, moderator, administrator).
     */
    private function authorizePublish(): void
    {
        $user = Auth::user();
        if (! $user || ! CoverArticle::userCanActivate($user)) {
            abort(403, 'No tienes permiso para publicar o activar portadas.');
        }
    }

    /**
     * Display a listing of covers. Cualquier usuario autenticado puede ver el listado.
     */
    public function index()
    {
        return view('cover.index');
    }

    /**
     * Show the form for creating a new cover. Cualquier usuario autenticado puede crear.
     */
    public function manage()
    {
        return view('cover.manage');
    }

    /**
     * Show the form for editing an existing cover.
     * Portada activa: cualquiera puede editar (cambios como versión pendiente).
     * Portada no activa: solo el dueño (creador) puede ver y editar.
     * Versión pendiente: se puede editar si el usuario puede ver la portada padre (activa o dueño).
     */
    public function edit(CoverArticle $cover)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Debes iniciar sesión para editar portadas.');
        }

        if ($cover->isPendingVersion()) {
            $parent = $cover->parent;
            if (! $parent) {
                abort(404, 'Portada no encontrada.');
            }
            if (! $parent->is_active && (int) $parent->created_by !== (int) $user->id) {
                abort(403, 'Solo el dueño de la portada puede ver y editar esta versión pendiente.');
            }
        } else {
            if (! $cover->is_active && (int) $cover->created_by !== (int) $user->id) {
                abort(403, 'Solo el dueño puede ver y editar esta portada.');
            }
        }

        return view('cover.edit', ['cover' => $cover]);
    }

    /**
     * Activate a cover (and publish if needed). Solo editor_chief, moderator, administrator.
     */
    public function activate(CoverArticle $cover)
    {
        $this->authorizePublish();

        $user = Auth::user();

        $result = $cover->activate($user);

        if ($result) {
            \App\Notifications\CoverNotificationService::notifyCreatorCoverActivated($cover);

            return redirect()->route('cover.index')
                ->with('message', 'Portada activada correctamente. Ahora es la portada visible en el sitio.');
        }

        return redirect()->route('cover.index')
            ->with('error', 'No se pudo activar la portada.');
    }

    /**
     * Approve and merge a specific pending version into its parent cover. Solo editor_chief, moderator, administrator.
     */
    public function approvePending(CoverArticle $cover)
    {
        $this->authorizePublish();

        $user = Auth::user();

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
            \App\Notifications\CoverNotificationService::notifyEditorChangesApproved($cover);

            return redirect()->route('cover.index')
                ->with('message', 'Cambios aprobados y aplicados a la portada.');
        }

        return redirect()->route('cover.index')
            ->with('error', 'No se pudieron aplicar los cambios.');
    }

    /**
     * Reject and delete a specific pending version. Solo editor_chief, moderator, administrator.
     */
    public function rejectPending(CoverArticle $cover)
    {
        $this->authorizePublish();

        $user = Auth::user();

        if (! $cover->isPendingVersion()) {
            return redirect()->route('cover.index')
                ->with('error', 'No hay cambios pendientes para rechazar.');
        }

        \App\Notifications\CoverNotificationService::notifyEditorChangesRejected($cover);

        $cover->delete();

        return redirect()->route('cover.index')
            ->with('message', 'Cambios pendientes rechazados y eliminados.');
    }
}

<?php

namespace App\Notifications;

use App\Mail\SimpleMessageMail;
use App\Models\CoverArticle;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CoverNotificationService
{
    private const EDITOR_ROLES = ['editor_chief', 'moderator', 'administrator'];

    /**
     * Notificar a editores que hay una nueva versión pendiente de portada para revisar.
     */
    public static function notifyEditorsPendingCoverCreated(CoverArticle $pendingCover): void
    {
        $editors = User::whereIn('rol', self::EDITOR_ROLES)->get();
        $parentName = $pendingCover->parent ? ($pendingCover->parent->name ?: 'portada activa') : 'portada';
        $title = 'Cambios pendientes en la portada';
        $body = sprintf(
            "Hay cambios pendientes de revisión para la %s.\n\nPuedes aprobar o rechazar los cambios desde el panel de portadas.",
            $parentName
        );

        foreach ($editors as $user) {
            if ($user->email) {
                Mail::to($user->email)->send(new SimpleMessageMail($title, $body));
            }
        }
    }

    /**
     * Notificar al creador de la portada que fue activada.
     */
    public static function notifyCreatorCoverActivated(CoverArticle $cover): void
    {
        $creator = $cover->creator;
        if (! $creator || ! $creator->email) {
            return;
        }

        $name = $cover->name ?: 'Sin nombre';
        $title = 'Portada activada';
        $body = "Hola {$creator->name},\n\nTu portada «{$name}» ha sido activada y ya es la portada visible en el sitio.";

        Mail::to($creator->email)->send(new SimpleMessageMail($title, $body));
    }

    /**
     * Notificar al usuario que editó la portada que sus cambios fueron aprobados.
     */
    public static function notifyEditorChangesApproved(CoverArticle $pendingCover): void
    {
        $editor = $pendingCover->creator;
        if (! $editor || ! $editor->email) {
            return;
        }

        $title = 'Cambios de portada aprobados';
        $body = "Hola {$editor->name},\n\nLos cambios que enviaste para la portada han sido aprobados y ya están publicados.";

        Mail::to($editor->email)->send(new SimpleMessageMail($title, $body));
    }

    /**
     * Notificar al usuario que editó la portada que sus cambios fueron rechazados.
     */
    public static function notifyEditorChangesRejected(CoverArticle $pendingCover): void
    {
        $editor = $pendingCover->creator;
        if (! $editor || ! $editor->email) {
            return;
        }

        $title = 'Cambios de portada no aplicados';
        $body = "Hola {$editor->name},\n\nLos cambios que enviaste para la portada no han sido aceptados.";

        Mail::to($editor->email)->send(new SimpleMessageMail($title, $body));
    }
}

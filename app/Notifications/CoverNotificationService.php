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
        $submitterName = $pendingCover->creator?->name ?? 'Un usuario';
        $link = url()->route('cover.index');
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $body = sprintf(
            "Hay cambios pendientes de revisión para la %s.\n\nEnviado por: %s\nFecha: %s",
            $parentName,
            $submitterName,
            $fecha
        );

        foreach ($editors as $user) {
            if ($user->email) {
                Mail::to($user->email)->send(new SimpleMessageMail($title, $body, $link, 'Ir al panel de portadas'));
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
        $link = url()->route('cover.index');
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $body = sprintf(
            "Hola %s,\n\nTu portada «%s» ha sido activada y ya es la portada visible en el sitio.\n\nFecha de activación: %s",
            $creator->name,
            $name,
            $fecha
        );

        Mail::to($creator->email)->send(new SimpleMessageMail($title, $body, $link, 'Panel de portadas'));
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
        $coverName = $pendingCover->parent?->name ?: 'la portada';
        $link = url()->route('cover.index');
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $body = sprintf(
            "Hola %s,\n\nLos cambios que enviaste para %s han sido aprobados y ya están publicados.\n\nFecha: %s",
            $editor->name,
            $coverName,
            $fecha
        );

        Mail::to($editor->email)->send(new SimpleMessageMail($title, $body, $link, 'Panel de portadas'));
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
        $coverName = $pendingCover->parent?->name ?: 'la portada';
        $link = url()->route('cover.index');
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $body = sprintf(
            "Hola %s,\n\nLos cambios que enviaste para %s no han sido aceptados.\n\nFecha: %s",
            $editor->name,
            $coverName,
            $fecha
        );

        Mail::to($editor->email)->send(new SimpleMessageMail($title, $body, $link, 'Ver panel de portadas'));
    }
}

<?php

namespace App\Notifications;

use App\Mail\SimpleMessageMail;
use App\Models\SuggestedTopic;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SuggestedTopicNotificationService
{
    /**
     * Notificar al usuario que se le ha asignado un tema sugerido (al crearlo).
     */
    public static function notifyUserTopicAssigned(SuggestedTopic $topic, User $assignedUser): void
    {
        if (! $assignedUser->email) {
            return;
        }

        $title = 'Tema sugerido asignado';
        $link = url()->route('suggested-topics.show', $topic);
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $section = $topic->section ? str_replace('_', ' ', $topic->section) : '—';
        $body = sprintf(
            "Hola %s,\n\nSe te ha asignado el tema sugerido: «%s».\n\nSección: %s\nFecha: %s",
            $assignedUser->name,
            $topic->title,
            $section,
            $fecha
        );

        Mail::to($assignedUser->email)->send(new SimpleMessageMail($title, $body, $link, 'Ver y editar tema'));
    }

    /**
     * Notificar al usuario cuando el responsable del tema le asigna el tema (desde asignar a solicitante).
     */
    public static function notifyUserAssignedToTopic(SuggestedTopic $topic, User $assignedUser): void
    {
        if (! $assignedUser->email) {
            return;
        }

        $title = 'Tema sugerido asignado';
        $link = url()->route('suggested-topics.show', $topic);
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $section = $topic->section ? str_replace('_', ' ', $topic->section) : '—';
        $body = sprintf(
            "Hola %s,\n\nSe te ha asignado el tema sugerido: «%s».\n\nSección: %s\nFecha: %s",
            $assignedUser->name,
            $topic->title,
            $section,
            $fecha
        );

        Mail::to($assignedUser->email)->send(new SimpleMessageMail($title, $body, $link, 'Ver y gestionar tema'));
    }

    /**
     * Notificar al asignado del tema que alguien ha solicitado el tema.
     */
    public static function notifyAssigneeTopicRequested(SuggestedTopic $topic, User $requester): void
    {
        $assignee = $topic->assignedUser;
        if (! $assignee || ! $assignee->email) {
            return;
        }

        $title = 'Solicitud de tema sugerido';
        $link = url()->route('suggested-topics.show', $topic);
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $body = sprintf(
            "Hola %s,\n\n%s ha solicitado el tema sugerido «%s».\n\nFecha: %s",
            $assignee->name,
            $requester->name,
            $topic->title,
            $fecha
        );

        Mail::to($assignee->email)->send(new SimpleMessageMail($title, $body, $link, 'Ver ficha del tema'));
    }

    /**
     * Notificar al usuario que su solicitud de tema fue rechazada.
     */
    public static function notifyUserRequestRejected(SuggestedTopic $topic, User $rejectedUser): void
    {
        if (! $rejectedUser->email) {
            return;
        }

        $title = 'Solicitud de tema no aceptada';
        $link = url()->route('suggested-topics.index');
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $body = sprintf(
            "Hola %s,\n\nTu solicitud para el tema «%s» no ha sido aceptada.\n\nFecha: %s",
            $rejectedUser->name,
            $topic->title,
            $fecha
        );

        Mail::to($rejectedUser->email)->send(new SimpleMessageMail($title, $body, $link, 'Ver temas sugeridos'));
    }
}

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
        $body = "Hola {$assignedUser->name},\n\nSe te ha asignado el tema sugerido: «{$topic->title}».";

        Mail::to($assignedUser->email)->send(new SimpleMessageMail($title, $body));
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
        $body = "Hola {$assignedUser->name},\n\nSe te ha asignado el tema sugerido: «{$topic->title}». Puedes verlo en Temas sugeridos.";

        Mail::to($assignedUser->email)->send(new SimpleMessageMail($title, $body));
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
        $body = sprintf(
            "Hola %s,\n\n%s ha solicitado el tema sugerido «%s». Puedes asignarlo o rechazar la solicitud desde la ficha del tema.",
            $assignee->name,
            $requester->name,
            $topic->title
        );

        Mail::to($assignee->email)->send(new SimpleMessageMail($title, $body));
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
        $body = sprintf(
            "Hola %s,\n\nTu solicitud para el tema «%s» no ha sido aceptada.",
            $rejectedUser->name,
            $topic->title
        );

        Mail::to($rejectedUser->email)->send(new SimpleMessageMail($title, $body));
    }
}

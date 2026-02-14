<?php

namespace App\Notifications;

use App\Mail\SimpleMessageMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ProfileNotificationService
{
    /**
     * Notificar al usuario que su perfil fue actualizado.
     */
    public static function notifyProfileUpdated(User $user): void
    {
        if (! $user->email) {
            return;
        }

        $title = 'Perfil actualizado';
        $body = "Hola {$user->name},\n\nTu perfil en Revista Mundo Real se ha actualizado correctamente.";

        Mail::to($user->email)->send(new SimpleMessageMail($title, $body));
    }

    /**
     * Notificar al usuario que su contraseña fue cambiada (seguridad).
     */
    public static function notifyPasswordChanged(User $user): void
    {
        if (! $user->email) {
            return;
        }

        $title = 'Contraseña actualizada';
        $body = "Hola {$user->name},\n\nLa contraseña de tu cuenta en Revista Mundo Real se ha cambiado correctamente. Si no fuiste tú, contacta con el equipo.";

        Mail::to($user->email)->send(new SimpleMessageMail($title, $body));
    }
}

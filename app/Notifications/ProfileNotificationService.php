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
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $body = sprintf(
            "Hola %s,\n\nTu perfil en Revista Mundo Real se ha actualizado correctamente.\n\nFecha: %s",
            $user->name,
            $fecha
        );

        Mail::to($user->email)->send(new SimpleMessageMail(
            $title,
            $body,
            url()->route('profile'),
            'Ver o editar perfil'
        ));
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
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $body = sprintf(
            "Hola %s,\n\n"
            . "La contraseña de tu cuenta en Revista Mundo Real se ha cambiado correctamente.\n\n"
            . "Fecha: %s\n\n"
            . "Si no fuiste tú quien la cambió, te recomendamos contactar con el equipo o restablecer la contraseña desde el panel.",
            $user->name,
            $fecha
        );

        Mail::to($user->email)->send(new SimpleMessageMail($title, $body));
    }
}

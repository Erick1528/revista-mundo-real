<?php

namespace App\Notifications;

use App\Mail\NewUserWelcomeMail;
use App\Mail\SimpleMessageMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserNotificationService
{
    /**
     * Envía al nuevo usuario un correo con su contraseña temporal (en claro).
     * El administrador no ve la contraseña; solo se envía por email.
     */
    public static function notifyUserCreated(User $user, string $temporaryPassword): void
    {
        if (! $user->email) {
            return;
        }
        Mail::to($user->email)->send(new NewUserWelcomeMail($user, $temporaryPassword));
    }

    /**
     * Notifica al usuario que su cuenta ha sido desactivada (soft delete).
     */
    public static function notifyUserDeleted(User $user): void
    {
        if (! $user->email) {
            return;
        }
        $title = 'Cuenta desactivada';
        $body = sprintf(
            "Hola %s,\n\nTu cuenta en Revista Mundo Real ha sido desactivada por un administrador.\n\nSi crees que ha sido un error, contacta con el equipo de la revista.",
            $user->name
        );
        Mail::to($user->email)->send(new SimpleMessageMail($title, $body));
    }

    /**
     * Notifica al usuario que su rol ha sido modificado.
     */
    public static function notifyUserRoleChanged(User $user, string $oldRolName, string $newRolName): void
    {
        if (! $user->email) {
            return;
        }
        $title = 'Cambio de rol';
        $body = sprintf(
            "Hola %s,\n\nTu rol en Revista Mundo Real ha sido actualizado.\n\nAntes: %s\nAhora: %s\n\nPuedes acceder al panel con tu correo y contraseña habituales.",
            $user->name,
            $oldRolName,
            $newRolName
        );
        $loginUrl = url('/');
        Mail::to($user->email)->send(new SimpleMessageMail($title, $body, $loginUrl, 'Acceder al panel'));
    }

    /**
     * Notifica al usuario que su cuenta ha sido reactivada (restaurada desde la papelera).
     */
    public static function notifyUserRestored(User $user): void
    {
        if (! $user->email) {
            return;
        }
        $title = 'Cuenta reactivada';
        $body = sprintf(
            "Hola %s,\n\nTu cuenta en Revista Mundo Real ha sido reactivada. Ya puedes iniciar sesión de nuevo con tu correo y contraseña.",
            $user->name
        );
        Mail::to($user->email)->send(new SimpleMessageMail($title, $body, url('/'), 'Acceder al panel'));
    }
}

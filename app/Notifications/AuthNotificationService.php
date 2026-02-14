<?php

namespace App\Notifications;

use App\Mail\SimpleMessageMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AuthNotificationService
{
    /**
     * Notificar al usuario que se ha iniciado sesión en su cuenta (seguridad).
     */
    public static function notifyLogin(User $user): void
    {
        if (! $user->email) {
            return;
        }

        $title = 'Inicio de sesión';
        $body = "Hola {$user->name},\n\nSe ha iniciado sesión en tu cuenta de Revista Mundo Real. Si no fuiste tú, te recomendamos cambiar tu contraseña.";

        Mail::to($user->email)->send(new SimpleMessageMail($title, $body));
    }
}

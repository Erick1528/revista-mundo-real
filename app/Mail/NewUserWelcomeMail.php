<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailables\Content;

/**
 * Correo de bienvenida para un usuario recién creado.
 * Incluye la contraseña temporal (en claro) para que pueda iniciar sesión y cambiarla desde el perfil.
 */
class NewUserWelcomeMail extends BaseNotificationMail
{
    public function __construct(
        public User $user,
        public string $temporaryPassword,
        ?string $logoUrl = null
    ) {
        parent::__construct('Bienvenido a Revista Mundo Real');
        $this->logoUrl = $logoUrl ?? config('mail.logo_url');
    }

    public ?string $logoUrl = null;

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-user-welcome',
            with: [
                'title' => $this->title,
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'loginUrl' => url('/'),
                'logoUrl' => $this->logoUrl,
            ]
        );
    }
}

<?php

namespace App\Mail;

use Illuminate\Mail\Mailables\Content;

/**
 * Correo que solo muestra un título y un mensaje usando el layout común.
 * Útil para notificaciones simples sin estructura propia.
 *
 * Uso:
 *   Mail::to($user)->send(new SimpleMessageMail(
 *       title: 'Nuevo artículo en revisión',
 *       body: "El artículo \"Título\" ha sido enviado a revisión."
 *   ));
 */
class SimpleMessageMail extends BaseNotificationMail
{
    public function __construct(
        string $title,
        public string $body
    ) {
        parent::__construct($title);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.message',
            with: [
                'title' => $this->title,
                'body' => $this->body,
            ]
        );
    }
}

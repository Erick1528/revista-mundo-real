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
        public string $body,
        ?string $buttonUrl = null,
        ?string $buttonText = null,
        ?string $logoUrl = null
    ) {
        parent::__construct($title);
        $this->buttonUrl = $buttonUrl;
        $this->buttonText = $buttonText;
        $this->logoUrl = $logoUrl ?? config('mail.logo_url');
    }

    /**
     * URL del logo en cabecera (PNG/JPG recomendado; WebP/SVG no son fiables en muchos clientes de correo).
     */
    public ?string $logoUrl = null;

    /** Si se definen, se muestra un botón CTA con estilos del dashboard bajo el mensaje. */
    public ?string $buttonUrl = null;

    public ?string $buttonText = null;

    public function content(): Content
    {
        return new Content(
            view: 'emails.message',
            with: [
                'title' => $this->title,
                'body' => $this->body,
                'buttonUrl' => $this->buttonUrl,
                'buttonText' => $this->buttonText,
                'logoUrl' => $this->logoUrl,
            ]
        );
    }
}

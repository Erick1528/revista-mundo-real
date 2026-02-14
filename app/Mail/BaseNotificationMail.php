<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

abstract class BaseNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Título del correo (opcional). Se muestra como cabecera del mensaje.
     */
    public string $title;

    /**
     * Crea una notificación usando el layout común de emails.
     * En las clases hijas, asigna $this->title y pasa los datos necesarios a la vista.
     */
    public function __construct(string $title = '')
    {
        $this->title = $title;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title ?: config('app.name'),
        );
    }

    /**
     * La vista debe extender emails.layout y definir @section('content').
     * Opcionalmente @section('title') para el título dentro del cuerpo.
     */
    abstract public function content(): Content;
}

<?php

namespace App\Mail;

use App\Models\Article;
use Illuminate\Mail\Mailables\Content;

/**
 * Correo para notificaciones de artículos: card con imagen (incl. WebP), datos y botón CTA.
 * Usa el mismo layout que el resto de correos (logo, pie) y estilos alineados al dashboard.
 */
class ArticleNotificationMail extends BaseNotificationMail
{
    public function __construct(
        string $title,
        public Article $article,
        public string $introMessage,
        public string $buttonUrl,
        public string $buttonText,
        public string $fecha,
        ?string $logoUrl = null
    ) {
        parent::__construct($title);
        $this->logoUrl = $logoUrl ?? config('mail.logo_url');
    }

    public ?string $logoUrl = null;

    public function content(): Content
    {
        $imageUrl = $this->article->image_path
            ? (str_starts_with($this->article->image_path, 'http') ? $this->article->image_path : asset($this->article->image_path))
            : null;

        return new Content(
            view: 'emails.article-notification',
            with: [
                'title' => $this->title,
                'article' => $this->article,
                'articleImageUrl' => $imageUrl,
                'introMessage' => $this->introMessage,
                'buttonUrl' => $this->buttonUrl,
                'buttonText' => $this->buttonText,
                'fecha' => $this->fecha,
                'logoUrl' => $this->logoUrl,
            ]
        );
    }
}

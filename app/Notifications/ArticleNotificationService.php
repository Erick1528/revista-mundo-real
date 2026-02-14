<?php

namespace App\Notifications;

use App\Mail\SimpleMessageMail;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

/**
 * Define cuándo se envían notificaciones por email y a quién.
 *
 * Resumen:
 * - Artículo nuevo enviado a revisión → editores
 * - Artículo publicado actualizado (vuelve a revisión) → editores
 * - Artículo publicado → autor del artículo
 * - Artículo rechazado → autor del artículo
 */
class ArticleNotificationService
{
    private const EDITOR_ROLES = ['editor_chief', 'moderator', 'administrator'];

    /**
     * Obtiene los usuarios que pueden revisar/publicar (editores).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public static function editors(): \Illuminate\Database\Eloquent\Collection
    {
        return User::whereIn('rol', self::EDITOR_ROLES)->get();
    }

    /**
     * Notificar a los editores que un artículo nuevo fue enviado a revisión.
     */
    public static function notifyEditorsNewArticleInReview(Article $article): void
    {
        $editors = self::editors();
        $title = 'Nuevo artículo en revisión';
        $body = sprintf(
            "Se ha enviado un artículo a revisión.\n\nTítulo: %s\n\nPuedes revisarlo desde el panel.",
            $article->title
        );

        foreach ($editors as $user) {
            if ($user->email) {
                Mail::to($user->email)->send(new SimpleMessageMail($title, $body));
            }
        }
    }

    /**
     * Notificar a los editores que un artículo publicado fue actualizado y vuelve a revisión.
     */
    public static function notifyEditorsArticleBackToReview(Article $article): void
    {
        $editors = self::editors();
        $title = 'Artículo actualizado – en revisión';
        $body = sprintf(
            "Un artículo que estaba publicado ha sido actualizado y vuelve a revisión.\n\nTítulo: %s\n\nDebe ser revisado y publicado de nuevo desde el panel.",
            $article->title
        );

        foreach ($editors as $user) {
            if ($user->email) {
                Mail::to($user->email)->send(new SimpleMessageMail($title, $body));
            }
        }
    }

    /**
     * Notificar al autor que su artículo fue publicado.
     */
    public static function notifyAuthorArticlePublished(Article $article): void
    {
        $author = $article->user;
        if (!$author || !$author->email) {
            return;
        }

        $title = 'Tu artículo ha sido publicado';
        $body = sprintf(
            "Hola %s,\n\nTu artículo «%s» ha sido publicado en Revista Mundo Real.",
            $author->name,
            $article->title
        );

        Mail::to($author->email)->send(new SimpleMessageMail($title, $body));
    }

    /**
     * Notificar al autor que su artículo fue rechazado.
     */
    public static function notifyAuthorArticleDenied(Article $article): void
    {
        $author = $article->user;
        if (!$author || !$author->email) {
            return;
        }

        $title = 'Actualización sobre tu artículo';
        $body = sprintf(
            "Hola %s,\n\nTu artículo «%s» no ha sido aceptado para publicación en este momento.",
            $author->name,
            $article->title
        );

        Mail::to($author->email)->send(new SimpleMessageMail($title, $body));
    }

    /**
     * Notificar al autor que su artículo fue movido a la papelera (eliminado).
     */
    public static function notifyAuthorArticleDeleted(Article $article): void
    {
        $author = $article->user;
        if (! $author || ! $author->email) {
            return;
        }

        $title = 'Artículo movido a la papelera';
        $body = sprintf(
            "Hola %s,\n\nTu artículo «%s» ha sido movido a la papelera. Puedes restaurarlo desde el panel (Papelera) si lo necesitas.",
            $author->name,
            $article->title
        );

        Mail::to($author->email)->send(new SimpleMessageMail($title, $body));
    }
}

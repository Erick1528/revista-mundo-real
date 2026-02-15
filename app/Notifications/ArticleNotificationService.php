<?php

namespace App\Notifications;

use App\Mail\ArticleNotificationMail;
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
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $intro = 'Se ha enviado un artículo a revisión. Revisa los datos en la ficha y puedes publicarlo o rechazarlo desde el panel.';
        $buttonUrl = url()->route('articles.edit', $article);

        foreach ($editors as $user) {
            if ($user->email) {
                Mail::to($user->email)->send(new ArticleNotificationMail(
                    $title,
                    $article,
                    $intro,
                    $buttonUrl,
                    'Revisar artículo',
                    $fecha
                ));
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
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $intro = 'Un artículo que estaba publicado ha sido actualizado y vuelve a revisión. Debe ser revisado y publicado de nuevo desde el panel.';
        $buttonUrl = url()->route('articles.edit', $article);

        foreach ($editors as $user) {
            if ($user->email) {
                Mail::to($user->email)->send(new ArticleNotificationMail(
                    $title,
                    $article,
                    $intro,
                    $buttonUrl,
                    'Revisar artículo',
                    $fecha
                ));
            }
        }
    }

    /**
     * Notificar al autor que su artículo fue publicado.
     */
    public static function notifyAuthorArticlePublished(Article $article): void
    {
        $author = $article->user;
        if (! $author || ! $author->email) {
            return;
        }

        $title = 'Tu artículo ha sido publicado';
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $intro = sprintf('Hola %s, tu artículo ha sido publicado en Revista Mundo Real. Puedes verlo en el sitio con el botón de abajo.', $author->name);
        Mail::to($author->email)->send(new ArticleNotificationMail(
            $title,
            $article,
            $intro,
            url()->route('article.show', $article),
            'Ver artículo en el sitio',
            $fecha
        ));
    }

    /**
     * Notificar al autor que su artículo fue rechazado.
     */
    public static function notifyAuthorArticleDenied(Article $article): void
    {
        $author = $article->user;
        if (! $author || ! $author->email) {
            return;
        }

        $title = 'Actualización sobre tu artículo';
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $intro = sprintf('Hola %s, tu artículo no ha sido aceptado para publicación en este momento. Puedes editarlo y volver a enviarlo a revisión desde el panel.', $author->name);
        Mail::to($author->email)->send(new ArticleNotificationMail(
            $title,
            $article,
            $intro,
            url()->route('articles.edit', $article),
            'Editar y reenviar a revisión',
            $fecha
        ));
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
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');
        $intro = sprintf('Hola %s, tu artículo ha sido movido a la papelera. Puedes restaurarlo o eliminarlo de forma permanente desde el panel.', $author->name);
        Mail::to($author->email)->send(new ArticleNotificationMail(
            $title,
            $article,
            $intro,
            url()->route('dashboard.papelera'),
            'Ir a la papelera',
            $fecha
        ));
    }
}

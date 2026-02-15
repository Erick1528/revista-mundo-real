<?php

use App\Models\Article;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('articles:purge-trash', function () {
    $cutoff = now()->subDays(30);
    $articles = Article::onlyTrashed()->where('deleted_at', '<', $cutoff)->get();
    $count = 0;
    foreach ($articles as $article) {
        $article->deleteMainImageFromStorage();
        $article->deleteContentImagesFromStorage();
        $article->forceDelete();
        $count++;
    }
    $this->info($count === 0
        ? 'Ningún artículo llevaba más de 30 días en la papelera.'
        : "Eliminados permanentemente {$count} artículo(s) en papelera más de 30 días.");
})->purpose('Elimina artículos que llevan más de 30 días en la papelera');

Schedule::command('articles:purge-trash')->daily();

// Cola de correos: cada minuto (si el cron ejecuta schedule:run cada minuto).
// En algunos hostings compartidos este comando no llega a ejecutarse desde el scheduler.
// Si los jobs no se procesan, añade un segundo cron que ejecute directamente:
//   cd /ruta/al/proyecto && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
Schedule::command('queue:work --stop-when-empty')->everyMinute();

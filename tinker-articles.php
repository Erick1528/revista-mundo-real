<?php
// Comando para usar en php artisan tinker

// Asegurar que existe un usuario
$user = \App\Models\User::first() ?? \App\Models\User::factory()->create([
    'name' => 'Redactor Principal',
    'email' => 'redactor@revista.com',
]);

// Crear artículos uno por uno usando Tinker
\App\Models\Article::create([
    'title' => 'Fortaleza de San Fernando de Omoa: Guardiana del Caribe Hondureño',
    'subtitle' => 'Una joya colonial que resiste el paso del tiempo en las costas de Honduras',
    'attribution' => 'Ana Martínez',
    'summary' => 'Descubre la majestuosa fortaleza colonial española que protegió las costas del Caribe durante siglos.',
    'slug' => 'fortaleza-san-fernando-omoa-guardiana-caribe-hondureno',
    'image_path' => 'articles/fortaleza-omoa.jpg',
    'visibility' => 'public',
    'status' => 'published',
    'published_at' => now()->subDays(5),
    'section' => 'destinations',
    'tags' => ['honduras', 'historia', 'colonial', 'caribe', 'turismo', 'fortaleza'],
    'content' => [
        [
            'type' => 'paragraph',
            'content' => 'La Fortaleza de San Fernando de Omoa se alza imponente frente al mar Caribe, como un testigo silencioso de la rica historia colonial de Honduras.'
        ]
    ],
    'view_count' => 150,
    'reading_time' => 12,
    'meta_description' => 'Explora la Fortaleza de San Fernando de Omoa, una joya colonial del Caribe hondureño.',
    'user_id' => $user->id,
]);

\App\Models\Article::create([
    'title' => 'Costa Brava: El Encanto Mediterráneo de Girona',
    'subtitle' => 'Calas cristalinas y pueblos pintorescos en la joya de Cataluña',
    'attribution' => 'María Fernández',
    'summary' => 'Un recorrido por la espectacular Costa Brava, donde el Mediterráneo muestra su cara más seductora.',
    'slug' => 'costa-brava-encanto-mediterraneo-girona',
    'image_path' => 'articles/costa-brava-girona.jpg',
    'visibility' => 'public',
    'status' => 'published',
    'published_at' => now()->subDays(3),
    'section' => 'destinations',
    'tags' => ['costa-brava', 'girona', 'mediterráneo', 'españa', 'turismo', 'playas'],
    'content' => [
        [
            'type' => 'paragraph',
            'content' => 'La Costa Brava de Girona es un regalo de la naturaleza donde el azul intenso del Mediterráneo se funde con los verdes pinos.'
        ]
    ],
    'view_count' => 200,
    'reading_time' => 8,
    'meta_description' => 'Descubre la magia de la Costa Brava en Girona: calas cristalinas y pueblos encantadores.',
    'user_id' => $user->id,
]);

// Continúa creando más artículos...

// Para ver todos los artículos creados:
\App\Models\Article::all();

// Para ver solo los títulos:
\App\Models\Article::pluck('title');
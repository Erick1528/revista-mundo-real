<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CoverController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuggestedTopicController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// Dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('dashboard/papelera', [DashboardController::class, 'papelera'])->name('dashboard.papelera')->middleware('auth');

// Portadas: cualquier usuario autenticado puede listar, crear y editar; solo editor_chief, moderator y administrator pueden activar/publicar y aprobar/rechazar cambios
Route::get('portadas', [CoverController::class, 'index'])
    ->name('cover.index')
    ->middleware('auth');
Route::get('portadas/nueva', [CoverController::class, 'manage'])
    ->name('cover.manage')
    ->middleware('auth');
Route::get('portadas/{cover}/editar', [CoverController::class, 'edit'])
    ->name('cover.edit')
    ->middleware('auth');
Route::post('portadas/{cover}/activar', [CoverController::class, 'activate'])
    ->name('cover.activate')
    ->middleware('auth');
Route::post('portadas/{cover}/aprobar', [CoverController::class, 'approvePending'])
    ->name('cover.approve')
    ->middleware('auth');
Route::post('portadas/{cover}/rechazar', [CoverController::class, 'rejectPending'])
    ->name('cover.reject')
    ->middleware('auth');

// Temas Sugeridos
Route::get('temas-sugeridos', [SuggestedTopicController::class, 'index'])
    ->name('suggested-topics.index')
    ->middleware('auth');
Route::get('temas-sugeridos/crear', [SuggestedTopicController::class, 'create'])
    ->name('suggested-topics.create')
    ->middleware('auth');
Route::get('temas-sugeridos/{topic}', [SuggestedTopicController::class, 'show'])
    ->name('suggested-topics.show')
    ->middleware('auth');
Route::get('temas-sugeridos/{topic}/editar', [SuggestedTopicController::class, 'edit'])
    ->name('suggested-topics.edit')
    ->middleware('auth');

// Content Creation and Management
    Route::get('articles/create', [ArticleController::class, 'create'])->name('articles.create')->middleware('auth');
    Route::get('articles/{article:slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit')->middleware('auth');

    // Profile Management
    Route::get('profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Article View
Route::get('article/{article:slug}', [ArticleController::class, 'show'])->name('article.show'); // Agregar comprobación de permisos antes de mostrar, si no es status published no se debe de mostrar.

// TODO:

// Mirar como hacer la funcionalidad de programar la publicación de un artículo a cierta hora y fecha

// Hacer funcionalidad de los botones de compartir en redes sociales
// Crear CRUD para nuevos editores o usuarios con permisos especiales

// Agregar campo de alt para imagen y credito para el bloque de galeria y que sean esos dos campos para cada foto no solo uno.

// Hacer funcionalidad de borrador de artículos para poder editarlos posteriormente

// Hacer funcion de contador de vistas y guardando la url que se visita y un id unico generado de la ip para que no se pueda repetir y se pueda hacer un analisis de las visitas a los articulos o guardar Ip y pedir cookies para estandarizar el contador de vistas.

// Muy Importante:
// Agregar bloque de anuncio en el editor de contenido
// Agregar en el select de sección la opción de "Anuncio" y que sea valida en la DB
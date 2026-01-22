<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// Dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

    // Content Creation and Management
    Route::get('articles/create', [ArticleController::class, 'create'])->name('articles.create')->middleware('auth');
    Route::get('articles/{article:slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit')->middleware('auth');

// Article View
Route::get('article/{article:slug}', [ArticleController::class, 'show'])->name('article.show'); // Agregar comprobación de permisos antes de mostrar, si no es status published no se debe de mostrar.

// TODO:
// Crear Panel para poder gestionar la portada de la revista con drag and drop con alpine.js
// Mirar como hacer la funcionalidad de programar la publicación de un artículo a cierta hora y fecha
// Crear vista de perfil
// Crear botones en la vista del articulo para cambiar estado (publicado, borrador, pendiente revisión), esto solo para usuarios con permisos de editor o admin o moderator creo
// Hacer funcionalidad de los botones de compartir en redes sociales
// Agregar Mails para notificaciones de nuevos artículos, revisiones, etc.
// Crear CRUD para nuevos editores o usuarios con permisos especiales

// Muy Importante:
// Agregar bloque de anuncio en el editor de contenido
// Agregar en el select de sección la opción de "Anuncio" y que sea valida en la DB
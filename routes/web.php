<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// Dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

    // Content Creation and Management
    Route::get('articles/create', [ArticleController::class, 'create'])->name('articles.create')->middleware('auth');
    Route::get('articles/{article:slug}/edit', [ArticleController::class, 'edit'])->name('articles.edit')->middleware('auth');

    // Profile Management
    Route::get('profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

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

// Agregar campo de alt para imagen y credito para el bloque de galeria y que sean esos dos campos para cada foto no solo uno.

// Hacer funcionalidad de borrador de artículos para poder editarlos posteriormente
// Hacer funcionalidad de borrar articulo por medio de soft delete para recuperarlos posteriormente si es necesario.

// Hacer funcion de contador de vistas y guardando la url que se visita y un id unico generado de la ip para que no se pueda repetir y se pueda hacer un analisis de las visitas a los articulos o guardar Ip y pedir cookies para estandarizar el contador de vistas.

// Muy Importante:
// Agregar bloque de anuncio en el editor de contenido
// Agregar en el select de sección la opción de "Anuncio" y que sea valida en la DB

// Crear CRUD para agreagr lista de temas sugeridos para los articulos.
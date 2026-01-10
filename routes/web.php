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

// Article View
Route::get('article/{article:slug}', [ArticleController::class, 'show'])->name('article.show')->middleware('auth'); // Agregar comprobación de permisos antes de mostrar, si no es status published no se debe de mostrar.
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
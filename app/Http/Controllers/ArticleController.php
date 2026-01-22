<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function create()
    {
        return view('dashboard.articles.create');
    }

    public function edit(Article $article)
    {
        return view('dashboard.articles.edit', compact('article'));
    }

    public function show(Article $article)
    {
        return view('show-article', compact('article'));
    }
}

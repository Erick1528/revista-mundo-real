@extends('layouts.index')

@php
    $shareImagePath = $article->image_jpg_path ?? $article->image_path;
    $shareImageUrl = $shareImagePath ? rtrim(config('app.url'), '/') . '/' . ltrim($shareImagePath, '/') : null;
    $shareDescription = $article->share_description;
    $shareTitle = e($article->title);
    $shareDescriptionEscaped = e($shareDescription);
@endphp

@section('title', $article->title)

@section('description', $article->meta_description ?: $shareDescription)

@section('keywords', $article->tags && is_array($article->tags) && count($article->tags) > 0 ? implode(', ', $article->tags) : 'revista, mundo real, internacional, Honduras, España, Estados Unidos, cultura, destinos, gastronomía')

@section('og_type', 'article')

@section('og_title', $shareTitle)

@section('og_description', $shareDescriptionEscaped)

@if($shareImageUrl)
@section('og_image', $shareImageUrl)
@if(str_starts_with($shareImageUrl, 'https'))
@section('og_image_secure_url', $shareImageUrl)
@endif
@section('og_image_width', '1200')
@section('og_image_height', '630')
@section('og_image_type', $article->image_jpg_path ? 'image/jpeg' : 'image/webp')
@section('og_image_alt', e($article->image_alt_text ?? $article->title))
@endif

@section('twitter_title', $shareTitle)

@section('twitter_description', $shareDescriptionEscaped)

@if($shareImageUrl)
@section('twitter_image', $shareImageUrl)
@section('twitter_image_alt', e($article->image_alt_text ?? $article->title))
@endif

@section('hero')
    <livewire:hero />
@endsection

@section('bg-content', 'bg-gray-super-light')

@section('content')
    <livewire:show-article :article="$article" />
@endsection

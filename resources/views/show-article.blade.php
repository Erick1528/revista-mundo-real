@extends('layouts.index')

@push('head')
    {{-- Metatags dinámicos para SEO --}}
    @if($article->meta_description)
        <meta name="description" content="{{ $article->meta_description }}">
    @endif
    @if($article->tags && is_array($article->tags) && count($article->tags) > 0)
        <meta name="keywords" content="{{ implode(', ', $article->tags) }}">
    @endif
    {{-- Open Graph / Facebook --}}
    <meta property="og:title" content="{{ $article->title }}">
    @if($article->summary)
        <meta property="og:description" content="{{ $article->summary }}">
    @endif
    <meta property="og:type" content="article">
    @if($article->image_path)
        <meta property="og:image" content="{{ asset($article->image_path) }}">
    @endif
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $article->title }}">
    @if($article->summary)
        <meta name="twitter:description" content="{{ $article->summary }}">
    @endif
    @if($article->image_path)
        <meta name="twitter:image" content="{{ asset($article->image_path) }}">
    @endif
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@section('title', $article->title)

@section('hero')
    <livewire:hero />
@endsection

@section('bg-content', 'bg-gray-super-light')

@section('content')
    <livewire:show-article :article="$article" />
@endsection

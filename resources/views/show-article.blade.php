@extends('layouts.index')

@push('head')
    @php
        $shareImagePath = $article->image_jpg_path ?? $article->image_path;
        $shareImageUrl = $shareImagePath ? url($shareImagePath) : null;
        $shareDescription = $article->share_description;
        $canonicalUrl = url()->current();
    @endphp
    {{-- Metatags dinámicos para SEO --}}
    @if($article->meta_description)
        <meta name="description" content="{{ $article->meta_description }}">
    @endif
    @if($article->tags && is_array($article->tags) && count($article->tags) > 0)
        <meta name="keywords" content="{{ implode(', ', $article->tags) }}">
    @endif
    {{-- Open Graph / Facebook --}}
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:title" content="{{ $article->title }}">
    @if($shareDescription)
        <meta property="og:description" content="{{ $shareDescription }}">
    @endif
    <meta property="og:type" content="article">
    @if($shareImageUrl)
        <meta property="og:image" content="{{ $shareImageUrl }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    @endif
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $article->title }}">
    @if($shareDescription)
        <meta name="twitter:description" content="{{ $shareDescription }}">
    @endif
    @if($shareImageUrl)
        <meta name="twitter:image" content="{{ $shareImageUrl }}">
    @endif
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endpush

@section('title', $article->title)

@section('hero')
    <livewire:hero />
@endsection

@section('bg-content', 'bg-gray-super-light')

@section('content')
    <livewire:show-article :article="$article" />
@endsection

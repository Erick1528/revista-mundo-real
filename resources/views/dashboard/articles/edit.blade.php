@extends('layouts.index')

@section('title', 'Editar Artículo ' . $article->title)

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <livewire:update-article :article="$article" />
@endsection
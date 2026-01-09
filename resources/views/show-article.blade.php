@extends('layouts.index')

@section('title', $article->title)

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <livewire:show-article :article="$article" />
@endsection

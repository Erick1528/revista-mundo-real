@extends('layouts.index')

@section('title', $article->title)

@section('hero')
    <livewire:hero />
@endsection

@section('bg-content', 'bg-gray-super-light')

@section('content')
    <livewire:show-article :article="$article" />
@endsection

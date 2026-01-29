@extends('layouts.index')

@section('title', 'Ver Tema Sugerido - ' . $topic->title)

@section('hero')
    <livewire:hero />
@endsection

@section('bg-content', 'bg-gray-super-light')

@section('content')
    <livewire:show-suggested-topic :topic="$topic" />
@endsection

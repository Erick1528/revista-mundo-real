@extends('layouts.index')

@section('title', 'Editar Tema Sugerido - ' . $topic->title)

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <livewire:edit-suggested-topic :topic="$topic" />
@endsection

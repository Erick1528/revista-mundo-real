@extends('layouts.index')

@section('title', 'Temas Sugeridos')

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <livewire:suggested-topic-list />
@endsection

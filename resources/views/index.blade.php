@extends('layouts.index')

@section('title', 'Inicio')

@section('content')
    
    {{-- Componente con los 4 primeros artículos --}}
    <livewire:main-articles />

    {{-- Componente con los siguientes 3 artículos --}}
    <livewire:mid-articles />

    {{-- Componente con los últimos 4 artículos --}}
    <livewire:latest-articles />

@endsection
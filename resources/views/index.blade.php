@extends('layouts.index')

@section('title', 'Inicio')

@section('content')
    
    {{-- Componente con los 4 primeros artículos --}}
    <livewire:main-articles />

@endsection
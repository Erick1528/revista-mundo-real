@extends('layouts.index')

@section('title', 'Dashboard')

@section('hero')
    {{-- Hacer Hero y NavBar personalizado para dashboard o crear layout para dashboard y usar el extends de ese --}}
    <livewire:hero />
@endsection

@section('content')
    <livewire:dashboard />
@endsection
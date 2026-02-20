@extends('layouts.index')

@section('title', 'Perfil de Usuario')

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <livewire:show-user :user="$user" />
@endsection

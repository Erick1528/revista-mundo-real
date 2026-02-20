@extends('layouts.index')

@section('title', 'Editar usuario - ' . $user->name)

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <livewire:edit-user :user="$user" />
@endsection

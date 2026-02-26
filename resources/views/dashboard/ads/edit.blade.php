@extends('layouts.index')

@section('title', 'Editar anuncio - ' . $ad->name)

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <livewire:edit-ad :ad="$ad" />
@endsection

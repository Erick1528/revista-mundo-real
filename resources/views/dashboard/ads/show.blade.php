@extends('layouts.index')

@section('title', 'Vista previa - ' . $ad->name)

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <livewire:show-ad :ad="$ad" />
@endsection

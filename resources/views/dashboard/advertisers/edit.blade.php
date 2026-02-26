@extends('layouts.index')

@section('title', 'Editar anunciante - ' . $advertiser->name)

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <livewire:edit-advertiser :advertiser="$advertiser" />
@endsection

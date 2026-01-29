@extends('layouts.index')

@section('title', 'Temas Sugeridos')

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <div class="px-4 sm:px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
            <h2 class="font-serif text-2xl sm:text-3xl text-primary">Temas Sugeridos</h2>
            <a href="{{ route('suggested-topics.create') }}"
                class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-12 px-6 bg-primary text-white text-base font-semibold font-montserrat transition-colors hover:bg-dark-sage">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Nueva Sugerencia
            </a>
        </div>

        @if (session('message'))
            <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 mb-6">
                <div class="flex items-center justify-between">
                    <span class="font-opensans text-sm">{{ session('message') }}</span>
                    <button type="button" onclick="this.parentElement.parentElement.remove()"
                        class="text-green-600 hover:text-green-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="w-full p-4 bg-red-50 border border-red-200 text-red-800 mb-6">
                <div class="flex items-center justify-between">
                    <span class="font-opensans text-sm">{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.parentElement.remove()"
                        class="text-red-600 hover:text-red-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <livewire:suggested-topic-list />
    </div>
@endsection

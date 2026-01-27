@extends('layouts.index')

@section('title', 'Portadas')

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <div class="px-4 sm:px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
            <h2 class="font-serif text-2xl sm:text-3xl text-primary">Portadas</h2>
            <a href="{{ route('cover.manage') }}"
                class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-12 px-6 bg-primary text-white text-base font-semibold font-montserrat transition-colors hover:bg-dark-sage">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Nueva portada
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

        <div class="space-y-3 sm:space-y-4">
            @forelse($covers as $cover)
                <div class="border border-gray-lighter p-4 sm:p-6 hover:bg-sage/30 transition-all duration-200 overflow-hidden">
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-start sm:justify-between">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-serif text-lg sm:text-xl text-primary break-words">{{ $cover->name ?: 'Sin nombre' }}</h3>
                            <p class="font-opensans text-xs sm:text-sm text-gray-light mt-1 break-words">
                                @if($cover->updated_at->isAfter(now()->subDays(30)))
                                    {{ $cover->updated_at->locale('es')->diffForHumans() }}
                                @else
                                    {{ $cover->updated_at->locale('es')->translatedFormat('M j \d\e Y') }}
                                @endif
                                @if($cover->editor)
                                    · Editado por <span class="break-all">{{ $cover->editor->name ?? '—' }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2 items-center shrink-0">
                            <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider whitespace-nowrap
                                {{ $cover->status === 'published' ? 'bg-green-light text-white' : '' }}
                                {{ $cover->status === 'pending_review' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $cover->status === 'draft' ? 'bg-gray-200 text-gray-700' : '' }}
                                {{ $cover->status === 'archived' ? 'bg-gray-lighter text-gray-light' : '' }}
                                {{ !in_array($cover->status, ['published','pending_review','draft','archived']) ? 'bg-gray-lighter text-gray-light' : '' }}">
                                {{ $cover->status_name }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider whitespace-nowrap
                                {{ $cover->visibility === 'public' ? 'bg-dark-sage text-white' : 'bg-gray-300 text-gray-800' }}">
                                {{ $cover->visibility_name }}
                            </span>
                            @if($cover->scheduled_at)
                                <span class="text-xs font-opensans text-gray-light whitespace-nowrap">
                                    Inicio: {{ $cover->scheduled_at->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="border border-gray-lighter p-6 sm:p-8 text-center">
                    <p class="font-opensans text-sm sm:text-base text-gray-light mb-4">Aún no hay portadas. Crea la primera para montar los artículos y publicar.</p>
                    <a href="{{ route('cover.manage') }}"
                        class="inline-flex items-center justify-center gap-2 h-12 px-6 bg-primary text-white text-base font-semibold font-montserrat transition-colors hover:bg-dark-sage">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Nueva portada
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection

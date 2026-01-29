<div>
    {{-- Filtros (mismo diseño que dashboard artículos) --}}
    <div class="mb-8 space-y-4">
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-light font-opensans">Filtrar portadas</span>
            <button type="button" wire:click="clearFilters"
                class="hidden sm:block text-sm text-gray-light hover:text-primary transition-colors font-opensans">
                Ver Todos
            </button>
        </div>

        {{-- Buscador --}}
        <div class="w-full max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre..."
                class="w-full px-4 py-3 border border-gray-lighter text-primary bg-white font-opensans text-sm focus:outline-none focus:border-dark-sage transition-colors">
        </div>

        {{-- Filtros por categorías --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <select wire:model.live="statusFilter"
                class="w-full px-3 py-3 sm:py-2 text-xs font-semibold uppercase font-montserrat tracking-wider border border-gray-lighter text-gray-light bg-white focus:outline-none focus:border-dark-sage transition-colors">
                <option value="">Estado</option>
                <option value="draft">Borrador</option>
                <option value="pending_review">Pendiente de revisión</option>
                <option value="published">Publicada</option>
                <option value="archived">Archivada</option>
            </select>

            <select wire:model.live="visibilityFilter"
                class="w-full px-3 py-3 sm:py-2 text-xs font-semibold uppercase font-montserrat tracking-wider border border-gray-lighter text-gray-light bg-white focus:outline-none focus:border-dark-sage transition-colors">
                <option value="">Visibilidad</option>
                <option value="public">Público</option>
                <option value="private">Privado</option>
            </select>

            <select wire:model.live="activeFilter"
                class="w-full px-3 py-3 sm:py-2 text-xs font-semibold uppercase font-montserrat tracking-wider border border-gray-lighter text-gray-light bg-white focus:outline-none focus:border-dark-sage transition-colors">
                <option value="">Activa</option>
                <option value="active">Solo activa</option>
                <option value="inactive">Solo inactivas</option>
            </select>
        </div>

        {{-- Botón Ver Todos para móviles --}}
        <div class="sm:hidden">
            <button type="button" wire:click="clearFilters"
                class="w-full px-4 py-3 border border-gray-lighter text-gray-light hover:text-primary hover:border-dark-sage transition-colors font-opensans text-sm bg-white">
                Ver Todos
            </button>
        </div>
    </div>

    {{-- Lista de portadas --}}
    <div class="space-y-3 sm:space-y-4">
        @forelse($covers as $cover)
            <div class="border border-gray-lighter p-4 sm:p-6 hover:bg-sage/30 transition-all duration-200 overflow-hidden {{ $cover->is_active ? 'border-l-4 border-l-primary' : '' }}">
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-serif text-lg sm:text-xl text-primary break-words">{{ $cover->name ?: 'Sin nombre' }}</h3>
                            @if($cover->is_active)
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase font-montserrat tracking-wider bg-primary text-white">
                                    Activa
                                </span>
                            @endif
                        </div>
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
                        @if($cover->is_active && $cover->activator)
                            <p class="font-opensans text-xs text-gray-light mt-0.5">
                                Activado por {{ $cover->activator->name }}
                                @if($cover->activated_at)
                                    · {{ $cover->activated_at->locale('es')->diffForHumans() }}
                                @endif
                            </p>
                        @endif
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
                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-lighter">
                    <a href="{{ route('cover.edit', $cover) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-montserrat font-medium text-primary border border-primary hover:bg-sage transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                    @if($canActivate && !$cover->is_active)
                        <form id="activate-form-{{ $cover->id }}" action="{{ route('cover.activate', $cover) }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        <button type="button"
                            @click="openActivateModal({{ $cover->id }}, '{{ addslashes($cover->name ?: 'Sin nombre') }}', '{{ $cover->status }}')"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-montserrat font-medium text-white bg-primary hover:bg-dark-sage transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Activar
                        </button>
                    @endif
                </div>

                @if($cover->pendingVersions->isNotEmpty() && $canActivate)
                    @foreach($cover->pendingVersions as $pending)
                        <div class="mt-4 ml-4 sm:ml-6 pl-4 border-l-2 border-yellow-400 bg-yellow-50/50 p-3 sm:p-4">
                            <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase font-montserrat tracking-wider bg-yellow-500 text-white">
                                            Cambios pendientes
                                        </span>
                                    </div>
                                    <p class="font-opensans text-xs text-gray-light mt-1">
                                        @if($pending->updated_at->isAfter(now()->subDays(30)))
                                            {{ $pending->updated_at->locale('es')->diffForHumans() }}
                                        @else
                                            {{ $pending->updated_at->locale('es')->translatedFormat('M j \d\e Y') }}
                                        @endif
                                        @if($pending->editor)
                                            · por {{ $pending->editor->name ?? '—' }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex flex-wrap gap-2 items-center shrink-0">
                                    <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider whitespace-nowrap
                                        {{ $pending->status === 'pending_review' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-200 text-gray-700' }}">
                                        {{ $pending->status_name }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-yellow-200">
                                <a href="{{ route('cover.edit', $pending) }}"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-montserrat font-medium text-white bg-primary hover:bg-dark-sage transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Ver cambios
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @empty
            <div class="border border-gray-lighter p-6 sm:p-8 text-center">
                @if($hasActiveFilters)
                    <p class="font-opensans text-sm sm:text-base text-gray-light mb-4">No se encontraron portadas con los filtros aplicados.</p>
                    <button type="button" wire:click="clearFilters"
                        class="text-dark-sage hover:text-primary transition-colors font-opensans text-sm">
                        Limpiar filtros
                    </button>
                @else
                    <p class="font-opensans text-sm sm:text-base text-gray-light mb-4">Aún no hay portadas. Crea la primera para montar los artículos y publicar.</p>
                    <a href="{{ route('cover.manage') }}"
                        class="inline-flex items-center justify-center gap-2 h-12 px-6 bg-primary text-white text-base font-semibold font-montserrat transition-colors hover:bg-dark-sage">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Nueva portada
                    </a>
                @endif
            </div>
        @endforelse
    </div>
</div>

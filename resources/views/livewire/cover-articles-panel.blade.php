<div class="flex-1 min-h-0 flex flex-col overflow-hidden" x-data="{ search: '', filtersOpen: false, myVisibleCount: {{ $myArticles->count() }}, allVisibleCount: {{ $allArticles->count() }} }" x-init="$watch('search', value => { if(value) { $wire.call('openAllSections'); } updateVisibleCounts(); }); function updateVisibleCounts() { const myItems = document.querySelectorAll('#section-my li[data-searchable]'); const allItems = document.querySelectorAll('#section-all li[data-searchable]'); myVisibleCount = Array.from(myItems).filter(item => !search || item.dataset.searchable.includes(search.toLowerCase())).length; allVisibleCount = Array.from(allItems).filter(item => !search || item.dataset.searchable.includes(search.toLowerCase())).length; }">
    {{-- Barra de búsqueda --}}
    <div class="shrink-0 px-2 sm:px-3 pt-2 sm:pt-3 pb-1.5 sm:pb-2 space-y-1.5 sm:space-y-2">
        <label for="cover-panel-search" class="sr-only">Buscar artículo</label>
        <input type="search"
            id="cover-panel-search"
            x-model="search"
            placeholder="Buscar artículo..."
            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-opensans border border-gray-300 bg-gray-50 text-primary placeholder:text-gray-light focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)]">
    </div>

    <div class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden scrollbar-auto-hide px-2 sm:px-3 pb-2 sm:pb-3 space-y-2 sm:space-y-3 min-w-0 max-w-full">
        {{-- Panel de filtros --}}
        <div class="border border-gray-lighter min-w-0">
            <button type="button" @click="filtersOpen = !filtersOpen"
                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors min-w-0">
                <span class="font-montserrat font-medium text-primary text-sm sm:text-base min-w-0 truncate">Filtros</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-light shrink-0 transform transition-transform" :class="{ 'rotate-90': filtersOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div x-show="filtersOpen" class="px-3 sm:px-4 py-3 sm:py-3 space-y-3 sm:space-y-3 border-t border-gray-lighter min-w-0">
            {{-- Usuario (solo para usuarios con permisos especiales) --}}
            @if($canFilterByUser)
                <div class="space-y-1.5">
                    <label for="filter-user" class="block text-xs font-montserrat font-medium text-primary">Usuario</label>
                    <select id="filter-user" wire:model.live="filterUser"
                        class="w-full px-3 py-2 text-xs font-opensans border border-gray-300 bg-gray-50 text-primary focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)]">
                        <option value="">Todos</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Sección --}}
            <div class="space-y-1.5">
                <label for="filter-section" class="block text-xs font-montserrat font-medium text-primary">Sección</label>
                <select id="filter-section" wire:model.live="filterSection"
                    class="w-full px-3 py-2 text-xs font-opensans border border-gray-300 bg-gray-50 text-primary focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)]">
                    <option value="">Todas</option>
                    <option value="destinations">Destinos</option>
                    <option value="inspiring_stories">Historias que Inspiran</option>
                    <option value="social_events">Eventos Sociales</option>
                    <option value="health_wellness">Salud y Bienestar</option>
                    <option value="gastronomy">Gastronomía con Identidad</option>
                    <option value="living_culture">Cultura Viva</option>
                </select>
            </div>

            {{-- Estado --}}
            <div class="space-y-1.5">
                <label for="filter-status" class="block text-xs font-montserrat font-medium text-primary">Estado</label>
                <select id="filter-status" wire:model.live="filterStatus"
                    class="w-full px-3 py-2 text-xs font-opensans border border-gray-300 bg-gray-50 text-primary focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)]">
                    <option value="">Todos</option>
                    <option value="draft">Borrador</option>
                    <option value="review">En Revisión</option>
                    <option value="published">Publicado</option>
                    <option value="denied">Rechazado</option>
                </select>
            </div>

            {{-- Visibilidad --}}
            <div class="space-y-1.5">
                <label for="filter-visibility" class="block text-xs font-montserrat font-medium text-primary">Visibilidad</label>
                <select id="filter-visibility" wire:model.live="filterVisibility"
                    class="w-full px-3 py-2 text-xs font-opensans border border-gray-300 bg-gray-50 text-primary focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)]">
                    <option value="">Todas</option>
                    <option value="public">Público</option>
                    <option value="private">Privado</option>
                </select>
            </div>

            {{-- Fechas --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1.5">
                    <label for="filter-date-from" class="block text-xs font-montserrat font-medium text-primary">Desde</label>
                    <input type="date" id="filter-date-from" wire:model.live="filterDateFrom"
                        class="w-full px-3 py-2 text-xs font-opensans border border-gray-300 bg-gray-50 text-primary focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)]">
                </div>
                <div class="space-y-1.5">
                    <label for="filter-date-to" class="block text-xs font-montserrat font-medium text-primary">Hasta</label>
                    <input type="date" id="filter-date-to" wire:model.live="filterDateTo"
                        class="w-full px-3 py-2 text-xs font-opensans border border-gray-300 bg-gray-50 text-primary focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)]">
                </div>
            </div>

            {{-- Botón limpiar filtros --}}
            @if($filterUser || $filterSection || $filterStatus || $filterVisibility || $filterDateFrom || $filterDateTo)
                <button type="button" wire:click="clearFilters"
                    class="w-full bg-transparent text-primary py-2 px-3 border border-primary font-montserrat font-medium text-xs relative overflow-hidden hover:text-white transition-colors duration-300 group">
                    <span class="absolute inset-0 bg-primary transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out"></span>
                    <span class="relative z-10">Limpiar filtros</span>
                </button>
            @endif
            </div>
        </div>

        {{-- Mis artículos --}}
        <div class="border border-gray-lighter min-w-0">
            <button wire:click="toggleSection('my')" type="button"
                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors min-w-0">
                <span class="font-montserrat font-medium text-primary text-sm sm:text-base min-w-0 truncate">Mis artículos</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-light shrink-0 transform transition-transform @if ($openSections['my']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-my"
                class="accordion-content px-2 sm:px-3 py-2 sm:py-3 space-y-1.5 sm:space-y-2 border-t border-gray-lighter min-w-0 @if (!$openSections['my']) hidden @endif">
                @php
                    $hasFilters = $filterSection || $filterStatus || $filterVisibility || $filterDateFrom || $filterDateTo;
                @endphp
                @if ($myArticles->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-sm text-gray-light font-opensans mb-1">Aún no hay artículos.</p>
                        <p class="text-xs text-gray-400 font-opensans">Crea tu primer artículo para comenzar.</p>
                    </div>
                @else
                    <ul class="space-y-1.5 sm:space-y-2" style="list-style: none;" x-show="myVisibleCount > 0">
                        @foreach ($myArticles as $article)
                            @php
                                $searchable = Str::lower($article->title . ' ' . ($article->section_name ?? '') . ' ' . ($article->status_name ?? ''));
                            @endphp
                            <li class="flex gap-1.5 sm:gap-2 border border-gray-lighter p-1 sm:p-1.5 bg-sage cursor-grab active:cursor-grabbing hover:border-dark-sage transition-colors min-w-0"
                                draggable="true"
                                data-article-id="{{ $article->id }}"
                                data-searchable="{{ e($searchable) }}"
                                x-show="!search || $el.dataset.searchable.includes(search.toLowerCase())"
                                x-init="$watch('search', () => { setTimeout(() => { const parent = $el.closest('[x-data]'); if (parent && parent.__x) { parent.__x.$data.updateVisibleCounts(); } }, 10); })"
                                ondragstart="event.dataTransfer.setData('text/plain', '{{ $article->id }}'); event.dataTransfer.effectAllowed = 'move';">
                                <div class="shrink-0 w-12 h-12 sm:w-16 sm:h-16 overflow-hidden bg-gray-lighter flex items-center justify-center">
                                    @if ($article->image_path)
                                        <img src="{{ asset($article->image_path) }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                    @else
                                        <span class="text-gray-400 text-[10px] sm:text-xs font-opensans">Sin img</span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1 flex flex-col justify-center">
                                    <p class="font-montserrat text-xs sm:text-sm text-primary line-clamp-2 break-words">{{ $article->title }}</p>
                                    <p class="text-[10px] sm:text-xs text-gray-light font-opensans mt-0.5 truncate">{{ $article->section_name }} · {{ $article->status_name }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div x-show="search && myVisibleCount === 0" class="text-center py-8">
                        <p class="text-sm text-gray-light font-opensans mb-1">No se encontraron artículos.</p>
                        <p class="text-xs text-gray-400 font-opensans">Intenta ajustar la búsqueda.</p>
                    </div>
                    @if($hasFilters && $myArticles->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-light font-opensans mb-1">No se encontraron artículos.</p>
                            <p class="text-xs text-gray-400 font-opensans">Intenta ajustar los filtros.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- Todos los artículos --}}
        <div class="border border-gray-lighter min-w-0">
            <button wire:click="toggleSection('all')" type="button"
                class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors min-w-0">
                <span class="font-montserrat font-medium text-primary text-sm sm:text-base min-w-0 truncate">Todos los artículos</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-light shrink-0 transform transition-transform @if ($openSections['all']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-all"
                class="accordion-content px-2 sm:px-3 py-2 sm:py-3 space-y-1.5 sm:space-y-2 border-t border-gray-lighter min-w-0 @if (!$openSections['all']) hidden @endif">
                @php
                    $hasFilters = $filterUser || $filterSection || $filterStatus || $filterVisibility || $filterDateFrom || $filterDateTo;
                @endphp
                @if ($allArticles->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-sm text-gray-light font-opensans mb-1">No hay artículos.</p>
                        <p class="text-xs text-gray-400 font-opensans">Crea un artículo para comenzar.</p>
                    </div>
                @else
                    <ul class="space-y-1.5 sm:space-y-2" style="list-style: none;" x-show="allVisibleCount > 0">
                        @foreach ($allArticles as $article)
                            @php
                                $searchable = Str::lower($article->title . ' ' . ($article->section_name ?? '') . ' ' . ($article->user->name ?? ''));
                            @endphp
                            <li class="flex gap-1.5 sm:gap-2 border border-gray-lighter p-1 sm:p-1.5 bg-white cursor-grab active:cursor-grabbing hover:border-dark-sage transition-colors min-w-0"
                                draggable="true"
                                data-article-id="{{ $article->id }}"
                                data-searchable="{{ e($searchable) }}"
                                x-show="!search || $el.dataset.searchable.includes(search.toLowerCase())"
                                x-init="$watch('search', () => { setTimeout(() => { const parent = $el.closest('[x-data]'); if (parent && parent.__x) { parent.__x.$data.updateVisibleCounts(); } }, 10); })"
                                ondragstart="event.dataTransfer.setData('text/plain', '{{ $article->id }}'); event.dataTransfer.effectAllowed = 'move';">
                                <div class="shrink-0 w-12 h-12 sm:w-16 sm:h-16 overflow-hidden bg-gray-lighter flex items-center justify-center">
                                    @if ($article->image_path)
                                        <img src="{{ asset($article->image_path) }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                    @else
                                        <span class="text-gray-400 text-[10px] sm:text-xs font-opensans">Sin img</span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1 flex flex-col justify-center">
                                    <p class="font-montserrat text-xs sm:text-sm text-primary line-clamp-2 break-words">{{ $article->title }}</p>
                                    <p class="text-[10px] sm:text-xs text-gray-light font-opensans mt-0.5 truncate">{{ $article->section_name }} · {{ $article->user->name ?? '' }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div x-show="search && allVisibleCount === 0" class="text-center py-8">
                        <p class="text-sm text-gray-light font-opensans mb-1">No se encontraron artículos.</p>
                        <p class="text-xs text-gray-400 font-opensans">Intenta ajustar la búsqueda.</p>
                    </div>
                    @if($hasFilters && $allArticles->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-light font-opensans mb-1">No se encontraron artículos.</p>
                            <p class="text-xs text-gray-400 font-opensans">Intenta ajustar los filtros.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

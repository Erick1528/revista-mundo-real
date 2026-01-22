<div class=" px-4 sm:px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">

    <h2 class=" mb-6 font-serif text-3xl text-primary">Acciones Rápidas</h2>

    <!-- Alertas de Success -->
    @if (session('message'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-opensans text-sm">{{ session('message') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="text-green-600 hover:text-green-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Alertas de Error -->
    @if (session('error'))
        <div class="w-full p-4 bg-red-50 border border-red-200 text-red-800 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-opensans text-sm">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="text-red-600 hover:text-red-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class=" flex flex-col md:grid md:grid-cols-3 gap-6">

        <a href="{{ route('articles.create') }}"
            class=" border border-dark-sage p-8 text-dark-sage md:max-w-[304px] w-full hover:bg-dark-sage/9 transition-all duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" height="32px" width="32px" viewBox="0 0 24 24" stroke="#b7b699"
                stroke-width="2" fill="none">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <h4 class=" mt-4 mb-2 font-serif text-xl text-primary">Nuevo Artículo</h4>
            <p class=" text-gray-light text-sm">Crear una nueva publicación para la revista</p>
        </a>

        <div wire:click="showInDevelopment('Editar Borradores')"
            class=" border border-dark-sage p-8 text-dark-sage md:max-w-[304px] w-full hover:bg-dark-sage/9 transition-all duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" height="32px" width="32px" viewBox="0 0 24 24" stroke="#b7b699"
                stroke-width="2" fill="none">
                <path d="m18 2 3 3-11 11h-3v-3z"></path>
                <path d="m21.5 6.5-3.5-3.5L7 14v3h3l11.5-10.5z"></path>
            </svg>
            <h4 class=" mt-4 mb-2 font-serif text-xl text-primary">Editar Borradores</h4>
            <p class=" text-gray-light text-sm">Continuar trabajando en artículos guardados</p>
        </div>

        <div wire:click="showInDevelopment('Ver Estadísticas')"
            class=" border border-dark-sage p-8 text-dark-sage md:max-w-[304px] w-full hover:bg-dark-sage/9 transition-all duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" height="32px" width="32px" viewBox="0 0 24 24" stroke="#b7b699"
                stroke-width="2" fill="none">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <h4 class=" mt-4 mb-2 font-serif text-xl text-primary">Ver Estadísticas</h4>
            <p class=" text-gray-light text-sm">Analizar el rendimiento de las publicaciones</p>
        </div>

    </div>

    {{-- Sección de Artículos --}}
    <div class="mt-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-serif text-3xl text-primary">Artículos Recientes</h2>

            <button type="button" wire:click="clearFilters"
                class="hidden sm:block text-sm text-gray-light hover:text-primary transition-colors font-opensans">
                Ver Todos
            </button>
        </div>

        {{-- Filtros --}}
        <div class="mb-8 space-y-4">
            {{-- Buscador --}}
            <div class="w-full max-w-md">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar artículos..."
                    class="w-full px-4 py-3 border border-gray-lighter text-primary bg-white font-opensans text-sm focus:outline-none focus:border-dark-sage transition-colors">
            </div>

            {{-- Filtros por categorías --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                {{-- Filtro por Status --}}
                <select wire:model.live="statusFilter"
                    class="w-full px-3 py-3 sm:py-2 text-xs font-semibold uppercase font-montserrat tracking-wider border border-gray-lighter text-gray-light bg-white focus:outline-none focus:border-dark-sage transition-colors">
                    <option value="">Estado</option>
                    <option value="draft">Borrador</option>
                    <option value="review">En Revisión</option>
                    <option value="published">Publicado</option>
                    <option value="denied">Rechazado</option>
                </select>

                {{-- Filtro por Sección --}}
                <select wire:model.live="sectionFilter"
                    class="w-full px-3 py-3 sm:py-2 text-xs font-semibold uppercase font-montserrat tracking-wider border border-gray-lighter text-gray-light bg-white focus:outline-none focus:border-dark-sage transition-colors">
                    <option value="">Sección</option>
                    <option value="destinations">Destinos</option>
                    <option value="inspiring_stories">Historias que Inspiran</option>
                    <option value="social_events">Eventos Sociales</option>
                    <option value="health_wellness">Salud y Bienestar</option>
                    <option value="gastronomy">Gastronomía con Identidad</option>
                    <option value="living_culture">Cultura Viva</option>
                </select>

                {{-- Filtro por Visibilidad --}}
                <select wire:model.live="visibilityFilter"
                    class="w-full px-3 py-3 sm:py-2 text-xs font-semibold uppercase font-montserrat tracking-wider border border-gray-lighter text-gray-light bg-white focus:outline-none focus:border-dark-sage transition-colors">
                    <option value="">Visibilidad</option>
                    <option value="public">Público</option>
                    <option value="private">Privado</option>
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

        {{-- Lista de Artículos --}}
        <div class="space-y-6">
            @forelse($articles as $article)
                <div
                    class="border border-gray-lighter p-4 sm:p-6 group cursor-pointer hover:bg-sage transition-all duration-200">
                    <div class="flex items-start justify-between mb-4">
                        {{-- Tags de sección y estado --}}
                        <div class="flex gap-2 flex-wrap items-center">
                            <span
                                class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">
                                {{ $article->section_name }}
                            </span>
                            <span
                                class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider 
                                       {{ $article->status === 'published'
                                           ? 'bg-green-light text-white'
                                           : ($article->status === 'review'
                                               ? 'bg-yellow-100 text-yellow-800'
                                               : ($article->status === 'denied'
                                                   ? 'bg-red-light text-white'
                                                   : 'bg-gray-lighter text-gray-light')) }}">
                                {{ $article->status_name }}
                            </span>
                        </div>

                        {{-- Acciones --}}
                        <div class="flex gap-1 sm:gap-3">
                            <a href="{{ route('article.show', $article->slug) }}"
                                class="p-1 sm:p-2 text-gray-light hover:text-dark-sage transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('articles.edit', $article->slug) }}"
                                class="p-1 sm:p-2 text-gray-light hover:text-dark-sage transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <button wire:click="showInDevelopment('eliminar')"
                                class="p-1 sm:p-2 text-gray-light hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Título --}}
                    <h3
                        class="text-lg sm:text-xl md:text-2xl font-serif text-primary mb-2 group-hover:text-dark-sage transition-all duration-200">
                        {{ $article->title }}
                    </h3>

                    {{-- Resumen/Subtítulo --}}
                    @if ($article->subtitle)
                        <p class="text-gray-light font-opensans mb-4 text-sm sm:text-base">{{ $article->subtitle }}</p>
                    @elseif($article->summary)
                        <p class="text-gray-light font-opensans mb-4 text-sm sm:text-base">
                            {{ Str::limit($article->summary, 150) }}</p>
                    @endif

                    {{-- Meta información --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-light font-opensans">
                            @if ($article->user)
                                <span>Por {{ $article->user->name }}</span>
                            @endif
                            <span>{{ $article->updated_at->diffForHumans() }}</span>
                            @if ($article->reading_time)
                                <span>{{ $article->reading_time }} min de lectura</span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-light font-opensans">
                            <span>{{ $article->view_count }} vistas</span>
                            @if ($article->visibility === 'private')
                                <span
                                    class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider bg-gray-lighter text-gray-light">
                                    Privado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-lighter mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-gray-light font-opensans">No se encontraron artículos</p>
                    @if ($search || $statusFilter || $sectionFilter || $visibilityFilter)
                        <button type="button" wire:click="clearFilters"
                            class="mt-2 text-dark-sage hover:text-primary transition-colors font-opensans text-sm">
                            Limpiar filtros
                        </button>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if ($articles->hasPages())
            <div class="mt-8">
                {{ $articles->links() }}
            </div>
        @endif
    </div>

    {{-- Modal de desarrollo --}}
    <livewire:develop-modal />

</div>

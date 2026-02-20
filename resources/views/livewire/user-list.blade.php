<div class="px-4 sm:px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
        <h2 class="font-serif text-2xl sm:text-3xl text-primary">Gestionar Usuarios</h2>
        <a href="{{ route('users.create') }}"
            class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-12 px-6 bg-primary text-white text-base font-semibold font-montserrat transition-colors hover:bg-dark-sage">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Nuevo usuario
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

    {{-- Filtros (mismo diseño que portadas/temas) --}}
    <div class="mb-8 space-y-4">
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-light font-opensans">Filtrar usuarios</span>
            <div class="flex items-center gap-2 sm:gap-3">
                <button type="button" wire:click="clearFilters"
                    class="hidden sm:block text-sm text-gray-light hover:text-primary transition-colors font-opensans">
                    Ver Todos
                </button>
                <span class="hidden sm:inline text-gray-light font-opensans">|</span>
                <a href="{{ route('users.trash') }}"
                    class="text-sm text-gray-light hover:text-primary transition-colors font-opensans">
                    Ver papelera
                </a>
            </div>
        </div>

        {{-- Buscador --}}
        <div class="w-full max-w-md">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nombre o email..."
                class="w-full px-4 py-3 border border-gray-lighter text-primary bg-white font-opensans text-sm focus:outline-none focus:border-dark-sage transition-colors">
        </div>

        {{-- Filtros por categorías --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <select wire:model.live="rolFilter"
                class="w-full px-3 py-3 sm:py-2 text-xs font-semibold uppercase font-montserrat tracking-wider border border-gray-lighter text-gray-light bg-white focus:outline-none focus:border-dark-sage transition-colors">
                <option value="">Rol</option>
                @foreach($roles as $rolKey => $rolLabel)
                    <option value="{{ $rolKey }}">{{ $rolLabel }}</option>
                @endforeach
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

    {{-- Lista de Usuarios --}}
    <div class="space-y-3 sm:space-y-4">
        @forelse($users as $user)
            <div class="border border-gray-lighter p-4 sm:p-6 hover:bg-sage/30 transition-all duration-200 overflow-hidden">
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-serif text-lg sm:text-xl text-primary break-words">{{ $user->name }}</h3>
                            @if($user->id === Auth::id())
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase font-montserrat tracking-wider bg-primary text-white">
                                    Tú
                                </span>
                            @endif
                        </div>
                        <p class="font-opensans text-xs sm:text-sm text-gray-light mt-1 break-words">
                            {{ $user->email }}
                        </p>
                        @if($user->created_at)
                        <p class="font-opensans text-xs sm:text-sm text-gray-light mt-1 break-words">
                            @if($user->created_at->isAfter(now()->subDays(30)))
                                Registrado {{ $user->created_at->locale('es')->diffForHumans() }}
                            @else
                                Registrado el {{ $user->created_at->locale('es')->translatedFormat('d \d\e M \d\e Y') }}
                            @endif
                        </p>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2 items-center shrink-0">
                        <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider whitespace-nowrap bg-dark-sage text-white">
                            {{ rol_label($user->rol) }}
                        </span>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-lighter">
                    <a href="{{ route('users.show', $user) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-montserrat font-medium text-primary border border-primary hover:bg-sage transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Ver
                    </a>
                    <a href="{{ route('users.edit', $user) }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-montserrat font-medium text-primary border border-primary hover:bg-sage transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                    @if($user->id !== Auth::id())
                        <button type="button" wire:click="openDeleteModal({{ $user->id }})"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-montserrat font-medium text-red-600 border border-red-500 hover:bg-red-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="border border-gray-lighter p-6 sm:p-8 text-center">
                @if($hasActiveFilters)
                    <p class="font-opensans text-sm sm:text-base text-gray-light mb-4">No se encontraron usuarios con los filtros aplicados.</p>
                    <button type="button" wire:click="clearFilters"
                        class="text-dark-sage hover:text-primary transition-colors font-opensans text-sm">
                        Limpiar filtros
                    </button>
                @else
                    <p class="font-opensans text-sm sm:text-base text-gray-light mb-4">No hay usuarios registrados.</p>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    @if ($users->hasPages())
        <div class="mt-8">
            {{ $users->links() }}
        </div>
    @endif

    {{-- Modal de confirmación para eliminar usuario --}}
    <div x-show="$wire.showDeleteModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
        x-effect="if ($wire.showDeleteModal) { document.body.style.overflow = 'hidden'; document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px'; } else { document.body.style.overflow = ''; document.body.style.paddingRight = ''; }"
        @click="$wire.closeDeleteModal()"
        @keydown.escape.window="if($wire.showDeleteModal) $wire.closeDeleteModal()"
        style="display: none;">
        <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" @click.stop>
            <div class="text-center mb-4 sm:mb-6">
                <div class="w-12 h-12 mx-auto mb-4 bg-red-light flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Eliminar este usuario?</h2>
                <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                    El usuario <strong class="font-semibold text-primary">{{ $selectedUserName }}</strong> será eliminado permanentemente.
                </p>
                <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                    Esta acción <strong class="font-semibold text-red-500">no se puede deshacer</strong>.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="button" wire:click="closeDeleteModal"
                    class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                    Cancelar
                </button>
                <button type="button" wire:click="confirmDeleteUser"
                    class="w-full sm:flex-1 bg-red-500 text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-red-600 transition-colors">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>

    {{-- Modal de desarrollo --}}
    <livewire:develop-modal />
</div>

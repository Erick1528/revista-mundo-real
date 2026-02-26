<div class="px-4 sm:px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">
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

    <p class="text-sm text-gray-light font-opensans mb-8">
        Anuncios eliminados. Puedes restaurarlos para que vuelvan al listado.
    </p>

    <div class="space-y-6">
        @forelse($ads as $ad)
            <div class="border border-gray-lighter p-4 sm:p-6 hover:bg-sage/30 transition-all duration-200">
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg sm:text-xl font-serif text-primary mb-1">{{ $ad->name }}</h3>
                        <p class="text-xs text-gray-light font-opensans">{{ $ad->slug }}</p>
                        <p class="text-xs text-gray-light font-opensans mt-1">
                            Eliminado {{ $ad->deleted_at->locale('es')->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center shrink-0">
                        <button type="button" wire:click="restoreAd({{ $ad->id }})"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-montserrat font-medium text-primary border border-primary hover:bg-sage transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Restaurar
                        </button>
                        <button type="button" wire:click="openForceDeleteModal({{ $ad->id }})"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-montserrat font-medium text-red-600 border border-red-500 hover:bg-red-50 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Eliminar permanentemente
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="border border-gray-lighter p-6 sm:p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <p class="text-gray-light font-opensans">No hay anuncios eliminados</p>
            </div>
        @endforelse
    </div>

    @if($ads->hasPages())
        <div class="mt-8">
            {{ $ads->links() }}
        </div>
    @endif

    <div x-show="$wire.showForceDeleteModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
        x-effect="if ($wire.showForceDeleteModal) { document.body.style.overflow = 'hidden'; document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px'; } else { document.body.style.overflow = ''; document.body.style.paddingRight = ''; }"
        @click="$wire.closeForceDeleteModal()"
        @keydown.escape.window="if($wire.showForceDeleteModal) $wire.closeForceDeleteModal()"
        style="display: none;">
        <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4 border border-gray-lighter" @click.stop>
            <div class="text-center mb-4 sm:mb-6">
                <div class="w-12 h-12 mx-auto mb-4 bg-red-100 border border-red-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Eliminar permanentemente?</h2>
                <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                    El anuncio <strong class="font-semibold text-primary">{{ $selectedAdName }}</strong> se borrará para siempre. Esta acción no se puede deshacer.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="button" wire:click="closeForceDeleteModal"
                    class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                    Cancelar
                </button>
                <button type="button" wire:click="confirmForceDelete"
                    class="w-full sm:flex-1 bg-red-500 text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-red-600 transition-colors">
                    Sí, eliminar para siempre
                </button>
            </div>
        </div>
    </div>
</div>

<div class="px-4 sm:px-10 lg:px-[120px] py-8 sm:py-12 max-w-[1200px] mx-auto w-full">
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

    <form wire:submit.prevent="update" class="space-y-4">
        <!-- Datos del anuncio -->
        <div class="border border-gray-lighter">
            <button wire:click="toggleSection('data')" type="button"
                class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                <span class="font-montserrat font-medium text-primary text-base">Datos del anuncio</span>
                <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['data']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-data"
                class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['data']) hidden @endif">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-montserrat font-medium text-primary">Nombre del anuncio</label>
                    <input type="text" id="name" placeholder="Ej. Banner principal" wire:model="name"
                        class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
                    @error('name')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="redirect_url" class="block text-sm font-montserrat font-medium text-primary">URL de redirección (opcional)</label>
                    <input type="url" id="redirect_url" placeholder="https://ejemplo.com" wire:model="redirect_url"
                        class="w-full px-4 py-3 border @error('redirect_url') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
                    @error('redirect_url')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 font-opensans">Debe ser una URL HTTPS. Máximo 2048 caracteres.</p>
                </div>

                <div class="space-y-2">
                    <label for="status" class="block text-sm font-montserrat font-medium text-primary">Estado</label>
                    <select id="status" wire:model="status"
                        class="w-full px-4 py-3 border @error('status') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm appearance-none bg-no-repeat bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M1%201L6%206L11%201%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-position-[right_16px_center] transition-all duration-200 focus:bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M11%207L6%202L1%207%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')]">
                        <option value="draft">Borrador</option>
                        <option value="review">En revisión</option>
                        <option value="published">Publicado</option>
                        <option value="denied">Denegado</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                @php
                    $selectedAdvertiser = $advertiser_id ? $this->advertisers->firstWhere('id', (int) $advertiser_id) : null;
                @endphp
                <div class="space-y-2" x-data="{ open: false }">
                    <label class="block text-sm font-montserrat font-medium text-primary">
                        Anunciante (Opcional)
                    </label>
                    <div class="relative">
                        <button type="button"
                            @click="open = !open"
                            class="w-full px-4 py-3 border @error('advertiser_id') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm text-left flex items-center gap-3 justify-between">
                            @if ($selectedAdvertiser)
                                <span class="flex items-center gap-3 min-w-0">
                                    @if ($selectedAdvertiser->logo_path)
                                        <img src="{{ $selectedAdvertiser->logo_url }}" alt="" class="h-6 w-auto object-contain shrink-0">
                                    @endif
                                    <span class="truncate">{{ $selectedAdvertiser->name }}</span>
                                </span>
                            @else
                                <span class="text-gray-500">Sin anunciante</span>
                            @endif
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open"
                            x-transition
                            @click.away="open = false"
                            class="absolute z-10 w-full mt-1 bg-white border border-gray-200 shadow-lg max-h-64 overflow-y-auto">
                            <button type="button"
                                wire:click="$set('advertiser_id', null)"
                                @click="open = false"
                                class="w-full px-4 py-3 text-left flex items-center gap-3 hover:bg-gray-50 border-b border-gray-100 font-opensans text-sm text-gray-500">
                                Sin anunciante
                            </button>
                            @foreach ($this->advertisers as $adv)
                                <button type="button"
                                    wire:click="$set('advertiser_id', {{ $adv->id }})"
                                    @click="open = false"
                                    class="w-full px-4 py-3 text-left flex items-center gap-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0 font-opensans text-sm">
                                    @if ($adv->logo_path)
                                        <img src="{{ $adv->logo_url }}" alt="" class="h-8 w-auto object-contain shrink-0">
                                    @else
                                        <span class="w-8 h-8 shrink-0 bg-gray-100 flex items-center justify-center text-gray-400 text-xs">—</span>
                                    @endif
                                    <span class="truncate">{{ $adv->name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @error('advertiser_id')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="border border-gray-lighter">
            <button wire:click="toggleSection('content')" type="button"
                class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                <span class="font-montserrat font-medium text-primary text-base">Contenido</span>
                <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['content']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-content"
                class="accordion-content px-6 py-6 border-t border-gray-lighter @if (!$openSections['content']) hidden @endif">
                <livewire:content-editor :is-ad-creator="true" />
                @error('content')
                    <p class="text-red-500 text-xs font-opensans mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
            <button type="submit"
                class="w-full sm:flex-1 h-12 bg-primary text-white text-base font-semibold font-montserrat flex items-center justify-center gap-2 transition-colors hover:bg-dark-sage disabled:opacity-70"
                wire:loading.attr="disabled" wire:target="update">
                <div wire:loading wire:target="update"
                    class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <span wire:loading.remove wire:target="update">Guardar cambios</span>
                <span wire:loading wire:target="update">Guardando...</span>
            </button>
            <a href="{{ route('ads.index') }}"
                class="w-full sm:flex-1 h-12 flex items-center justify-center border border-gray-light text-gray-light hover:bg-sage transition-colors font-montserrat font-semibold text-base">
                Cancelar
            </a>
        </div>
    </form>

    @if ($showCancelModal)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50 p-4"
            x-data="{
                init() {
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px';
                },
                destroy() {
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                }
            }" x-init="init()" x-on:click.self="$wire.closeCancelModal(); destroy()">
            <div class="bg-white max-w-md w-full mx-4 border border-gray-lighter shadow-xl" x-on:click.stop>
                <div class="px-6 py-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-sage">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-montserrat font-semibold text-primary text-center mb-2">
                        ¿Confirmar cancelación?
                    </h3>
                    <p class="text-sm font-opensans text-gray-light text-center mb-6">
                        Si cancelas ahora, <strong class="text-primary">se perderán los cambios</strong> y los archivos subidos que no se hayan guardado. Esta acción no se puede deshacer.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" wire:click="closeCancelModal"
                            class="w-full sm:flex-1 px-4 py-2 h-10 border border-primary text-primary bg-white hover:bg-sage font-montserrat font-medium transition-colors"
                            x-on:click="destroy()">
                            Continuar editando
                        </button>
                        <button type="button" wire:click="confirmCancel"
                            class="w-full sm:flex-1 px-4 py-2 h-10 bg-primary text-white hover:bg-dark-sage font-montserrat font-medium transition-colors"
                            x-on:click="destroy()">
                            Sí, cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

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

    <form wire:submit.prevent="save" class="space-y-4">
        <div class="border border-gray-lighter">
            <button wire:click="toggleSection('data')" type="button"
                class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                <span class="font-montserrat font-medium text-primary text-base">Datos del anunciante</span>
                <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['data']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-data"
                class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['data']) hidden @endif">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-montserrat font-medium text-primary">Nombre de la empresa o anunciante</label>
                    <input type="text" id="name" placeholder="Ej. Mi Marca S.L." wire:model="name"
                        class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
                    @error('name')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-montserrat font-medium text-primary">Logo (opcional)</label>
                    @if ($logo && is_object($logo) && method_exists($logo, 'temporaryUrl') && $this->logoPreviewUrl)
                        <div class="relative w-full h-auto flex items-center justify-center">
                            <img src="{{ $this->logoPreviewUrl }}" alt="Vista previa del logo"
                                class="max-h-[200px] h-full object-contain">
                            <button type="button" wire:click="$set('logo', null)"
                                class="absolute top-2 right-2 h-8 w-8 text-primary hover:text-white transition-colors flex items-center justify-center"
                                onmouseover="this.style.backgroundColor='var(--color-red-light)'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <x-close-svg width="20px" height="20px" fill="currentColor" />
                            </button>
                        </div>
                    @else
                        <div class="w-full flex flex-col items-center justify-center py-8 text-center" wire:loading wire:target="logo">
                            <div class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto"></div>
                            <span class="mt-3 text-primary font-opensans text-sm">Cargando...</span>
                        </div>
                        <div wire:loading.remove wire:target="logo">
                            <input type="file" id="logo" wire:model="logo" accept=".webp,.jpeg,.jpg,.png,.gif"
                                class="w-full px-4 py-3 border @error('logo') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-dark-sage file:text-white hover:file:bg-primary">
                            @error('logo')
                                <p class="text-red-500 text-xs font-opensans mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 font-opensans mt-1">JPG, PNG, WebP o GIF. Máximo 10 MB.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
            <button type="submit"
                class="w-full sm:flex-1 h-12 bg-primary text-white text-base font-semibold font-montserrat flex items-center justify-center gap-2 transition-colors hover:bg-dark-sage disabled:opacity-70"
                wire:loading.attr="disabled" wire:target="save">
                <div wire:loading wire:target="save"
                    class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <span wire:loading.remove wire:target="save">Crear anunciante</span>
                <span wire:loading wire:target="save">Guardando...</span>
            </button>
            <a href="{{ route('advertisers.index') }}"
                class="w-full sm:flex-1 h-12 flex items-center justify-center border border-gray-light text-gray-light hover:bg-sage transition-colors font-montserrat font-semibold text-base">
                Cancelar
            </a>
        </div>
    </form>
</div>

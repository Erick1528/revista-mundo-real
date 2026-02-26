<div class="max-w-7xl mx-auto px-4 sm:px-10 lg:px-[120px] pb-12">
    <div class="pt-12 pb-6">
        <h1 class="font-serif text-2xl md:text-4xl lg:text-5xl leading-tight text-balance text-primary mb-2">
            {{ $ad->name }}
        </h1>
        <p class="text-sm text-gray-light font-opensans mb-4">{{ $ad->slug }}</p>

        {{-- Cambiar estado (solo editor_chief, moderator, administrator) --}}
        @if ($this->canChangeStatus())
            <div class="mt-4 sm:mt-5 border-t border-b border-gray-lighter py-4 sm:py-5">
                <p class="text-xs text-gray-400 font-opensans italic mb-3">Esta sección solo es visible para editores. No se muestra en la vista pública del anuncio.</p>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-6">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs md:text-sm text-muted-foreground font-montserrat text-gray-light">
                        <span class="font-semibold uppercase tracking-wider">Estado:</span>
                        <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider
                            {{ $ad->status === 'published' ? 'bg-green-light text-white' : '' }}
                            {{ $ad->status === 'review' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $ad->status === 'denied' ? 'bg-red-light text-white' : '' }}
                            {{ $ad->status === 'draft' ? 'bg-gray-lighter text-gray-light' : '' }}">
                            {{ $ad->status_name }}
                        </span>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-2">
                        <label for="status_select" class="sr-only">Nuevo estado</label>
                        <select id="status_select" wire:model="newStatus"
                            class="w-full sm:w-auto min-w-0 px-3 py-2.5 sm:py-2 text-xs font-montserrat tracking-wider border border-gray-lighter text-gray-light bg-white focus:outline-none focus:border-dark-sage transition-colors">
                            @foreach (\App\Livewire\ShowAd::getAllowedStatuses() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="button" wire:click="updateStatusFromSelect"
                            class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-xs font-semibold uppercase font-montserrat tracking-wider border border-dark-sage text-dark-sage hover:bg-dark-sage hover:text-gray-super-light transition-colors whitespace-nowrap">
                            Cambiar estado
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Contenido del anuncio (mismo formato que artículos: JSON de bloques) --}}
    <livewire:content-view :content="$ad->content" :isAd="true" :adId="$ad->id" />
</div>

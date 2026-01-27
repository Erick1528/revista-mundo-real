@extends('layouts.index')

@section('title', 'Gestionar Portada')

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <div class="relative" x-data="{ panelOpen: false, saveModalOpen: false, cancelModalOpen: false, duplicateWarningModalOpen: false }"
        x-init="
            document.body.addEventListener('cover-save-modal-toggled', (e) => { saveModalOpen = e.detail; });
            document.body.addEventListener('cover-cancel-modal-toggled', (e) => { cancelModalOpen = e.detail; });
            document.body.addEventListener('cover-duplicate-warning-modal-toggled', (e) => { duplicateWarningModalOpen = e.detail; });
        "
        x-effect="document.body.classList.toggle('overflow-hidden', panelOpen)">
        {{-- Pestaña para abrir panel de artículos; se oculta cuando el modal de guardar, el de descartar o el de advertencia de duplicados están abiertos --}}
        <button type="button"
            x-show="!panelOpen && !saveModalOpen && !cancelModalOpen && !duplicateWarningModalOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:leave="transition ease-in duration-150"
            @click="panelOpen = true"
            class="fixed right-0 top-1/2 -translate-y-1/2 z-[50] h-28 w-7 border border-l border-gray-lighter bg-sage font-montserrat text-[10px] font-semibold uppercase text-primary hover:bg-dark-sage hover:text-white transition-colors duration-200 flex items-center justify-center overflow-hidden shrink-0"
            style="writing-mode: vertical-rl; text-orientation: mixed;"
            aria-label="Abrir panel de artículos">
            Artículos
        </button>

        {{-- Overlay: pointer-events-none para que el drag and drop llegue al contenido --}}
        <div x-show="panelOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 z-[55] pointer-events-none"
            x-cloak
            style="display: none;"
            aria-hidden="true"></div>

        {{-- Right panel (slide from right). En móvil ocupa max 70% del ancho para dejar espacio a las zonas de drop. --}}
        <div class="fixed top-0 right-0 h-full w-[280px] min-w-0 max-w-[70vw] sm:w-96 sm:max-w-none bg-white border-l border-gray-lighter z-[60] flex flex-col transform transition-transform duration-300 ease-in-out translate-x-full"
            :class="{ '!translate-x-0': panelOpen }">
            <div class="flex justify-between items-center px-4 sm:px-6 min-h-[68px] shrink-0 border-b border-gray-lighter">
                <h3 class="font-montserrat font-medium text-primary text-base">Artículos para la portada</h3>
                <button type="button"
                    @click="panelOpen = false"
                    class="text-black transition-all h-9 w-9 shrink-0 duration-200 hover:bg-red-light hover:text-white flex items-center justify-center"
                    aria-label="Cerrar panel">
                    <x-close-svg height="24px" width="24px" />
                </button>
            </div>
            <livewire:cover-articles-panel />
        </div>

        {{-- Cover content (zonas de drop): z-[25] para estar sobre el overlay y recibir el drop --}}
        <div class="relative z-[25]">
            <livewire:manage-cover />
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('saveModalToggled', (e) => {
                const open = !!(typeof e === 'object' && e !== null && 'isOpen' in e ? e.isOpen : e);
                document.body.dispatchEvent(new CustomEvent('cover-save-modal-toggled', { detail: open }));
            });
            Livewire.on('cancelModalToggled', (e) => {
                const open = !!(typeof e === 'object' && e !== null && 'open' in e ? e.open : e);
                document.body.dispatchEvent(new CustomEvent('cover-cancel-modal-toggled', { detail: open }));
            });
            Livewire.on('duplicateWarningModalToggled', (e) => {
                const open = !!(typeof e === 'object' && e !== null && 'open' in e ? e.open : e);
                document.body.dispatchEvent(new CustomEvent('cover-duplicate-warning-modal-toggled', { detail: open }));
            });
        });
    </script>
@endsection

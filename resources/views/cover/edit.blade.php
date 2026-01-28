@extends('layouts.index')

@section('title', 'Editar Portada')

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <div class="relative" x-data="{ panelOpen: false, saveModalOpen: false, cancelModalOpen: false, duplicateWarningModalOpen: false, activateModalOpen: false }"
        x-init="
            document.body.addEventListener('cover-save-modal-toggled', (e) => { saveModalOpen = e.detail; });
            document.body.addEventListener('cover-cancel-modal-toggled', (e) => { cancelModalOpen = e.detail; });
            document.body.addEventListener('cover-duplicate-warning-modal-toggled', (e) => { duplicateWarningModalOpen = e.detail; });
            document.body.addEventListener('cover-activate-modal-toggled', (e) => { activateModalOpen = e.detail; });
        "
        x-effect="document.body.classList.toggle('overflow-hidden', panelOpen)">
        {{-- Articles panel - Hidden for pending versions (read-only mode) --}}
        @if(!$cover->isPendingVersion())
            {{-- Tab to open articles panel; hidden when any modal is open --}}
            <button type="button"
                x-show="!panelOpen && !saveModalOpen && !cancelModalOpen && !duplicateWarningModalOpen && !activateModalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:leave="transition ease-in duration-150"
                @click="panelOpen = true"
                class="fixed right-0 top-1/2 -translate-y-1/2 z-[50] h-28 w-7 border border-l border-gray-lighter bg-sage font-montserrat text-[10px] font-semibold uppercase text-primary hover:bg-dark-sage hover:text-white transition-colors duration-200 flex items-center justify-center overflow-hidden shrink-0"
                style="writing-mode: vertical-rl; text-orientation: mixed;"
                aria-label="Abrir panel de artículos">
                Artículos
            </button>

            {{-- Overlay --}}
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

            {{-- Right panel (slide from right) --}}
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
        @endif

        {{-- Cover content (drop zones) --}}
        <div class="relative z-[25]">
            <livewire:edit-cover :cover="$cover" />
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
            Livewire.on('activateModalToggled', (e) => {
                const open = !!(typeof e === 'object' && e !== null && 'open' in e ? e.open : e);
                document.body.dispatchEvent(new CustomEvent('cover-activate-modal-toggled', { detail: open }));
            });
        });
    </script>
@endsection

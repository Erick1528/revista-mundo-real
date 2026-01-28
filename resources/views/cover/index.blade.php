@extends('layouts.index')

@section('title', 'Portadas')

@section('hero')
    <livewire:hero />
@endsection

@section('content')
    <div class="px-4 sm:px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full"
        x-data="{
            showActivateModal: false,
            showApproveModal: false,
            showRejectModal: false,
            selectedCover: null,
            selectedCoverName: '',
            selectedCoverStatus: '',
            selectedPendingId: null,
            selectedParentName: '',
            openActivateModal(coverId, coverName, coverStatus) {
                this.selectedCover = coverId;
                this.selectedCoverName = coverName;
                this.selectedCoverStatus = coverStatus;
                this.showActivateModal = true;
                document.body.style.overflow = 'hidden';
            },
            closeActivateModal() {
                this.showActivateModal = false;
                this.selectedCover = null;
                document.body.style.overflow = '';
            },
            submitActivate() {
                if (this.selectedCover) {
                    document.getElementById('activate-form-' + this.selectedCover).submit();
                }
            },
            openApproveModal(pendingId, parentName) {
                this.selectedPendingId = pendingId;
                this.selectedParentName = parentName;
                this.showApproveModal = true;
                document.body.style.overflow = 'hidden';
            },
            closeApproveModal() {
                this.showApproveModal = false;
                this.selectedPendingId = null;
                document.body.style.overflow = '';
            },
            submitApprove() {
                if (this.selectedPendingId) {
                    document.getElementById('approve-form-' + this.selectedPendingId).submit();
                }
            },
            openRejectModal(pendingId, parentName) {
                this.selectedPendingId = pendingId;
                this.selectedParentName = parentName;
                this.showRejectModal = true;
                document.body.style.overflow = 'hidden';
            },
            closeRejectModal() {
                this.showRejectModal = false;
                this.selectedPendingId = null;
                document.body.style.overflow = '';
            },
            submitReject() {
                if (this.selectedPendingId) {
                    document.getElementById('reject-form-' + this.selectedPendingId).submit();
                }
            }
        }">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8">
            <h2 class="font-serif text-2xl sm:text-3xl text-primary">Portadas</h2>
            <a href="{{ route('cover.manage') }}"
                class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-12 px-6 bg-primary text-white text-base font-semibold font-montserrat transition-colors hover:bg-dark-sage">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Nueva portada
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

        <livewire:cover-list />

        {{-- Activate confirmation modal --}}
        <div x-show="showActivateModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
            @click="closeActivateModal()"
            @keydown.escape.window="if(showActivateModal) closeActivateModal()"
            style="display: none;">
            <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" @click.stop>
                <div class="text-center mb-4 sm:mb-6">
                    <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Activar esta portada?</h2>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                        <strong class="font-semibold text-primary" x-text="selectedCoverName"></strong>
                    </p>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                        <template x-if="selectedCoverStatus !== 'published'">
                            <span>Esta portada <strong class="font-semibold text-primary">se publicará automáticamente</strong> y será visible en el sitio web.</span>
                        </template>
                        <template x-if="selectedCoverStatus === 'published'">
                            <span>Esta portada <strong class="font-semibold text-primary">será visible en el sitio web</strong> como la portada principal.</span>
                        </template>
                    </p>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mt-3">
                        Cualquier otra portada activa será desactivada.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button @click="closeActivateModal()" type="button"
                        class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                        Cancelar
                    </button>
                    <button @click="submitActivate()" type="button"
                        class="w-full sm:flex-1 bg-primary text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-dark-sage transition-colors">
                        Sí, activar
                    </button>
                </div>
            </div>
        </div>

        {{-- Approve changes modal --}}
        <div x-show="showApproveModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
            @click="closeApproveModal()"
            @keydown.escape.window="if(showApproveModal) closeApproveModal()"
            style="display: none;">
            <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" @click.stop>
                <div class="text-center mb-4 sm:mb-6">
                    <div class="w-12 h-12 mx-auto mb-4 bg-sage flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Aprobar cambios?</h2>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                        Los cambios se aplicarán a la portada <strong class="font-semibold text-primary" x-text="selectedParentName"></strong>.
                    </p>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                        Esta acción <strong class="font-semibold text-primary">reemplazará el contenido actual</strong> de la portada con los cambios pendientes.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button @click="closeApproveModal()" type="button"
                        class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                        Cancelar
                    </button>
                    <button @click="submitApprove()" type="button"
                        class="w-full sm:flex-1 bg-primary text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-dark-sage transition-colors">
                        Sí, aprobar
                    </button>
                </div>
            </div>
        </div>

        {{-- Reject changes modal --}}
        <div x-show="showRejectModal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
            @click="closeRejectModal()"
            @keydown.escape.window="if(showRejectModal) closeRejectModal()"
            style="display: none;">
            <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" @click.stop>
                <div class="text-center mb-4 sm:mb-6">
                    <div class="w-12 h-12 mx-auto mb-4 bg-red-light flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Rechazar cambios?</h2>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                        Los cambios pendientes de <strong class="font-semibold text-primary" x-text="selectedParentName"></strong> serán eliminados.
                    </p>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                        Esta acción <strong class="font-semibold text-red-500">no se puede deshacer</strong>.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button @click="closeRejectModal()" type="button"
                        class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                        Cancelar
                    </button>
                    <button @click="submitReject()" type="button"
                        class="w-full sm:flex-1 bg-red-500 text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-red-600 transition-colors">
                        Sí, rechazar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

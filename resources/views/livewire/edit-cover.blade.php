<div class="max-w-[1200px] mx-auto px-4 py-12" x-data="{
    readOnly: {{ $cover->isPendingVersion() ? 'true' : 'false' }},
    showApproveModal: false,
    showRejectModal: false,
    openApproveModal() {
        this.showApproveModal = true;
        document.body.style.overflow = 'hidden';
    },
    closeApproveModal() {
        this.showApproveModal = false;
        document.body.style.overflow = '';
    },
    submitApprove() {
        document.getElementById('approve-pending-form').submit();
    },
    openRejectModal() {
        this.showRejectModal = true;
        document.body.style.overflow = 'hidden';
    },
    closeRejectModal() {
        this.showRejectModal = false;
        document.body.style.overflow = '';
    },
    submitReject() {
        document.getElementById('reject-pending-form').submit();
    },
    handleDrop(event, zone) {
        if (this.readOnly) return;
        event.preventDefault();
        event.stopPropagation();
        const articleId = event.dataTransfer.getData('text/plain');
        if (!articleId) return;
        
        let targetElement = event.target;
        let targetId = null;
        
        while (targetElement && targetElement !== event.currentTarget) {
            if (targetElement.dataset && targetElement.dataset.articleId) {
                targetId = targetElement.dataset.articleId;
                break;
            }
            targetElement = targetElement.parentElement;
        }
        
        if (targetId && targetId !== articleId && targetId !== '') {
            $wire.call('placeArticleAt', zone, articleId, targetId);
        } else {
            $wire.call('addToZone', zone, articleId);
        }
    },
    handleDragOver(event) {
        if (this.readOnly) return;
        event.preventDefault();
        event.stopPropagation();
        event.dataTransfer.dropEffect = 'move';
        event.currentTarget.classList.add('border-dark-sage', 'bg-sage');
    },
    handleDragLeave(event) {
        if (this.readOnly) return;
        event.currentTarget.classList.remove('border-dark-sage', 'bg-sage');
    },
    handleDragEnter(event) {
        if (this.readOnly) return;
        event.preventDefault();
        event.currentTarget.classList.add('border-dark-sage', 'bg-sage');
    }
}">
    {{-- Header with cover info --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-serif text-primary">Editar portada</h1>
                <p class="text-sm text-gray-light font-opensans mt-1">{{ $cover->name ?? 'Sin nombre' }}</p>
            </div>
            <div class="flex flex-wrap gap-2 items-center">
                {{-- Status badge --}}
                <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider whitespace-nowrap
                    {{ $cover->status === 'published' ? 'bg-green-light text-white' : '' }}
                    {{ $cover->status === 'pending_review' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $cover->status === 'draft' ? 'bg-gray-200 text-gray-700' : '' }}
                    {{ $cover->status === 'archived' ? 'bg-gray-lighter text-gray-light' : '' }}">
                    {{ $cover->status_name }}
                </span>
                {{-- Active badge --}}
                @if($cover->is_active)
                    <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider whitespace-nowrap bg-primary text-white">
                        Activa
                    </span>
                @endif
                {{-- Visibility badge --}}
                <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider whitespace-nowrap
                    {{ $cover->visibility === 'public' ? 'bg-dark-sage text-white' : 'bg-gray-300 text-gray-800' }}">
                    {{ $cover->visibility_name }}
                </span>
            </div>
        </div>

        {{-- Warning for active cover --}}
        @if($cover->is_active)
            <div class="bg-yellow-50 border border-yellow-200 p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-yellow-800 font-montserrat">Esta portada está activa</p>
                        <p class="text-xs text-yellow-700 font-opensans mt-1">Al guardar los cambios se crearán como <strong>cambios pendientes</strong>. La portada activa actual no será modificada hasta que se aprueben.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Info for pending version --}}
        @if($cover->isPendingVersion())
            <div class="bg-blue-50 border border-blue-200 p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-800 font-montserrat">Cambios pendientes de aprobación</p>
                        <p class="text-xs text-blue-700 font-opensans mt-1">
                            Estos son cambios pendientes de la portada <strong>{{ $cover->parent->name ?? 'original' }}</strong>.
                            Cuando se aprueben, se aplicarán a la portada original.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Activate button (only for non-pending, non-active covers) --}}
        @if($canActivate && !$cover->is_active && !$cover->isPendingVersion())
            <button wire:click="openActivateModal" type="button"
                class="inline-flex items-center gap-2 px-4 py-2 mt-4 bg-primary text-white font-montserrat font-medium text-sm hover:bg-dark-sage transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Activar portada
            </button>
        @endif
    </div>

    {{-- Floating buttons for pending versions (read-only: approve/reject) --}}
    @if($cover->isPendingVersion() && $canActivate)
        <div class="fixed bottom-0 left-0 right-0 z-[30] bg-white/80 backdrop-blur-md shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1),0_-2px_4px_-1px_rgba(0,0,0,0.06)]">
            <div class="max-w-[1200px] mx-auto px-4 py-4 flex flex-row-reverse gap-2 sm:gap-4 items-center justify-center">
                {{-- Hidden forms for approve/reject --}}
                <form id="approve-pending-form" action="{{ route('cover.approve', $cover) }}" method="POST" class="hidden">
                    @csrf
                </form>
                <form id="reject-pending-form" action="{{ route('cover.reject', $cover) }}" method="POST" class="hidden">
                    @csrf
                </form>
                <button type="button" @click="openApproveModal()"
                    class="sm:min-w-[140px] h-12 px-4 bg-primary text-white font-montserrat font-medium text-sm hover:bg-dark-sage transition-colors">
                    Aprobar cambios
                </button>
                <button type="button" @click="openRejectModal()"
                    class="sm:min-w-[140px] h-12 px-4 bg-transparent text-primary border border-primary font-montserrat font-medium text-sm hover:bg-sage transition-colors">
                    Rechazar
                </button>
            </div>
        </div>

        {{-- Approve confirmation modal --}}
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
                        Los cambios se aplicarán a la portada <strong class="font-semibold text-primary">{{ $cover->parent->name ?? 'original' }}</strong>.
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

        {{-- Reject confirmation modal --}}
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
                    <div class="w-12 h-12 mx-auto mb-4 bg-red-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Rechazar cambios?</h2>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                        Los cambios pendientes de <strong class="font-semibold text-primary">{{ $cover->parent->name ?? 'original' }}</strong> serán eliminados.
                    </p>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                        Esta acción <strong class="font-semibold text-gray-600">no se puede deshacer</strong>.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button @click="closeRejectModal()" type="button"
                        class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                        Cancelar
                    </button>
                    <button @click="submitReject()" type="button"
                        class="w-full sm:flex-1 bg-transparent text-red-600 py-3 px-4 border border-red-300 font-montserrat font-medium text-xs sm:text-sm hover:bg-red-50 transition-colors">
                        Sí, rechazar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Floating buttons for regular editing (not pending versions) --}}
    @if($hasContent && !$cover->isPendingVersion())
        <div class="fixed bottom-0 left-0 right-0 z-[30] bg-white/80 backdrop-blur-md shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1),0_-2px_4px_-1px_rgba(0,0,0,0.06)]">
            <div class="max-w-[1200px] mx-auto px-4 py-4 flex flex-row-reverse gap-2 sm:gap-4 items-center justify-center">
                <button wire:click="openSaveModal" type="button"
                    class="sm:min-w-[140px] h-12 px-4 bg-primary text-white font-montserrat font-medium text-sm hover:bg-dark-sage transition-colors">
                    Guardar cambios
                </button>
                <button wire:click="openCancelModal" type="button"
                    class="sm:min-w-[140px] h-12 px-4 bg-transparent text-primary border border-primary font-montserrat font-medium text-sm hover:bg-sage transition-colors">
                    Cancelar
                </button>
            </div>
        </div>
    @endif

    {{-- Main Zone (4 articles) --}}
    <div class="mb-16">
        <h2 class="text-2xl sm:text-3xl font-serif text-primary pb-4 border-b border-gray-lighter mb-8">Artículos Principales</h2>
        <div class="h-fit flex flex-col md:grid md:grid-cols-2 gap-8">
            {{-- Main article --}}
            <div class="dropzone min-h-[200px] border-2 border-dashed border-gray-lighter p-4 flex items-center justify-center transition-colors"
                @drop="handleDrop($event, 'main'); $event.currentTarget.classList.remove('border-dark-sage', 'bg-sage')"
                @dragover="handleDragOver($event)"
                @dragleave="handleDragLeave($event)"
                @dragenter="handleDragEnter($event)"
                data-zone="main">
                @if(isset($mainArticles[0]))
                    @php $article = $mainArticles[0]; @endphp
                    <div class="group cursor-pointer h-fit w-full relative"
                        @if(!$cover->isPendingVersion()) draggable="true" @endif
                        data-article-id="{{ $article->id }}"
                        @if(!$cover->isPendingVersion()) ondragstart="event.dataTransfer.setData('text/plain', '{{ $article->id }}'); event.dataTransfer.effectAllowed = 'move';" @endif>
                        @if(!$cover->isPendingVersion())
                            <button wire:click="removeFromZone('main', {{ $article->id }})" type="button"
                                class="absolute top-2 right-2 z-10 h-8 w-8 bg-red-light text-white hover:bg-red-light transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100"
                                ondragstart="event.stopPropagation();">
                                <x-close-svg width="16px" height="16px" fill="currentColor" />
                            </button>
                        @endif
                        <div class="w-full mb-4 overflow-hidden">
                            @if($article->image_path)
                                <img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt_text ?? $article->title }}"
                                    class="w-full h-auto max-h-[456px] md:max-h-[396px] md:h-full object-cover group-hover:scale-105 transition-all duration-200">
                            @else
                                <div class="w-full h-64 bg-gray-lighter flex items-center justify-center">
                                    <span class="text-gray-400 text-sm font-opensans">Sin imagen</span>
                                </div>
                            @endif
                        </div>
                        <div class="space-y-3">
                            <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">{{ $article->section_name }}</p>
                            <h2 class="text-2xl sm:text-4xl font-serif text-balance group-hover:text-dark-sage transition-all duration-200">{{ $article->title }}</h2>
                            <p class="text-gray-light text-[10px] sm:text-sm font-opensans">Por {{ $article->user->name ?? 'Autor' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-light font-opensans text-sm">Arrastra un artículo aquí</p>
                @endif
            </div>

            {{-- 3 Secondary articles --}}
            <div class="space-y-8 h-fit">
                @for($i = 1; $i < 4; $i++)
                    <div class="dropzone min-h-[120px] border-2 border-dashed border-gray-lighter p-3 flex items-center transition-colors"
                        @drop="handleDrop($event, 'main'); $event.currentTarget.classList.remove('border-dark-sage', 'bg-sage')"
                        @dragover="handleDragOver($event)"
                        @dragleave="handleDragLeave($event)"
                        @dragenter="handleDragEnter($event)"
                        data-zone="main"
                        data-article-id="{{ isset($mainArticles[$i]) ? $mainArticles[$i]->id : '' }}">
                        @if(isset($mainArticles[$i]))
                            @php $article = $mainArticles[$i]; @endphp
                            <div class="flex gap-x-4 group cursor-pointer w-full relative"
                                @if(!$cover->isPendingVersion()) draggable="true" @endif
                                data-article-id="{{ $article->id }}"
                                @if(!$cover->isPendingVersion()) ondragstart="event.dataTransfer.setData('text/plain', '{{ $article->id }}'); event.dataTransfer.effectAllowed = 'move';" @endif>
                                @if(!$cover->isPendingVersion())
                                    <button wire:click="removeFromZone('main', {{ $article->id }})" type="button"
                                        class="absolute top-1 right-1 z-10 h-6 w-6 bg-red-light text-white hover:bg-red-light transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100"
                                        ondragstart="event.stopPropagation();">
                                        <x-close-svg width="12px" height="12px" fill="currentColor" />
                                    </button>
                                @endif
                                <div class="w-32 h-32 overflow-hidden shrink-0">
                                    @if($article->image_path)
                                        <img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt_text ?? $article->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-all duration-200 aspect-square">
                                    @else
                                        <div class="w-full h-full bg-gray-lighter flex items-center justify-center">
                                            <span class="text-gray-400 text-xs font-opensans">Sin img</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-2 min-w-0 flex-1">
                                    <p class="text-[10px] sm:text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">{{ $article->section_name }}</p>
                                    <h3 class="text-[18px] sm:text-xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200 line-clamp-2">{{ $article->title }}</h3>
                                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por {{ $article->user->name ?? 'Autor' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-light font-opensans text-xs">Arrastra un artículo aquí</p>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Mid Zone (3 articles) --}}
    <div class="mb-16">
        <h2 class="text-2xl sm:text-3xl font-serif text-primary pb-4 border-b border-gray-lighter mb-8">Artículos Secundarios</h2>
        <div class="flex flex-col sm:grid sm:grid-cols-3 gap-8">
            @for($i = 0; $i < 3; $i++)
                <div class="dropzone min-h-[200px] border-2 border-dashed border-gray-lighter p-4 flex items-center justify-center transition-colors"
                    @drop="handleDrop($event, 'mid'); $event.currentTarget.classList.remove('border-dark-sage', 'bg-sage')"
                    @dragover="handleDragOver($event)"
                    @dragleave="handleDragLeave($event)"
                    @dragenter="handleDragEnter($event)"
                    data-zone="mid"
                    data-article-id="{{ isset($midArticles[$i]) ? $midArticles[$i]->id : '' }}">
                    @if(isset($midArticles[$i]))
                        @php $article = $midArticles[$i]; @endphp
                        <div class="group cursor-pointer w-full relative"
                            @if(!$cover->isPendingVersion()) draggable="true" @endif
                            data-article-id="{{ $article->id }}"
                            @if(!$cover->isPendingVersion()) ondragstart="event.dataTransfer.setData('text/plain', '{{ $article->id }}'); event.dataTransfer.effectAllowed = 'move';" @endif>
                            @if(!$cover->isPendingVersion())
                                <button wire:click="removeFromZone('mid', {{ $article->id }})" type="button"
                                    class="absolute top-2 right-2 z-10 h-8 w-8 bg-red-light text-white hover:bg-red-light transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100"
                                    ondragstart="event.stopPropagation();">
                                    <x-close-svg width="16px" height="16px" fill="currentColor" />
                                </button>
                            @endif
                            <div class="sm:max-w-[368px] sm:max-h-[368px] overflow-hidden mb-4 mx-auto">
                                @if($article->image_path)
                                    <img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt_text ?? $article->title }}"
                                        class="aspect-square object-cover sm:max-w-[368px] max-h-[456px] sm:max-h-[368px] w-full h-full group-hover:scale-105 transition-all duration-200">
                                @else
                                    <div class="aspect-square bg-gray-lighter flex items-center justify-center">
                                        <span class="text-gray-400 text-sm font-opensans">Sin imagen</span>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">{{ $article->section_name }}</p>
                                <h2 class="text-xl font-serif text-primary text-balance group-hover:text-dark-sage transition-all duration-200 line-clamp-2">{{ $article->title }}</h2>
                                <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por {{ $article->user->name ?? 'Autor' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-light font-opensans text-sm">Arrastra un artículo aquí</p>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    {{-- Latest Zone (4 articles) --}}
    <div class="mb-16">
        <h2 class="text-2xl sm:text-3xl font-serif text-primary pb-4 border-b border-gray-lighter mb-8">Últimas Publicaciones</h2>
        <div class="flex flex-col sm:grid sm:grid-cols-2 sm:grid-rows-2 gap-x-8 gap-y-12">
            @for($i = 0; $i < 4; $i++)
                <div class="dropzone min-h-[200px] border-2 border-dashed border-gray-lighter p-4 flex items-center justify-center transition-colors"
                    @drop="handleDrop($event, 'latest'); $event.currentTarget.classList.remove('border-dark-sage', 'bg-sage')"
                    @dragover="handleDragOver($event)"
                    @dragleave="handleDragLeave($event)"
                    @dragenter="handleDragEnter($event)"
                    data-zone="latest"
                    data-article-id="{{ isset($latestArticles[$i]) ? $latestArticles[$i]->id : '' }}">
                    @if(isset($latestArticles[$i]))
                        @php $article = $latestArticles[$i]; @endphp
                        <div class="group cursor-pointer h-fit w-full relative"
                            @if(!$cover->isPendingVersion()) draggable="true" @endif
                            data-article-id="{{ $article->id }}"
                            @if(!$cover->isPendingVersion()) ondragstart="event.dataTransfer.setData('text/plain', '{{ $article->id }}'); event.dataTransfer.effectAllowed = 'move';" @endif>
                            @if(!$cover->isPendingVersion())
                                <button wire:click="removeFromZone('latest', {{ $article->id }})" type="button"
                                    class="absolute top-2 right-2 z-10 h-8 w-8 bg-red-light text-white hover:bg-red-light transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100"
                                    ondragstart="event.stopPropagation();">
                                    <x-close-svg width="16px" height="16px" fill="currentColor" />
                                </button>
                            @endif
                            <div class="max-h-80 h-full w-full overflow-hidden mb-4">
                                @if($article->image_path)
                                    <img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt_text ?? $article->title }}"
                                        class="group-hover:scale-105 transition-all duration-200 aspect-video max-h-80 h-full w-full object-cover">
                                @else
                                    <div class="aspect-video bg-gray-lighter flex items-center justify-center">
                                        <span class="text-gray-400 text-sm font-opensans">Sin imagen</span>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-2">
                                <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">{{ $article->section_name }}</p>
                                <h3 class="text-xl sm:text-2xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200 line-clamp-2">{{ $article->title }}</h3>
                                <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por {{ $article->user->name ?? 'Autor' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-light font-opensans text-sm">Arrastra un artículo aquí</p>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    {{-- Save modal --}}
    @if($showSaveModal)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
            x-data="{ init() { document.body.classList.add('overflow-hidden'); }, destroy() { document.body.classList.remove('overflow-hidden'); } }"
            x-init="init()"
            wire:click="closeSaveModal">
            <div class="bg-white shadow-xl max-w-2xl w-[calc(100%-2rem)] sm:w-full max-h-[90vh] p-4 sm:p-6 md:p-8 overflow-y-auto scrollbar-auto-hide" wire:click.stop>
                <div class="flex justify-between items-center mb-4 sm:mb-6">
                    <h2 class="text-xl sm:text-2xl font-serif text-primary">Guardar cambios</h2>
                    <button wire:click="closeSaveModal" type="button"
                        class="text-black transition-all h-9 w-9 duration-200 hover:bg-red-light hover:text-white">
                        <x-close-svg height="36px" width="36px" />
                    </button>
                </div>

                <form wire:submit.prevent="saveDraft" class="space-y-4 sm:space-y-5">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-montserrat font-medium text-primary">Nombre de la portada</label>
                        <input type="text" id="name" wire:model="name" placeholder="Ej: Portada Enero 2026"
                            class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm placeholder:text-gray-400">
                        @error('name')
                            <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="visibility" class="block text-sm font-montserrat font-medium text-primary">Visibilidad</label>
                        <select id="visibility" wire:model="visibility"
                            class="w-full px-4 py-3 border @error('visibility') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
                            <option value="public">Público</option>
                            <option value="private">Privado</option>
                        </select>
                        @error('visibility')
                            <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="scheduled_at" class="block text-sm font-montserrat font-medium text-primary">Fecha de inicio (opcional)</label>
                            <input type="datetime-local" id="scheduled_at" wire:model="scheduled_at" placeholder="Selecciona fecha y hora"
                                class="w-full px-4 py-3 border @error('scheduled_at') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm placeholder:text-gray-400">
                            @error('scheduled_at')
                                <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="ends_at" class="block text-sm font-montserrat font-medium text-primary">Fecha de fin (opcional)</label>
                            <input type="datetime-local" id="ends_at" wire:model="ends_at" placeholder="Selecciona fecha y hora"
                                class="w-full px-4 py-3 border @error('ends_at') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm placeholder:text-gray-400">
                            @error('ends_at')
                                <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="notes" class="block text-sm font-montserrat font-medium text-primary">Notas (opcional)</label>
                        <textarea id="notes" wire:model="notes" rows="3" placeholder="Agrega notas adicionales sobre esta portada..."
                            class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm resize-none placeholder:text-gray-400"></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2 sm:pt-4">
                        <button wire:click.prevent="publish" type="button"
                            class="flex-1 bg-primary text-white py-3 px-4 font-montserrat font-medium text-sm hover:bg-dark-sage transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-0">
                            Enviar a revisión
                        </button>
                        <button type="submit"
                            class="flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-sm relative overflow-hidden hover:text-white transition-colors duration-300 group">
                            <span class="absolute inset-0 bg-primary transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out"></span>
                            <span class="relative z-10">Guardar borrador</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Cancel modal --}}
    @if($showCancelModal)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
            x-data="{ init() { document.body.style.overflow = 'hidden'; }, destroy() { document.body.style.overflow = ''; } }" x-init="init()"
            wire:click="closeCancelModal">
            <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" wire:click.stop>
                <div class="text-center mb-4 sm:mb-6">
                    <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Descartar cambios?</h2>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                        Si cancelas ahora, <strong class="font-semibold text-primary">se perderán los cambios no guardados</strong>. Esta acción no se puede deshacer.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button wire:click="closeCancelModal" type="button"
                        class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                        Continuar editando
                    </button>
                    <button wire:click="confirmCancel" type="button"
                        class="w-full sm:flex-1 bg-primary text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-dark-sage transition-colors">
                        Sí, descartar
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Duplicate warning modal --}}
    @if($showDuplicateWarningModal)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
            x-data="{ init() { document.body.style.overflow = 'hidden'; }, destroy() { document.body.style.overflow = ''; } }" x-init="init()"
            wire:click="cancelPublish">
            <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" wire:click.stop>
                <div class="text-center mb-4 sm:mb-6">
                    <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">Artículos duplicados</h2>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                        @if(count($duplicateArticles) === 1)
                            Un artículo se encuentra repetido en diferentes secciones.
                        @else
                            Varios artículos se encuentran repetidos en diferentes secciones.
                        @endif
                    </p>
                    <div class="text-left bg-gray-50 border border-gray-lighter p-3 mb-3">
                        <p class="text-xs font-montserrat font-medium text-primary mb-2">Artículos duplicados:</p>
                        <ul class="space-y-1">
                            @foreach($duplicateArticleNames as $id => $title)
                                <li class="text-xs font-opensans text-gray-light">• {{ $title }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                        ¿Deseas <strong class="font-semibold text-primary">modificar las secciones</strong> o <strong class="font-semibold text-primary">continuar con la publicación</strong>?
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button wire:click="cancelPublish" type="button"
                        class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                        Modificar secciones
                    </button>
                    <button wire:click="publishAnyway" type="button"
                        class="w-full sm:flex-1 bg-primary text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-dark-sage transition-colors">
                        Continuar publicación
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Activate confirmation modal --}}
    @if($showActivateModal)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
            x-data="{ init() { document.body.style.overflow = 'hidden'; }, destroy() { document.body.style.overflow = ''; } }" x-init="init()"
            wire:click="closeActivateModal">
            <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" wire:click.stop>
                <div class="text-center mb-4 sm:mb-6">
                    <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Activar esta portada?</h2>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                        @if($cover->status !== 'published')
                            Esta portada <strong class="font-semibold text-primary">se publicará automáticamente</strong> y será visible en el sitio web.
                        @else
                            Esta portada <strong class="font-semibold text-primary">será visible en el sitio web</strong> como la portada principal.
                        @endif
                        <br><br>
                        Cualquier otra portada activa será desactivada.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button wire:click="closeActivateModal" type="button"
                        class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="activate" type="button"
                        class="w-full sm:flex-1 bg-primary text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-dark-sage transition-colors">
                        Sí, activar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

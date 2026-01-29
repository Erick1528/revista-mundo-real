{{-- Mismo ancho y padding que el Hero para alinear la línea y el contenido --}}
<div class="max-w-[1200px] mx-auto px-5 sm:px-10 md:px-[120px] pb-8 sm:pb-12">

    <div class="pt-6 sm:pt-8 md:pt-10 pb-4 sm:pb-6">
        {{-- Badge de sección --}}
        <p class="inline-block px-3 py-1.5 sm:py-2 bg-dark-sage text-gray-super-light text-xs font-montserrat uppercase tracking-wider font-semibold mb-2 sm:mb-3 md:mb-4">
            {{ $topic->section_name }}
        </p>

        {{-- Título --}}
        <h1 class="font-serif text-xl sm:text-2xl md:text-4xl lg:text-5xl leading-tight text-balance mb-2 sm:mb-3 md:mb-4">
            {{ $topic->title }}
        </h1>

        {{-- Descripción --}}
        @if ($topic->description)
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-gray-light font-serif italic text-pretty mb-3 sm:mb-4 md:mb-6">
                {{ $topic->description }}
            </p>
        @endif

        {{-- Metadatos: en móvil en columna, en sm+ en fila con wrap --}}
        <div class="border-t border-b border-gray-lighter py-3 sm:py-4 space-y-2 sm:space-y-0 sm:flex sm:flex-wrap sm:items-center sm:gap-x-4 md:gap-x-6 sm:gap-y-2 text-xs sm:text-sm font-montserrat text-gray-light">
            <div class="flex items-center gap-1.5 md:gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-calendar h-3.5 w-3.5 md:h-4 md:w-4 shrink-0">
                    <path d="M8 2v4"></path>
                    <path d="M16 2v4"></path>
                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                    <path d="M3 10h18"></path>
                </svg>
                <span>
                    @if($topic->updated_at->isAfter(now()->subDays(30)))
                        {{ $topic->updated_at->locale('es')->diffForHumans() }}
                    @else
                        {{ $topic->updated_at->locale('es')->translatedFormat('M j \d\e Y') }}
                    @endif
                </span>
            </div>

            @if($topic->creator)
                <div class="flex items-center gap-1.5 md:gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user h-3.5 w-3.5 md:h-4 md:w-4 shrink-0">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Creado por {{ $topic->creator->name }}</span>
                </div>
            @endif

            @if($topic->assignedUser)
                <div class="flex items-center gap-1.5 md:gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-check-circle h-3.5 w-3.5 md:h-4 md:w-4 shrink-0">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>Tomado por {{ $topic->assignedUser->name }}</span>
                </div>
            @endif

            @if($topic->topicRequests->isNotEmpty())
                <div class="flex items-center gap-1.5 md:gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-clock h-3.5 w-3.5 md:h-4 md:w-4 shrink-0">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>{{ $topic->topicRequests->count() }} {{ $topic->topicRequests->count() === 1 ? 'solicitud' : 'solicitudes' }}</span>
                </div>
            @endif

            {{-- Badge de estado --}}
            <div class="flex items-center gap-1.5 md:gap-2 pt-1 sm:pt-0">
                <span class="inline-block px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider
                    {{ $topic->status === 'available' ? 'bg-green-light text-white' : '' }}
                    {{ $topic->status === 'taken' ? 'bg-sage text-primary' : '' }}
                    {{ $topic->status === 'requested' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $topic->status === 'completed' ? 'bg-gray-200 text-gray-700' : '' }}
                    {{ $topic->status === 'cancelled' ? 'bg-red-light text-white' : '' }}
                    {{ !in_array($topic->status, ['available','taken','requested','completed','cancelled']) ? 'bg-gray-lighter text-gray-light' : '' }}">
                    {{ $topic->status_name }}
                </span>
            </div>
        </div>
    </div>

    {{-- Acciones principales: Tomar / Solicitar / Liberar (visibles sin scroll) --}}
    @if ($canTake || $canRequest || $canRelease)
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mt-6 sm:mt-8">
            @if ($canTake && !$isOwner)
                <button wire:click="takeTopic"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white font-montserrat font-medium text-sm hover:bg-dark-sage transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Tomar Tema
                </button>
            @endif
            @if ($canRequest)
                <button wire:click="openRequestModal"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-transparent text-primary border border-dark-sage font-montserrat font-medium text-sm hover:bg-sage transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Solicitar Tema
                </button>
            @endif
            @if ($canRelease)
                <button wire:click="openReleaseModal"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-transparent text-primary border border-gray-lighter font-montserrat font-medium text-sm hover:bg-sage transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Liberar
                </button>
            @endif
        </div>
    @endif

    {{-- Recursos e Ideas --}}
    @if (!empty($topic->resources))
        <livewire:content-view :content="$topic->resources" />
    @endif

    {{-- Solicitudes pendientes (solo para quien tiene el tema asignado) --}}
    @if ($hasRequest && $isAssigned && $topic->topicRequests->isNotEmpty())
        <div class="border border-gray-lighter bg-sage p-4 sm:p-6 mt-8 md:mt-12">
            <h2 class="font-montserrat font-semibold text-primary text-base sm:text-lg mb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-bell h-5 w-5 text-dark-sage shrink-0">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                Solicitudes de tema ({{ $topic->topicRequests->count() }})
            </h2>
            <p class="text-sm font-opensans text-gray-light mb-4">
                Los siguientes usuarios han solicitado que les asignes este tema.
            </p>
            <div class="space-y-4">
                @foreach ($topic->topicRequests as $request)
                    @if ($request->user)
                        <div class="bg-white p-3 sm:p-4 border border-gray-lighter flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="space-y-2 flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-user text-gray-light shrink-0">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <div>
                                        <span class="text-xs font-montserrat font-semibold text-gray-light uppercase tracking-wider block">Nombre</span>
                                        <p class="font-opensans text-sm text-primary">{{ $request->user->name }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-mail text-gray-light shrink-0">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                    <div>
                                        <span class="text-xs font-montserrat font-semibold text-gray-light uppercase tracking-wider block">Email</span>
                                        <p class="font-opensans text-sm text-primary break-all">{{ $request->user->email }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="lucide lucide-clock text-gray-light shrink-0">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <div>
                                        <span class="text-xs font-montserrat font-semibold text-gray-light uppercase tracking-wider block">Solicitado</span>
                                        <p class="font-opensans text-sm text-primary">
                                            {{ $request->created_at->locale('es')->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-2 shrink-0">
                                <button wire:click="openAssignModal({{ $request->user_id }})"
                                    class="px-4 py-2 bg-primary text-white border border-primary font-montserrat font-medium text-sm hover:bg-dark-sage transition-colors inline-flex items-center justify-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Dar tema
                                </button>
                                <button wire:click="openRejectModal({{ $request->user_id }})"
                                    class="px-4 py-2 bg-transparent text-primary border border-gray-lighter font-montserrat font-medium text-sm hover:bg-red-50 hover:text-red-600 hover:border-red-light transition-colors">
                                    Rechazar
                                </button>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Mensajes de sesión --}}
    @if (session('message'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 mt-6 md:mt-8 mb-6">
            <div class="flex items-center justify-between">
                <span class="font-opensans text-sm">{{ session('message') }}</span>
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

    @if (session('error'))
        <div class="w-full p-4 bg-red-50 border border-red-200 text-red-800 mt-6 md:mt-8 mb-6">
            <div class="flex items-center justify-between">
                <span class="font-opensans text-sm">{{ session('error') }}</span>
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

    {{-- Modal de confirmación: dar tema al solicitante --}}
    @if ($showAssignModal)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50 p-4"
            x-data="{ init() { document.body.style.overflow = 'hidden'; document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px'; }, destroy() { document.body.style.overflow = ''; document.body.style.paddingRight = ''; } }"
            x-init="init()"
            x-on:click.self="$wire.closeAssignModal(); destroy()">
            <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" x-on:click.stop>
                <div class="text-center mb-4 sm:mb-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-sage">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">
                        ¿Dar el tema a {{ $assignModalUser?->name ?? 'el solicitante' }}?
                    </h2>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                        El tema <strong class="font-semibold text-primary">{{ $topic->title }}</strong> quedará asignado a esta persona y dejará de estar solicitado.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="button" wire:click="closeAssignModal"
                            class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors"
                            x-on:click="destroy()">
                            Cancelar
                        </button>
                        <button type="button" wire:click="assignToRequester"
                            class="w-full sm:flex-1 bg-primary text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-dark-sage transition-colors"
                            x-on:click="destroy()">
                            Sí, dar tema
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de confirmación: rechazar solicitud --}}
    @if ($showRejectModal)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50 p-4"
            x-data="{ init() { document.body.style.overflow = 'hidden'; document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px'; }, destroy() { document.body.style.overflow = ''; document.body.style.paddingRight = ''; } }"
            x-init="init()"
            x-on:click.self="$wire.closeRejectModal(); destroy()">
            <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" x-on:click.stop>
                <div class="text-center mb-4 sm:mb-6">
                    <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-red-light">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">
                        ¿Rechazar esta solicitud?
                    </h2>
                    <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                        La solicitud de <strong class="font-semibold text-primary">{{ $rejectModalUser?->name ?? 'el usuario' }}</strong> será rechazada. El tema seguirá asignado a quien lo tiene actualmente.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="button" wire:click="closeRejectModal"
                            class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors"
                            x-on:click="destroy()">
                            Cancelar
                        </button>
                        <button type="button" wire:click="rejectRequest"
                            class="w-full sm:flex-1 bg-red-500 text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-red-600 transition-colors"
                            x-on:click="destroy()">
                            Sí, rechazar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal de confirmación para solicitar tema --}}
    <div x-show="$wire.showRequestModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
        x-effect="if ($wire.showRequestModal) { document.body.style.overflow = 'hidden'; document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px'; } else { document.body.style.overflow = ''; document.body.style.paddingRight = ''; }"
        @click="$wire.closeRequestModal()"
        @keydown.escape.window="if($wire.showRequestModal) $wire.closeRequestModal()"
        style="display: none;">
        <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" @click.stop>
            <div class="text-center mb-4 sm:mb-6">
                <div class="w-12 h-12 mx-auto mb-4 bg-sage flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Solicitar este tema?</h2>
                <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                    El tema <strong class="font-semibold text-primary">{{ $topic->title }}</strong> está actualmente tomado por otro usuario.
                </p>
                <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                    Se enviará una solicitud para que el tema quede disponible para ti cuando el usuario actual lo libere.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="button" wire:click="closeRequestModal"
                    class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                    Cancelar
                </button>
                <button type="button" wire:click="confirmRequestTopic"
                    class="w-full sm:flex-1 bg-primary text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-dark-sage transition-colors">
                    Sí, solicitar
                </button>
            </div>
        </div>
    </div>

    {{-- Modal de confirmación para liberar tema --}}
    <div x-show="$wire.showReleaseModal"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-[70] flex items-center justify-center p-4"
        x-effect="if ($wire.showReleaseModal) { document.body.style.overflow = 'hidden'; document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px'; } else { document.body.style.overflow = ''; document.body.style.paddingRight = ''; }"
        @click="$wire.closeReleaseModal()"
        @keydown.escape.window="if($wire.showReleaseModal) $wire.closeReleaseModal()"
        style="display: none;">
        <div class="bg-white shadow-xl max-w-md w-full p-4 sm:p-8 mx-4" @click.stop>
            <div class="text-center mb-4 sm:mb-6">
                <div class="w-12 h-12 mx-auto mb-4 bg-sage flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-serif text-primary mb-3 sm:mb-4">¿Liberar este tema?</h2>
                <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed mb-3">
                    El tema <strong class="font-semibold text-primary">{{ $topic->title }}</strong> quedará disponible para que otros usuarios lo tomen.
                </p>
                <p class="text-xs sm:text-sm font-opensans text-gray-light leading-relaxed">
                    Si hay solicitudes pendientes, se asignará automáticamente al primero que lo solicitó.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="button" wire:click="closeReleaseModal"
                    class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-xs sm:text-sm hover:bg-sage transition-colors">
                    Cancelar
                </button>
                <button type="button" wire:click="releaseTopic"
                    class="w-full sm:flex-1 bg-primary text-white py-3 px-4 font-montserrat font-medium text-xs sm:text-sm hover:bg-dark-sage transition-colors">
                    Sí, liberar
                </button>
            </div>
        </div>
    </div>
</div>

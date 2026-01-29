<div class="px-4 sm:px-10 lg:px-[120px] py-8 sm:py-12 max-w-[1200px] mx-auto w-full">

    <form action="" class="space-y-4">

        <!-- Información Básica -->
        <div class="border border-gray-lighter">

            <button wire:click="toggleSection('basic')" type="button"
                class="w-full px-4 sm:px-6 py-3 sm:py-4 text-left flex items-center justify-between bg-white hover:bg-sage transition-colors">
                <span class="font-montserrat font-medium text-primary text-sm sm:text-base">Información Básica</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-light transform transition-transform @if ($openSections['basic']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div id="section-basic"
                class="accordion-content px-4 sm:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6 border-t border-gray-lighter @if (!$openSections['basic']) hidden @endif">

                <div class="space-y-2">
                    <label for="title" class="block text-sm font-montserrat font-medium text-primary">
                        Título del Tema
                    </label>
                    <input type="text" id="title" placeholder="Ingresa el título del tema" wire:model="title"
                        class="w-full px-4 py-3 border @error('title') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                    @error('title')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="section" class="block text-sm font-montserrat font-medium text-primary">
                        Sección
                    </label>
                    <select id="section" wire:model="section"
                        class="w-full px-4 py-3 border @error('section') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm appearance-none bg-no-repeat bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M1%201L6%206L11%201%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-position-[right_16px_center] transition-all duration-200 focus:bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M11%207L6%202L1%207%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')]">
                        <option value="">-- Selecciona una sección --</option>
                        <option value="destinations">Destinos</option>
                        <option value="inspiring_stories">Historias que Inspiran</option>
                        <option value="social_events">Eventos Sociales</option>
                        <option value="health_wellness">Salud y Bienestar</option>
                        <option value="gastronomy">Gastronomía con Identidad</option>
                        <option value="living_culture">Cultura Viva</option>
                    </select>

                    @error('section')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="description" class="block text-sm font-montserrat font-medium text-primary">
                        Descripción (Opcional)
                    </label>
                    <textarea id="description" rows="4" wire:model="description"
                        placeholder="Escribe una breve descripción del tema..."
                        class="w-full px-4 py-3 border @error('description') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm resize-none"></textarea>

                    @error('description')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Asignación -->
        <div class="border border-gray-lighter">

            <button wire:click="toggleSection('assignment')" type="button"
                class="w-full px-4 sm:px-6 py-3 sm:py-4 text-left flex items-center justify-between bg-white hover:bg-sage transition-colors">
                <span class="font-montserrat font-medium text-primary text-sm sm:text-base">Asignación</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-light transform transition-transform @if ($openSections['assignment']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div id="section-assignment"
                class="accordion-content px-4 sm:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6 border-t border-gray-lighter @if (!$openSections['assignment']) hidden @endif">
                <div class="space-y-4">
                    <div class="space-y-3">
                        <label class="block text-sm font-montserrat font-medium text-primary">
                            ¿Qué deseas hacer con este tema?
                        </label>

                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" wire:model.live="assignmentType" value="none"
                                    class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                <span class="font-opensans text-sm text-gray-light">Dejar disponible para que otros lo tomen</span>
                            </label>

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" wire:model.live="assignmentType" value="take_myself"
                                    class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                <span class="font-opensans text-sm text-gray-light">Tomarlo para mí</span>
                            </label>

                            @if ($canAssign)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" wire:model.live="assignmentType" value="assign_to_user"
                                        class="w-4 h-4 text-primary border-gray-300 focus:ring-primary">
                                    <span class="font-opensans text-sm text-gray-light">Asignar a otro usuario</span>
                                </label>
                            @endif
                        </div>
                    </div>

                    @if ($assignmentType === 'assign_to_user' && $canAssign)
                        <div class="space-y-2">
                            <label for="assignedToUserId" class="block text-sm font-montserrat font-medium text-primary">
                                Seleccionar usuario
                            </label>
                            <select id="assignedToUserId" wire:model="assignedToUserId"
                                class="w-full px-4 py-3 border @error('assignedToUserId') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm appearance-none bg-no-repeat bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M1%201L6%206L11%201%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-position-[right_16px_center] transition-all duration-200 focus:bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M11%207L6%202L1%207%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')]">
                                <option value="">-- Selecciona un usuario --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>

                            @error('assignedToUserId')
                                <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recursos e Ideas -->
        <div class="border border-gray-lighter">

            <button wire:click="toggleSection('resources')" type="button"
                class="w-full px-4 sm:px-6 py-3 sm:py-4 text-left flex items-center justify-between bg-white hover:bg-sage transition-colors">
                <span class="font-montserrat font-medium text-primary text-sm sm:text-base">Recursos e Ideas</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-light transform transition-transform @if ($openSections['resources']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div id="section-resources"
                class="accordion-content px-4 sm:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6 border-t border-gray-lighter @if (!$openSections['resources']) hidden @endif">
                <livewire:content-editor />

                @error('resources')
                    <p class="text-red-500 text-xs font-opensans mt-1">{{ $message }}</p>
                @enderror

                <!-- Mostrar errores específicos de validación de recursos -->
                @if (!empty($contentErrors))
                    <div class="w-full p-4 bg-red-50 border border-red-500 text-red-500">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-opensans text-sm font-medium">Errores en los recursos:</span>
                                </div>
                                <div class="space-y-1">
                                    @foreach ($contentErrors as $error)
                                        <p class="font-opensans text-xs ml-7">• {{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                            <button type="button" wire:click="$set('contentErrors', [])"
                                class="text-red-500 hover:text-red-700 transition-colors ml-4 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
            <button wire:click.prevent="save"
                class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-sm relative overflow-hidden hover:text-white transition-colors duration-300 group disabled:opacity-70"
                wire:loading.attr="disabled" wire:target="save">
                <span class="absolute inset-0 bg-primary transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out"></span>
                <span class="relative z-10 flex items-center justify-center gap-2">
                    <div wire:loading wire:target="save"
                        class="w-5 h-5 border-2 border-current border-t-transparent rounded-full animate-spin"></div>
                    <span wire:loading.remove wire:target="save">Guardar Tema</span>
                    <span wire:loading wire:target="save">Guardando...</span>
                </span>
            </button>

            <button type="button" wire:click="cancel"
                class="w-full sm:flex-1 bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-sm hover:bg-sage transition-colors">
                Cancelar
            </button>
        </div>

        @if (session('message'))
            <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 mb-4">
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
            <div class="w-full p-4 bg-red-50 border border-red-200 text-red-800 mb-4">
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

    </form>

    <!-- Modal de confirmación para cancelar -->
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
                        Si cancelas ahora, <strong class="text-primary">se perderá todo el contenido</strong> que has
                        escrito. Esta acción no se puede deshacer.
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

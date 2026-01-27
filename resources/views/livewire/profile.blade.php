<div class=" px-4 sm:px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">

    <div class="flex items-center justify-between mb-6">
        <h2 class="font-serif text-3xl text-primary">Mi Perfil</h2>
        @if (!$editingMode)
            <button wire:click="toggleEditMode"
                class="hidden sm:flex h-12 px-6 bg-primary text-white text-base font-semibold font-montserrat items-center justify-center gap-2 transition-colors hover:bg-dark-sage">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar Perfil
            </button>
        @endif
    </div>

    <!-- Alertas de Success -->
    @if (session('message'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-opensans text-sm">{{ session('message') }}</span>
                </div>
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

    <!-- Alertas de Error -->
    @if (session('error'))
        <div class="w-full p-4 bg-red-50 border border-red-500 text-red-500 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-opensans text-sm">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="text-red-500 hover:text-red-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (!$editingMode)
        {{-- Vista Estática --}}
        <div class="space-y-8">
            {{-- Fila de Información (misma altura) --}}
            <div class="flex flex-col lg:flex-row gap-8 items-stretch">
                {{-- Información Personal --}}
                <div class="flex-1 lg:w-2/3 border border-gray-lighter p-6">
                    <h3 class="font-montserrat font-medium text-primary text-lg mb-6">Información Personal</h3>
                    <div class="space-y-4">
                        @if ($currentAvatar)
                            <div class="flex items-center gap-4 mb-4">
                                <img src="{{ asset($currentAvatar) }}" alt="Avatar"
                                    class="w-24 h-24 object-cover border-2 border-gray-300">
                            </div>
                        @endif
                        <div>
                            <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider mb-1">Nombre</p>
                            <p class="text-base font-opensans text-primary">{{ $name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider mb-1">Correo Electrónico</p>
                            <p class="text-base font-opensans text-primary">{{ $email }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider mb-1">Rol</p>
                            <p class="text-base font-opensans text-primary">{{ $this->getRolName() }}</p>
                        </div>
                        @if ($description)
                            <div>
                                <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider mb-1">Descripción</p>
                                <p class="text-base font-opensans text-primary">{{ $description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Información de Cuenta --}}
                <div class="lg:w-1/3 border border-gray-lighter p-6 flex flex-col">
                    <h3 class="font-montserrat font-medium text-primary text-lg mb-6">Información de Cuenta</h3>
                    <div class="space-y-4 flex-1">
                        <div>
                            <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider mb-1">Fecha de Creación</p>
                            <p class="text-base font-opensans text-primary">
                                {{ $this->formatDate($createdAt) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider mb-1">Última Actualización</p>
                            <p class="text-base font-opensans text-primary">
                                {{ $this->formatDate($updatedAt) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider mb-1">Última Sesión</p>
                            @if ($lastSession)
                                <p class="text-base font-opensans text-primary">
                                    {{ $this->formatDate($lastSession) }}
                                </p>
                            @else
                                <p class="text-base font-opensans text-gray-light">No disponible</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider mb-1">Total de Artículos</p>
                            <p class="text-base font-opensans text-primary">{{ $totalArticles }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botón Editar Perfil (solo móviles) --}}
            @if (!$editingMode)
                <div class="flex justify-end sm:hidden">
                    <button wire:click="toggleEditMode"
                        class="h-12 px-6 bg-primary text-white text-base font-semibold font-montserrat flex items-center justify-center gap-2 transition-colors hover:bg-dark-sage w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar Perfil
                    </button>
                </div>
            @endif

            {{-- Artículos Recientes --}}
            <div class="border border-gray-lighter">
                <div class="px-6 py-4 border-b border-gray-lighter flex items-center justify-between">
                    <h3 class="font-montserrat font-medium text-primary text-lg">Artículos Recientes</h3>
                    @if ($totalArticles > 3)
                        <a href="{{ route('dashboard') }}"
                            class="text-sm text-gray-light hover:text-primary transition-colors font-opensans">
                            Ver Todos ({{ $totalArticles }})
                        </a>
                    @endif
                </div>

                <div class="p-6">
                    @if ($articles && $articles->count() > 0)
                        <div class="space-y-6">
                            @foreach ($articles as $article)
                                <div class="border border-gray-lighter p-4 sm:p-6 group hover:bg-sage transition-all duration-200">
                                    <div class="flex items-start justify-between mb-4">
                                        {{-- Tags de sección y estado --}}
                                        <div class="flex gap-2 flex-wrap items-center">
                                            <span class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">
                                                {{ $article->section_name }}
                                            </span>
                                            <span
                                                class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider 
                                                       {{ $article->status === 'published'
                                                           ? 'bg-green-light text-white'
                                                           : ($article->status === 'review'
                                                               ? 'bg-yellow-100 text-yellow-800'
                                                               : ($article->status === 'denied'
                                                                   ? 'bg-red-light text-white'
                                                                   : 'bg-gray-lighter text-gray-light')) }}">
                                                {{ $article->status_name }}
                                            </span>
                                        </div>

                                        {{-- Acciones --}}
                                        <div class="flex gap-1 sm:gap-3">
                                            <a href="{{ route('article.show', $article->slug) }}"
                                                class="p-1 sm:p-2 text-gray-light hover:text-dark-sage transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('articles.edit', $article->slug) }}"
                                                class="p-1 sm:p-2 text-gray-light hover:text-dark-sage transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Título --}}
                                    <h4 class="text-lg sm:text-xl font-serif text-primary mb-2 group-hover:text-dark-sage transition-all duration-200">
                                        {{ $article->title }}
                                    </h4>

                                    {{-- Resumen/Subtítulo --}}
                                    @if ($article->subtitle)
                                        <p class="text-gray-light font-opensans mb-4 text-sm sm:text-base">{{ $article->subtitle }}</p>
                                    @elseif($article->summary)
                                        <p class="text-gray-light font-opensans mb-4 text-sm sm:text-base">
                                            {{ Str::limit($article->summary, 150) }}</p>
                                    @endif

                                    {{-- Meta información --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-light font-opensans">
                                            <span>{{ $article->updated_at->diffForHumans() }}</span>
                                            @if ($article->reading_time)
                                                <span>{{ $article->reading_time }} min de lectura</span>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-light font-opensans">
                                            <span>{{ $article->view_count }} vistas</span>
                                            @if ($article->visibility === 'private')
                                                <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider bg-gray-lighter text-gray-light">
                                                    Privado
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-lighter mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-light font-opensans">No has creado ningún artículo aún</p>
                            <a href="{{ route('articles.create') }}"
                                class="inline-block mt-4 text-dark-sage hover:text-primary transition-colors font-opensans text-sm">
                                Crear tu primer artículo
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        {{-- Modo Edición --}}
        <form wire:submit.prevent="updateProfile" class="space-y-4">

            <!-- Información Personal -->
            <div class="border border-gray-lighter">
                <button wire:click="toggleSection('personal')" type="button"
                    class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                    <span class="font-montserrat font-medium text-primary text-base">Información Personal</span>
                    <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['personal']) rotate-90 @endif"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div id="section-personal"
                    class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['personal']) hidden @endif">

                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-montserrat font-medium text-primary">
                            Nombre Completo
                        </label>
                        <input type="text" id="name" placeholder="Tu nombre completo" wire:model="name"
                            class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                        @error('name')
                            <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-montserrat font-medium text-primary">
                            Correo Electrónico
                        </label>
                        <input type="email" id="email" value="{{ $email }}" disabled
                            class="w-full px-4 py-3 border border-gray-300 bg-gray-100 font-opensans text-sm cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Tu correo electrónico no puede ser modificado desde aquí</p>
                    </div>

                    <div class="space-y-2">
                        <label for="rol" class="block text-sm font-montserrat font-medium text-primary">
                            Rol
                        </label>
                        <input type="text" id="rol" value="{{ $this->getRolName() }}" disabled
                            class="w-full px-4 py-3 border border-gray-300 bg-gray-100 font-opensans text-sm cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Tu rol no puede ser modificado desde aquí</p>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-montserrat font-medium text-primary">
                            Descripción (Opcional)
                        </label>
                        <textarea id="description" rows="4" wire:model="description"
                            placeholder="Escribe una breve descripción sobre ti..."
                            class="w-full px-4 py-3 border @error('description') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm resize-none"></textarea>

                        @error('description')
                            <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Máximo 1000 caracteres</p>
                    </div>
                </div>
            </div>

            <!-- Avatar -->
            <div class="border border-gray-lighter">
                <button wire:click="toggleSection('avatar')" type="button"
                    class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                    <span class="font-montserrat font-medium text-primary text-base">Foto de Perfil</span>
                    <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['avatar']) rotate-90 @endif"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div id="section-avatar"
                    class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['avatar']) hidden @endif">

                    <div class="space-y-4">
                        <!-- Avatar actual o vista previa de nueva imagen -->
                        @if ($avatar && is_object($avatar))
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <img src="{{ $avatar->temporaryUrl() }}" alt="Vista previa"
                                        class="w-24 h-24 object-cover border-2 border-dark-sage">
                                    <button type="button" wire:click="removeAvatar"
                                        class="absolute top-2 right-2 h-8 w-8 text-primary hover:text-white transition-colors flex items-center justify-center"
                                        style="hover:background-color: var(--color-red-light);"
                                        onmouseover="this.style.backgroundColor='var(--color-red-light)'"
                                        onmouseout="this.style.backgroundColor='transparent'">
                                        <x-close-svg width="20px" height="20px" fill="currentColor" />
                                    </button>
                                </div>
                                <div>
                                    <p class="text-sm font-opensans text-gray-700">Vista previa</p>
                                    <p class="text-xs font-opensans text-gray-500">Nueva imagen seleccionada</p>
                                </div>
                            </div>
                        @elseif ($currentAvatar)
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <img src="{{ asset($currentAvatar) }}" alt="Avatar actual"
                                        class="w-24 h-24 object-cover border-2 border-gray-300">
                                </div>
                                <div>
                                    <p class="text-sm font-opensans text-gray-700">Avatar actual</p>
                                    <p class="text-xs font-opensans text-gray-500">Se reemplazará al subir una nueva imagen</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-24 bg-gray-200 flex items-center justify-center border-2 border-gray-300">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-opensans text-gray-700">No hay avatar</p>
                                    <p class="text-xs font-opensans text-gray-500">Sube una imagen para establecer tu foto de perfil</p>
                                </div>
                            </div>
                        @endif

                        <!-- Input para subir avatar -->
                        <div class="space-y-2">
                            <label for="avatar" class="block text-sm font-montserrat font-medium text-primary">
                                Nueva Foto de Perfil
                            </label>
                            <input type="file" id="avatar" wire:model="avatar" accept="image/jpeg,image/jpg,image/png,image/webp,image/gif"
                                class="w-full px-4 py-3 border @error('avatar') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                            @error('avatar')
                                <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Formatos permitidos: JPG, PNG, GIF, WebP. Máximo 10MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="border border-gray-lighter">
                <button wire:click="toggleSection('password')" type="button"
                    class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                    <span class="font-montserrat font-medium text-primary text-base">Cambiar Contraseña</span>
                    <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['password']) rotate-90 @endif"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <div id="section-password"
                    class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['password']) hidden @endif">

                    <div class="space-y-2">
                        <label for="current_password" class="block text-sm font-montserrat font-medium text-primary">
                            Contraseña actual
                        </label>
                        <input type="password" id="current_password" placeholder="Introduce tu contraseña actual"
                            wire:model="current_password" autocomplete="current-password"
                            class="w-full px-4 py-3 border @error('current_password') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                        @error('current_password')
                            <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-montserrat font-medium text-primary">
                            Nueva contraseña
                        </label>
                        <input type="password" id="password" placeholder="Introduce la nueva contraseña"
                            wire:model="password" autocomplete="new-password"
                            class="w-full px-4 py-3 border @error('password') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                        @error('password')
                            <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres</p>
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-montserrat font-medium text-primary">
                            Confirmar nueva contraseña
                        </label>
                        <input type="password" id="password_confirmation" placeholder="Repite la nueva contraseña"
                            wire:model="password_confirmation" autocomplete="new-password"
                            class="w-full px-4 py-3 border @error('password_confirmation') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                        @error('password_confirmation')
                            <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button type="submit"
                    class="w-full sm:flex-1 h-12 bg-primary text-white text-base font-semibold font-montserrat flex items-center justify-center gap-2 transition-colors disabled:opacity-70"
                    wire:loading.attr="disabled" wire:target="updateProfile">
                    <!-- Spinner de carga -->
                    <div wire:loading wire:target="updateProfile"
                        class="w-5 h-5 border-2 border-white border-t-transparent animate-spin rounded-full"></div>
                    <!-- Texto del botón -->
                    <span wire:loading.remove wire:target="updateProfile">Guardar Cambios</span>
                    <span wire:loading wire:target="updateProfile">Guardando...</span>
                </button>

                <button type="button" wire:click="toggleEditMode"
                    class="w-full sm:flex-1 flex justify-center items-center h-12 text-base font-semibold font-montserrat border text-gray-light border-gray-light hover:bg-sage transition-colors">
                    Cancelar
                </button>
            </div>

        </form>
    @endif

</div>

<div class=" px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">

    <form action="" class="space-y-4">

        <!-- Información Básica -->
        <div class="border border-gray-lighter">

            <button wire:click="toggleSection('basic')" type="button"
                class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                <span class="font-montserrat font-medium text-primary text-base">Información Básica</span>
                <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['basic']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div id="section-basic"
                class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['basic']) hidden @endif">

                <div class="space-y-2">
                    <label for="title" class="block text-sm font-montserrat font-medium text-primary">
                        Título del Artículo
                    </label>
                    <input type="text" id="title" placeholder="Ingresa el titulo del artículo" wire:model="title"
                        class="w-full px-4 py-3 border @error('title') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                    @error('title')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="subtitle" class="block text-sm font-montserrat font-medium text-primary">
                        Subtitulo del Artículo
                    </label>
                    <input type="text" id="subtitle" placeholder="Ingresa el subtitulo del artículo"
                        wire:model="subtitle"
                        class="w-full px-4 py-3 border @error('subtitle') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                    @error('subtitle')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="summary" class="block text-sm font-montserrat font-medium text-primary">
                        Resumen del Artículo (Opcional)
                    </label>
                    <textarea id="summary" rows="4" wire:model="summary"
                        placeholder="Escribe un breve resumen del artículo para mostrar en las vistas previas..."
                        class="w-full px-4 py-3 border @error('summary') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm resize-none"></textarea>

                    @error('summary')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="attribution" class="block text-sm font-montserrat font-medium text-primary">
                        Créditos/Fuente de la Información (Opcional)
                    </label>
                    <input type="text" id="attribution" wire:model="attribution"
                        placeholder="Ej: Información cortesía de National Geographic"
                        class="w-full px-4 py-3 border @error('attribution') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                    @error('attribution')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror

                    <p class="text-xs text-gray-500 mt-1">Fuente de donde se obtuvo la información o contenido del
                        artículo</p>
                </div>
            </div>
        </div>

        <!-- Imagen Destacada -->
        <div class="border border-gray-lighter">
            <button wire:click="toggleSection('image')" type="button"
                class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                <span class="font-montserrat font-medium text-primary text-base">Imagen Destacada</span>
                <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['image']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-image"
                class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['image']) hidden @endif">
                <div class="space-y-2">
                    @if ($image)
                        <div class="relative w-full h-auto bg-sage flex items-center justify-center">
                            <img src="{{ $image->temporaryUrl() }}" alt=""
                                class="max-h-[380px] h-full object-contain">

                            <button type="button" wire:click="removeImage"
                                class="absolute top-2 right-2 h-8 w-8 text-primary hover:text-white transition-colors flex items-center justify-center"
                                style="hover:background-color: var(--color-red-light);"
                                onmouseover="this.style.backgroundColor='var(--color-red-light)'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <x-close-svg width="20px" height="20px" fill="currentColor" />
                            </button>
                        </div>
                    @else
                        <label for="image" class="block text-sm font-montserrat font-medium text-primary">
                            Imagen Principal
                        </label>

                        <!-- Loading spinner -->
                        <div class="w-full flex flex-col items-center justify-center py-8 text-center" wire:loading
                            wire:target="image">
                            <div
                                class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto">
                            </div>
                            <span class="mt-3 text-primary font-opensans text-sm">Cargando...</span>
                        </div>

                        <!-- File input -->
                        <div wire:loading.remove wire:target="image">
                            <input type="file" id="image" accept=".webp,.jpeg,.jpg,.png,.gif" wire:model="image"
                                class="w-full px-4 py-3 border @error('image') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-dark-sage file:text-white hover:file:bg-primary">

                            @error('image')
                                <p class="text-red-500 text-xs font-opensans mt-1">{{ $message }}</p>
                            @enderror

                            <p class="text-xs text-gray-500 mt-1">Tamaño máximo: 10MB. Formatos: JPG, PNG, WEBP</p>
                        </div>
                    @endif

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
                class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['content']) hidden @endif">
                <livewire:content-editor />
            </div>
        </div>

        <!-- Clasificación -->
        <div class="border border-gray-lighter">
            <button wire:click="toggleSection('classification')" type="button"
                class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                <span class="font-montserrat font-medium text-primary text-base">Clasificación</span>
                <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['classification']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-classification"
                class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['classification']) hidden @endif">
                <div class="space-y-2">
                    <label for="section" class="block text-sm font-montserrat font-medium text-primary">
                        Sección del Artículo
                    </label>
                    <select id="section" wire:model="section"
                        class="w-full px-4 py-3 border @error('section') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm appearance-none bg-no-repeat bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M1%201L6%206L11%201%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-position-[right_16px_center] transition-all duration-200 focus:bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M11%207L6%202L1%207%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')]">
                        <option value="">-- Selecciona una sección --</option>
                        <option value="destinations">Destinos</option>
                        <option value="inspiring_stories">Historias que inspiran</option>
                        <option value="social_events">Eventos Sociales</option>
                        <option value="health_wellness">Salud y Bienestar</option>
                        <option value="gastronomy">Gastronomía</option>
                        <option value="living_culture">Cultura Viva</option>
                    </select>

                    @error('section')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="tags" class="block text-sm font-montserrat font-medium text-primary">
                        Tags / Etiquetas
                    </label>
                    <input type="text" id="tags" wire:model="tagInput" wire:keydown.enter.prevent="addTag"
                        placeholder="Escribe un tag y presiona Enter"
                        class="w-full px-4 py-3 border @error('tags') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                    @error('tags')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror

                    @if (count($tags) > 0)
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ($tags as $index => $tag)
                                <span class="inline-flex items-center px-3 py-1 text-sm bg-dark-sage text-white gap-2">
                                    {{ $tag }}
                                    <button type="button" wire:click="removeTag({{ $index }})"
                                        class="text-white transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <p class="text-xs text-gray-500">Presiona Enter después de escribir cada tag. Máximo 10 tags.
                        @if (count($tags) > 0)
                            ({{ count($tags) }}/10)
                        @endif
                    </p>
                </div>

                <div class="space-y-2">
                    <label for="related_articles" class="block text-sm font-montserrat font-medium text-primary">
                        Artículos Relacionados (Opcional)
                    </label>
                    <div class="relative">
                        <input type="text" id="related_articles" wire:model.live="relatedArticleSearch"
                            placeholder="Busca artículos relacionados..."
                            class="w-full px-4 py-3 border @error('relatedArticleSearch') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                        @error('relatedArticleSearch')
                            <p class="text-red-500 text-xs font-opensans mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Sugerencias -->
                        @if (count($this->searchSuggestions) > 0)
                            <div
                                class="absolute z-10 w-full mt-1 bg-white border border-gray-200 max-h-64 overflow-y-auto shadow-lg">
                                @foreach ($this->searchSuggestions as $suggestion)
                                    <button type="button"
                                        wire:click="addRelatedArticle({{ $suggestion['id'] }}, '{{ addslashes($suggestion['title']) }}', '{{ $suggestion['section'] }}', '{{ addslashes($suggestion['attribution']) }}', '{{ addslashes($suggestion['summary']) }}')"
                                        class="w-full px-4 py-3 text-left hover:bg-gray-50 border-b border-gray-100 last:border-b-0 transition-colors">
                                        <div class="space-y-1">
                                            <div class="font-opensans text-sm text-primary font-medium">
                                                {{ $suggestion['title'] }}
                                            </div>
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                @if($suggestion['section'])
                                                    <span class="px-2 py-1 bg-sage text-primary text-xs font-medium">
                                                        @switch($suggestion['section'])
                                                            @case('destinations')
                                                                Destinos
                                                                @break
                                                            @case('inspiring_stories')
                                                                Historias que Inspiran
                                                                @break
                                                            @case('social_events')
                                                                Eventos Sociales
                                                                @break
                                                            @case('health_wellness')
                                                                Salud y Bienestar
                                                                @break
                                                            @case('gastronomy')
                                                                Gastronomía
                                                                @break
                                                            @case('living_culture')
                                                                Cultura Viva
                                                                @break
                                                            @default
                                                                {{ $suggestion['section'] }}
                                                        @endswitch
                                                    </span>
                                                @endif
                                                @if($suggestion['attribution'])
                                                    <span>Por {{ $suggestion['attribution'] }}</span>
                                                @endif
                                                @if($suggestion['published_at'])
                                                    <span>• {{ $suggestion['published_at'] }}</span>
                                                @endif
                                            </div>
                                            @if($suggestion['summary'])
                                                <div class="text-xs text-gray-600 line-clamp-2">
                                                    {{ $suggestion['summary'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Artículos seleccionados -->
                    @if (count($related_articles) > 0)
                        <div class="space-y-3 mt-3">
                            @foreach ($related_articles as $index => $article)
                                <div class="flex items-start justify-between p-4 bg-gray-50 border border-gray-200">
                                    <div class="flex-1 space-y-2">
                                        <div class="font-opensans text-sm text-primary font-medium">
                                            {{ $article['title'] }}
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            @if(isset($article['section']) && $article['section'])
                                                <span class="px-2 py-1 bg-white border border-gray-300 text-primary text-xs font-medium">
                                                    @switch($article['section'])
                                                        @case('destinations')
                                                            Destinos
                                                            @break
                                                        @case('inspiring_stories')
                                                            Historias que Inspiran
                                                            @break
                                                        @case('social_events')
                                                            Eventos Sociales
                                                            @break
                                                        @case('health_wellness')
                                                            Salud y Bienestar
                                                            @break
                                                        @case('gastronomy')
                                                            Gastronomía
                                                            @break
                                                        @case('living_culture')
                                                            Cultura Viva
                                                            @break
                                                        @default
                                                            {{ $article['section'] }}
                                                    @endswitch
                                                </span>
                                            @endif
                                            @if(isset($article['attribution']) && $article['attribution'])
                                                <span>Por {{ $article['attribution'] }}</span>
                                            @endif
                                        </div>
                                        @if(isset($article['summary']) && $article['summary'])
                                            <div class="text-xs text-gray-600">
                                                {{ $article['summary'] }}
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" wire:click="removeRelatedArticle({{ $index }})"
                                        class="ml-3 text-gray-400 hover:text-red-500 transition-colors flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <p class="text-xs text-gray-500">Máximo 5 artículos relacionados.
                        @if (count($related_articles) > 0)
                            ({{ count($related_articles) }}/5)
                        @endif
                    </p>
                </div>

            </div>
        </div>

        <!-- Publicación y Visibilidad -->
        <div class="border border-gray-lighter">
            <button wire:click="toggleSection('publication')" type="button"
                class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                <span class="font-montserrat font-medium text-primary text-base">Publicación y Visibilidad</span>
                <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['publication']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-publication"
                class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['publication']) hidden @endif">
                <div class="space-y-2">
                    <label for="visibility" class="block text-sm font-montserrat font-medium text-primary">
                        Visibilidad
                    </label>
                    <select id="visibility" wire:model="visibility"
                        class="w-full px-4 py-3 border @error('visibility') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm appearance-none bg-no-repeat bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M1%201L6%206L11%201%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-position-[right_16px_center] transition-all duration-200 focus:bg-[url('data:image/svg+xml,%3Csvg%20width%3D%2212%22%20height%3D%228%22%20viewBox%3D%220%200%2012%208%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M11%207L6%202L1%207%22%20stroke%3D%22%23666666%22%20stroke-width%3D%221.5%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')]">
                        <option value="">-- Selecciona la visibilidad --</option>
                        <option value="private">Privado (Solo administradores)</option>
                        <option value="public">Público (Visible para todos)</option>
                    </select>

                    @error('visibility')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="published_at" class="block text-sm font-montserrat font-medium text-primary">
                        Programar Publicación (Opcional)
                    </label>
                    <input type="datetime-local" id="published_at" wire:model="published_at"
                        class="w-full px-4 py-3 border @error('published_at') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                    @error('published_at')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror

                    <p class="text-xs text-gray-500 mt-1">Si no se selecciona, el artículo se publicará cuando cambie
                        el estado a "publicado"</p>
                </div>
            </div>
        </div>

        <!-- SEO -->
        <div class="border border-gray-lighter">
            <button wire:click="toggleSection('seo')" type="button"
                class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                <span class="font-montserrat font-medium text-primary text-base">SEO</span>
                <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['seo']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-seo"
                class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['seo']) hidden @endif">
                <div class="space-y-2">
                    <label for="meta_description" class="block text-sm font-montserrat font-medium text-primary">
                        Meta Descripción (Opcional)
                    </label>
                    <textarea id="meta_description" rows="3" maxlength="160" wire:model="meta_description"
                        placeholder="Descripción para motores de búsqueda (máximo 160 caracteres)"
                        class="w-full px-4 py-3 border @error('meta_description') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm resize-none"></textarea>

                    @error('meta_description')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror

                    <p class="text-xs text-gray-500">Esta descripción aparecerá en los resultados de búsqueda de Google
                    </p>
                </div>

                <div class="space-y-2">
                    <label for="reading_time" class="block text-sm font-montserrat font-medium text-primary">
                        Tiempo de Lectura (Opcional)
                    </label>
                    <input type="number" id="reading_time" placeholder="Ej: 5" min="1" max="60"
                        wire:model="reading_time"
                        class="w-full px-4 py-3 border @error('reading_time') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                    @error('reading_time')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror

                    <p class="text-xs text-gray-500">Tiempo estimado que toma leer el artículo completo</p>
                </div>
            </div>
        </div>

        <!-- Métricas -->
        <div class="border border-gray-lighter">
            <button wire:click="toggleSection('metrics')" type="button"
                class="w-full px-6 py-4 text-left flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
                <span class="font-montserrat font-medium text-primary text-base">Métricas</span>
                <svg class="w-5 h-5 text-gray-light transform transition-transform @if ($openSections['metrics']) rotate-90 @endif"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div id="section-metrics"
                class="accordion-content px-6 py-6 space-y-6 border-t border-gray-lighter @if (!$openSections['metrics']) hidden @endif">
                <p class="text-gray-light font-opensans">Las métricas del artículo se mostrarán aquí una vez publicado.
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <button  wire:click.prevent="store"
                class=" flex-1 h-12 bg-primary text-white text-base font-semibold font-montserrat">Publicar
                Artículo</button>
            <button wire:click.prevent="saveDraft"
                class="flex-1 h-12 text-base font-semibold font-montserrat border border-primary hover:bg-sage transition-colors">Guardar
                Borrador</button>
            <a href="" wire:click.prevent="cancel"
                class="flex-1 flex justify-center items-center h-12 text-base font-semibold font-montserrat border text-gray-light border-gray-light hover:bg-sage transition-colors">Cancelar</a>
        </div>

        @if (session('message'))
            <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-opensans text-sm">{{ session('message') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" 
                        class="text-green-600 hover:text-green-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

    </form>

</div>

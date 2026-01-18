<div class=" px-4 sm:px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">

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
                <div class="space-y-2 grid grid-cols-1 gap-3">
                    @if ($image)
                        <div class="relative w-full h-auto flex items-center justify-center">
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

                        <div class="space-y-4 mt-4">
                            <div class="space-y-2 p-4 bg-white border-gray-300 border">
                                <div class="flex items-center justify-between mb-2">
                                    <label for="image_caption" class="block text-[12px] font-montserrat font-medium text-gray-500 uppercase">
                                        Pie de Foto (Opcional)
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="insertMarkdownCaption(this, '*', '*')"
                                            class="px-2 py-1 text-xs border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 font-opensans italic"
                                            title="Cursiva">
                                            <em>I</em>
                                        </button>
                                        <button type="button" onclick="insertMarkdownCaption(this, '**', '**')"
                                            class="px-2 py-1 text-xs border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 font-opensans font-bold"
                                            title="Negrita">
                                            B
                                        </button>
                                        <span class="text-xs text-gray-400 font-opensans ml-2">
                                            <span class="hidden sm:inline">Markdown</span>
                                        </span>
                                    </div>
                                </div>
                                <textarea id="image_caption"
                                    placeholder="Escribe un pie de foto para la imagen... Soporta *cursiva*, **negrita** y saltos de línea"
                                    class="w-full border-0 focus:outline-none font-opensans text-sm leading-relaxed p-2 min-h-20 resize-none"
                                    style="field-sizing: content;" wire:model.blur="image_caption">{{ $image_caption ?? '' }}</textarea>

                                @error('image_caption')
                                    <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                                @enderror

                                @if (!empty($image_caption))
                                    <div class="border-t border-gray-100 pt-2">
                                        <button type="button"
                                            class="flex items-center gap-1 text-xs text-gray-500 hover:text-gray-700 font-opensans mb-1 transition-colors"
                                            onclick="this.nextElementSibling.classList.toggle('hidden')">
                                            <svg class="w-3 h-3 transform transition-transform" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                            Vista previa
                                        </button>
                                        <div class="hidden">
                                            <div
                                                class="text-sm font-opensans leading-relaxed text-gray-700 bg-gray-50 p-2 border border-gray-200 whitespace-pre-line">
                                                @php
                                                    $content = e($image_caption ?? '');
                                                    // Aplicar formato básico
                                                    $content = preg_replace(
                                                        [
                                                            '/\*\*\*(.*?)\*\*\*/',
                                                            '/\*\*(.*?)\*\*/',
                                                            '/\*(.*?)\*/',
                                                        ],
                                                        [
                                                            '<strong><em>$1</em></strong>',
                                                            '<strong>$1</strong>',
                                                            '<em>$1</em>',
                                                        ],
                                                        $content,
                                                    );

                                                    // Procesar listas (reemplazar toda la línea)
                                                    $content = preg_replace(
                                                        '/^- (.+)/m',
                                                        '<span class="flex items-start gap-1"><span>•</span><span>$1</span></span>',
                                                        $content,
                                                    );
                                                    $content = preg_replace(
                                                        '/^\d+\. (.+)/m',
                                                        '<span class="flex items-start gap-1"><span>$0</span></span>',
                                                        $content,
                                                    );
                                                @endphp
                                                {!! $content !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <label for="image_alt_text" class="block text-sm font-montserrat font-medium text-primary">
                                    Texto Alternativo (Opcional)
                                </label>
                                <input type="text" id="image_alt_text" wire:model="image_alt_text"
                                    placeholder="Descripción de la imagen para accesibilidad..."
                                    class="w-full px-4 py-3 border @error('image_alt_text') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                                @error('image_alt_text')
                                    <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                                @enderror

                                <p class="text-xs text-gray-500 mt-1">Texto que describe la imagen para lectores de pantalla y SEO</p>
                            </div>

                            <div class="space-y-2">
                                <label for="image_credits" class="block text-sm font-montserrat font-medium text-primary">
                                    Créditos de la Imagen (Opcional)
                                </label>
                                <input type="text" id="image_credits" wire:model="image_credits"
                                    placeholder="Ej: Foto: Juan Pérez / Cortesía de National Geographic"
                                    class="w-full px-4 py-3 border @error('image_credits') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">

                                @error('image_credits')
                                    <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                                @enderror

                                <p class="text-xs text-gray-500 mt-1">Atribución o créditos del fotógrafo/fuente de la imagen</p>
                            </div>
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

                @error('content')
                    <p class="text-red-500 text-xs font-opensans mt-1">{{ $message }}</p>
                @enderror

                <!-- Mostrar errores específicos de validación de contenido -->
                @if (!empty($contentErrors))
                    <div class="w-full p-4 bg-red-50 border border-red-200 text-red-800">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-opensans text-sm font-medium">Errores en el contenido:</span>
                                </div>
                                <div class="space-y-1">
                                    @foreach ($contentErrors as $error)
                                        <p class="font-opensans text-xs ml-7">• {{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                            <button type="button" wire:click="$set('contentErrors', [])"
                                class="text-red-600 hover:text-red-800 transition-colors ml-4 flex-shrink-0">
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
                                                @if ($suggestion['section'])
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
                                                @if ($suggestion['attribution'])
                                                    <span>Por {{ $suggestion['attribution'] }}</span>
                                                @endif
                                                @if ($suggestion['published_at'])
                                                    <span>• {{ $suggestion['published_at'] }}</span>
                                                @endif
                                            </div>
                                            @if ($suggestion['summary'])
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
                                            @if (isset($article['section']) && $article['section'])
                                                <span
                                                    class="px-2 py-1 bg-white border border-gray-300 text-primary text-xs font-medium">
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
                                            @if (isset($article['attribution']) && $article['attribution'])
                                                <span>Por {{ $article['attribution'] }}</span>
                                            @endif
                                        </div>
                                        @if (isset($article['summary']) && $article['summary'])
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
            <button wire:click.prevent="store"
                class="w-full sm:flex-1 h-12 bg-primary text-white text-base font-semibold font-montserrat flex items-center justify-center gap-2 transition-colors disabled:opacity-70"
                wire:loading.attr="disabled" wire:target="store">
                <!-- Spinner de carga -->
                <div wire:loading wire:target="store"
                    class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <!-- Texto del botón -->
                <span wire:loading.remove wire:target="store">Publicar Artículo</span>
                <span wire:loading wire:target="store">Publicando...</span>
            </button>

            <button wire:click.prevent="saveDraft"
                class="w-full sm:flex-1 h-12 text-base font-semibold font-montserrat border border-primary hover:bg-sage transition-colors flex items-center justify-center gap-2 disabled:opacity-70"
                wire:loading.attr="disabled" wire:target="saveDraft">
                <!-- Spinner de carga -->
                <div wire:loading wire:target="saveDraft"
                    class="w-5 h-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                <!-- Texto del botón -->
                <span wire:loading.remove wire:target="saveDraft">Guardar Borrador</span>
                <span wire:loading wire:target="saveDraft">Guardando...</span>
            </button>

            <button type="button" wire:click="cancel"
                class="w-full sm:flex-1 flex justify-center items-center h-12 text-base font-semibold font-montserrat border text-gray-light border-gray-light hover:bg-sage transition-colors">
                Cancelar
            </button>
        </div>

        @if (session('message'))
            <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800">
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

    <!-- Modal de desarrollo -->
    <livewire:develop-modal />

</div>

<script>
    function insertMarkdownCaption(button, startTag, endTag) {
        // Encontrar el textarea de caption
        const textarea = document.getElementById('image_caption');
        if (!textarea) return;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const selectedText = text.substring(start, end);

        let newText;
        let newCursorPos;

        if (start === end) {
            // No hay selección, insertar tags vacíos y posicionar cursor en el medio
            newText = text.substring(0, start) + startTag + endTag + text.substring(end);
            newCursorPos = start + startTag.length;
        } else {
            // Hay texto seleccionado - aplicar toggle
            let replacement;

            if (startTag === '**') {
                // Botón negrita
                if (selectedText.startsWith('***') && selectedText.endsWith('***')) {
                    // Tiene negrita+cursiva -> quitar negrita, mantener cursiva
                    replacement = selectedText.slice(1, -1);
                } else if (selectedText.startsWith('**') && selectedText.endsWith('**')) {
                    // Tiene negrita -> quitar negrita
                    replacement = selectedText.slice(2, -2);
                } else if (selectedText.startsWith('*') && selectedText.endsWith('*') && !selectedText.startsWith('**')) {
                    // Tiene cursiva -> agregar negrita (negrita+cursiva)
                    replacement = '*' + selectedText + '*';
                } else {
                    // Sin formato -> agregar negrita
                    replacement = '**' + selectedText + '**';
                }
            } else {
                // Botón cursiva
                if (selectedText.startsWith('***') && selectedText.endsWith('***')) {
                    // Tiene negrita+cursiva -> quitar cursiva, mantener negrita
                    replacement = selectedText.slice(1, -1);
                } else if (selectedText.startsWith('**') && selectedText.endsWith('**')) {
                    // Tiene negrita -> agregar cursiva (negrita+cursiva)
                    replacement = '*' + selectedText + '*';
                } else if (selectedText.startsWith('*') && selectedText.endsWith('*') && !selectedText.startsWith('**')) {
                    // Tiene cursiva -> quitar cursiva
                    replacement = selectedText.slice(1, -1);
                } else {
                    // Sin formato -> agregar cursiva
                    replacement = '*' + selectedText + '*';
                }
            }

            newText = text.substring(0, start) + replacement + text.substring(end);
            newCursorPos = start + replacement.length;
        }

        textarea.value = newText;
        textarea.focus();
        textarea.setSelectionRange(newCursorPos, newCursorPos);

        // Disparar eventos para Livewire
        textarea.dispatchEvent(new Event('input', {
            bubbles: true
        }));
        textarea.dispatchEvent(new Event('blur', {
            bubbles: true
        }));
    }
</script>

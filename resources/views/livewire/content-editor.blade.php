<div class="space-y-4">
    <!-- Indicador de carga para subida de imágenes -->
    <div wire:loading.delay wire:target="blocks"
        class="mb-4 p-3 bg-blue-100 border border-blue-400 text-blue-700 flex items-center gap-2 font-opensans text-sm">
        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
            </circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
        Procesando y optimizando imagen...
    </div>

    <!-- Header del Editor -->
    <div class="flex items-center justify-between flex-wrap gap-2">
        <h3 class="font-montserrat font-medium text-primary text-base sm:text-lg">Bloques del Artículo</h3>
        <div class="flex items-center gap-1 sm:gap-2">
            <span class="text-xs sm:text-sm text-gray-500 font-opensans">{{ count($blocks) }} bloque(s)</span>
            <button type="button"
                class="px-2 sm:px-3 py-1 text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors font-opensans">
                Vista previa
            </button>
        </div>
    </div>

    <!-- Área de contenido -->
    <div class="border border-gray-300 bg-white {{ empty($blocks) ? 'min-h-[200px]' : 'min-h-0' }}">

        @if (empty($blocks))
            <!-- Estado vacío -->
            @if (!$showBlockSelector)
                <div class="flex flex-col items-center justify-center py-8 sm:py-12 text-center px-4">
                    <h4 class="font-montserrat font-medium text-primary text-base sm:text-lg mb-2">Comienza a escribir
                        tu artículo
                    </h4>
                    <p class="text-gray-500 font-opensans mb-4 sm:mb-6 max-w-xs sm:max-w-md text-sm sm:text-base">
                        Agrega bloques de contenido como texto, imágenes, videos y más para crear un artículo atractivo.
                    </p>
                    <button type="button" wire:click="testClick"
                        class="px-4 sm:px-6 py-2 sm:py-3 bg-primary text-white font-montserrat font-medium hover:bg-dark-sage transition-colors text-sm sm:text-base">
                        + Agregar primer bloque
                    </button>
                </div>
            @else
                <!-- Panel de tipos de bloque -->
                <div class="p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-montserrat font-medium text-primary text-base sm:text-lg">Selecciona un tipo de
                            bloque</h4>
                        <button type="button" wire:click="closeBlockSelector"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Bloques principales (siempre visibles) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <button type="button" wire:click="addBlock('paragraph', null)"
                            class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <div class="font-opensans font-medium text-sm">Párrafo</div>
                                <div class="font-opensans text-xs text-gray-500">Texto normal</div>
                            </div>
                        </button>

                        <button type="button" wire:click="addBlock('heading', null)"
                            class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <div class="font-opensans font-medium text-sm">Encabezado</div>
                                <div class="font-opensans text-xs text-gray-500">H2, H3, H4</div>
                            </div>
                        </button>

                        <button type="button" wire:click="addBlock('image', null)"
                            class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <div class="font-opensans font-medium text-sm">Imagen</div>
                                <div class="font-opensans text-xs text-gray-500">Subir archivo</div>
                            </div>
                        </button>

                        <button type="button" wire:click="addBlock('quote', null)"
                            class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <div>
                                <div class="font-opensans font-medium text-sm">Cita</div>
                                <div class="font-opensans text-xs text-gray-500">Destacar texto</div>
                            </div>
                        </button>
                    </div>

                    <!-- Bloques adicionales (colapsables) -->
                    @if ($showMoreBlocks)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                            <button type="button" wire:click="addBlock('list', null)"
                                class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                <div>
                                    <div class="font-opensans font-medium text-sm">Lista</div>
                                    <div class="font-opensans text-xs text-gray-500">Con viñetas o numerada</div>
                                </div>
                            </button>

                            <button type="button" wire:click="addBlock('video', null)"
                                class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <div class="font-opensans font-medium text-sm">Video</div>
                                    <div class="font-opensans text-xs text-gray-500">YouTube, Vimeo</div>
                                </div>
                            </button>

                            <button type="button" wire:click="addBlock('gallery', null)"
                                class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <div>
                                    <div class="font-opensans font-medium text-sm">Galería</div>
                                    <div class="font-opensans text-xs text-gray-500">Múltiples imágenes</div>
                                </div>
                            </button>

                            <button type="button" wire:click="addBlock('review', null)"
                                class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <div>
                                    <div class="font-opensans font-medium text-sm">Reseña</div>
                                    <div class="font-opensans text-xs text-gray-500">Testimonios con foto</div>
                                </div>
                            </button>
                        </div>
                    @endif

                    <!-- Botón para mostrar más/menos opciones -->
                    <div class="text-center border-t border-gray-100 pt-4">
                        @if ($showMoreBlocks)
                            <button type="button" wire:click="toggleMoreBlocks"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-opensans text-gray-600 hover:text-primary transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                                Mostrar menos opciones
                            </button>
                        @else
                            <button type="button" wire:click="toggleMoreBlocks"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-opensans text-gray-600 hover:text-primary transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                                Ver más opciones (3)
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <!-- Bloques de contenido -->
            <div class="p-6 space-y-8">
                @foreach ($blocks as $index => $block)
                    <div class="group relative border border-gray-200 hover:border-gray-300 transition-colors">

                        <!-- Toolbar del bloque -->
                        <div
                            class="absolute -top-10 left-0 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity bg-white border border-gray-200 shadow-sm flex items-center gap-1 px-2 py-1">
                            <button type="button" wire:click="moveBlockUp({{ $index }})"
                                class="p-1 text-gray-500 hover:text-primary transition-colors {{ $index === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                title="Mover arriba" {{ $index === 0 ? 'disabled' : '' }}>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                            <button type="button" wire:click="moveBlockDown({{ $index }})"
                                class="p-1 text-gray-500 hover:text-primary transition-colors {{ $index === count($blocks) - 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                title="Mover abajo" {{ $index === count($blocks) - 1 ? 'disabled' : '' }}>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="h-4 w-px bg-gray-300 mx-1"></div>
                            <button type="button" wire:click="duplicateBlock({{ $index }})"
                                class="p-1 text-gray-500 hover:text-primary transition-colors" title="Duplicar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                            <button type="button" wire:click="deleteBlock({{ $index }})"
                                class="p-1 text-gray-500 hover:text-red-500 transition-colors" title="Eliminar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Contenido del bloque según su tipo -->
                        <div class="p-4">
                            @switch($block['type'])
                                @case('paragraph')
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs text-gray-500 font-opensans font-medium">PÁRRAFO</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" onclick="insertMarkdown(this, '*', '*')"
                                                    class="px-2 py-1 text-xs border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 font-opensans italic"
                                                    title="Cursiva">
                                                    <em>I</em>
                                                </button>
                                                <button type="button" onclick="insertMarkdown(this, '**', '**')"
                                                    class="px-2 py-1 text-xs border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 font-opensans font-bold"
                                                    title="Negrita">
                                                    B
                                                </button>
                                                <span class="text-xs text-gray-400 font-opensans ml-2">
                                                    <span class="hidden sm:inline">Markdown</span>
                                                </span>
                                            </div>
                                        </div>
                                        <textarea id="paragraph-{{ $index }}"
                                            placeholder="Escribe tu contenido aquí... Soporta *cursiva*, **negrita**, listas (- elemento) y saltos de línea"
                                            class="w-full border-0 focus:outline-none font-opensans text-sm leading-relaxed p-2 min-h-20 resize-none"
                                            style="field-sizing: content;" wire:model.blur="blocks.{{ $index }}.content">{{ $block['content'] ?? '' }}</textarea>

                                        @if (!empty($block['content']))
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
                                                            $content = e($block['content']);
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
                                @break

                                @case('heading')
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-xs text-gray-500 font-opensans font-medium">ENCABEZADO</span>
                                            <select wire:model.live="blocks.{{ $index }}.level"
                                                class="text-xs border border-gray-300 px-2 py-1 bg-white focus:outline-none focus:border-primary">
                                                <option value="2">H2</option>
                                                <option value="3">H3</option>
                                                <option value="4">H4</option>
                                            </select>
                                        </div>
                                        <input type="text" placeholder="Título del encabezado..."
                                            class="w-full border-0 focus:outline-none font-montserrat font-semibold text-xl"
                                            value="{{ $block['content'] ?? '' }}"
                                            wire:model.blur="blocks.{{ $index }}.content">
                                    </div>
                                @break

                                @case('image')
                                    <div class="space-y-4">
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0 mb-3">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs text-gray-500 font-opensans font-medium">IMAGEN</span>
                                            </div>
                                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                                <select wire:model.live="blocks.{{ $index }}.layout"
                                                    class="text-xs border border-gray-300 px-2 py-1 bg-white focus:outline-none focus:border-primary flex-1 sm:flex-none min-w-0">
                                                    <option value="full">Solo imagen</option>
                                                    <option value="text-right">Texto a la derecha</option>
                                                    <option value="text-left">Texto a la izquierda</option>
                                                    <option value="text-below">Texto abajo</option>
                                                </select>
                                                <select wire:model.live="blocks.{{ $index }}.size"
                                                    class="text-xs border border-gray-300 px-2 py-1 bg-white focus:outline-none focus:border-primary flex-1 sm:flex-none min-w-0">
                                                    <option value="small">Pequeña</option>
                                                    <option value="medium">Mediana</option>
                                                    <option value="large">Grande</option>
                                                </select>
                                            </div>
                                        </div>

                                        @php
                                            $imageUrl = $block['url'] ?? '';
                                            $layout = $block['layout'] ?? 'full';
                                            $size = $block['size'] ?? 'large';
                                        @endphp

                                        @if ($imageUrl)
                                            <!-- Vista previa con layout -->
                                            <div class="border border-gray-200 p-4 bg-gray-50 relative">
                                                <!-- Botón eliminar imagen -->
                                                <button type="button" wire:click="removeImage({{ $index }})"
                                                    class="absolute top-2 right-2 z-10 h-8 w-8 text-primary hover:text-white transition-colors flex items-center justify-center"
                                                    style="hover:background-color: var(--color-red-light);"
                                                    onmouseover="this.style.backgroundColor='var(--color-red-light)'"
                                                    onmouseout="this.style.backgroundColor='transparent'">
                                                    <x-close-svg width="20px" height="20px" fill="currentColor" />
                                                </button>

                                                @if ($layout === 'full')
                                                    <!-- Solo imagen -->
                                                    <div class="text-center space-y-2">
                                                        <img src="{{ $imageUrl }}" alt="{{ $block['alt_text'] ?? '' }}"
                                                            class="@if ($size === 'small') max-w-xs @elseif($size === 'medium') max-w-md @else max-w-full @endif mx-auto h-auto max-h-96 object-contain">
                                                        @if (!empty($block['credits']))
                                                            <p class="text-xs text-gray-500 font-opensans italic">
                                                                {{ $block['credits'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @elseif($layout === 'text-right')
                                                    <!-- Imagen izquierda, texto derecha -->
                                                    <div class="flex gap-4 items-start">
                                                        <div class="flex-shrink-0 space-y-1">
                                                            <img src="{{ $imageUrl }}"
                                                                alt="{{ $block['alt_text'] ?? '' }}"
                                                                class="@if ($size === 'small') w-32 @elseif($size === 'medium') w-48 @else w-64 @endif h-auto max-h-80 object-contain">
                                                            @if (!empty($block['credits']))
                                                                <p
                                                                    class="text-xs text-gray-500 font-opensans italic text-center">
                                                                    {{ $block['credits'] }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm text-gray-600 font-opensans">
                                                                {{ $block['caption'] ?? 'Texto descriptivo aparecerá aquí...' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @elseif($layout === 'text-left')
                                                    <!-- Texto izquierda, imagen derecha -->
                                                    <div class="flex gap-4 items-start">
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm text-gray-600 font-opensans">
                                                                {{ $block['caption'] ?? 'Texto descriptivo aparecerá aquí...' }}
                                                            </p>
                                                        </div>
                                                        <div class="flex-shrink-0 space-y-1">
                                                            <img src="{{ $imageUrl }}"
                                                                alt="{{ $block['alt_text'] ?? '' }}"
                                                                class="@if ($size === 'small') w-32 @elseif($size === 'medium') w-48 @else w-64 @endif h-auto max-h-80 object-contain">
                                                            @if (!empty($block['credits']))
                                                                <p
                                                                    class="text-xs text-gray-500 font-opensans italic text-center">
                                                                    {{ $block['credits'] }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @elseif($layout === 'text-below')
                                                    <!-- Imagen arriba, texto abajo -->
                                                    <div class="space-y-3">
                                                        <div class="text-center space-y-1">
                                                            <img src="{{ $imageUrl }}"
                                                                alt="{{ $block['alt_text'] ?? '' }}"
                                                                class="@if ($size === 'small') max-w-xs @elseif($size === 'medium') max-w-md @else max-w-full @endif mx-auto h-auto max-h-96 border border-gray-200">
                                                            @if (!empty($block['credits']))
                                                                <p class="text-xs text-gray-500 font-opensans italic">
                                                                    {{ $block['credits'] }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <p class="text-sm text-gray-600 font-opensans text-left">
                                                            {{ $block['caption'] ?? 'Texto descriptivo aparecerá aquí...' }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Campos de edición -->
                                            <div class="space-y-3 pt-3">
                                                <div class="grid grid-cols-1 gap-3">
                                                    @if ($layout !== 'full')
                                                        <div>
                                                            <label
                                                                class="block text-xs font-medium text-gray-700 mb-1">Descripción/Caption</label>
                                                            <textarea rows="2" placeholder="Descripción que aparece con la imagen..."
                                                                class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-primary resize-none h-28"
                                                                wire:model.blur="blocks.{{ $index }}.caption">{{ $block['caption'] ?? '' }}</textarea>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Alt Text
                                                            (accesibilidad)
                                                        </label>
                                                        <input type="text"
                                                            placeholder="Descripción breve para lectores de pantalla..."
                                                            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-primary"
                                                            wire:model.blur="blocks.{{ $index }}.alt_text"
                                                            value="{{ $block['alt_text'] ?? '' }}">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Créditos
                                                            (opcional)</label>
                                                        <input type="text" placeholder="Fotógrafo, fuente, etc..."
                                                            class="w-full border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:border-primary"
                                                            wire:model.blur="blocks.{{ $index }}.credits"
                                                            value="{{ $block['credits'] ?? '' }}">
                                                    </div>
                                                    <!-- Loading spinner -->
                                                    <div class="w-full flex flex-col items-center justify-center py-4 text-center"
                                                        wire:loading wire:target="blocks.{{ $index }}.image_file">
                                                        <div
                                                            class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto">
                                                        </div>
                                                        <span
                                                            class="mt-3 text-primary font-opensans text-sm">Cargando...</span>
                                                    </div>

                                                    <!-- File input -->
                                                    <div class="block w-full" wire:loading.remove
                                                        wire:target="blocks.{{ $index }}.image_file">
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Cambiar
                                                            imagen</label>
                                                        <input type="file" accept="image/*,.avif"
                                                            class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-primary text-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-dark-sage font-opensans"
                                                            wire:model="blocks.{{ $index }}.image_file">
                                                        <p class="text-xs text-gray-500 mt-1 font-opensans">Tamaño máximo:
                                                            10MB. Se convertirá automáticamente a WebP</p>
                                                        @if (session()->has('error'))
                                                            <div
                                                                class="mt-2 p-3 bg-red-100 border border-red-400 text-red-700 font-opensans text-sm">
                                                                {{ session('error') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Estado sin imagen -->
                                            <div class="space-y-4">
                                                <!-- Loading spinner -->
                                                <div class="w-full flex flex-col items-center justify-center py-8 text-center"
                                                    wire:loading wire:target="blocks.{{ $index }}.image_file">
                                                    <div
                                                        class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto">
                                                    </div>
                                                    <span class="mt-3 text-primary font-opensans text-sm">Cargando...</span>
                                                </div>

                                                <!-- File input -->
                                                <div class="block w-full" wire:loading.remove
                                                    wire:target="blocks.{{ $index }}.image_file">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1 font-opensans">Imagen</label>
                                                    <input type="file" accept="image/*,.avif"
                                                        class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-primary text-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-dark-sage font-opensans"
                                                        wire:model="blocks.{{ $index }}.image_file">
                                                    <p class="text-xs text-gray-500 mt-1 font-opensans">Tamaño máximo: 10MB.
                                                        Se convertirá automáticamente a WebP</p>
                                                    @if (session()->has('error'))
                                                        <div
                                                            class="mt-2 p-3 bg-red-100 border border-red-400 text-red-700 font-opensans text-sm">
                                                            {{ session('error') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @break

                                @case('quote')
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                <span class="text-xs text-gray-500 font-opensans font-medium">CITA</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" onclick="insertMarkdownQuote(this, '**', '**')"
                                                    class="px-2 py-1 text-xs border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 font-opensans font-bold"
                                                    title="Negrita">
                                                    B
                                                </button>
                                            </div>
                                        </div>
                                        <div class="border-l-4 border-primary bg-gray-50 p-4 space-y-3">
                                            <textarea id="quote-content-{{ $index }}" rows="3" placeholder="Escribe la cita aquí..."
                                                class="w-full border-0 bg-transparent resize-none focus:outline-none font-opensans text-lg italic"
                                                wire:model.blur="blocks.{{ $index }}.content">{{ $block['content'] ?? '' }}</textarea>
                                            <input id="quote-author-{{ $index }}" type="text"
                                                placeholder="Autor de la cita (opcional)"
                                                class="w-full border-0 bg-transparent focus:outline-none font-opensans text-sm font-medium"
                                                value="{{ $block['author'] ?? '' }}"
                                                wire:model.blur="blocks.{{ $index }}.author">
                                        </div>

                                        @if (!empty($block['content']))
                                            <div class="border-t border-gray-100 pt-2">
                                                <div class="text-xs text-gray-500 font-opensans mb-1">Vista previa:</div>
                                                <div class="border-l-4 border-primary bg-white p-3">
                                                    <blockquote class="text-lg italic text-gray-700 mb-2">
                                                        "{!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', e($block['content'])) !!}"
                                                    </blockquote>
                                                    @if (!empty($block['author']))
                                                        <cite class="text-sm font-medium text-gray-600">
                                                            — {!! preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', e($block['author'])) !!}
                                                        </cite>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @break

                                @case('list')
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                            </svg>
                                            <span class="text-xs text-gray-500 font-opensans font-medium">LISTA</span>
                                            <select wire:model.live="blocks.{{ $index }}.listType"
                                                class="text-xs border border-gray-300 px-2 py-1 bg-white focus:outline-none focus:border-primary">
                                                <option value="bullet">Con viñetas</option>
                                                <option value="numbered">Numerada</option>
                                            </select>
                                            <div class="flex items-center gap-2 ml-auto">
                                                <button type="button" onclick="insertMarkdownList(this, '*', '*')"
                                                    class="px-2 py-1 text-xs border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 font-opensans italic"
                                                    title="Cursiva">
                                                    <em>I</em>
                                                </button>
                                                <button type="button" onclick="insertMarkdownList(this, '**', '**')"
                                                    class="px-2 py-1 text-xs border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 font-opensans font-bold"
                                                    title="Negrita">
                                                    B
                                                </button>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            @php
                                                $listItems = $block['items'] ?? [''];
                                                $listType = $block['listType'] ?? 'bullet';
                                            @endphp

                                            @foreach ($listItems as $itemIndex => $item)
                                                <div class="flex items-start gap-3">
                                                    @if ($listType === 'numbered')
                                                        <span
                                                            class="text-gray-400 font-opensans text-sm mt-1 w-4">{{ $itemIndex + 1 }}.</span>
                                                    @else
                                                        <span class="text-gray-400 text-sm mt-2">•</span>
                                                    @endif
                                                    <div class="flex-1 flex items-center gap-2">
                                                        <input id="list-item-{{ $index }}-{{ $itemIndex }}"
                                                            type="text" placeholder="Elemento de la lista..."
                                                            class="flex-1 border-0 focus:outline-none font-opensans text-sm py-1"
                                                            value="{{ $item }}"
                                                            wire:model.blur="blocks.{{ $index }}.items.{{ $itemIndex }}">
                                                        @if (count($listItems) > 1)
                                                            <button type="button"
                                                                wire:click="removeListItem({{ $index }}, {{ $itemIndex }})"
                                                                class="p-1 text-gray-400 hover:text-red-500 transition-colors"
                                                                title="Eliminar elemento">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach

                                            <button type="button" wire:click="addListItem({{ $index }})"
                                                class="text-xs text-primary hover:text-dark-sage transition-colors font-opensans flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Agregar elemento
                                            </button>
                                        </div>

                                        @if (!empty($listItems) && !empty($listItems[0]))
                                            <div class="border-t border-gray-100 pt-2">
                                                <div class="text-xs text-gray-500 font-opensans mb-1">Vista previa:</div>
                                                <div
                                                    class="text-sm font-opensans leading-relaxed text-gray-700 bg-gray-50 p-2 border border-gray-200">
                                                    @if ($listType === 'numbered')
                                                        <ol class="list-decimal list-inside space-y-1">
                                                            @foreach ($listItems as $item)
                                                                @if (!empty($item))
                                                                    <li>{!! preg_replace(
                                                                        ['/\*\*\*(.*?)\*\*\*/', '/\*\*(.*?)\*\*/', '/\*(.*?)\*/'],
                                                                        ['<strong><em>$1</em></strong>', '<strong>$1</strong>', '<em>$1</em>'],
                                                                        e($item),
                                                                    ) !!}</li>
                                                                @endif
                                                            @endforeach
                                                        </ol>
                                                    @else
                                                        <ul class="list-disc list-inside space-y-1">
                                                            @foreach ($listItems as $item)
                                                                @if (!empty($item))
                                                                    <li>{!! preg_replace(
                                                                        ['/\*\*\*(.*?)\*\*\*/', '/\*\*(.*?)\*\*/', '/\*(.*?)\*/'],
                                                                        ['<strong><em>$1</em></strong>', '<strong>$1</strong>', '<em>$1</em>'],
                                                                        e($item),
                                                                    ) !!}</li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @break

                                @case('gallery')
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                <span class="text-xs text-gray-500 font-opensans font-medium">GALERÍA</span>
                                            </div>
                                        </div>

                                        @php
                                            $images = $block['images'] ?? [];
                                            $currentImage = $block['currentImage'] ?? 0;
                                        @endphp

                                        <!-- Spinner de carga para galería -->
                                        <div class="w-full flex flex-col items-center justify-center py-8 text-center"
                                            wire:loading wire:target="galleryFiles.{{ $index }}">
                                            <div
                                                class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto">
                                            </div>
                                            <span class="mt-3 text-primary font-opensans text-sm">Subiendo imágenes...</span>
                                        </div>

                                        <!-- Contenedor principal (ocultar durante carga) -->
                                        <div wire:loading.remove wire:target="galleryFiles.{{ $index }}">

                                            <!-- Imagen principal -->
                                            @if (!empty($images))
                                                <div class="relative border border-gray-200 bg-white">
                                                    <img src="{{ $images[$currentImage] }}"
                                                        alt="Imagen {{ $currentImage + 1 }}"
                                                        class="w-full h-auto max-h-96 object-contain">
                                                    <!-- Botón eliminar solo en imagen principal -->
                                                    <button type="button"
                                                        wire:click="removeGalleryImage({{ $index }}, {{ $currentImage }})"
                                                        class="absolute top-2 right-2 z-10 h-8 w-8 text-primary hover:text-white transition-colors flex items-center justify-center"
                                                        style="hover:background-color: var(--color-red-light);"
                                                        onmouseover="this.style.backgroundColor='var(--color-red-light)'"
                                                        onmouseout="this.style.backgroundColor='transparent'">
                                                        <x-close-svg width="20px" height="20px" fill="currentColor" />
                                                    </button>

                                                    <!-- Contador simple -->
                                                    <div
                                                        class="absolute bottom-3 right-3 bg-black bg-opacity-70 text-white px-3 py-1 text-xs font-opensans whitespace-nowrap min-w-0">
                                                        {{ $currentImage + 1 }} / {{ count($images) }}
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Miniaturas + botón agregar (solo si hay imágenes) -->
                                            @if (!empty($images))
                                                <div class="flex gap-2 overflow-x-auto py-2">
                                                    @foreach ($images as $imageIndex => $imageUrl)
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ $imageUrl }}"
                                                                alt="Imagen {{ $imageIndex + 1 }}"
                                                                class="w-16 h-16 object-cover border-2 cursor-pointer transition-colors {{ $imageIndex === $currentImage ? 'border-primary' : 'border-gray-300 hover:border-gray-400' }}"
                                                                wire:click="setGalleryImage({{ $index }}, {{ $imageIndex }})">
                                                        </div>
                                                    @endforeach

                                                    <!-- Miniatura para agregar nueva imagen (solo si no se alcanzó el límite) -->
                                                    @if (count($images) < 15)
                                                        <label class="flex-shrink-0 cursor-pointer">
                                                            <div
                                                                class="w-16 h-16 border-2 border-dashed border-gray-300 hover:border-primary transition-colors flex items-center justify-center bg-gray-50 hover:bg-gray-100">
                                                                <svg class="w-6 h-6 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                                </svg>
                                                            </div>
                                                            <input type="file" multiple accept="image/*"
                                                                wire:model="galleryFiles.{{ $index }}" class="hidden">
                                                        </label>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Área de subida inicial (solo si no hay imágenes) -->
                                            @if (empty($images))
                                                <label class="block cursor-pointer">
                                                    <div
                                                        class="border-2 border-dashed border-gray-300 hover:border-primary transition-colors p-8 text-center">
                                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4"
                                                            stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path
                                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                                stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <span class="font-opensans text-primary hover:text-primary/80">Agregar
                                                            imágenes a la galería</span>
                                                        <p class="text-xs text-gray-500 font-opensans mt-2">Selecciona
                                                            múltiples archivos (máximo 15)</p>
                                                    </div>
                                                    <input type="file" multiple accept="image/*"
                                                        wire:model="galleryFiles.{{ $index }}" class="hidden">
                                                </label>
                                            @endif

                                            <!-- Mensaje de límite alcanzado -->
                                            @if (!empty($images) && count($images) >= 15)
                                                <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 text-center">
                                                    <p class="text-xs text-yellow-700 font-opensans">
                                                        Límite de 15 imágenes alcanzado
                                                    </p>
                                                </div>
                                            @endif

                                            <!-- Botones de navegación simples -->
                                            @if (count($images) > 1)
                                                <div class="flex justify-between mt-3">
                                                    <button type="button"
                                                        wire:click="changeGalleryImage({{ $index }}, 'prev')"
                                                        class="px-3 py-2 bg-white border border-gray-300 hover:bg-gray-50 font-opensans text-sm {{ $currentImage === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ $currentImage === 0 ? 'disabled' : '' }}>
                                                        ← Anterior
                                                    </button>

                                                    <button type="button"
                                                        wire:click="changeGalleryImage({{ $index }}, 'next')"
                                                        class="px-3 py-2 bg-white border border-gray-300 hover:bg-gray-50 font-opensans text-sm {{ $currentImage === count($images) - 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ $currentImage === count($images) - 1 ? 'disabled' : '' }}>
                                                        Siguiente →
                                                    </button>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @break

                                @case('review')
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                <span class="text-xs text-gray-500 font-opensans font-medium">RESEÑAS</span>
                                            </div>
                                        </div>

                                        @php
                                            $reviews = $block['reviews'] ?? [];
                                            $currentReview = $block['currentReview'] ?? 0;
                                        @endphp

                                        <!-- Vista previa principal -->
                                        @if (!empty($reviews))
                                            @if (count($reviews) > 1)
                                                <div class="flex justify-end mb-4">
                                                    <button type="button"
                                                        wire:click="removeReview({{ $index }}, {{ $currentReview }})"
                                                        class="text-xs text-red-500 hover:text-red-700 font-opensans flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Eliminar esta reseña
                                                    </button>
                                                </div>
                                            @endif

                                            <div class="border border-gray-200 bg-white p-6 relative">
                                                @php
                                                    $review = $reviews[$currentReview] ?? [];
                                                @endphp

                                                <!-- Estructura de una reseña -->
                                                <div class="flex gap-6 items-start">
                                                    <!-- Foto de la persona -->
                                                    <div class="shrink-0">
                                                        @if (!empty($review['photo']))
                                                            <img src="{{ $review['photo'] }}"
                                                                alt="{{ $review['name'] ?? 'Persona' }}"
                                                                class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                                                        @else
                                                            <div
                                                                class="w-20 h-20 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center">
                                                                <svg class="w-8 h-8 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Contenido de la reseña -->
                                                    <div class="flex-1 min-w-0">
                                                        <!-- Texto de la reseña -->
                                                        <blockquote
                                                            class="text-gray-700 font-opensans text-base leading-relaxed mb-4 italic">
                                                            "{{ $review['content'] ?? 'Escribe aquí el contenido de la reseña...' }}"
                                                        </blockquote>

                                                        <!-- Nombre y título -->
                                                        <div class="space-y-1">
                                                            <div class="font-montserrat font-semibold text-primary">
                                                                {{ $review['name'] ?? 'Nombre de la persona' }}
                                                            </div>
                                                            @if (!empty($review['title']))
                                                                <div class="font-opensans text-sm text-gray-500">
                                                                    {{ $review['title'] }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Estrellas (opcional) -->
                                                        @if (!empty($review['rating']) && $review['rating'] > 0)
                                                            <div class="flex gap-1 mt-2">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i <= $review['rating'])
                                                                        <svg class="w-4 h-4 text-yellow-400"
                                                                            fill="currentColor" viewBox="0 0 20 20">
                                                                            <path
                                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                        </svg>
                                                                    @else
                                                                        <svg class="w-4 h-4 text-gray-300" fill="currentColor"
                                                                            viewBox="0 0 20 20">
                                                                            <path
                                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                        </svg>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Contador si hay múltiples reseñas -->
                                                @if (count($reviews) > 1)
                                                    <div
                                                        class="absolute bottom-3 right-3 bg-black bg-opacity-70 text-white px-3 py-1 text-xs font-opensans">
                                                        {{ $currentReview + 1 }} / {{ count($reviews) }}
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Controles de carrusel -->
                                            @if (count($reviews) > 1)
                                                <div class="flex justify-between items-center mt-4">
                                                    <button type="button"
                                                        wire:click="changeReview({{ $index }}, 'prev')"
                                                        class="flex items-center gap-2 px-3 py-2 text-sm font-opensans text-gray-600 hover:text-primary transition-colors {{ $currentReview === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ $currentReview === 0 ? 'disabled' : '' }}>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7" />
                                                        </svg>
                                                        Anterior
                                                    </button>

                                                    <button type="button"
                                                        wire:click="changeReview({{ $index }}, 'next')"
                                                        class="flex items-center gap-2 px-3 py-2 text-sm font-opensans text-gray-600 hover:text-primary transition-colors {{ $currentReview === count($reviews) - 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                        {{ $currentReview === count($reviews) - 1 ? 'disabled' : '' }}>
                                                        Siguiente
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Indicadores de puntos -->
                                                <div class="flex justify-center gap-2 mt-3">
                                                    @foreach ($reviews as $index => $rev)
                                                        <button type="button"
                                                            wire:click="setCurrentReview({{ $index }}, {{ $index }})"
                                                            class="w-2 h-2 rounded-full transition-colors {{ $index === $currentReview ? 'bg-primary' : 'bg-gray-300 hover:bg-gray-400' }}">
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @else
                                            <!-- Estado vacío -->
                                            <button type="button" wire:click="addReview({{ $index }})"
                                                class="w-full border-2 border-dashed border-gray-300 p-8 text-center bg-gray-50 hover:bg-gray-100 hover:border-primary transition-colors cursor-pointer">
                                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                <div class="font-opensans text-primary font-medium">
                                                    Agregar primera reseña
                                                </div>
                                                <p class="text-xs text-gray-500 font-opensans mt-1">Testimonios de clientes o
                                                    usuarios</p>
                                            </button>
                                        @endif

                                        <!-- Panel de edición -->
                                        @if (!empty($reviews))
                                            <div class="pt-4 space-y-4">
                                                <div class="space-y-4">
                                                    <!-- Foto -->
                                                    <div class=" grid grid-cols-1 gap-3">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto de la
                                                            persona</label>

                                                        <!-- Vista previa de la foto actual -->
                                                        @if (!empty($reviews[$currentReview]['photo'] ?? ''))
                                                            <div class="mb-3 relative">
                                                                <img src="{{ $reviews[$currentReview]['photo'] }}"
                                                                    alt="Vista previa"
                                                                    class="w-20 h-20 object-cover border-2 border-gray-200">
                                                                <!-- Botón eliminar foto -->
                                                                <button type="button"
                                                                    wire:click="removeReviewPhoto({{ $index }}, {{ $currentReview }})"
                                                                    class="absolute top-2 right-2 z-10 h-8 w-8 text-primary hover:text-white transition-colors flex items-center justify-center"
                                                                    style="hover:background-color: var(--color-red-light);"
                                                                    onmouseover="this.style.backgroundColor='var(--color-red-light)'"
                                                                    onmouseout="this.style.backgroundColor='transparent'"
                                                                    title="Eliminar foto">
                                                                    <x-close-svg width="20px" height="20px"
                                                                        fill="currentColor" />
                                                                </button>
                                                                {{-- <p class="text-xs text-green-600 mt-2">✓ Foto cargada</p> --}}
                                                            </div>
                                                        @endif

                                                        <!-- Loading spinner -->
                                                        <div class="w-full flex flex-col items-center justify-center py-6 text-center"
                                                            wire:loading
                                                            wire:target="reviewFiles.{{ $index }}.{{ $currentReview }}">
                                                            <div
                                                                class="w-6 h-6 border-2 border-primary border-t-transparent rounded-full animate-spin mx-auto">
                                                            </div>
                                                            <span
                                                                class="mt-3 text-primary font-opensans text-sm">Subiendo...</span>
                                                        </div>

                                                        <!-- File input -->
                                                        <div wire:loading.remove
                                                            wire:target="reviewFiles.{{ $index }}.{{ $currentReview }}">
                                                            <input type="file" accept="image/*"
                                                                class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:border-primary text-sm file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-dark-sage font-opensans"
                                                                wire:model="reviewFiles.{{ $index }}.{{ $currentReview }}">
                                                            <p class="text-xs text-gray-500 mt-2 font-opensans h-auto">Tamaño
                                                                máximo: 10MB. Se convertirá a WebP</p>
                                                        </div>
                                                    </div>

                                                    <!-- Nombre -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre
                                                            completo</label>
                                                        <input type="text" placeholder="Nombre de la persona"
                                                            class="w-full border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-primary h-12"
                                                            wire:model.blur="blocks.{{ $index }}.reviews.{{ $currentReview }}.name"
                                                            value="{{ $reviews[$currentReview]['name'] ?? '' }}">
                                                    </div>

                                                    <!-- Título/Cargo -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Título o
                                                            cargo (opcional)</label>
                                                        <input type="text" placeholder="Ej: CEO, Cliente, Usuario"
                                                            class="w-full border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-primary h-12"
                                                            wire:model.blur="blocks.{{ $index }}.reviews.{{ $currentReview }}.title"
                                                            value="{{ $reviews[$currentReview]['title'] ?? '' }}">
                                                    </div>

                                                    <!-- Calificación -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-2">Calificación
                                                            (opcional)</label>
                                                        <select
                                                            class="w-full border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:border-primary h-12"
                                                            wire:model.live="blocks.{{ $index }}.reviews.{{ $currentReview }}.rating">
                                                            <option value="">Sin calificación</option>
                                                            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                                            <option value="4">⭐⭐⭐⭐ (4)</option>
                                                            <option value="3">⭐⭐⭐ (3)</option>
                                                            <option value="2">⭐⭐ (2)</option>
                                                            <option value="1">⭐ (1)</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Contenido de la reseña -->
                                                <div class="space-y-2 border border-gray-300 p-4">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <div class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                            </svg>
                                                            <span
                                                                class="text-xs text-gray-500 font-opensans font-medium">CONTENIDO</span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <button type="button" onclick="insertMarkdown(this, '*', '*')"
                                                                class="px-2 py-1 text-xs border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 font-opensans italic"
                                                                title="Cursiva">
                                                                <em>I</em>
                                                            </button>
                                                            <button type="button" onclick="insertMarkdown(this, '**', '**')"
                                                                class="px-2 py-1 text-xs border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 font-opensans font-bold"
                                                                title="Negrita">
                                                                B
                                                            </button>
                                                            <span class="text-xs text-gray-400 font-opensans ml-2">
                                                                <span class="hidden sm:inline">Markdown</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <textarea id="review-content-{{ $index }}-{{ $currentReview }}"
                                                        placeholder="Escribe aquí el contenido de la reseña... Soporta *cursiva*, **negrita** y saltos de línea"
                                                        class="w-full border-0 focus:outline-none font-opensans text-sm leading-relaxed p-2 min-h-20 resize-none"
                                                        style="field-sizing: content;"
                                                        wire:model.blur="blocks.{{ $index }}.reviews.{{ $currentReview }}.content">{{ $reviews[$currentReview]['content'] ?? '' }}</textarea>

                                                    @if (!empty($reviews[$currentReview]['content'] ?? ''))
                                                        <div class="border-t border-gray-100 pt-2">
                                                            <button type="button"
                                                                class="flex items-center gap-1 text-xs text-gray-500 hover:text-gray-700 font-opensans mb-1 transition-colors"
                                                                onclick="this.nextElementSibling.classList.toggle('hidden')">
                                                                <svg class="w-3 h-3 transform transition-transform"
                                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                                </svg>
                                                                Vista previa
                                                            </button>
                                                            <div class="hidden">
                                                                <div
                                                                    class="text-sm font-opensans leading-relaxed text-gray-700 bg-gray-50 p-2 border border-gray-200 whitespace-pre-line">
                                                                    @php
                                                                        $content = e(
                                                                            $reviews[$currentReview]['content'] ?? '',
                                                                        );
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
                                            </div>
                                        @endif
                                    </div>
                                @break

                                @default
                                    <div class="text-center py-4 text-gray-500">
                                        <p class="font-opensans text-sm">Tipo de bloque no reconocido</p>
                                    </div>
                            @endswitch
                        </div>

                        <!-- Botón para agregar bloque debajo -->
                        <div
                            class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                            <button type="button" wire:click="openBlockSelector({{ $index }})"
                                class="w-8 h-8 bg-white border border-gray-300 hover:border-primary text-primary hover:bg-primary hover:text-white transition-colors flex items-center justify-center shadow-md z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Selector de tipos de bloque (aparece después del bloque seleccionado) -->
                    @if ($showBlockSelector && $blockSelectorIndex === $index)
                        <div class="p-4 sm:p-6 bg-gray-50 border border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-opensans font-medium text-sm sm:text-base text-gray-700">Selecciona un
                                    tipo de
                                    bloque</h3>
                                <button type="button" wire:click="closeBlockSelector"
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Bloques principales (siempre visibles) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                <button type="button" wire:click="addBlock('paragraph', {{ $index }})"
                                    class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-white border border-gray-200 transition-colors">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <div class="font-opensans font-medium text-sm">Párrafo</div>
                                        <div class="font-opensans text-xs text-gray-500">Texto normal</div>
                                    </div>
                                </button>

                                <button type="button" wire:click="addBlock('heading', {{ $index }})"
                                    class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-white border border-gray-200 transition-colors">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <div class="font-opensans font-medium text-sm">Encabezado</div>
                                        <div class="font-opensans text-xs text-gray-500">H2, H3, H4</div>
                                    </div>
                                </button>

                                <button type="button" wire:click="addBlock('image', {{ $index }})"
                                    class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-white border border-gray-200 transition-colors">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <div>
                                        <div class="font-opensans font-medium text-sm">Imagen</div>
                                        <div class="font-opensans text-xs text-gray-500">Subir archivo</div>
                                    </div>
                                </button>

                                <button type="button" wire:click="addBlock('quote', {{ $index }})"
                                    class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-white border border-gray-200 transition-colors">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <div>
                                        <div class="font-opensans font-medium text-sm">Cita</div>
                                        <div class="font-opensans text-xs text-gray-500">Destacar texto</div>
                                    </div>
                                </button>
                            </div>

                            <!-- Bloques adicionales (colapsables) -->
                            @if ($showMoreBlocks)
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                    <button type="button" wire:click="addBlock('list', {{ $index }})"
                                        class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-white border border-gray-200 transition-colors">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                        </svg>
                                        <div>
                                            <div class="font-opensans font-medium text-sm">Lista</div>
                                            <div class="font-opensans text-xs text-gray-500">Con viñetas o numerada
                                            </div>
                                        </div>
                                    </button>

                                    <button type="button" wire:click="addBlock('video', {{ $index }})"
                                        class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-white border border-gray-200 transition-colors">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        <div>
                                            <div class="font-opensans font-medium text-sm">Video</div>
                                            <div class="font-opensans text-xs text-gray-500">YouTube, Vimeo</div>
                                        </div>
                                    </button>

                                    <button type="button" wire:click="addBlock('gallery', {{ $index }})"
                                        class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-white border border-gray-200 transition-colors">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <div>
                                            <div class="font-opensans font-medium text-sm">Galería</div>
                                            <div class="font-opensans text-xs text-gray-500">Múltiples imágenes</div>
                                        </div>
                                    </button>

                                    <button type="button" wire:click="addBlock('review', {{ $index }})"
                                        class="flex items-center gap-3 p-3 sm:p-4 text-left hover:bg-white border border-gray-200 transition-colors">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <div>
                                            <div class="font-opensans font-medium text-sm">Reseña</div>
                                            <div class="font-opensans text-xs text-gray-500">Testimonios con foto</div>
                                        </div>
                                    </button>
                                </div>
                            @endif

                            <!-- Botón para mostrar más/menos opciones -->
                            <div class="text-center border-t border-gray-300 pt-4">
                                @if ($showMoreBlocks)
                                    <button type="button" wire:click="toggleMoreBlocks"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-opensans text-gray-600 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 15l7-7 7 7" />
                                        </svg>
                                        Mostrar menos opciones
                                    </button>
                                @else
                                    <button type="button" wire:click="toggleMoreBlocks"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-opensans text-gray-600 hover:text-primary transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                        Ver más opciones (3)
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

    </div>
</div>

<script>
    // Variable global para rastrear el último input de lista enfocado
    let lastFocusedListInput = null;

    function insertMarkdown(button, startTag, endTag) {
        // Encontrar el textarea más cercano al botón - buscar en múltiples contenedores
        let textarea = button.closest('.space-y-2')?.querySelector('textarea');
        if (!textarea) {
            textarea = button.closest('.space-y-4')?.querySelector('textarea');
        }
        if (!textarea) {
            // Fallback: buscar el textarea más cercano en el DOM
            textarea = button.closest('div').querySelector('textarea');
        }
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
                } else if (selectedText.startsWith('*') && selectedText.endsWith('*') && !selectedText.startsWith(
                        '**')) {
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
                } else if (selectedText.startsWith('*') && selectedText.endsWith('*') && !selectedText.startsWith(
                        '**')) {
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

    function insertMarkdownList(button, startTag, endTag) {
        // Usar el último input enfocado si está disponible
        let input = lastFocusedListInput;

        // Si no hay input enfocado o no está dentro del mismo bloque, buscar alternativas
        if (!input || !button.closest('.space-y-2').contains(input)) {
            // Buscar el primer input disponible en este bloque de lista
            const listContainer = button.closest('.space-y-2');
            input = listContainer.querySelector('input[id*="list-item"]');
        }

        if (!input) return;

        const start = input.selectionStart || 0;
        const end = input.selectionEnd || 0;
        const text = input.value;
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
                    replacement = selectedText.slice(1, -1);
                } else if (selectedText.startsWith('**') && selectedText.endsWith('**')) {
                    replacement = selectedText.slice(2, -2);
                } else if (selectedText.startsWith('*') && selectedText.endsWith('*') && !selectedText.startsWith(
                        '**')) {
                    replacement = '*' + selectedText + '*';
                } else {
                    replacement = '**' + selectedText + '**';
                }
            } else {
                // Botón cursiva
                if (selectedText.startsWith('***') && selectedText.endsWith('***')) {
                    replacement = selectedText.slice(1, -1);
                } else if (selectedText.startsWith('**') && selectedText.endsWith('**')) {
                    replacement = '*' + selectedText + '*';
                } else if (selectedText.startsWith('*') && selectedText.endsWith('*') && !selectedText.startsWith(
                        '**')) {
                    replacement = selectedText.slice(1, -1);
                } else {
                    replacement = '*' + selectedText + '*';
                }
            }

            newText = text.substring(0, start) + replacement + text.substring(end);
            newCursorPos = start + replacement.length;
        }

        input.value = newText;
        input.focus();
        input.setSelectionRange(newCursorPos, newCursorPos);

        // Actualizar la referencia del último input enfocado
        lastFocusedListInput = input;

        // Disparar eventos para Livewire
        input.dispatchEvent(new Event('input', {
            bubbles: true
        }));
        input.dispatchEvent(new Event('blur', {
            bubbles: true
        }));
    }

    function insertMarkdownQuote(button, startTag, endTag) {
        // Encontrar el campo activo (textarea de cita o input de autor)
        const quoteContainer = button.closest('.space-y-2');
        const activeElement = document.activeElement;

        // Determinar qué campo usar
        let input = null;
        if (activeElement && quoteContainer.contains(activeElement)) {
            // Si el elemento activo está dentro del bloque de cita, usarlo
            if (activeElement.tagName === 'TEXTAREA' || activeElement.tagName === 'INPUT') {
                input = activeElement;
            }
        }

        // Si no hay elemento activo válido, usar el textarea de la cita por defecto
        if (!input) {
            input = quoteContainer.querySelector('textarea[id*="quote-content"]');
        }

        if (!input) return;

        const start = input.selectionStart || 0;
        const end = input.selectionEnd || 0;
        const text = input.value;
        const selectedText = text.substring(start, end);

        let newText;
        let newCursorPos;

        if (start === end) {
            // No hay selección, insertar tags vacíos y posicionar cursor en el medio
            newText = text.substring(0, start) + startTag + endTag + text.substring(end);
            newCursorPos = start + startTag.length;
        } else {
            // Hay texto seleccionado - aplicar toggle para negrita
            let replacement;

            if (selectedText.startsWith('**') && selectedText.endsWith('**')) {
                // Tiene negrita -> quitar negrita
                replacement = selectedText.slice(2, -2);
            } else {
                // Sin negrita -> agregar negrita
                replacement = '**' + selectedText + '**';
            }

            newText = text.substring(0, start) + replacement + text.substring(end);
            newCursorPos = start + replacement.length;
        }

        input.value = newText;
        input.focus();
        input.setSelectionRange(newCursorPos, newCursorPos);

        // Disparar eventos para Livewire
        input.dispatchEvent(new Event('input', {
            bubbles: true
        }));
        input.dispatchEvent(new Event('blur', {
            bubbles: true
        }));
    }

    // Agregar listeners para rastrear el foco de los inputs de lista
    document.addEventListener('DOMContentLoaded', function() {
        // Delegar el evento focus para inputs de lista
        document.addEventListener('focus', function(e) {
            if (e.target.type === 'text' && e.target.id && e.target.id.includes('list-item')) {
                lastFocusedListInput = e.target;
            }
        }, true);
    });
</script>

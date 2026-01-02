<div class="space-y-4">
    <!-- Indicador de carga para subida de imágenes -->
    <div wire:loading.delay wire:target="blocks"
        class="mb-4 p-3 bg-blue-100 border border-blue-400 text-blue-700 rounded flex items-center gap-2 font-opensans text-sm">
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
    <div class="flex items-center justify-between">
        <h3 class="font-montserrat font-medium text-primary text-lg">Editor de Contenido</h3>
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500 font-opensans">{{ count($blocks) }} bloque(s)</span>
            <button type="button"
                class="px-3 py-1 text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors font-opensans">
                Vista previa
            </button>
        </div>
    </div>

    <!-- Área de contenido -->
    <div class="border border-gray-300 bg-white {{ empty($blocks) ? 'min-h-[400px]' : 'min-h-0' }}">

        @if (empty($blocks))
            <!-- Estado vacío -->
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="text-6xl mb-4">📝</div>
                <h4 class="font-montserrat font-medium text-primary text-lg mb-2">Comienza a escribir tu artículo</h4>
                <p class="text-gray-500 font-opensans mb-6 max-w-md">
                    Agrega bloques de contenido como texto, imágenes, videos y más para crear un artículo atractivo.
                </p>
                <div class="space-y-3">
                    <button type="button" wire:click="testClick"
                        class="px-6 py-3 bg-primary text-white font-montserrat font-medium hover:bg-dark-sage transition-colors">
                        + Agregar primer bloque
                    </button>
                    <button type="button" wire:click="openBlockSelector"
                        class="px-6 py-3 bg-gray-500 text-white font-montserrat font-medium hover:bg-gray-600 transition-colors">
                        Test alternativo
                    </button>
                </div>
            </div>
        @else
            <!-- Bloques de contenido -->
            <div class="p-6 space-y-8">
                @foreach ($blocks as $index => $block)
                    <div class="group relative border border-gray-200 hover:border-gray-300 transition-colors">

                        <!-- Toolbar del bloque -->
                        <div
                            class="absolute -top-10 left-0 opacity-0 group-hover:opacity-100 transition-opacity bg-white border border-gray-200 shadow-sm flex items-center gap-1 px-2 py-1">
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
                                        <textarea id="paragraph-{{ $index }}" rows="4"
                                            placeholder="Escribe tu párrafo aquí... Usa *cursiva* y **negrita**"
                                            class="w-full border-0 resize-none focus:outline-none font-opensans text-sm leading-relaxed p-2"
                                            wire:model.blur="blocks.{{ $index }}.content">{{ $block['content'] ?? '' }}</textarea>

                                        @if (!empty($block['content']))
                                            <div class="border-t border-gray-100 pt-2">
                                                <div class="text-xs text-gray-500 font-opensans mb-1">Vista previa:</div>
                                                <div
                                                    class="text-sm font-opensans leading-relaxed text-gray-700 bg-gray-50 p-2 border border-gray-200">
                                                    {!! preg_replace(
                                                        ['/\*\*\*(.*?)\*\*\*/', '/\*\*(.*?)\*\*/', '/\*(.*?)\*/'],
                                                        ['<strong><em>$1</em></strong>', '<strong>$1</strong>', '<em>$1</em>'],
                                                        e($block['content']),
                                                    ) !!}
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
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span class="text-xs text-gray-500 font-opensans font-medium">IMAGEN</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <select wire:model.live="blocks.{{ $index }}.layout"
                                                    class="text-xs border border-gray-300 px-2 py-1 bg-white focus:outline-none focus:border-primary">
                                                    <option value="full">Solo imagen</option>
                                                    <option value="text-right">Texto a la derecha</option>
                                                    <option value="text-left">Texto a la izquierda</option>
                                                    <option value="text-below">Texto abajo</option>
                                                </select>
                                                <select wire:model.live="blocks.{{ $index }}.size"
                                                    class="text-xs border border-gray-300 px-2 py-1 bg-white focus:outline-none focus:border-primary">
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
                                                        class="block text-sm font-medium text-gray-700 mb-1 font-opensans">Imagen
                                                        Principal</label>
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

                                @default
                                    <div class="text-center py-4 text-gray-500">
                                        <p class="font-opensans text-sm">Tipo de bloque no reconocido</p>
                                    </div>
                            @endswitch
                        </div>

                        <!-- Botón para agregar bloque debajo -->
                        <div
                            class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button" wire:click="openBlockSelector({{ $index }})"
                                class="w-8 h-8 bg-white border border-gray-300 hover:border-primary text-primary hover:bg-primary hover:text-white transition-colors flex items-center justify-center shadow-md z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    <!-- Debug info -->
    @if (session('debug'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 text-sm">
            {{ session('debug') }} - showBlockSelector: {{ $showBlockSelector ? 'true' : 'false' }}
        </div>
    @endif

    <!-- Panel de tipos de bloque (aparece al hacer clic en +) -->
    @if ($showBlockSelector)
        <div class="bg-white border-2 border-primary shadow-lg p-4 space-y-2 mt-4">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-montserrat font-medium text-primary text-sm">Selecciona un tipo de bloque</h4>
                <button type="button" wire:click="closeBlockSelector"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" wire:click="addBlock('paragraph', {{ $blockSelectorIndex ?? 'null' }})"
                    class="flex items-center gap-3 p-3 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <div class="font-opensans font-medium text-sm">Párrafo</div>
                        <div class="font-opensans text-xs text-gray-500">Texto normal</div>
                    </div>
                </button>
                <button type="button" wire:click="addBlock('heading', {{ $blockSelectorIndex ?? 'null' }})"
                    class="flex items-center gap-3 p-3 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <div class="font-opensans font-medium text-sm">Encabezado</div>
                        <div class="font-opensans text-xs text-gray-500">H2, H3, H4</div>
                    </div>
                </button>
                <button type="button" wire:click="addBlock('image', {{ $blockSelectorIndex ?? 'null' }})"
                    class="flex items-center gap-3 p-3 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <div class="font-opensans font-medium text-sm">Imagen</div>
                        <div class="font-opensans text-xs text-gray-500">Subir archivo</div>
                    </div>
                </button>
                <button type="button" wire:click="addBlock('quote', {{ $blockSelectorIndex ?? 'null' }})"
                    class="flex items-center gap-3 p-3 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div>
                        <div class="font-opensans font-medium text-sm">Cita</div>
                        <div class="font-opensans text-xs text-gray-500">Destacar texto</div>
                    </div>
                </button>
                <button type="button" wire:click="addBlock('list', {{ $blockSelectorIndex ?? 'null' }})"
                    class="flex items-center gap-3 p-3 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <div>
                        <div class="font-opensans font-medium text-sm">Lista</div>
                        <div class="font-opensans text-xs text-gray-500">Con viñetas o numerada</div>
                    </div>
                </button>
                <button type="button" wire:click="addBlock('video', {{ $blockSelectorIndex ?? 'null' }})"
                    class="flex items-center gap-3 p-3 text-left hover:bg-gray-50 border border-gray-200 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <div class="font-opensans font-medium text-sm">Video</div>
                        <div class="font-opensans text-xs text-gray-500">YouTube, Vimeo</div>
                    </div>
                </button>
            </div>
        </div>
    @endif

    <!-- Debug temporal -->
    <div class="text-xs text-gray-500 mt-2 p-2 bg-gray-100 border">
        <strong>Debug Info:</strong><br>
        showBlockSelector = {{ $showBlockSelector ? 'true' : 'false' }}<br>
        blockSelectorIndex = {{ $blockSelectorIndex ?? 'null' }}<br>
        bloques = {{ count($blocks) }}<br>
        Livewire ID = {{ $this->getId() }}<br>
        @if (session('debug'))
            <span class="text-green-600">{{ session('debug') }}</span>
        @endif
    </div>
</div>

<script>
    // Variable global para rastrear el último input de lista enfocado
    let lastFocusedListInput = null;

    function insertMarkdown(button, startTag, endTag) {
        // Encontrar el textarea más cercano al botón
        const textarea = button.closest('.space-y-2').querySelector('textarea');
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

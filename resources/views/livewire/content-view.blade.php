<div class="content-view space-y-6 {{ $isAd ? 'content-view--ad border border-gray-lighter bg-[rgba(183,182,153,0.12)] py-6 px-4 sm:px-6 transition-colors duration-200 hover:border-dark-sage/50 hover:bg-[rgba(183,182,153,0.18)] cursor-pointer' : '' }}"
    @if($isAd && $adUrl) wire:click="clickAd('{{ $adUrl }}')" @endif>
    @if($isAd)
        <div class="flex items-center gap-2 mb-4 -mt-2">
            {{-- <span class="inline-block px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider bg-gray-lighter text-gray-light">
                Publicidad
            </span> --}}
            @if($adAdvertiserName)
                <div class="flex items-center gap-1 text-[10px] sm:text-xs text-gray-500">
                    <span class="font-opensans italic">patrocinado por</span>
                    @if($adAdvertiserLogoUrl)
                        <img src="{{ $adAdvertiserLogoUrl }}" alt="Logo {{ $adAdvertiserName }}" class="h-4 sm:h-5 w-auto">
                    @else
                        <span class="font-montserrat font-semibold not-italic text-[10px] sm:text-xs text-gray-500">
                            {{ $adAdvertiserName }}
                        </span>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <style>
        .content-view p {
            margin-bottom: 24px;
        }

        /* Listas dentro de párrafos (generadas por markdown) */
        .content-view p ul,
        .content-view p ol,
        .content-view .prose ul,
        .content-view .prose ol {
            margin: 16px 0;
            padding-left: 1.5rem;
            list-style-position: outside;
            list-style-type: disc;
        }

        .content-view p ol,
        .content-view .prose ol {
            list-style-type: decimal;
        }

        .content-view p li,
        .content-view .prose li {
            margin-bottom: 8px;
            padding-left: 0.35rem;
            color: rgb(34 34 29 / 0.9);
            line-height: 1.75;
            font-family: 'Montserrat', ui-sans-serif, system-ui, sans-serif;
        }

        /* Listas como bloques independientes (Recursos, etc.) */
        .content-view > ul,
        .content-view > ol {
            margin: 24px 0;
            padding-left: 1.5rem;
            list-style-position: outside;
        }

        .content-view > ul {
            list-style-type: disc;
        }

        .content-view > ol {
            list-style-type: decimal;
        }

        .content-view > ul > li,
        .content-view > ol > li {
            margin-bottom: 8px;
            padding-left: 0.35rem;
        }

        /* Enlaces (solo dentro de .prose para no afectar el resto de la página) */
        .content-view .prose a,
        .content-view > ul a,
        .content-view > ol a {
            color: #b7b699;
            text-decoration: underline;
            text-underline-offset: 2px;
            transition: color 0.2s;
            word-break: break-word;
        }

        .content-view .prose a:hover,
        .content-view > ul a:hover,
        .content-view > ol a:hover {
            color: #22221d;
        }
    </style>

    @forelse ($blocks as $block)
        @switch($block['type'])
            @case('paragraph')
                <div class="text-primary/90 leading-relaxed font-montserrat text-sm sm:text-base lg:text-[18px] prose">
                    {!! fixStrongSpacing(Str::markdown(markdownLite($block['content']))) !!}
                </div>
            @break

            @case('heading')
                @php $level = $block['level'] ?? 2; @endphp
                @if ($level == 2)
                    <h2 class="font-serif text-2xl md:text-3xl lg:text-4xl leading-tight text-balance mt-12 mb-6">
                        {{ $block['content'] }}</h2>
                @elseif ($level == 3)
                    <h3 class="font-serif text-xl md:text-2xl lg:text-3xl leading-tight text-balance mt-12 mb-6">
                        {{ $block['content'] }}</h3>
                @elseif ($level == 4)
                    <h4 class="font-serif text-lg md:text-xl lg:text-2xl leading-tight text-balance mt-12 mb-6">
                        {{ $block['content'] }}</h4>
                @endif
            @break

            @case('list')
                @if ($block['listType'] === 'bullet')
                    <ul class="content-view">
                        @foreach ($block['items'] as $item)
                            <li class="mb-2 text-primary/90 leading-relaxed font-montserrat text-sm sm:text-base lg:text-[18px]">
                                {!! markdownLite($item) !!}
                            </li>
                        @endforeach
                    </ul>
                @elseif ($block['listType'] === 'numbered')
                    <ol class="content-view">
                        @foreach ($block['items'] as $item)
                            <li class="mb-2 text-primary/90 leading-relaxed font-montserrat text-sm sm:text-base lg:text-[18px]">
                                {!! markdownLite($item) !!}
                            </li>
                        @endforeach
                    </ol>
                @endif
            @break

            @case('quote')
                <blockquote
                    class="border-l-4 border-dark-sage bg-sage p-6 pr-1.5 pl-4 md:pl-6 italic text-primary/90 leading-relaxed font-montserrat text-base lg:text-[18px]">
                    {!! markdownLite($block['content']) !!}

                    @if (!empty($block['author']))
                        <footer class="mt-2 font-montserrat text-sm text-gray-light">&mdash; {{ $block['author'] }}
                        </footer>
                    @endif
                </blockquote>
            @break

            @case('review')
                @foreach ($block['reviews'] as $review)
                    <div class="flex items-start gap-4 bg-gray-50 border border-gray-200 p-6 mb-6">
                        @if (!empty($review['photo']))
                            <img src="{{ asset($review['photo']) }}" alt="Foto de {{ $review['name'] }}"
                                class="w-16 h-16 object-cover bg-gray-200">
                        @else
                            <div class="w-16 h-16 bg-gray-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="font-bold text-lg font-montserrat text-gray-800">{{ $review['name'] }}</div>
                            @if (!empty($review['title']))
                                <div class="text-sm text-gray-500 mb-1 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 12.414a2 2 0 00-2.828 0l-4.243 4.243M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $review['title'] }}
                                </div>
                            @endif
                            <div class="italic text-gray-700 text-base font-montserrat mb-2">{!! $review['content'] !!}</div>
                            @if (!empty($review['rating']))
                                <div class="flex items-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 {{ $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' }}"
                                            viewBox="0 0 24 24" fill="currentColor">
                                            <polygon
                                                points="12,2 15,9 22,9.3 17,14.1 18.5,21 12,17.5 5.5,21 7,14.1 2,9.3 9,9" />
                                        </svg>
                                    @endfor
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @break

            @case('gallery')
                <div class="my-12">
                    @php
                        $carouselId = 'carousel-' . uniqid();
                    @endphp

                    <!-- Main Image -->
                    <div class="relative aspect-video">
                        @foreach ($block['images'] as $index => $image)
                            <img class="carousel-image w-full h-full object-contain absolute inset-0 transition-opacity duration-300 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                src="{{ asset($image['url'] ?? $image) }}"
                                alt="{{ $image['alt_text'] ?? 'Imagen ' . ($index + 1) }}" data-carousel="{{ $carouselId }}"
                                data-index="{{ $index }}" data-credit="{{ $image['credits'] ?? '' }}">
                        @endforeach

                        <!-- Navigation Arrows -->
                        <button onclick="carouselPrev('{{ $carouselId }}')"
                            class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 hover:bg-white flex items-center justify-center transition-colors"
                            aria-label="Anterior">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button onclick="carouselNext('{{ $carouselId }}')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 hover:bg-white flex items-center justify-center transition-colors"
                            aria-label="Siguiente">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Counter -->
                        <div class="absolute bottom-4 right-4 bg-black/75 text-white px-3 py-1 text-sm font-sans">
                            <span id="{{ $carouselId }}-counter">1</span> / {{ count($block['images']) }}
                        </div>
                    </div>

                    <!-- Thumbnails (hidden on mobile) -->
                    <div class="hidden md:flex gap-2 mt-4 overflow-x-auto pb-2">
                        @foreach ($block['images'] as $index => $image)
                            <button onclick="carouselGoTo('{{ $carouselId }}', {{ $index }})"
                                class="carousel-thumb shrink-0 w-20 h-20 border-2 transition-all {{ $index === 0 ? 'border-dark-sage' : 'border-transparent' }}"
                                data-carousel="{{ $carouselId }}" data-index="{{ $index }}">
                                <img src="{{ asset($image['url'] ?? $image) }}"
                                    alt="{{ $image['alt_text'] ?? 'Miniatura ' . ($index + 1) }}"
                                    class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>

                    <!-- Credit -->
                    @if (isset($block['images'][0]['credits']) && !empty($block['images'][0]['credits']))
                        <p class="text-xs text-muted-foreground mt-2 font-sans italic" id="{{ $carouselId }}-credit">
                            Crédito: {{ $block['images'][0]['credits'] }}
                        </p>
                    @endif
                </div>

                @once
                    @push('scripts')
                        <script>
                            function carouselPrev(carouselId) {
                                const images = document.querySelectorAll(`[data-carousel="${carouselId}"].carousel-image`);
                                const currentIndex = Array.from(images).findIndex(img => img.classList.contains('opacity-100'));
                                const newIndex = currentIndex === 0 ? images.length - 1 : currentIndex - 1;
                                updateCarousel(carouselId, newIndex);
                            }

                            function carouselNext(carouselId) {
                                const images = document.querySelectorAll(`[data-carousel="${carouselId}"].carousel-image`);
                                const currentIndex = Array.from(images).findIndex(img => img.classList.contains('opacity-100'));
                                const newIndex = currentIndex === images.length - 1 ? 0 : currentIndex + 1;
                                updateCarousel(carouselId, newIndex);
                            }

                            function carouselGoTo(carouselId, index) {
                                updateCarousel(carouselId, index);
                            }

                            function updateCarousel(carouselId, newIndex) {
                                // Update images
                                const images = document.querySelectorAll(`[data-carousel="${carouselId}"].carousel-image`);
                                images.forEach((img, i) => {
                                    img.classList.toggle('opacity-100', i === newIndex);
                                    img.classList.toggle('opacity-0', i !== newIndex);
                                });

                                // Update thumbnails - only active has border
                                const thumbs = document.querySelectorAll(`[data-carousel="${carouselId}"].carousel-thumb`);
                                thumbs.forEach((thumb, i) => {
                                    if (i === newIndex) {
                                        thumb.classList.remove('border-transparent');
                                        thumb.classList.add('border-dark-sage');
                                    } else {
                                        thumb.classList.remove('border-dark-sage');
                                        thumb.classList.add('border-transparent');
                                    }
                                });

                                // Update counter
                                const counter = document.getElementById(`${carouselId}-counter`);
                                if (counter) counter.textContent = newIndex + 1;

                                // Update credit
                                const credit = document.getElementById(`${carouselId}-credit`);
                                if (credit) {
                                    const currentImage = images[newIndex];
                                    const creditText = currentImage.dataset.credit;
                                    if (creditText && creditText.trim() !== '') {
                                        credit.textContent = `Crédito: ${creditText}`;
                                        credit.style.display = 'block';
                                    } else {
                                        credit.style.display = 'none';
                                    }
                                }
                            }
                        </script>
                    @endpush
                @endonce
            @break

            @case('image')
                @php
                    $imageUrl = $block['url'] ?? '';
                    $layout = $block['layout'] ?? 'full';
                    $size = $block['size'] ?? 'large';
                    
                    // Procesar caption con markdown
                    $captionRaw = $block['caption'] ?? '';
                    if (!empty($captionRaw)) {
                        $captionFormatted = fixStrongSpacing(Str::markdown(markdownLite($captionRaw)));
                    } else {
                        $captionFormatted = '';
                    }
                    
                    // Créditos como texto plano (sin markdown)
                    $creditsRaw = $block['credits'] ?? '';
                    $creditsFormatted = !empty($creditsRaw) ? $creditsRaw : '';
                @endphp

                @if ($imageUrl)
                    @if ($layout === 'full')
                        <!-- Solo imagen -->
                        <div class="mb-6 text-center space-y-2">
                            <img src="{{ asset($imageUrl) }}" alt="{{ $block['alt_text'] ?? '' }}"
                                class="@if ($size === 'small') max-w-xs @elseif($size === 'medium') max-w-md @else max-w-full @endif mx-auto h-auto max-h-96 object-contain">
                            @if (!empty($creditsFormatted))
                                <div class="text-[10px] md:text-xs text-gray-500 font-opensans text-center">
                                    {{ $creditsFormatted }}
                                </div>
                            @endif
                        </div>
                    @elseif($layout === 'text-right')
                        @if (!empty($captionFormatted))
                            <!-- Imagen izquierda, texto derecha -->
                            <div class="flex flex-col md:flex-row gap-4 md:items-stretch">
                                <div class="@if ($size === 'small') md:flex-[0_0_25%] @elseif($size === 'medium') md:flex-[0_0_35%] @else md:flex-[0_0_45%] @endif flex flex-col space-y-1 md:space-y-2 min-w-0">
                                    <div class="flex-1 flex items-center justify-center md:h-auto">
                                        <img src="{{ asset($imageUrl) }}"
                                            alt="{{ $block['alt_text'] ?? '' }}"
                                            class="w-full h-auto md:h-full object-contain @if ($size === 'small') max-w-xs @elseif($size === 'medium') max-w-md @else max-w-full @endif mx-auto md:mx-0">
                                    </div>
                                    @if (!empty($creditsFormatted))
                                        <p class="text-[10px] md:text-xs text-gray-500 font-opensans text-center !mb-0 mt-0">
                                            {{ $creditsFormatted }}
                                        </p>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 text-primary/90 leading-relaxed font-montserrat text-base lg:text-[18px] prose -mb-6">
                                    {!! $captionFormatted !!}
                                </div>
                            </div>
                        @else
                            <!-- Sin caption: mostrar como full -->
                            <div class="text-center space-y-1 md:space-y-2">
                                <img src="{{ asset($imageUrl) }}" alt="{{ $block['alt_text'] ?? '' }}"
                                    class="@if ($size === 'small') max-w-xs @elseif($size === 'medium') max-w-md @else max-w-full @endif mx-auto h-auto max-h-96 object-contain">
                                @if (!empty($creditsFormatted))
                                    <p class="text-[10px] md:text-xs text-gray-500 font-opensans text-center !mb-0 mt-0">
                                        {{ $creditsFormatted }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    @elseif($layout === 'text-left')
                        @if (!empty($captionFormatted))
                            <!-- Texto izquierda, imagen derecha -->
                            <div class="flex flex-col md:flex-row gap-4 md:items-stretch">
                                <div class="flex-1 min-w-0 text-primary/90 leading-relaxed font-montserrat text-base lg:text-[18px] prose -mb-6">
                                    {!! $captionFormatted !!}
                                </div>
                                <div class="@if ($size === 'small') md:flex-[0_0_25%] @elseif($size === 'medium') md:flex-[0_0_35%] @else md:flex-[0_0_45%] @endif flex flex-col space-y-1 md:space-y-2 min-w-0">
                                    <div class="flex-1 flex items-center justify-center md:h-auto">
                                        <img src="{{ asset($imageUrl) }}"
                                            alt="{{ $block['alt_text'] ?? '' }}"
                                            class="w-full h-auto md:h-full object-contain @if ($size === 'small') max-w-xs @elseif($size === 'medium') max-w-md @else max-w-full @endif mx-auto md:mx-0">
                                    </div>
                                    @if (!empty($creditsFormatted))
                                        <p class="text-[10px] md:text-xs text-gray-500 font-opensans text-center !mb-0 mt-0">
                                            {{ $creditsFormatted }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Sin caption: mostrar como full -->
                            <div class=" text-center space-y-1 md:space-y-2">
                                <img src="{{ asset($imageUrl) }}" alt="{{ $block['alt_text'] ?? '' }}"
                                    class="@if ($size === 'small') max-w-xs @elseif($size === 'medium') max-w-md @else max-w-full @endif mx-auto h-auto max-h-96 object-contain">
                                @if (!empty($creditsFormatted))
                                    <p class="text-[10px] md:text-xs text-gray-500 font-opensans text-center !mb-0 mt-0">
                                        {{ $creditsFormatted }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    @elseif($layout === 'text-below')
                        <!-- Imagen arriba, texto abajo -->
                        <div class="mb-6 space-y-3">
                            <div class="text-center space-y-2">
                                <img src="{{ asset($imageUrl) }}"
                                    alt="{{ $block['alt_text'] ?? '' }}"
                                    class="@if ($size === 'small') max-w-xs @elseif($size === 'medium') max-w-md @else max-w-full @endif mx-auto h-auto max-h-96 object-contain">
                                @if (!empty($creditsFormatted))
                                    <p class="text-[10px] md:text-xs text-gray-500 font-opensans text-center !mb-0 mt-0">
                                        {{ $creditsFormatted }}
                                    </p>
                                @endif
                            </div>
                            @if (!empty($captionFormatted))
                                <div class="text-primary/90 leading-relaxed font-montserrat text-base lg:text-[18px] prose">
                                    {!! $captionFormatted !!}
                                </div>
                            @endif
                        </div>
                    @endif
                @endif
            @break

            @case('ad')
                @php
                    $adBlock = \App\Models\Ad::find($block['ad_id'] ?? 0);
                @endphp
                @if ($adBlock && $adBlock->status === 'published')
                    <livewire:content-view :is-ad="true" :ad-id="$adBlock->id" />
                @endif
            @break

            @default
        @endswitch

        {{-- <br>
        {{ json_encode($block) }}
        <br> --}}
        @empty
            <p>No hay contenido disponible.</p>
        @endforelse

    </div>

    <script>
        (function() {
            function applyExternalLinks() {
                document.querySelectorAll('.content-view a[href^="http"]').forEach(function(a) {
                    if (a.hostname !== window.location.hostname) {
                        a.setAttribute('target', '_blank');
                        a.setAttribute('rel', 'noopener noreferrer');
                    }
                });
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', applyExternalLinks);
            } else {
                applyExternalLinks();
            }
            document.addEventListener('livewire:navigated', applyExternalLinks);

            document.addEventListener('livewire:init', function() {
                Livewire.on('open-ad-new-tab', function(event) {
                    if (event.url) {
                        window.open(event.url, '_blank', 'noopener');
                    }
                });
            });
        })();
    </script>

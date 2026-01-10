<div class="content-view space-y-6">

    <style>
        .content-view p {
            margin-bottom: 24px;
        }
        .content-view ul {
            list-style-type: disc;
            list-style-position: inside;
        }
    </style>

    @php
        function markdownLite($text)
        {
            // Negrita (soporta espacios antes y después)
            $text = preg_replace('/\*\*\s*(.*?)\s*\*\*/', '<strong>$1</strong>', $text);
            // Itálica (soporta espacios antes y después)
            $text = preg_replace('/\*\s*(.*?)\s*\*/', '<em>$1</em>', $text);
            return $text;
        }
    @endphp

    @forelse ($blocks as $block)
        @switch($block['type'])
            @case('paragraph')
                <div class="text-primary/90 leading-relaxed font-montserrat text-base lg:text-[18px] prose">
                    {!! Str::markdown(markdownLite($block['content'])) !!}
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
                    <ul>
                        @foreach ($block['items'] as $item)
                            <li class="mb-2 text-primary/90 leading-relaxed font-montserrat text-base lg:text-[18px]">
                                {!! markdownLite($item) !!}
                            </li>
                        @endforeach
                    </ul>
                @elseif ($block['listType'] === 'numbered')
                    <ol class="list-decimal list-inside">
                        @foreach ($block['items'] as $item)
                            <li class="mb-2 text-primary/90 leading-relaxed font-montserrat text-base lg:text-[18px]">
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
                    <div class="relative aspect-video bg-muted">
                        @foreach ($block['images'] as $index => $image)
                            <img class="carousel-image w-full h-full object-cover absolute inset-0 transition-opacity duration-300 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
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
                <div class="relative aspect-video bg-muted mb-6">
                    <img class="w-full h-full object-cover" src="{{ asset($block['url']) }}" alt="">
                </div>
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

<div class=" space-y-6">

    <style>
        p {
            margin-bottom: 24px;
        }

        ul {
            list-style-type: disc;
            list-style-position: inside;
        }
    </style>

    @php
        function markdownLite($text)
        {
            // Negrita
            $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
            // Itálica
            $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
            return $text;
        }
    @endphp

    @forelse ($blocks as $block)
        @switch($block['type'])
            @case('paragraph')
                <div class="text-primary/90 leading-relaxed font-montserrat text-base lg:text-[18px] prose">
                    {!! Str::markdown($block['content']) !!}
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

            {{-- @case('image')
                <div class="relative aspect-video bg-muted mb-6">
                    <img class="w-full h-full object-cover" src="{{ asset($block['url']) }}" alt="">
                </div>
            @break --}}

            @default
        @endswitch

        {{-- <br>
        {{ json_encode($block) }}
        <br> --}}
        @empty
            <p>No hay contenido disponible.</p>
        @endforelse

    </div>

<div class="max-w-[1200px] mx-auto pt-12 pb-0 px-4">
    @if($hasActiveCover && $articles->isNotEmpty())
        {{-- Dynamic content from active cover --}}
        <div class="h-fit flex flex-col md:grid md:grid-cols-2 gap-8">
            {{-- Main article --}}
            @if(isset($articles[0]))
                @php $article = $articles[0]; @endphp
                <a href="{{ route('article.show', $article->slug) }}" class="group cursor-pointer h-fit">
                    <div class="w-full mb-4 overflow-hidden">
                        @if($article->image_path)
                            <img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt_text ?? $article->title }}"
                                class="w-full h-auto max-h-[456px] md:max-h-[396px] md:h-full object-cover group-hover:scale-105 transition-all duration-200">
                        @else
                            <div class="w-full h-64 bg-gray-lighter flex items-center justify-center">
                                <span class="text-gray-400 text-sm font-opensans">Sin imagen</span>
                            </div>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">{{ $article->section_name }}</p>
                        <h2 class="text-2xl sm:text-4xl font-serif text-balance group-hover:text-dark-sage transition-all duration-200">{{ $article->title }}</h2>
                        <p class="text-gray-light text-[10px] sm:text-sm font-opensans">Por {{ $article->user->name ?? 'Autor' }}</p>
                    </div>
                </a>
            @endif

            {{-- 3 Secondary articles --}}
            <div class="space-y-8 h-fit">
                @for($i = 1; $i < 4; $i++)
                    @if(isset($articles[$i]))
                        @php $article = $articles[$i]; @endphp
                        <a href="{{ route('article.show', $article->slug) }}" class="flex gap-x-4 group cursor-pointer">
                            <div class="w-32 h-32 overflow-hidden shrink-0">
                                @if($article->image_path)
                                    <img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt_text ?? $article->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-all duration-200 aspect-square">
                                @else
                                    <div class="w-full h-full bg-gray-lighter flex items-center justify-center">
                                        <span class="text-gray-400 text-xs font-opensans">Sin img</span>
                                    </div>
                                @endif
                            </div>
                            <div class="space-y-2">
                                <p class="text-[10px] sm:text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">{{ $article->section_name }}</p>
                                <h3 class="text-[18px] sm:text-xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200 line-clamp-2">{{ $article->title }}</h3>
                                <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por {{ $article->user->name ?? 'Autor' }}</p>
                            </div>
                        </a>
                    @endif
                @endfor
            </div>
        </div>
    @else
        {{-- Static demo content when no active cover --}}
        <div class="h-fit flex flex-col md:grid md:grid-cols-2 gap-8">
            {{-- Main article --}}
            <a href="#" class="group cursor-pointer h-fit">
                <div class="w-full mb-4 overflow-hidden">
                    <img src="{{ asset('build/assets/C11.jpeg') }}" alt=""
                        class="w-full h-auto max-h-[456px] md:max-h-[396px] md:h-full object-cover group-hover:scale-105 transition-all duration-200">
                </div>
                <div class="space-y-3">
                    <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Destinos</p>
                    <h2 class="text-2xl sm:text-4xl font-serif text-balance group-hover:text-dark-sage transition-all duration-200">
                        Fortaleza de San Fernando de Omoa: Guardiana del Caribe Hondureño</h2>
                    <p class="text-gray-light text-[10px] sm:text-sm font-opensans">Por Ana Martínez · 12 min de lectura</p>
                </div>
            </a>

            {{-- 3 Secondary articles --}}
            <div class="space-y-8 h-fit">
                <a href="#" class="flex gap-x-4 group cursor-pointer">
                    <div class="w-32 h-32 overflow-hidden shrink-0">
                        <img src="{{ asset('build/assets/costabrava.webp') }}" alt=""
                            class="w-full h-full object-cover group-hover:scale-105 transition-all duration-200 aspect-square">
                    </div>
                    <div class="space-y-2">
                        <p class="text-[10px] sm:text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Destinos</p>
                        <h3 class="text-[18px] sm:text-xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">Costa Brava: El Encanto Mediterráneo de Girona</h3>
                        <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por María Fernández</p>
                    </div>
                </a>

                <a href="#" class="flex gap-x-4 group cursor-pointer">
                    <div class="w-32 h-32 overflow-hidden shrink-0">
                        <img src="{{ asset('build/assets/newyork.webp') }}" alt=""
                            class="w-full h-full object-cover group-hover:scale-105 transition-all duration-200 aspect-square">
                    </div>
                    <div class="space-y-2">
                        <p class="text-[10px] sm:text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Gastronomía con Identidad</p>
                        <h3 class="text-[18px] sm:text-xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">Nueva York: Donde el Mundo se Encuentra en un Plato</h3>
                        <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por Diego Rodríguez</p>
                    </div>
                </a>

                <a href="#" class="flex gap-x-4 group cursor-pointer">
                    <div class="w-32 h-32 overflow-hidden shrink-0">
                        <img src="{{ asset('build/assets/yoga.webp') }}" alt=""
                            class="w-full h-full object-cover group-hover:scale-105 transition-all duration-200 aspect-square">
                    </div>
                    <div class="space-y-2">
                        <p class="text-[10px] sm:text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Salud y Bienestar</p>
                        <h3 class="text-[18px] sm:text-xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">Encontrando el Equilibrio en Tiempos Acelerados</h3>
                        <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por Carmen Silva</p>
                    </div>
                </a>
            </div>
        </div>
    @endif
</div>

<div class="max-w-[1200px] mx-auto my-16 pt-0 px-4">
    @if($hasActiveCover && $articles->isNotEmpty())
        {{-- Dynamic content from active cover --}}
        <div class="flex flex-col sm:grid sm:grid-cols-3 gap-8">
            @foreach($articles as $article)
                <a href="{{ route('article.show', $article->slug) }}" class="group cursor-pointer">
                    <div class="sm:max-w-[368px] sm:max-h-[368px] overflow-hidden mb-4 mx-auto">
                        @if($article->image_path)
                            <img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt_text ?? $article->title }}"
                                class="aspect-square object-cover sm:max-w-[368px] max-h-[456px] sm:max-h-[368px] w-full h-full group-hover:scale-105 transition-all duration-200">
                        @else
                            <div class="aspect-square bg-gray-lighter flex items-center justify-center">
                                <span class="text-gray-400 text-sm font-opensans">Sin imagen</span>
                            </div>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">{{ $article->section_name }}</p>
                        <h2 class="text-xl font-serif text-primary text-balance group-hover:text-dark-sage transition-all duration-200 line-clamp-2">{{ $article->title }}</h2>
                        <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por {{ $article->user->name ?? 'Autor' }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        {{-- Static demo content when no active cover --}}
        <div class="flex flex-col sm:grid sm:grid-cols-3 gap-8">
            <a href="#" class="group cursor-pointer">
                <div class="sm:max-w-[368px] sm:max-h-[368px] overflow-hidden mb-4">
                    <img src="{{ asset('build/assets/gironamedieval.webp') }}" alt=""
                        class="aspect-square object-cover sm:max-w-[368px] max-h-[456px] sm:max-h-[368px] w-full h-full group-hover:scale-105 transition-all duration-200">
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Cultura Viva</p>
                    <h2 class="text-xl font-serif text-primary text-balance group-hover:text-dark-sage transition-all duration-200">
                        El Call de Girona: Un Viaje Medieval en el Tiempo</h2>
                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por María Elena Vázquez</p>
                </div>
            </a>

            <a href="#" class="group cursor-pointer">
                <div class="sm:max-w-[368px] sm:max-h-[368px] overflow-hidden mb-4">
                    <img src="{{ asset('build/assets/gastronomiahonduras.webp') }}" alt=""
                        class="aspect-square object-cover sm:max-w-[368px] max-h-[456px] sm:max-h-[368px] w-full h-full group-hover:scale-105 transition-all duration-200">
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Gastronomía con Identidad</p>
                    <h2 class="text-xl font-serif text-primary text-balance group-hover:text-dark-sage transition-all duration-200">
                        Sabores de Honduras: Tradición en Cada Bocado</h2>
                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por Diego Hernández</p>
                </div>
            </a>

            <a href="#" class="group cursor-pointer">
                <div class="sm:max-w-[368px] sm:max-h-[368px] overflow-hidden mb-4">
                    <img src="{{ asset('build/assets/apple.webp') }}" alt=""
                        class="aspect-square object-cover sm:max-w-[368px] max-h-[456px] sm:max-h-[368px] w-full h-full group-hover:scale-105 transition-all duration-200">
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Historias que Inspiran</p>
                    <h2 class="text-xl font-serif text-primary text-balance group-hover:text-dark-sage transition-all duration-200">
                        Voces que Transforman Comunidades</h2>
                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por Sofía Delgado</p>
                </div>
            </a>
        </div>
    @endif
</div>

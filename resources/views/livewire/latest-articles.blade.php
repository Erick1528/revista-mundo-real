<div class="max-w-[1200px] mx-auto mb-16 pt-0 px-4">
    <h2 class="text-2xl sm:text-3xl font-serif text-primary pb-4 border-b border-gray-lighter mb-8">Últimas Publicaciones</h2>

    @if($hasActiveCover && $articles->isNotEmpty())
        {{-- Dynamic content from active cover --}}
        <div class="flex flex-col sm:grid sm:grid-cols-2 sm:grid-rows-2 gap-x-8 gap-y-12">
            @foreach($articles as $article)
                <a href="{{ route('article.show', $article->slug) }}" class="group cursor-pointer h-fit">
                    <div class="max-h-80 h-full w-full overflow-hidden mb-4">
                        @if($article->image_path)
                            <img src="{{ asset($article->image_path) }}" alt="{{ $article->image_alt_text ?? $article->title }}"
                                class="group-hover:scale-105 transition-all duration-200 aspect-video max-h-80 h-full w-full object-cover">
                        @else
                            <div class="aspect-video bg-gray-lighter flex items-center justify-center">
                                <span class="text-gray-400 text-sm font-opensans">Sin imagen</span>
                            </div>
                        @endif
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">{{ $article->section_name }}</p>
                        <h3 class="text-xl sm:text-2xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200 line-clamp-2">{{ $article->title }}</h3>
                        <p class="text-gray-light text-[10px] sm:text-xs font-opensans">
                            @if($article->published_at)
                                {{ $article->published_at->locale('es')->diffForHumans() }}
                            @else
                                Por {{ $article->user->name ?? 'Autor' }}
                            @endif
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        {{-- Static demo content when no active cover --}}
        <div class="flex flex-col sm:grid sm:grid-cols-2 sm:grid-rows-2 gap-x-8 gap-y-12">
            <div class="group cursor-pointer h-fit">
                <div class="max-h-80 h-full w-full overflow-hidden mb-4">
                    <img src="{{ asset('build/assets/festivales.webp') }}" alt=""
                        class="group-hover:scale-105 transition-all duration-200 aspect-video max-h-80 h-full w-full object-cover">
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Eventos Sociales</p>
                    <h3 class="text-xl sm:text-2xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">
                        Festivales que Celebran la Identidad Cultural</h3>
                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Hace 4 días</p>
                </div>
            </div>

            <div class="group cursor-pointer h-fit">
                <div class="max-h-80 h-full w-full overflow-hidden mb-4">
                    <img src="{{ asset('build/assets/sanfrancisco.webp') }}" alt=""
                        class="group-hover:scale-105 transition-all duration-200 aspect-video max-h-80 h-full w-full object-cover">
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Destinos</p>
                    <h3 class="text-xl sm:text-2xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">
                        San Francisco: La Ciudad de las Mil Colinas y Culturas</h3>
                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Hace 6 días</p>
                </div>
            </div>

            <div class="group cursor-pointer h-fit">
                <div class="max-h-80 h-full w-full overflow-hidden mb-4">
                    <img src="{{ asset('build/assets/verduras.webp') }}" alt=""
                        class="group-hover:scale-105 transition-all duration-200 aspect-video max-h-80 h-full w-full object-cover">
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Salud y Bienestar</p>
                    <h3 class="text-xl sm:text-2xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">
                        Nutrición Consciente: Alimentar Cuerpo y Mente</h3>
                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Hace 2 días</p>
                </div>
            </div>

            <div class="group cursor-pointer h-fit">
                <div class="max-h-80 h-full w-full overflow-hidden mb-4">
                    <img src="{{ asset('build/assets/maya.webp') }}" alt=""
                        class="group-hover:scale-105 transition-all duration-200 aspect-video max-h-80 h-full w-full object-cover">
                </div>
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Cultura Viva</p>
                    <h3 class="text-xl sm:text-2xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">
                        Artesanías que Cuentan Historias Ancestrales</h3>
                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Hace 1 semana</p>
                </div>
            </div>
        </div>
    @endif
</div>

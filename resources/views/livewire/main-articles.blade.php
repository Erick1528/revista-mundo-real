<div class=" max-w-[1200px] mx-auto pt-12 pb-0 px-4">

    {{-- sm:grid-cols-1 sm:grid-rows-2 md:grid-rows-1 -> esto era inecesario --}}
    <div class=" h-fit flex flex-col md:grid  md:grid-cols-2 gap-8">

        {{-- Artículo principal --}}
        <a href="#" class=" group cursor-pointer h-fit">
            <div class="w-full mb-4 overflow-hidden">
                <img src="{{ asset('build/assets/C11.jpeg') }}" alt=""
                    class="w-full h-auto max-h-[456px] md:max-h-[396px] md:h-full object-cover group-hover:scale-105 transition-all duration-200">
            </div>

            <div class=" space-y-3">
                {{-- Tipo de sección --}}
                <p class="text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Destinos</p>

                {{-- Titulo y subtitulo --}}
                <h2 class=" text-2xl sm:text-4xl font-serif text-balance group-hover:text-dark-sage transition-all duration-200">
                    Fortaleza de San Fernando de Omoa: Guardiana del Caribe
                    Hondureño</h2>

                <p class=" text-gray-light text-[10px] sm:text-sm font-opensans">Por Ana Martínez · 12 min de lectura</p>
            </div>
        </a>

        {{-- 3 Artículos --}}
        <div class=" space-y-8 h-fit">

            <a href="#" class=" flex gap-x-4 group cursor-pointer">

                <div class="w-32 h-32 overflow-hidden shrink-0">
                    <img src="{{ asset('build/assets/costabrava.webp') }}" alt=""
                        class="w-full h-full object-cover group-hover:scale-105 transition-all duration-200 aspect-square">
                </div>

                <div class=" space-y-2">
                    <p class=" text-[10px] sm:text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Destinos
                    </p>

                    <h3 class=" text-[18px] sm:text-xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">Costa Brava: El Encanto Mediterráneo de Girona</h3>

                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por María Fernández</p>
                </div>

            </a>

            <a href="#" class=" flex gap-x-4 group cursor-pointer">

                <div class="w-32 h-32 overflow-hidden shrink-0">
                    <img src="{{ asset('build/assets/newyork.webp') }}" alt=""
                        class="w-full h-full object-cover group-hover:scale-105 transition-all duration-200 aspect-square">
                </div>

                <div class=" space-y-2">
                    <p class=" text-[10px] sm:text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Gastronomía con Identidad</p>

                    <h3 class=" text-[18px] sm:text-xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">Nueva York: Donde el Mundo se Encuentra en un Plato</h3>

                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por Diego Rodríguez</p>
                </div>

            </a>

            <a href="#" class=" flex gap-x-4 group cursor-pointer">

                <div class="w-32 h-32 overflow-hidden shrink-0">
                    <img src="{{ asset('build/assets/yoga.webp') }}" alt=""
                        class="w-full h-full object-cover group-hover:scale-105 transition-all duration-200 aspect-square">
                </div>

                <div class=" space-y-2">
                    <p class=" text-[10px] sm:text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">Salud y Bienestar</p>

                    <h3 class=" text-[18px] sm:text-xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">Encontrando el Equilibrio en Tiempos Acelerados</h3>

                    <p class="text-gray-light text-[10px] sm:text-xs font-opensans">Por Carmen Silva</p>
                </div>

            </a>

        </div>

    </div>

</div>

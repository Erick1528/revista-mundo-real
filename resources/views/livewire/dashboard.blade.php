<div class=" px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">

    <h2 class=" mb-6 font-serif text-3xl text-primary">Acciones Rápidas</h2>

    <!-- Alertas de Success -->
    @if (session('message'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 mb-6">
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

    <!-- Alertas de Error -->
    @if (session('error'))
        <div class="w-full p-4 bg-red-50 border border-red-200 text-red-800 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-opensans text-sm">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="text-red-600 hover:text-red-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class=" flex flex-col md:grid md:grid-cols-3 gap-6">

        <a href="{{ route('articles.create') }}"
            class=" border border-dark-sage p-8 text-dark-sage md:max-w-[304px] w-full hover:bg-dark-sage/9 transition-all duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" height="32px" width="32px" viewBox="0 0 24 24" stroke="#b7b699"
                stroke-width="2" fill="none">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <h4 class=" mt-4 mb-2 font-serif text-xl text-primary">Nuevo Artículo</h4>
            <p class=" text-gray-light text-sm">Crear una nueva publicación para la revista</p>
        </a>

        <div
            class=" border border-dark-sage p-8 text-dark-sage md:max-w-[304px] w-full hover:bg-dark-sage/9 transition-all duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" height="32px" width="32px" viewBox="0 0 24 24" stroke="#b7b699"
                stroke-width="2" fill="none">
                <path d="m18 2 3 3-11 11h-3v-3z"></path>
                <path d="m21.5 6.5-3.5-3.5L7 14v3h3l11.5-10.5z"></path>
            </svg>
            <h4 class=" mt-4 mb-2 font-serif text-xl text-primary">Editar Borradores</h4>
            <p class=" text-gray-light text-sm">Continuar trabajando en artículos guardados</p>
        </div>

        <div
            class=" border border-dark-sage p-8 text-dark-sage md:max-w-[304px] w-full hover:bg-dark-sage/9 transition-all duration-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" height="32px" width="32px" viewBox="0 0 24 24" stroke="#b7b699"
                stroke-width="2" fill="none">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <h4 class=" mt-4 mb-2 font-serif text-xl text-primary">Ver Estadísticas</h4>
            <p class=" text-gray-light text-sm">Analizar el rendimiento de las publicaciones</p>
        </div>

    </div>

    {{-- Agregar lista de artículos y sus filtros --}}

</div>

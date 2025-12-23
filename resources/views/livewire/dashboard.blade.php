<div class=" px-10 lg:px-[120px] py-12 max-w-[1200px] mx-auto w-full">

    <h2 class=" mb-6 font-serif text-3xl text-primary">Acciones Rápidas</h2>

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

</div>

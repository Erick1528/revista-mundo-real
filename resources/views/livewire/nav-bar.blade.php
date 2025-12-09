<nav class=" flex justify-between items-center max-w-[1200px] mx-auto p-4 h-[68px]">
    
    <button class=" h-9 w-9 flex justify-center items-center outline-none">
        {{-- Botón de menú --}}
        <x-menu-svg 
            :height="'36px'"
            :width="'36px'"
        />
    </button>

    <ul class=" text-[14px] font-montserrat font-medium flex gap-8 text-primary">
        <li class=" h-5">
            <a class=" h-5 hover:text-dark-sage" href="#">Destinos</a>
        </li>

        <li class=" h-5">
            <a class=" h-5 hover:text-dark-sage" href="#">Historias que inspiran</a>
        </li>

        <li class=" h-5">
            <a class=" h-5 hover:text-dark-sage" href="#">Eventos Sociales</a>
        </li>

        <li class=" h-5">
            <a class=" h-5 hover:text-dark-sage" href="#">Salud y Bienestar</a>
        </li>

        <li class=" h-5">
            <a class=" h-5 hover:text-dark-sage" href="#">Gastronomía</a>
        </li>

        <li class=" h-5">
            <a class=" h-5 hover:text-dark-sage" href="#">Cultura Viva</a>
        </li>
    </ul>

    <button class=" h-9 w-9 flex justify-center items-center outline-none">
        {{-- Botón de búsqueda --}}
        <x-search-svg />
    </button>

</nav>

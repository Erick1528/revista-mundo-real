<div
    class=" py-8 @if (request()->is('dashboard')) max-w-[1200px] mx-auto px-5 sm:px-10 md:px-[120px] relative after:absolute after:bottom-0 after:left-0 after:right-0 after:w-screen after:h-px after:bg-gray-lighter after:ml-[calc(-50vw+50%)] @else border-b border-gray-lighter @endif ">
    @if (request()->is('dashboard'))
        <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Panel de
            Administración</h1>
        <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">
            Bienvenido al panel editorial de Revista Mundo Real</p>
    @else
        <h1
            class=" text-6xl sm:text-7xl font-serif text-center text-primary px-6 sm:px-10 lg:px-4 text-balance font-normal">
            Revista Mundo Real</h1>
        <p
            class=" text-[12px] sm:text-sm font-montserrat text-center mt-3 text-gray-light uppercase px-4 sm:px-6 md:p-0">
            Conectando Culturas · Inspirando Viajes · Celebrando Tradiciones</p>
    @endif

</div>

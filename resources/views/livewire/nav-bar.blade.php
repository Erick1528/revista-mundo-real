<nav class=" lg:px-8 lg:py-0 px-10 border-b border-gray-lighter">

    <div class="flex justify-between items-center max-w-[1200px] mx-auto h-[68px]">
        <button wire:click="toggleMenuState"
            class="hover:bg-red-light h-9 w-9 flex justify-center items-center outline-none text-black hover:text-white transition-all duration-200">
            {{-- Botón de menú --}}
            <x-menu-svg height="36px" width="36px" />
        </button>

        <ul class=" text-[14px] font-montserrat font-medium lg:flex gap-8 text-primary hidden" style="list-style-type: none;">
            <li class=" h-5">
                <a class=" h-5 hover:text-dark-sage" href="#">Destinos</a>
            </li>

            <li class=" h-5">
                <a class=" h-5 hover:text-dark-sage" href="#">Historias que Inspiran</a>
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

        <button
            class="hover:bg-red-light h-9 w-9 flex justify-center items-center outline-none text-black hover:text-white transition-all duration-200">
            {{-- Botón de búsqueda --}}
            <x-search-svg height="36px" width="36px" />
        </button>
    </div>

    {{-- Overlay del menu --}}
    @if ($toggleMenu)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-40" wire:click="toggleMenuState"></div>
    @endif

    {{-- Menu lateral deslizante --}}
    <div
        class="fixed top-0 left-0 h-full w-80 bg-white z-50 transform transition-transform duration-300 ease-in-out {{ $toggleMenu ? 'translate-x-0' : '-translate-x-full' }}">
        {{-- Header del menu --}}
        <div class="flex justify-between items-center px-8 max-h-[68px] h-full border-b border-gray-lighter">
            <h2 class="text-lg font-montserrat text-primary">Menú</h2>
            <button wire:click="toggleMenuState"
                class="text-black transition-all h-9 w-9 duration-200 hover:bg-red-light hover:text-white">
                <x-close-svg height="36px" width="36px" />
            </button>
        </div>

        {{-- Menu items --}}
        <div class="p-6">
            <ul class="text-[16px] font-montserrat font-medium text-primary space-y-4" style="list-style-type: none;">
                <li class="py-2 border-b border-gray-100">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block">Destinos</a>
                </li>

                <li class="py-2 border-b border-gray-100">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block">Historias que
                        Inspiran</a>
                </li>

                <li class="py-2 border-b border-gray-100">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block">Eventos
                        Sociales</a>
                </li>

                <li class="py-2 border-b border-gray-100">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block">Salud y
                        Bienestar</a>
                </li>

                <li class="py-2 border-b border-gray-100">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block">Gastronomía</a>
                </li>

                <li class="py-2 border-b border-gray-100">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block">Cultura Viva</a>
                </li>

                <li class="py-2 border-b border-gray-100">
                    <button wire:click="$dispatch('openLoginModal')" class="hover:text-dark-sage transition-colors duration-200 block text-left w-full">Iniciar Sesión</button>
                </li>
            </ul>
        </div>
    </div>

</nav>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('toggleMenu', (isOpen) => {
            if (isOpen[0]) {
                document.body.classList.add('overflow-hidden');
            } else {
                document.body.classList.remove('overflow-hidden');
            }
        });
    });
</script>

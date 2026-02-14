@php
    $navCurrentPath = request()->path();
@endphp
<nav class=" lg:px-8 lg:py-0 px-10 border-b border-gray-lighter"
    x-data="{
        currentPath: @js($navCurrentPath),
        isDirtyRoute(path) {
            return path === 'articles/create'
                || /^articles\/[^\/]+\/edit$/.test(path)
                || path === 'temas-sugeridos/crear'
                || /^temas-sugeridos\/[^\/]+\/editar$/.test(path);
        },
        dispatchCancel(path, targetUrl) {
            const payload = { redirectUrl: targetUrl };
            if (path === 'articles/create') Livewire.dispatch('cancelCreateArticle', payload);
            else if (/^articles\/[^\/]+\/edit$/.test(path)) Livewire.dispatch('cancelEditArticle', payload);
            else if (path === 'temas-sugeridos/crear') Livewire.dispatch('cancelCreateTopic', payload);
            else if (/^temas-sugeridos\/[^\/]+\/editar$/.test(path)) Livewire.dispatch('cancelEditTopic', payload);
        },
        handleNavLinkClick(e) {
            if (!this.isDirtyRoute(this.currentPath)) return;
            e.preventDefault();
            this.dispatchCancel(this.currentPath, e.currentTarget.href);
        }
    }">
    <div class="flex justify-between items-center max-w-[1200px] mx-auto h-[68px]">
        <button wire:click="toggleMenuState"
            class="hover:bg-red-light h-9 w-9 flex justify-center items-center outline-none text-black hover:text-white transition-all duration-200">
            {{-- Botón de menú --}}
            <x-menu-svg height="36px" width="36px" />
        </button>

        <ul class=" text-[14px] font-montserrat font-medium lg:flex gap-8 text-primary hidden"
            style="list-style-type: none;">
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
        class="fixed top-0 left-0 h-full w-80 max-w-[100vw] bg-white z-50 transform transition-transform duration-300 ease-in-out flex flex-col {{ $toggleMenu ? 'translate-x-0' : '-translate-x-full' }}">
        {{-- Header del menu (altura fija, sin encoger) --}}
        <div class="flex justify-between items-center px-4 sm:px-8 min-h-[68px] shrink-0 border-b border-gray-lighter">
            <h2 class="text-lg font-montserrat text-primary">Menú</h2>
            <button wire:click="toggleMenuState"
                class="text-black transition-all h-9 w-9 shrink-0 duration-200 hover:bg-red-light hover:text-white">
                <x-close-svg height="36px" width="36px" />
            </button>
        </div>

        {{-- Menu items (con scroll vertical cuando el contenido es alto) --}}
        <div class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden p-4 sm:p-6">
            <ul class="text-[16px] font-montserrat font-medium text-primary space-y-4 min-w-0" style="list-style-type: none;">
                <li class="py-2 border-b border-gray-100 min-w-0">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block break-words">Destinos</a>
                </li>

                <li class="py-2 border-b border-gray-100 min-w-0">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block break-words">Historias que Inspiran</a>
                </li>

                <li class="py-2 border-b border-gray-100 min-w-0">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block break-words">Eventos Sociales</a>
                </li>

                <li class="py-2 border-b border-gray-100 min-w-0">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block break-words">Salud y Bienestar</a>
                </li>

                <li class="py-2 border-b border-gray-100 min-w-0">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block break-words">Gastronomía</a>
                </li>

                <li class="py-2 border-b border-gray-100 min-w-0">
                    <a href="#" class="hover:text-dark-sage transition-colors duration-200 block break-words">Cultura Viva</a>
                </li>

                <li class="min-w-0">
                    @auth
                        {{-- Bloque usuario fijo: nombre, correo, permiso y enlaces siempre visibles --}}
                        <div class="border border-gray-lighter bg-sage p-4 min-w-0 overflow-hidden">
                            <p class="font-montserrat font-semibold text-primary text-base break-words">{{ Auth::user()->name }}</p>
                            <p class="text-gray-light text-xs font-opensans mt-0.5 break-all">{{ Auth::user()->email }}</p>
                            @if (Auth::user()->rol)
                                <span class="inline-block mt-2 px-2 py-0.5 text-xs font-montserrat font-medium text-gray-light border border-gray-lighter bg-white">{{ rol_label(Auth::user()->rol) }}</span>
                            @endif
                            <div class="mt-4 space-y-1 min-w-0">
                                <a href="{{ route('profile') }}"
                                    @click="handleNavLinkClick($event)"
                                    class="hover:bg-dark-sage/15 hover:text-primary flex items-center gap-2 px-2 py-2 text-sm transition-colors duration-200 min-w-0">
                                    <svg class="w-4 h-4 shrink-0 text-gray-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Mi Perfil
                                </a>
                                <a href="{{ route('dashboard') }}"
                                    @click="handleNavLinkClick($event)"
                                    class="hover:bg-dark-sage/15 hover:text-primary flex items-center gap-2 px-2 py-2 text-sm transition-colors duration-200 min-w-0">
                                    <svg class="w-4 h-4 shrink-0 text-gray-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                    </svg>
                                    Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="hover:bg-dark-sage/15 hover:text-red-light w-full flex items-center gap-2 px-2 py-2 text-sm transition-colors duration-200 font-inherit border-0 cursor-pointer text-left min-w-0">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <button wire:click="$dispatch('openLoginModal')"
                            class="hover:text-dark-sage transition-colors duration-200 block text-left w-full py-2">
                            Iniciar Sesión
                        </button>
                    @endauth
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
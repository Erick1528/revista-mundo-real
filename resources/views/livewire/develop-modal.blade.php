<div>
    @if($isOpen)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] flex items-center justify-center z-50 p-4" wire:click="closeModal">
            <div class="bg-white max-w-md w-full mx-4 border border-gray-lighter shadow-xl" wire:click.stop>
                <div class="px-6 py-6">
                {{-- Icono de advertencia --}}
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 bg-sage">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>

                {{-- Título --}}
                <h3 class="text-lg font-montserrat font-semibold text-primary text-center mb-2">
                    En Desarrollo
                </h3>

                {{-- Mensaje --}}
                <p class="text-sm font-opensans text-gray-light text-center mb-6">
                    La función de <span class="font-semibold text-primary">{{ $action }}</span> aún está en desarrollo. 
                    Estamos trabajando en ello 🚧
                </p>

                {{-- Botón de cerrar --}}
                <div class="flex justify-center">
                    <button wire:click="closeModal" 
                            class="w-full px-4 py-2 h-10 bg-primary text-white hover:bg-dark-sage font-montserrat font-medium transition-colors">
                        Entendido
                    </button>
                </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', function () {
            Livewire.on('block-scroll', function () {
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px';
            });

            Livewire.on('unblock-scroll', function () {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });
        });
    </script>
</div>

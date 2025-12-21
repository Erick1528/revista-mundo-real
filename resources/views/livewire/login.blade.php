<div>
    @if ($showModal)
        <div class="fixed inset-0 bg-[rgba(0,0,0,0.5)] z-50 flex items-center justify-center p-4" wire:click="closeModal">
            <div class="bg-white shadow-xl max-w-md w-full p-8" wire:click.stop>
                {{-- Botón de cerrar --}}
                <div class="flex justify-end mb-4">
                    <button wire:click="closeModal"
                        class="text-black transition-all h-9 w-9 duration-200 hover:bg-red-light hover:text-white">
                        <x-close-svg height="36px" width="36px" />
                    </button>
                </div>

                {{-- Header del modal --}}
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-serif text-primary">Inicio de Sesión</h2>
                </div>

                {{-- Formulario --}}
                <form class="space-y-6">
                    {{-- Campo de email --}}
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-montserrat font-medium text-primary">
                            Correo electrónico
                        </label>
                        <input type="email" id="email" placeholder="tu@correo.com" wire:model="email"
                            class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-dark-sage focus:border-transparent font-opensans text-sm">
                    </div>

                    {{-- Campo de contraseña --}}
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-montserrat font-medium text-primary">
                            Contraseña
                        </label>
                        <input type="password" id="password" placeholder="••••••••" wire:model="password"
                            class="w-full px-4 py-3 border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-dark-sage focus:border-transparent font-opensans text-sm">
                    </div>

                    {{-- Enlace olvidaste contraseña --}}
                    <div class="text-center">
                        <a href="#"
                            class="text-sm font-montserrat text-gray-light hover:text-dark-sage transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    {{-- Botón de envío --}}
                    <button wire:click.prevent="login" type="submit"
                        class="w-full bg-transparent text-primary py-3 px-4 border border-primary font-montserrat font-medium text-sm relative overflow-hidden hover:text-white transition-colors duration-300 group">
                        <span
                            class="absolute inset-0 bg-primary transform -translate-x-full group-hover:translate-x-0 transition-transform duration-300 ease-out"></span>
                        <span class="relative z-10">Iniciar Sesión</span>
                    </button>
                </form>

                {{-- Enlaces de ayuda --}}
                <div class="text-center mt-6">
                    <p class="text-sm font-montserrat text-gray-light">
                        ¿Necesitas ayuda?
                        <a href="#" class="text-dark-sage hover:text-primary transition-colors">Contacta
                            soporte</a>
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('loginModalToggled', (isOpen) => {
            if (isOpen[0]) {
                document.body.classList.add('overflow-hidden');
            } else {
                document.body.classList.remove('overflow-hidden');
            }
        });
    });
</script>

<div class="px-4 sm:px-10 lg:px-[120px] py-8 sm:py-12 max-w-[1200px] mx-auto w-full">
    @if (session('message'))
        <div class="w-full p-4 bg-green-50 border border-green-200 text-green-800 mb-6">
            <div class="flex items-center justify-between">
                <span class="font-opensans text-sm">{{ session('message') }}</span>
                <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="text-green-600 hover:text-green-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="w-full p-4 bg-red-50 border border-red-200 text-red-800 mb-6">
            <div class="flex items-center justify-between">
                <span class="font-opensans text-sm">{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="text-red-600 hover:text-red-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="updateUser" class="space-y-4">
        <div class="border border-gray-lighter">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-lighter">
                <h3 class="font-montserrat font-medium text-primary text-sm sm:text-base">Datos del usuario</h3>
            </div>
            <div class="px-4 sm:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-montserrat font-medium text-primary">
                        Nombre
                    </label>
                    <input type="text" id="name" placeholder="Nombre del usuario" wire:model="name"
                        class="w-full px-4 py-3 border @error('name') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
                    @error('name')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-montserrat font-medium text-primary">
                        Correo electrónico
                    </label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled
                        class="w-full px-4 py-3 border border-gray-300 bg-gray-100 font-opensans text-sm cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">El correo no se puede modificar desde aquí.</p>
                </div>

                <div class="space-y-2">
                    <label for="rol" class="block text-sm font-montserrat font-medium text-primary">
                        Rol
                    </label>
                    <select id="rol" wire:model="rol"
                        class="w-full px-4 py-3 border @error('rol') border-red-500 @else border-gray-300 @enderror bg-gray-50 focus:outline-none focus:border-dark-sage focus:shadow-[0_0_0_2px_rgba(183,182,153,0.5)] font-opensans text-sm">
                        @foreach($rolesOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('rol')
                        <p class="text-red-500 text-xs font-opensans">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
            <button type="submit"
                class="w-full sm:flex-1 h-12 bg-primary text-white text-base font-semibold font-montserrat flex items-center justify-center gap-2 transition-colors hover:bg-dark-sage disabled:opacity-70"
                wire:loading.attr="disabled" wire:target="updateUser">
                <div wire:loading wire:target="updateUser"
                    class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <span wire:loading.remove wire:target="updateUser">Guardar cambios</span>
                <span wire:loading wire:target="updateUser">Guardando...</span>
            </button>
            <a href="{{ route('users.index') }}"
                class="w-full sm:flex-1 h-12 flex items-center justify-center border border-gray-light text-gray-light hover:bg-sage transition-colors font-montserrat font-semibold text-base">
                Cancelar
            </a>
        </div>
    </form>

</div>

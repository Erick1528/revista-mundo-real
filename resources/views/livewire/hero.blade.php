<div class="overflow-x-hidden">
    <div
        class=" py-8 @if (!request()->is('/')) max-w-[1200px] mx-auto px-5 sm:px-10 md:px-[120px] relative after:absolute after:bottom-0 after:left-[50%] after:-translate-x-[50%] after:w-screen after:h-px after:bg-gray-lighter @else border-b border-gray-lighter @endif ">
        @if (request()->is('dashboard'))
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Panel de
                Administración</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">
                Bienvenido al panel editorial de Revista Mundo Real</p>
        @elseif ($showCreateArticleView)
            <button wire:click="cancelCreateArticle"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al Dashboard</p>
            </button>

            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Crear Nuevo
                Artículo
            </h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Completa la
                información para publicar un nuevo artículo</p>
        @else
            <h1
                class=" text-6xl sm:text-7xl font-serif text-center text-primary px-6 sm:px-10 lg:px-4 text-balance font-normal">
                Revista Mundo Real</h1>
            <p
                class=" text-[12px] sm:text-sm font-montserrat text-center mt-3 text-gray-light uppercase px-4 sm:px-6 md:p-0">
                Conectando Culturas · Inspirando Viajes · Celebrando Tradiciones</p>
        @endif

    </div>
</div>

<script>
    let historyPushed = false;
    let isInCreateArticleView = @js($showCreateArticleView);

    // Detectar cuando el usuario presiona el botón atrás del navegador o gestos en móvil
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar una entrada al historial cuando se muestra la vista de crear artículo
        if (isInCreateArticleView && !historyPushed) {
            history.pushState({
                createArticle: true
            }, '', window.location.href);
            historyPushed = true;
        }
    });

    // Detectar navegación hacia atrás
    window.addEventListener('popstate', function(event) {
        console.log('Popstate triggered, isInCreateArticleView:', isInCreateArticleView);

        // Si estamos en la vista de crear artículo
        if (isInCreateArticleView) {
            console.log('Calling cancelCreateArticle');
            event.preventDefault();
            @this.call('cancelCreateArticle');
            return false;
        }
    });

    // Actualizar el estado cuando Livewire se actualiza
    document.addEventListener('livewire:updated', function() {
        const newState = @js($showCreateArticleView);

        if (newState && !historyPushed) {
            history.pushState({
                createArticle: true
            }, '', window.location.href);
            historyPushed = true;
            isInCreateArticleView = true;
        } else if (!newState) {
            historyPushed = false;
            isInCreateArticleView = false;
        }

        console.log('Livewire updated, new state:', newState);
    });

    // Escuchar cambios específicos de Livewire para el componente Hero
    window.addEventListener('livewire:init', function() {
        Livewire.hook('component.updated', (component) => {
            if (component.fingerprint.name === 'hero') {
                isInCreateArticleView = component.data.showCreateArticleView || false;
                console.log('Hero component updated, createArticleView:', isInCreateArticleView);
            }
        });
    });
</script>

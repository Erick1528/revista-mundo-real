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
        @elseif ($showEditArticleView)
            <button wire:click="cancelEditArticle"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al Dashboard</p>
            </button>

            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Editar
                Artículo
            </h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Completa la
                información para actualizar el artículo</p>
        @elseif ($showCreateTopicView)
            <button wire:click="cancelCreateTopic"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </button>

            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Crear Nuevo
                Tema Sugerido
            </h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Completa la
                información para proponer un nuevo tema</p>
        @elseif ($showEditTopicView)
            <button wire:click="cancelEditTopic"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </button>

            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Editar
                Tema Sugerido
            </h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Completa la
                información para actualizar el tema</p>
        @elseif ($showViewTopicView)
            <button wire:click="cancelViewTopic"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </button>

            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Ver
                Tema Sugerido
            </h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Información
                detallada del tema sugerido</p>
        @elseif ($showViewUserView)
            <button wire:click="cancelViewUser"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </button>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Perfil de Usuario</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Información del usuario</p>
        @elseif ($showEditUserView)
            <button wire:click="cancelEditUser"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </button>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Editar Usuario</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Modifica el nombre y el rol del usuario</p>
        @elseif ($showCreateUserView)
            <button wire:click="cancelCreateUser"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </button>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Crear Usuario</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Añade un nuevo usuario al sistema</p>
        @elseif ($showArticleTrashView)
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al Dashboard</p>
            </a>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Papelera de artículos</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Restaura artículos movidos a la papelera o elimínalos permanentemente</p>
        @elseif ($showUserTrashView)
            <button wire:click="cancelUserTrash"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </button>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Papelera de usuarios</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Restaura usuarios dados de baja</p>
        @elseif ($showCreateAdvertiserView)
            <a href="{{ route('advertisers.index') }}"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </a>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Crear Anunciante</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Añade una empresa o anunciante para usarlo en artículos y anuncios</p>
        @elseif ($showEditAdvertiserView)
            <a href="{{ route('advertisers.index') }}"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </a>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Editar Anunciante</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Modifica el nombre y el logo del anunciante</p>
        @elseif ($showAdvertiserTrashView)
            <a href="{{ route('advertisers.index') }}"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </a>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Papelera de anunciantes</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Restaura anunciantes eliminados para que vuelvan al listado</p>
        @elseif ($showCreateAdView)
            <button type="button" wire:click="cancelCreateAd"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </button>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Crear Anuncio</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Completa el contenido del anuncio reutilizable</p>
        @elseif ($showEditAdView)
            <button type="button" wire:click="cancelEditAd"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </button>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Editar Anuncio</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Modifica el contenido del anuncio</p>
        @elseif ($showAdView)
            <a href="{{ route('ads.index') }}"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </a>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Vista previa del anuncio</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Así se verá el anuncio · Cambia el estado si tienes permiso</p>
        @elseif ($showAdTrashView)
            <a href="{{ route('ads.index') }}"
                class="inline-flex items-center gap-2 text-primary hover:text-dark-sage transition-colors mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <p class=" text-sm font-montserrat">Volver al listado</p>
            </a>
            <h1 class=" text-4xl sm:text-5xl font-serif text-left text-primary text-balance font-normal">Papelera de anuncios</h1>
            <p class=" text-[12px] sm:text-sm font-montserrat text-left mt-3 text-gray-light uppercase">Restaura anuncios movidos a la papelera</p>
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
(function() {
    'use strict';
    let historyPushed = false;
    let isInCreateArticleView = @js($showCreateArticleView);
    let isInEditArticleView = @js($showEditArticleView);
    let isInCreateTopicView = @js($showCreateTopicView);
    let isInEditTopicView = @js($showEditTopicView);
    let isInViewTopicView = @js($showViewTopicView);
    let isInCreateAdView = @js($showCreateAdView);
    let isInEditAdView = @js($showEditAdView);

    // Detectar cuando el usuario presiona el botón atrás del navegador o gestos en móvil
    document.addEventListener('DOMContentLoaded', function() {
        // Agregar una entrada al historial cuando se muestra la vista de crear artículo
        if (isInCreateArticleView && !historyPushed) {
            history.pushState({
                createArticle: true
            }, '', window.location.href);
            historyPushed = true;
        }
        // Agregar una entrada al historial cuando se muestra la vista de editar artículo
        if (isInEditArticleView && !historyPushed) {
            history.pushState({
                editArticle: true
            }, '', window.location.href);
            historyPushed = true;
        }
        // Agregar una entrada al historial cuando se muestra la vista de crear tema
        if (isInCreateTopicView && !historyPushed) {
            history.pushState({
                createTopic: true
            }, '', window.location.href);
            historyPushed = true;
        }
        // Agregar una entrada al historial cuando se muestra la vista de editar tema
        if (isInEditTopicView && !historyPushed) {
            history.pushState({
                editTopic: true
            }, '', window.location.href);
            historyPushed = true;
        }
        if (isInCreateAdView && !historyPushed) {
            history.pushState({
                createAd: true
            }, '', window.location.href);
            historyPushed = true;
        }
        if (isInEditAdView && !historyPushed) {
            history.pushState({
                editAd: true
            }, '', window.location.href);
            historyPushed = true;
        }
    });

    // Detectar navegación hacia atrás
    window.addEventListener('popstate', function(event) {
        // Si estamos en la vista de crear artículo
        if (isInCreateArticleView) {
            event.preventDefault();
            @this.call('cancelCreateArticle');
            return false;
        }

        // Si estamos en la vista de editar artículo
        if (isInEditArticleView) {
            event.preventDefault();
            @this.call('cancelEditArticle');
            return false;
        }

        // Si estamos en la vista de crear tema
        if (isInCreateTopicView) {
            event.preventDefault();
            @this.call('cancelCreateTopic');
            return false;
        }

        // Si estamos en la vista de editar tema
        if (isInEditTopicView) {
            event.preventDefault();
            @this.call('cancelEditTopic');
            return false;
        }

        // Si estamos en la vista de crear anuncio
        if (isInCreateAdView) {
            event.preventDefault();
            @this.call('cancelCreateAd');
            return false;
        }

        // Si estamos en la vista de editar anuncio
        if (isInEditAdView) {
            event.preventDefault();
            @this.call('cancelEditAd');
            return false;
        }
    });

    // Actualizar el estado cuando Livewire se actualiza
    document.addEventListener('livewire:updated', function() {
        const newCreateArticleState = @js($showCreateArticleView);
        const newEditArticleState = @js($showEditArticleView);
        const newCreateTopicState = @js($showCreateTopicView);
        const newEditTopicState = @js($showEditTopicView);
        const newCreateAdState = @js($showCreateAdView);
        const newEditAdState = @js($showEditAdView);

        if (newCreateArticleState && !historyPushed) {
            history.pushState({
                createArticle: true
            }, '', window.location.href);
            historyPushed = true;
            isInCreateArticleView = true;
        } else if (newEditArticleState && !historyPushed) {
            history.pushState({
                editArticle: true
            }, '', window.location.href);
            historyPushed = true;
            isInEditArticleView = true;
        } else if (newCreateTopicState && !historyPushed) {
            history.pushState({
                createTopic: true
            }, '', window.location.href);
            historyPushed = true;
            isInCreateTopicView = true;
        } else if (newEditTopicState && !historyPushed) {
            history.pushState({
                editTopic: true
            }, '', window.location.href);
            historyPushed = true;
            isInEditTopicView = true;
        } else if (newCreateAdState && !historyPushed) {
            history.pushState({
                createAd: true
            }, '', window.location.href);
            historyPushed = true;
            isInCreateAdView = true;
        } else if (newEditAdState && !historyPushed) {
            history.pushState({
                editAd: true
            }, '', window.location.href);
            historyPushed = true;
            isInEditAdView = true;
        } else if (!newCreateArticleState && !newEditArticleState && !newCreateTopicState && !newEditTopicState && !newCreateAdState && !newEditAdState) {
            historyPushed = false;
            isInCreateArticleView = false;
            isInEditArticleView = false;
            isInCreateTopicView = false;
            isInEditTopicView = false;
            isInCreateAdView = false;
            isInEditAdView = false;
        }
    });

    // Escuchar cambios específicos de Livewire para el componente Hero
    window.addEventListener('livewire:init', function() {
        Livewire.hook('component.updated', (component) => {
            if (component.fingerprint.name === 'hero') {
                isInCreateArticleView = component.data.showCreateArticleView || false;
                isInEditArticleView = component.data.showEditArticleView || false;
                isInCreateTopicView = component.data.showCreateTopicView || false;
                isInEditTopicView = component.data.showEditTopicView || false;
                isInCreateAdView = component.data.showCreateAdView || false;
                isInEditAdView = component.data.showEditAdView || false;
            }
        });
    });
})();
</script>

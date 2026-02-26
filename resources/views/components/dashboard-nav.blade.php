@props([])
@php
    $current = request()->route() ? request()->route()->getName() : null;
    $currentPath = request()->path();
    $items = [
        ['route' => 'dashboard', 'label' => 'Inicio', 'icon' => 'dashboard', 'path' => 'dashboard', 'active' => $current === 'dashboard', 'isInicio' => true],
        ['route' => 'suggested-topics.index', 'label' => 'Temas sugeridos', 'icon' => 'layers', 'path' => 'temas-sugeridos', 'active' => $current && str_starts_with($current, 'suggested-topics'), 'isInicio' => false],
        ['route' => 'cover.index', 'label' => 'Portadas', 'icon' => 'grid', 'path' => 'portadas', 'active' => $current && str_starts_with($current, 'cover'), 'isInicio' => false],
    ];
    if (Auth::check() && Auth::user()->rol === 'administrator') {
        $items[] = ['route' => 'users.index', 'label' => 'Usuarios', 'icon' => 'users', 'path' => 'usuarios', 'active' => $current && str_starts_with($current, 'users'), 'isInicio' => false];
    }
    if (Auth::check() && in_array(Auth::user()->rol, ['editor_chief', 'administrator', 'moderator'], true)) {
        $items[] = ['route' => 'advertisers.index', 'label' => 'Anunciantes', 'icon' => 'advertisers', 'path' => 'anunciantes', 'active' => $current && str_starts_with($current, 'advertisers'), 'isInicio' => false];
        $items[] = ['route' => 'ads.index', 'label' => 'Anuncios', 'icon' => 'ads', 'path' => 'anuncios', 'active' => $current && str_starts_with($current, 'ads'), 'isInicio' => false];
    }
@endphp
<nav aria-label="Panel de la revista" class="bg-white border-b border-gray-lighter overflow-hidden min-w-0"
    x-data="{
        currentPath: @js($currentPath),
        isDirtyRoute(path) {
            return path === 'articles/create'
                || /^articles\/[^\/]+\/edit$/.test(path)
                || path === 'temas-sugeridos/crear'
                || /^temas-sugeridos\/[^\/]+\/editar$/.test(path)
                || path === 'anuncios/crear'
                || /^anuncios\/[^\/]+\/editar$/.test(path);
        },
        dispatchCancel(path, targetUrl) {
            const payload = { redirectUrl: targetUrl };
            if (path === 'articles/create') Livewire.dispatch('cancelCreateArticle', payload);
            else if (/^articles\/[^\/]+\/edit$/.test(path)) Livewire.dispatch('cancelEditArticle', payload);
            else if (path === 'temas-sugeridos/crear') Livewire.dispatch('cancelCreateTopic', payload);
            else if (/^temas-sugeridos\/[^\/]+\/editar$/.test(path)) Livewire.dispatch('cancelEditTopic', payload);
            else if (path === 'anuncios/crear') Livewire.dispatch('cancelCreateAd', payload);
            else if (/^anuncios\/[^\/]+\/editar$/.test(path)) Livewire.dispatch('cancelEditAd', payload);
        },
        handleNavClick(e, targetPath) {
            if (!this.isDirtyRoute(this.currentPath)) return;
            if (targetPath === this.currentPath) return;
            e.preventDefault();
            const targetUrl = e.currentTarget.href;
            this.dispatchCancel(this.currentPath, targetUrl);
        }
    }">
    <div class="px-4 sm:px-10 lg:px-[120px] max-w-[1200px] mx-auto w-full min-w-0 overflow-hidden">
        <div class="flex items-center justify-between sm:justify-start gap-1 sm:gap-2 overflow-x-auto overflow-y-hidden scrollbar-hide py-3 -mx-4 px-4 sm:mx-0 sm:px-0 min-h-[44px] touch-pan-x min-w-0">
            @foreach($items as $item)
                <a href="{{ route($item['route']) }}"
                    title="{{ $item['label'] }}"
                    @click="handleNavClick($event, @js($item['path']))"
                    class="shrink-0 flex items-center justify-center sm:justify-start gap-2 px-2 sm:px-3 py-2 min-w-[44px] sm:min-w-0 font-montserrat text-sm font-medium transition-colors duration-200
                        {{ $item['active']
                            ? 'bg-sage text-primary border border-dark-sage/30'
                            : 'text-gray-light hover:text-primary hover:bg-sage border border-transparent' }}">
                    @if($item['icon'] === 'dashboard')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    @elseif($item['icon'] === 'plus')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    @elseif($item['icon'] === 'layers')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/></svg>
                    @elseif($item['icon'] === 'grid')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    @elseif($item['icon'] === 'trash')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    @elseif($item['icon'] === 'users')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    @elseif($item['icon'] === 'advertisers')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" stroke-linecap="round" stroke-linejoin="round" rx="1"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h10M7 16h4"/></svg>
                    @elseif($item['icon'] === 'ads')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    @endif
                    <span class="hidden sm:inline">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</nav>

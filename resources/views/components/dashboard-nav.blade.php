@props([])
@php
    $current = request()->route() ? request()->route()->getName() : null;
    $currentPath = request()->path();
    $items = [
        ['route' => 'dashboard', 'label' => 'Inicio', 'icon' => 'dashboard', 'active' => $current === 'dashboard', 'isInicio' => true],
        ['route' => 'articles.create', 'label' => 'Nuevo artículo', 'icon' => 'plus', 'active' => $current === 'articles.create', 'isInicio' => false],
        ['route' => 'suggested-topics.index', 'label' => 'Temas sugeridos', 'icon' => 'layers', 'active' => $current && str_starts_with($current, 'suggested-topics'), 'isInicio' => false],
        ['route' => 'cover.index', 'label' => 'Portadas', 'icon' => 'grid', 'active' => $current && str_starts_with($current, 'cover'), 'isInicio' => false],
        ['route' => 'dashboard.papelera', 'label' => 'Papelera', 'icon' => 'trash', 'active' => $current === 'dashboard.papelera', 'isInicio' => false],
        ['route' => 'profile', 'label' => 'Perfil', 'icon' => 'user', 'active' => $current === 'profile', 'isInicio' => false],
    ];
@endphp
<nav aria-label="Panel de la revista" class="bg-white border-b border-gray-lighter"
    x-data="{
        currentPath: @js($currentPath),
        dashboardUrl: @js(route('dashboard')),
        goToDashboard(e) {
            const path = this.currentPath;
            if (path === 'articles/create') {
                e.preventDefault();
                Livewire.dispatch('cancelCreateArticle');
            } else if (path.match(/^articles\/[^\/]+\/edit$/)) {
                e.preventDefault();
                Livewire.dispatch('cancelEditArticle');
            } else if (path === 'temas-sugeridos/crear') {
                e.preventDefault();
                Livewire.dispatch('cancelCreateTopic');
            } else if (path.match(/^temas-sugeridos\/[^\/]+\/editar$/)) {
                e.preventDefault();
                Livewire.dispatch('cancelEditTopic');
            }
        }
    }">
    <div class="px-4 sm:px-10 lg:px-[120px] max-w-[1200px] mx-auto w-full min-w-0">
        <div class="flex items-center justify-between sm:justify-start gap-1 sm:gap-2 overflow-x-auto scrollbar-auto-hide py-3 -mx-4 px-4 sm:mx-0 sm:px-0 min-h-[44px] touch-pan-x">
            @foreach($items as $item)
                @if($item['isInicio'] ?? false)
                    <a href="{{ route($item['route']) }}"
                        title="{{ $item['label'] }}"
                        @click="goToDashboard($event)"
                        class="shrink-0 flex items-center justify-center sm:justify-start gap-2 px-2 sm:px-3 py-2 min-w-[44px] sm:min-w-0 font-montserrat text-sm font-medium transition-colors duration-200
                            {{ $item['active']
                                ? 'bg-sage text-primary border border-dark-sage/30'
                                : 'text-gray-light hover:text-primary hover:bg-sage border border-transparent' }}">
                @else
                    <a href="{{ route($item['route']) }}"
                        title="{{ $item['label'] }}"
                        class="shrink-0 flex items-center justify-center sm:justify-start gap-2 px-2 sm:px-3 py-2 min-w-[44px] sm:min-w-0 font-montserrat text-sm font-medium transition-colors duration-200
                            {{ $item['active']
                                ? 'bg-sage text-primary border border-dark-sage/30'
                                : 'text-gray-light hover:text-primary hover:bg-sage border border-transparent' }}">
                @endif
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
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    @endif
                    <span class="hidden sm:inline">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</nav>

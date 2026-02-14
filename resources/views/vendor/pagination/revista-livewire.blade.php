<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="font-montserrat">
            {{-- Móvil --}}
            <div class="flex justify-between flex-1 sm:hidden gap-2">
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-light bg-white border border-gray-lighter cursor-not-allowed">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary bg-white border border-gray-lighter hover:bg-sage transition-colors">
                        {!! __('pagination.previous') !!}
                    </button>
                @endif

                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary bg-white border border-gray-lighter hover:bg-sage transition-colors">
                        {!! __('pagination.next') !!}
                    </button>
                @else
                    <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-light bg-white border border-gray-lighter cursor-not-allowed">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </div>

            {{-- Desktop --}}
            <div class="hidden sm:flex sm:flex-1 sm:gap-4 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-light leading-5">
                        {!! __('Showing') !!}
                        <span class="font-medium text-primary">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium text-primary">{{ $paginator->lastItem() }}</span>
                        {!! __('of') !!}
                        <span class="font-medium text-primary">{{ $paginator->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>

                <div class="inline-flex shadow-sm border border-gray-lighter bg-white">
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-light bg-white border-r border-gray-lighter cursor-not-allowed" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            </span>
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            class="inline-flex items-center px-2 py-2 text-sm font-medium text-primary bg-white border-r border-gray-lighter hover:bg-sage transition-colors"
                            aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                        </button>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-light bg-white border-r border-gray-lighter cursor-default">{{ $element }}</span>
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page">
                                            <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-primary bg-sage border-r border-dark-sage/30 cursor-default">{{ $page }}</span>
                                        </span>
                                    @else
                                        <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                            class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-primary bg-white border-r border-gray-lighter hover:bg-sage transition-colors"
                                            aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                            {{ $page }}
                                        </button>
                                    @endif
                                </span>
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-primary bg-white hover:bg-sage transition-colors"
                            aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        </button>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-light bg-white cursor-not-allowed" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                            </span>
                        </span>
                    @endif
                </div>
            </div>
        </nav>
    @endif
</div>

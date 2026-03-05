<div class="max-w-7xl mx-auto px-4 sm:px-10 lg:px-[120px] pb-12">

    <style>
        .image-caption-content {
            font-size: 0.75rem;
            color: #6b7280;
            font-family: 'Open Sans', sans-serif;
            font-style: italic;
            text-align: center;
        }
    
        .image-caption-content * {
            font-size: inherit;
            color: inherit;
            font-family: inherit;
            font-style: inherit;
            text-align: inherit;
            margin: 0;
            padding: 0;
            line-height: inherit;
        }
    
        .image-caption-content p {
            display: inline;
        }
    
        .image-caption-content p:not(:last-child)::after {
            content: ' ';
        }
    </style>

    <div class=" pt-12 pb-6">

        <p
            class="inline-block px-3 py-2 bg-dark-sage text-gray-super-light text-xs font-montserrat uppercase tracking-wider font-semibold mb-3 md:mb-4">
            {{ $section }}
        </p>

        <h1 class="font-serif text-2xl md:text-4xl lg:text-5xl leading-tight text-balance mb-3 md:mb-4">
            {{ $article->title }}
        </h1>

        <p
            class="text-lg md:text-xl lg:text-2xl text-gray-light text-muted-foreground font-serif italic text-pretty mb-4 md:mb-6">
            {{ $article->subtitle }}
        </p>

        <div
            class="flex flex-wrap items-center gap-x-4 md:gap-x-6 gap-y-2 md:gap-y-3 text-xs md:text-sm text-muted-foreground font-montserrat border-t border-b border-border py-3 md:py-4 border-gray-lighter text-gray-light">

            <div class="flex items-center gap-1.5 md:gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-user h-3.5 w-3.5 md:h-4 md:w-4">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span>{{ $authorName }}</span>
            </div>

            <div class="flex items-center gap-1.5 md:gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-calendar h-3.5 w-3.5 md:h-4 md:w-4">
                    <path d="M8 2v4"></path>
                    <path d="M16 2v4"></path>
                    <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                    <path d="M3 10h18"></path>
                </svg>
                <span>{{ $fecha }}</span>
            </div>

            @if ($article->reading_time)
                <div class="flex items-center gap-1.5 md:gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-clock h-3.5 w-3.5 md:h-4 md:w-4">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>{{ $article->reading_time }} min de lectura</span>
                </div>
            @endif

            @if ($article->view_count)
                <div class="flex items-center gap-1.5 md:gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-eye h-3.5 w-3.5 md:h-4 md:w-4">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span>{{ $article->view_count }} vistas</span>
                </div>
            @endif

        </div>

        {{-- Cambiar estado (solo editor_chief, moderator, administrator) --}}
        @if ($this->canChangeStatus())
            <div class="mt-4 sm:mt-5 border-t border-b border-gray-lighter py-4 sm:py-5">
                <p class="text-xs text-gray-400 font-opensans italic mb-3">Esta sección solo es visible para editores. No se muestra en la vista pública del artículo.</p>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 sm:gap-6">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs md:text-sm text-muted-foreground font-montserrat text-gray-light">
                        <span class="font-semibold uppercase tracking-wider">Estado:</span>
                        <span class="px-2 py-1 text-xs font-semibold uppercase font-montserrat tracking-wider
                            {{ $article->status === 'published' ? 'bg-green-light text-white' : '' }}
                            {{ $article->status === 'review' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $article->status === 'denied' ? 'bg-red-light text-white' : '' }}
                            {{ $article->status === 'draft' ? 'bg-gray-lighter text-gray-light' : '' }}">
                            {{ $article->status_name }}
                        </span>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-2">
                        <label for="status_select" class="sr-only">Nuevo estado</label>
                        <select id="status_select" wire:model="newStatus"
                            class="w-full sm:w-auto min-w-0 px-3 py-2.5 sm:py-2 text-xs font-montserrat tracking-wider border border-gray-lighter text-gray-light bg-white focus:outline-none focus:border-dark-sage transition-colors">
                            @foreach (\App\Livewire\ShowArticle::getAllowedStatuses() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="button" wire:click="updateStatusFromSelect"
                            class="w-full sm:w-auto px-4 py-2.5 sm:py-2 text-xs font-semibold uppercase font-montserrat tracking-wider border border-dark-sage text-dark-sage hover:bg-dark-sage hover:text-gray-super-light transition-colors whitespace-nowrap">
                            Cambiar estado
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <div class="relative sm:aspect-video mb-6">
            <x-image-with-fallback
                :src="!empty(trim((string) ($article->image_path ?? ''))) ? asset($article->image_path) : null"
                :alt="$article->image_alt_text ? e($article->image_alt_text) : e($article->title)"
                class="w-full h-full min-h-[200px] sm:min-h-0"
                img-class="w-full h-full object-contain"
                fallback-class="w-full h-full min-h-[200px] sm:min-h-0 flex items-center justify-center bg-gray-100 border border-gray-lighter text-gray-light font-opensans text-sm" />

        @if ($article->image_caption || $article->image_credits)
            <div class="image-caption-content text-xs text-gray-500 font-opensans italic text-center mt-2">
                @if ($article->image_caption)
                    {!! fixStrongSpacing(Str::markdown(markdownLite($article->image_caption))) !!}
                @endif
                @if ($article->image_caption && $article->image_credits)
                    <span> / </span>
                @endif
                @if ($article->image_credits)
                    <span>{{ $article->image_credits }}</span>
                @endif
            </div>
        @endif
    </div>

    @php
        $shareUrl = url()->route('article.show', $article->slug);
        $shareTitle = $article->title;
        $shareUrlEnc = rawurlencode($shareUrl);
        $shareTitleEnc = rawurlencode($shareTitle);
        $shareWhatsAppText = rawurlencode($shareTitle . ' ' . $shareUrl);
    @endphp
    <div class="flex flex-wrap items-center gap-3 md:gap-4 mb-6 md:mb-8" x-data="{ copied: false }">
        {{-- Facebook --}}
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrlEnc }}" target="_blank" rel="noopener noreferrer" aria-label="Compartir en Facebook"
            class="group inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 border border-primary hover:bg-dark-sage/40 hover:border-dark-sage transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-facebook h-4 w-4 md:h-5 md:w-5 group-hover:text-dark-sage">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
            </svg>
        </a>
        {{-- X (Twitter) --}}
        <a href="https://twitter.com/intent/tweet?url={{ $shareUrlEnc }}&text={{ $shareTitleEnc }}" target="_blank" rel="noopener noreferrer" aria-label="Compartir en X (Twitter)"
            class="group inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 border border-primary hover:bg-dark-sage/40 hover:border-dark-sage transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                class="h-4 w-4 md:h-5 md:w-5 text-primary group-hover:text-dark-sage">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
        </a>
        {{-- WhatsApp --}}
        <a href="https://api.whatsapp.com/send?text={{ $shareWhatsAppText }}" target="_blank" rel="noopener noreferrer" aria-label="Compartir en WhatsApp"
            class="group inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 border border-primary hover:bg-dark-sage/40 hover:border-dark-sage transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                class="h-4 w-4 md:h-5 md:w-5 text-primary group-hover:text-dark-sage">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </a>
        {{-- Compartir (Web Share API o copiar enlace) --}}
        <button type="button"
            data-share-url="{{ $shareUrl }}"
            data-share-title="{{ $shareTitle }}"
            aria-label="Compartir"
            @click="if (navigator.share) { navigator.share({ title: $el.dataset.shareTitle, url: $el.dataset.shareUrl }).catch(() => {}) } else { navigator.clipboard.writeText($el.dataset.shareUrl).then(() => { copied = true; setTimeout(() => copied = false, 2000) }) }"
            class="group inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 border border-primary hover:bg-dark-sage/40 hover:border-dark-sage transition-colors relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-share2 h-4 w-4 md:h-5 md:w-5 group-hover:text-dark-sage">
                <circle cx="18" cy="5" r="3"></circle>
                <circle cx="6" cy="12" r="3"></circle>
                <circle cx="18" cy="19" r="3"></circle>
                <line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line>
                <line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line>
            </svg>
            <span x-show="copied" x-transition class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 text-xs font-montserrat bg-primary text-white whitespace-nowrap">Copiado</span>
        </button>
    </div>

    {{-- Content --}}
    <livewire:content-view :content="$article->content" />

    @if (!empty($article->tags) && is_array($article->tags) && count($article->tags) > 0)
        <div
            class=" mt-12 flex flex-wrap items-center gap-x-4 md:gap-x-6 gap-y-2 md:gap-y-3 text-xs md:text-sm text-muted-foreground font-montserrat border-t @if(!($relatedArticles && $relatedArticles->count() > 0)) border-b @endif border-gray-lighter py-3 md:py-4 text-gray-light">
            @foreach ($article->tags as $tag)
                <span
                    class="group inline-flex items-center justify-center px-3 py-1.5 md:px-4 md:py-2 border border-gray-lighter hover:bg-dark-sage/40 hover:border-dark-sage transition-colors text-primary group-hover:text-dark-sage cursor-pointer">
                    {{ $tag }}
                </span>
            @endforeach
        </div>
    @endif

    @if ($relatedArticles && $relatedArticles->count() > 0)
        <div class="border-t border-gray-lighter pt-8 md:pt-12 pb-6 md:pb-8">
            <h2 class="font-serif text-2xl md:text-3xl lg:text-4xl mb-6 md:mb-8">
                Artículos Relacionados
            </h2>
            <div class="space-y-8 md:space-y-0 md:grid md:grid-cols-3 md:gap-6 lg:gap-8">
                @foreach ($relatedArticles->take(5) as $relatedArticle)
                    @php
                        $authorName = !empty(trim($relatedArticle->attribution)) 
                            ? $relatedArticle->attribution 
                            : ($relatedArticle->user->name ?? 'Autor desconocido');
                    @endphp
                    <a href="{{ route('article.show', $relatedArticle->slug) }}" class="flex gap-x-4 md:flex-col md:gap-x-0 group cursor-pointer">
                        <div class="w-32 h-32 md:w-full md:h-auto overflow-hidden shrink-0 md:shrink aspect-square md:aspect-[3/2]">
                            <x-image-with-fallback
                                :src="!empty(trim((string) ($relatedArticle->image_path ?? ''))) ? asset($relatedArticle->image_path) : null"
                                :alt="e($relatedArticle->title)"
                                class="w-full h-full"
                                img-class="w-full h-full object-cover group-hover:scale-105 transition-all duration-200"
                                fallback-class="absolute inset-0 flex items-center justify-center text-gray-400 font-opensans text-xs text-center px-2 bg-gray-100 border border-gray-lighter" />
                        </div>
                        <div class="space-y-2 md:space-y-2 md:mt-3 md:mb-0">
                            <p class="text-[10px] sm:text-xs md:text-xs font-semibold uppercase font-montserrat text-gray-light tracking-wider">
                                {{ $relatedArticle->section_name }}
                            </p>
                            <h3 class="text-[18px] sm:text-xl md:text-lg lg:text-xl font-serif text-primary group-hover:text-dark-sage transition-all duration-200">
                                {{ $relatedArticle->title }}
                            </h3>
                            <p class="text-gray-light text-[10px] sm:text-xs md:hidden font-opensans">
                                Por {{ $authorName }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>
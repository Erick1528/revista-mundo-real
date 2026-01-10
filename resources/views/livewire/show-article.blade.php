<div class="max-w-7xl mx-auto px-4 sm:px-10 lg:px-[120px]">

    <div class=" pt-12 pb-6">

        <p
            class="inline-block px-3 py-1.5 bg-dark-sage text-gray-super-light text-xs font-sans uppercase tracking-wider font-semibold mb-3 md:mb-4">
            {{ $section }}</p>

        <h1 class="font-serif text-2xl md:text-4xl lg:text-5xl leading-tight text-balance mb-3 md:mb-4">
            {{ $article->title }}</h1>

        <p
            class="text-lg md:text-xl lg:text-2xl text-gray-light text-muted-foreground font-serif italic text-pretty mb-4 md:mb-6">
            {{ $article->subtitle }}</p>

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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-clock h-3.5 w-3.5 md:h-4 md:w-4">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>{{ $article->reading_time }} min de lectura</span>
                </div>
            @endif

            @if ($article->view_count)
                <div class="flex items-center gap-1.5 md:gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-eye h-3.5 w-3.5 md:h-4 md:w-4">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span>{{ $article->view_count }} vistas</span>
                </div>
            @endif

        </div>

    </div>

    <div class="relative aspect-video bg-muted mb-6">
        <img class="w-full h-full object-cover" src="{{ asset($article->image_path) }}" alt="">
    </div>

    <div class="flex flex-wrap items-center gap-3 md:gap-4 mb-6 md:mb-8">
        {{-- <span class="text-xs md:text-sm font-montserrat text-muted-foreground">Compartir:</span> --}}
        <a href="#"
            target="_blank" rel="noopener noreferrer"
            class="group inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 border border-primary hover:bg-dark-sage/40 hover:border-dark-sage transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-facebook h-4 w-4 md:h-5 md:w-5 group-hover:text-dark-sage">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
            </svg>
        </a>
        <a href="#"
            target="_blank" rel="noopener noreferrer"
            class="group inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 border border-primary hover:bg-dark-sage/40 hover:border-dark-sage transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-twitter h-4 w-4 md:h-5 md:w-5 group-hover:text-dark-sage">
                <path
                    d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z">
                </path>
            </svg>
        </a>
        <a href="#"
            target="_blank" rel="noopener noreferrer"
            class="group inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 border border-primary hover:bg-dark-sage/40 hover:border-dark-sage transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-share2 h-4 w-4 md:h-5 md:w-5 group-hover:text-dark-sage">
                <circle cx="18" cy="5" r="3"></circle>
                <circle cx="6" cy="12" r="3"></circle>
                <circle cx="18" cy="19" r="3"></circle>
                <line x1="8.59" x2="15.42" y1="13.51" y2="17.49"></line>
                <line x1="15.41" x2="8.59" y1="6.51" y2="10.49"></line>
            </svg>
        </a>
        <a href="#" target="_blank" rel="noopener noreferrer"
            class="group inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 border border-primary hover:bg-dark-sage/40 hover:border-dark-sage transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round"
                class="lucide lucide-instagram h-4 w-4 md:h-5 md:w-5 group-hover:text-dark-sage">
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
            </svg>
        </a>
    </div>

    {{-- Content --}}
    <livewire:content-view :content="$article->content" />

</div>

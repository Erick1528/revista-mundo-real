<div class=" px-[120px] max-w-7xl mx-auto">

    <div class=" pt-12 pb-6">

        <p
            class="inline-block px-3 py-1 bg-dark-sage text-gray-super-light text-xs font-sans uppercase tracking-wider font-semibold mb-3 md:mb-4">
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

</div>

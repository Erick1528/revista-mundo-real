<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Favicon y logos desde public/build/assets/ --}}
    <link rel="icon" href="{{ asset('build/assets/logosquare.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('build/assets/logo.svg') }}" sizes="180x180">

    {{-- Fuentes de Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&display=swap"
        rel="stylesheet">

    {{-- Open Sans Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&display=swap"
        rel="stylesheet">

    <title>Revista Mundo Real - @yield('title')</title>

    {{-- SEO Meta Tags --}}
    <meta name="description" content="@yield('description', 'Revista Mundo Real - Revista internacional con presencia en Honduras, España y Estados Unidos. Información, cultura y estilo de vida.')">
    <meta name="keywords" content="@yield('keywords', 'revista, mundo real, internacional, Honduras, España, Estados Unidos, cultura, destinos, gastronomía')">
    <meta name="author" content="Revista Mundo Real">
    <meta name="robots" content="index, follow">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="@yield('og_title', 'Revista Mundo Real')">
    <meta property="og:description" content="@yield('og_description', 'Revista internacional con presencia en Honduras, España y Estados Unidos. Información, cultura y estilo de vida.')">
    <meta property="og:image" content="@yield('og_image', asset('build/assets/logo.svg'))">
    @hasSection('og_image_width')
    <meta property="og:image:width" content="@yield('og_image_width')">
    @endif
    @hasSection('og_image_height')
    <meta property="og:image:height" content="@yield('og_image_height')">
    @endif
    @hasSection('og_image_type')
    <meta property="og:image:type" content="@yield('og_image_type')">
    @endif
    @hasSection('og_image_alt')
    <meta property="og:image:alt" content="@yield('og_image_alt')">
    @endif
    @hasSection('og_image_secure_url')
    <meta property="og:image:secure_url" content="@yield('og_image_secure_url')">
    @endif
    <meta property="og:site_name" content="Revista Mundo Real">
    <meta property="og:locale" content="es_ES">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'Revista Mundo Real')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Revista internacional con presencia en Honduras, España y Estados Unidos. Información, cultura y estilo de vida.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('build/assets/logo.svg'))">
    @hasSection('twitter_image_alt')
    <meta name="twitter:image:alt" content="@yield('twitter_image_alt')">
    @endif

    {{-- Canonical URL (sin query string) --}}
    <link rel="canonical" href="{{ request()->url() }}">

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="min-h-screen grid grid-rows-[auto_1fr_auto]">

    <header>
        {{-- Componente de header y navegación --}}
        <livewire:nav-bar />
        @yield('hero')
    </header>

    {{-- Navegación del panel (dashboard, papelera, portadas, temas sugeridos, perfil) --}}
    @auth
        @if(request()->routeIs('dashboard*', 'profile', 'cover.*', 'suggested-topics.*', 'users.*', 'advertisers.*', 'ads.*', 'articles.create', 'articles.edit'))
            <x-dashboard-nav />
        @endif
    @endauth

    <main class="@yield('bg-content', 'bg-sage')">
        {{-- Contenido principal --}}
        @yield('content')
    </main>


    {{-- Componente de footer --}}
    <livewire:footer />

    {{-- Modal de Login --}}
    <livewire:login />

    {{-- Scripts --}}
    @livewireScripts
    @stack('scripts')

</body>

</html>

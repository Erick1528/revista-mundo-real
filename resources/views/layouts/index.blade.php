<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

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

    @stack('head')

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="min-h-screen grid grid-rows-[auto_1fr_auto]">

    <header>
        {{-- Componente de header y navegación --}}
        <livewire:nav-bar />
        @yield('hero')
    </header>

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

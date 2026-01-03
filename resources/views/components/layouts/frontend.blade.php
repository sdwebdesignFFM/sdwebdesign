<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}

    {{-- Favicons --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="canonical" href="{{ url()->current() }}" />

    {{-- hreflang Tags for SEO --}}
    <link rel="alternate" hreflang="de" href="{{ alternate_locale_url('de') }}" />
    <link rel="alternate" hreflang="en" href="{{ alternate_locale_url('en') }}" />
    <link rel="alternate" hreflang="x-default" href="{{ alternate_locale_url('de') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="font-sans antialiased bg-background text-foreground">
    {{-- Skip Link for Keyboard Navigation (WCAG 2.1 Level A - 2.4.1) --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[60] focus:px-4 focus:py-2 focus:bg-foreground focus:text-background focus:outline-none focus:ring-2 focus:ring-accent">
        {{ __('accessibility.skip_to_content') }}
    </a>

    <div class="min-h-screen flex flex-col">
        <x-frontend.header />

        <main id="main-content" class="flex-1" role="main">
            {{ $slot }}
        </main>

        <x-frontend.footer />

        {{-- Global Contact Modal --}}
        <livewire:contact-modal />
    </div>

    @livewireScripts
    <script>
        // Disable Livewire's built-in 419 dialog and auto-refresh instead
        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status === 419) {
                        preventDefault();
                        window.location.reload();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

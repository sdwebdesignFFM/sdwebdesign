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

    <link rel="canonical" href="{{ url()->current() }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="font-sans antialiased bg-background text-foreground">
    <div class="min-h-screen flex flex-col">
        <x-frontend.header />

        <main class="flex-1">
            {{ $slot }}
        </main>

        <x-frontend.footer />
    </div>

    @livewireScripts
    <script>
        // Auto-refresh on session expiry instead of showing dialog
        document.addEventListener('livewire:init', () => {
            Livewire.hook('request', ({ fail }) => {
                fail(({ status }) => {
                    if (status === 419) {
                        // Session expired - silently refresh the page
                        window.location.reload();
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

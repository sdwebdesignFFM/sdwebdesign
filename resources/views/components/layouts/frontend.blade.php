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

    @foreach (($pageSchemas ?? []) as $pageSchema)
        <script type="application/ld+json">{!! json_encode($pageSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endforeach

    {{-- Favicons --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Canonical is emitted by SEOMeta::generate() above; do not duplicate here. --}}

    {{-- hreflang Tags for SEO — only emit when a true equivalent exists --}}
    @php
        $hreflangDe = alternate_locale_url('de', strict: true);
        $hreflangEn = alternate_locale_url('en', strict: true);
    @endphp
    @if ($hreflangDe)
    <link rel="alternate" hreflang="de" href="{{ $hreflangDe }}" />
    @endif
    @if ($hreflangEn)
    <link rel="alternate" hreflang="en" href="{{ $hreflangEn }}" />
    @endif
    @if ($hreflangDe)
    <link rel="alternate" hreflang="x-default" href="{{ $hreflangDe }}" />
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="font-sans antialiased bg-background text-foreground overflow-x-hidden">
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

        {{-- Global Workshop-Request Modal (Discovery-Workshop & future workshops) --}}
        <livewire:workshop-request-modal />
    </div>

    @livewireScripts
    <script>
        // Global delegated handler for any element marked with data-modal-event.
        // Avoids escaping issues that come from inlining JSON inside onclick="…".
        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-modal-event]');
            if (! trigger) return;
            const eventName = trigger.dataset.modalEvent;
            if (! eventName || typeof Livewire === 'undefined') return;
            let payload = null;
            if (trigger.dataset.modalPayload) {
                try { payload = JSON.parse(trigger.dataset.modalPayload); } catch (e) { payload = null; }
            }
            payload ? Livewire.dispatch(eventName, payload) : Livewire.dispatch(eventName);
        });

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

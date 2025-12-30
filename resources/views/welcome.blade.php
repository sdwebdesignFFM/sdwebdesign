<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {!! SEOMeta::generate() !!}
        {!! OpenGraph::generate() !!}
        {!! Twitter::generate() !!}
        {!! JsonLd::generate() !!}

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="text-xl font-bold text-gray-900">
                            SD Webdesign
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-gray-900">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Registrieren</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Professionelles Webdesign
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-blue-100">
                        Wir erstellen moderne, SEO-optimierte Websites fuer Ihr Business
                    </p>
                    <div class="flex justify-center space-x-4">
                        <a href="#kontakt" class="bg-white text-blue-600 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition">
                            Kontakt aufnehmen
                        </a>
                        <a href="#leistungen" class="border-2 border-white text-white px-8 py-3 rounded-md font-semibold hover:bg-white hover:text-blue-600 transition">
                            Unsere Leistungen
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Services Section -->
        <section id="leistungen" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Unsere Leistungen</h2>
                    <p class="text-xl text-gray-600">Alles aus einer Hand - von der Konzeption bis zur Umsetzung</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <article class="bg-gray-50 p-8 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Webdesign</h3>
                        <p class="text-gray-600">Modernes, responsives Design, das auf allen Geraeten perfekt aussieht.</p>
                    </article>
                    <article class="bg-gray-50 p-8 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Webentwicklung</h3>
                        <p class="text-gray-600">Massgeschneiderte Webanwendungen mit Laravel und modernsten Technologien.</p>
                    </article>
                    <article class="bg-gray-50 p-8 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">SEO Optimierung</h3>
                        <p class="text-gray-600">Suchmaschinenoptimierung fuer bessere Rankings und mehr Sichtbarkeit.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="kontakt" class="py-20 bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Bereit fuer Ihr Projekt?</h2>
                <p class="text-xl text-gray-300 mb-8">Kontaktieren Sie uns fuer eine unverbindliche Beratung</p>
                <a href="mailto:info@sdwebdesign.test" class="bg-blue-600 text-white px-8 py-3 rounded-md font-semibold hover:bg-blue-700 transition inline-block">
                    Jetzt anfragen
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-800 text-gray-300 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-white font-semibold mb-4">SD Webdesign</h4>
                        <p class="text-sm">Professionelles Webdesign und Webentwicklung fuer Ihr Business.</p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Links</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#leistungen" class="hover:text-white">Leistungen</a></li>
                            <li><a href="#kontakt" class="hover:text-white">Kontakt</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white">Login</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Rechtliches</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="/impressum" class="hover:text-white">Impressum</a></li>
                            <li><a href="/datenschutz" class="hover:text-white">Datenschutz</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm">
                    <p>&copy; {{ date('Y') }} SD Webdesign. Alle Rechte vorbehalten.</p>
                </div>
            </div>
        </footer>
    </body>
</html>

<header
    x-data="{ mobileMenuOpen: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
    :class="{ 'bg-white/95 backdrop-blur-md shadow-sm': scrolled, 'bg-transparent': !scrolled }"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    <div class="max-w-8xl mx-auto px-6">
        <nav class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 border-2 border-foreground flex items-center justify-center group-hover:bg-foreground transition-colors">
                    <span class="text-lg font-bold group-hover:text-background transition-colors">sd</span>
                </div>
                <div class="hidden sm:block">
                    <span class="text-sm font-medium tracking-tight">sdWebdesign</span>
                    <span class="block text-xs text-muted-foreground">Digitale Systeme</span>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ route('solutions') }}" class="text-sm hover:text-accent transition-colors {{ request()->routeIs('solutions*') ? 'text-accent' : '' }}">
                    Lösungen
                </a>
                <a href="{{ route('references') }}" class="text-sm hover:text-accent transition-colors {{ request()->routeIs('references*') ? 'text-accent' : '' }}">
                    Referenzen
                </a>
                <a href="{{ route('about') }}" class="text-sm hover:text-accent transition-colors {{ request()->routeIs('about') ? 'text-accent' : '' }}">
                    Über uns
                </a>
                <a href="{{ route('blog') }}" class="text-sm hover:text-accent transition-colors {{ request()->routeIs('blog*') ? 'text-accent' : '' }}">
                    Blog
                </a>
            </div>

            {{-- CTA Button --}}
            <div class="hidden lg:block">
                <a href="{{ route('contact') }}" class="btn-primary text-sm py-3 px-6">
                    Projekt anfragen
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden p-2 -mr-2"
                aria-label="Menü öffnen"
            >
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="20" y1="12" y2="12"/>
                    <line x1="4" x2="20" y1="6" y2="6"/>
                    <line x1="4" x2="20" y1="18" y2="18"/>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </nav>
    </div>

    {{-- Mobile Menu --}}
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        x-cloak
        class="lg:hidden border-t border-border bg-white"
    >
        <div class="max-w-8xl mx-auto px-6 py-6 space-y-4">
            <a href="{{ route('solutions') }}" class="block py-3 text-lg border-b border-border hover:text-accent transition-colors">
                Lösungen
            </a>
            <a href="{{ route('references') }}" class="block py-3 text-lg border-b border-border hover:text-accent transition-colors">
                Referenzen
            </a>
            <a href="{{ route('about') }}" class="block py-3 text-lg border-b border-border hover:text-accent transition-colors">
                Über uns
            </a>
            <a href="{{ route('blog') }}" class="block py-3 text-lg border-b border-border hover:text-accent transition-colors">
                Blog
            </a>
            <a href="{{ route('contact') }}" class="block w-full btn-primary text-center mt-6">
                Projekt anfragen
            </a>
        </div>
    </div>
</header>

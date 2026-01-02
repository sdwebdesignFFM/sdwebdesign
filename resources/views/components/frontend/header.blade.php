<header
    x-data="{
        mobileMenuOpen: false,
        scrolled: false,
        mobileFocusIndex: -1,
        mobileMenuItems: [],
        initMobileMenu() {
            this.$nextTick(() => {
                this.mobileMenuItems = [...this.$refs.mobileMenuContent.querySelectorAll('a')];
                this.mobileFocusIndex = -1;
            });
        },
        focusMobileNext() {
            if (this.mobileMenuItems.length === 0) return;
            this.mobileFocusIndex = (this.mobileFocusIndex + 1) % this.mobileMenuItems.length;
            this.mobileMenuItems[this.mobileFocusIndex]?.focus();
        },
        focusMobilePrev() {
            if (this.mobileMenuItems.length === 0) return;
            this.mobileFocusIndex = this.mobileFocusIndex <= 0 ? this.mobileMenuItems.length - 1 : this.mobileFocusIndex - 1;
            this.mobileMenuItems[this.mobileFocusIndex]?.focus();
        },
        closeMobileMenu() {
            this.mobileMenuOpen = false;
            this.$refs.mobileMenuButton.focus();
        }
    }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 })"
    :class="{ 'bg-white/95 backdrop-blur-md shadow-sm': scrolled, 'bg-transparent': !scrolled }"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    <div class="max-w-8xl mx-auto px-6">
        <nav class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ localized_route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 border-2 border-foreground flex items-center justify-center group-hover:bg-foreground transition-colors">
                    <span class="text-lg font-bold group-hover:text-background transition-colors">sd</span>
                </div>
                <div class="hidden sm:block">
                    <span class="text-sm font-medium tracking-tight">sdWebdesign</span>
                    <span class="block text-xs text-muted-foreground">{{ __('navigation.tagline') }}</span>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-8">
                <x-frontend.mega-menu />
                <a href="{{ localized_route('references') }}" class="text-sm hover:text-accent transition-colors {{ request()->routeIs('*.references*') ? 'text-accent' : '' }}">
                    {{ __('navigation.references') }}
                </a>
                <a href="{{ localized_route('about') }}" class="text-sm hover:text-accent transition-colors {{ request()->routeIs('*.about') ? 'text-accent' : '' }}">
                    {{ __('navigation.about') }}
                </a>
                <a href="{{ localized_route('guides') }}" class="text-sm hover:text-accent transition-colors {{ request()->routeIs('*.guides*') || request()->routeIs('*.guide.*') ? 'text-accent' : '' }}">
                    {{ app()->getLocale() === 'de' ? 'Ratgeber' : 'Guides' }}
                </a>

                {{-- Language Switcher --}}
                <x-frontend.language-switcher />
            </div>

            {{-- CTA Button --}}
            <div class="hidden lg:block">
                <a href="{{ localized_route('contact') }}" class="btn-primary text-sm py-3 px-6">
                    {{ __('navigation.cta') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button
                x-ref="mobileMenuButton"
                @click="mobileMenuOpen = !mobileMenuOpen; if(mobileMenuOpen) initMobileMenu()"
                @keydown.arrow-down.prevent="mobileMenuOpen = true; initMobileMenu(); $nextTick(() => focusMobileNext())"
                class="lg:hidden p-2 -mr-2"
                :aria-expanded="mobileMenuOpen.toString()"
                :aria-label="mobileMenuOpen ? '{{ __('accessibility.close_menu') }}' : '{{ __('accessibility.open_menu') }}'"
                aria-controls="mobile-menu"
            >
                <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="4" x2="20" y1="12" y2="12"/>
                    <line x1="4" x2="20" y1="6" y2="6"/>
                    <line x1="4" x2="20" y1="18" y2="18"/>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </nav>
    </div>

    {{-- Mobile Menu --}}
    <nav
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        x-cloak
        id="mobile-menu"
        class="lg:hidden border-t border-border bg-white"
        aria-label="{{ app()->getLocale() === 'de' ? 'Mobile Navigation' : 'Mobile navigation' }}"
        @keydown.arrow-down.prevent="focusMobileNext()"
        @keydown.arrow-up.prevent="focusMobilePrev()"
        @keydown.escape.prevent="closeMobileMenu()"
        @keydown.home.prevent="mobileFocusIndex = 0; mobileMenuItems[0]?.focus()"
        @keydown.end.prevent="mobileFocusIndex = mobileMenuItems.length - 1; mobileMenuItems[mobileFocusIndex]?.focus()"
    >
        <div x-ref="mobileMenuContent" class="max-w-8xl mx-auto px-6 py-6 space-y-4">
            <a href="{{ localized_route('solutions') }}" class="block py-3 text-lg border-b border-border hover:text-accent transition-colors">
                {{ __('navigation.solutions') }}
            </a>
            <a href="{{ localized_route('references') }}" class="block py-3 text-lg border-b border-border hover:text-accent transition-colors">
                {{ __('navigation.references') }}
            </a>
            <a href="{{ localized_route('about') }}" class="block py-3 text-lg border-b border-border hover:text-accent transition-colors">
                {{ __('navigation.about') }}
            </a>
            <a href="{{ localized_route('guides') }}" class="block py-3 text-lg border-b border-border hover:text-accent transition-colors">
                {{ app()->getLocale() === 'de' ? 'Ratgeber' : 'Guides' }}
            </a>

            {{-- Mobile Language Switcher --}}
            <div class="flex items-center gap-4 py-3 border-b border-border">
                @foreach (['de' => 'Deutsch', 'en' => 'English'] as $locale => $label)
                    <a
                        href="{{ alternate_locale_url($locale) }}"
                        class="text-sm {{ app()->getLocale() === $locale ? 'font-bold text-accent' : 'text-muted-foreground hover:text-foreground' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <a href="{{ localized_route('contact') }}" class="block w-full btn-primary text-center mt-6">
                {{ __('navigation.cta') }}
            </a>
        </div>
    </nav>
</header>

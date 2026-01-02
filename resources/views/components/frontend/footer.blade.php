<footer class="border-t border-border bg-muted/30">
    <div class="max-w-[1400px] mx-auto px-6 py-20">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
            {{-- Company Info --}}
            <div class="lg:col-span-1">
                <a href="{{ localized_route('home') }}" class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 border-2 border-foreground flex items-center justify-center">
                        <span class="text-lg font-bold">sd</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium tracking-tight">{{ $settings->company_name ?? 'sdWebdesign' }}</span>
                        <span class="block text-xs text-muted-foreground">{{ $settings->tagline ?? __('navigation.tagline') }}</span>
                    </div>
                </a>
                <p class="text-sm text-muted-foreground leading-relaxed mb-6">
                    {{ __('footer.tagline') }}
                </p>
                <div class="space-y-2 text-sm text-muted-foreground">
                    <p>{{ $settings->city ?? 'Frankfurt am Main' }}</p>
                    <x-frontend.obfuscated-contact
                        type="email"
                        :value="$settings->email ?? 'info@sdwebdesign.de'"
                        :showIcon="false"
                        class="block hover:text-foreground transition-colors"
                    />
                    @if($settings->phone ?? false)
                    <x-frontend.obfuscated-contact
                        type="phone"
                        :value="$settings->phone"
                        :showIcon="false"
                        class="block hover:text-foreground transition-colors"
                    />
                    @endif
                </div>

                {{-- Social Media Icons --}}
                @if(($settings->linkedin_url ?? false) || ($settings->xing_url ?? false) || ($settings->instagram_url ?? false) || ($settings->github_url ?? false))
                <div class="flex gap-4 mt-6">
                    @if($settings->linkedin_url ?? false)
                    <a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="text-muted-foreground hover:text-foreground transition-colors" aria-label="{{ __('accessibility.linkedin') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    @endif
                    @if($settings->xing_url ?? false)
                    <a href="{{ $settings->xing_url }}" target="_blank" rel="noopener noreferrer" class="text-muted-foreground hover:text-foreground transition-colors" aria-label="{{ __('accessibility.xing') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M18.188 0c-.517 0-.741.325-.927.66 0 0-7.455 13.224-7.702 13.657.015.024 4.919 9.023 4.919 9.023.17.308.436.66.967.66h3.454c.211 0 .375-.078.463-.22.089-.151.089-.346-.009-.536l-4.879-8.916c-.004-.006-.004-.016 0-.022L22.139.756c.095-.191.097-.387.006-.535C22.056.078 21.894 0 21.686 0h-3.498zM3.648 4.74c-.211 0-.385.074-.473.216-.09.149-.078.339.02.531l2.34 4.05c.004.01.004.016 0 .021L1.86 16.026c-.099.188-.093.381 0 .529.085.142.239.234.45.234h3.461c.518 0 .766-.348.945-.667l3.734-6.609-2.378-4.155c-.172-.315-.434-.659-.962-.659H3.648v.041z"/>
                        </svg>
                    </a>
                    @endif
                    @if($settings->instagram_url ?? false)
                    <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" class="text-muted-foreground hover:text-foreground transition-colors" aria-label="{{ __('accessibility.instagram') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    @endif
                    @if($settings->github_url ?? false)
                    <a href="{{ $settings->github_url }}" target="_blank" rel="noopener noreferrer" class="text-muted-foreground hover:text-foreground transition-colors" aria-label="{{ __('accessibility.github') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                    @endif
                </div>
                @endif
            </div>

            {{-- Lösungen / Solutions --}}
            <div>
                <h4 class="text-sm font-medium uppercase tracking-wider mb-6">{{ __('navigation.solutions') }}</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ localized_route('solutions.show', ['slug' => 'digitale-plattformen']) }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ app()->getLocale() === 'de' ? 'Digitale Plattformen' : 'Digital Platforms' }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('solutions.show', ['slug' => 'prozessdigitalisierung']) }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ app()->getLocale() === 'de' ? 'Prozessdigitalisierung' : 'Process Digitization' }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('solutions.show', ['slug' => 'api-integration']) }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ app()->getLocale() === 'de' ? 'API-Integration' : 'API Integration' }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('solutions.show', ['slug' => 'e-commerce']) }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            E-Commerce
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('solutions.show', ['slug' => 'ios-app-entwicklung']) }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ app()->getLocale() === 'de' ? 'iOS App Entwicklung' : 'iOS App Development' }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Unternehmen / Company --}}
            <div>
                <h4 class="text-sm font-medium uppercase tracking-wider mb-6">{{ app()->getLocale() === 'de' ? 'Unternehmen' : 'Company' }}</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ localized_route('about') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ __('navigation.about') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('references') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ __('navigation.references') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('guides') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ app()->getLocale() === 'de' ? 'Ratgeber' : 'Guides' }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('maintenance') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ app()->getLocale() === 'de' ? 'Betrieb & Wartung' : 'Hosting & Maintenance' }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('contact') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ __('navigation.contact') }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Rechtliches & Regionen --}}
            <div>
                <h4 class="text-sm font-medium uppercase tracking-wider mb-6">{{ __('footer.legal') }}</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ localized_route('imprint') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ __('navigation.imprint') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ localized_route('privacy') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                            {{ __('navigation.privacy') }}
                        </a>
                    </li>
                </ul>

                {{-- Lokale Expertise --}}
                @if(app()->getLocale() === 'de')
                <div class="mt-8">
                    <a href="/in/" class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                        <x-frontend.icon name="map-pin" class="w-4 h-4" />
                        Lokale Expertise im Rhein-Main-Gebiet
                    </a>
                </div>
                @endif

                {{-- Öffnungszeiten / Business Hours --}}
                @if($settings->business_hours ?? false)
                <div class="mt-8">
                    <h4 class="text-sm font-medium uppercase tracking-wider mb-3">{{ app()->getLocale() === 'de' ? 'Erreichbarkeit' : 'Availability' }}</h4>
                    <p class="text-sm text-muted-foreground">{{ $settings->business_hours }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="mt-16 pt-8 border-t border-border flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm text-muted-foreground">
                &copy; {{ date('Y') }} {{ $settings->company_name ?? 'sdWebdesign' }}. {{ __('footer.copyright') }}
            </p>
            <p class="text-sm text-muted-foreground">
                {{ app()->getLocale() === 'de' ? 'Technisch sauber. Zukunftssicher. Wartbar.' : 'Clean code. Future-proof. Maintainable.' }}
            </p>
        </div>
    </div>
</footer>

<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $challenge = $page->getSection('challenge');
        $approach = $page->getSection('approach');
        $capabilities = $page->getSection('capabilities', []);
        $technical = $page->getSection('technical', []);
        $useCases = $page->getSection('use_cases', []);
        $benefits = $page->getSection('benefits', []);
        $cta = $page->getSection('cta');
    @endphp

    {{-- Breadcrumb --}}
    <section class="pt-24 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6 py-6">
            <a href="{{ route('solutions') }}" class="flex items-center gap-2 text-[0.875rem] text-muted-foreground hover:text-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Zurück zu allen Lösungen
            </a>
        </div>
    </section>

    {{-- Hero Section --}}
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up">
                    <div class="flex items-start gap-6 mb-8">
                        <div class="p-6 border-2 border-foreground bg-white shrink-0">
                            <x-frontend.icon :name="$hero['icon'] ?? 'code'" class="w-12 h-12" />
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-4">
                                <span class="text-[0.875rem] font-mono text-muted-foreground">
                                    {{ $hero['number'] ?? '01' }}
                                </span>
                                <div class="h-px flex-1 bg-border"></div>
                            </div>
                            <h1 class="mb-4">{{ $page->title }}</h1>
                            @if($hero['tagline'] ?? false)
                            <p class="text-[1.25rem] text-muted-foreground leading-relaxed">
                                {{ $hero['tagline'] }}
                            </p>
                            @endif
                        </div>
                    </div>

                    @if($hero['description'] ?? false)
                    <p class="text-[1.0625rem] leading-relaxed max-w-[900px]">
                        {{ $hero['description'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Challenge & Approach --}}
    @if(($challenge ?? false) || ($approach ?? false))
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="grid md:grid-cols-2 gap-8">
                    @if($challenge ?? false)
                    <div class="motion motion-fade-up p-8 border-l-4 border-red-500 bg-red-50/30">
                        <h2 class="mb-4 text-[1.125rem] flex items-center gap-2">
                            <span class="text-red-500">⚠</span>
                            {{ $challenge['title'] ?? 'Herausforderung' }}
                        </h2>
                        <p class="text-[0.9375rem] leading-relaxed text-muted-foreground">
                            {{ $challenge['text'] }}
                        </p>
                    </div>
                    @endif

                    @if($approach ?? false)
                    <div class="motion motion-fade-up motion-delay-1 p-8 border-l-4 border-green-500 bg-green-50/30">
                        <h2 class="mb-4 text-[1.125rem] flex items-center gap-2">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-green-500" />
                            {{ $approach['title'] ?? 'Unser Ansatz' }}
                        </h2>
                        <p class="text-[0.9375rem] leading-relaxed text-muted-foreground">
                            {{ $approach['text'] }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Capabilities & Technical Stack --}}
    @if(count($capabilities) > 0 || count($technical) > 0)
    <section class="py-20 border-t border-border bg-gradient-to-b from-muted/5 to-transparent">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="grid lg:grid-cols-2 gap-12">
                    @if(count($capabilities) > 0)
                    <div class="motion motion-fade-up">
                        <div class="flex items-center gap-3 mb-8">
                            <x-frontend.icon name="settings" class="w-5 h-5 text-muted-foreground" />
                            <h2 class="text-[1.125rem]">Systemfähigkeiten</h2>
                        </div>
                        <div class="space-y-3">
                            @foreach($capabilities as $index => $capability)
                            <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex items-start gap-3 p-4 border border-border hover:border-foreground transition-all hover:shadow-md bg-white">
                                <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                                <span class="text-[0.9375rem]">{{ $capability }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(count($technical) > 0)
                    <div class="motion motion-fade-up motion-delay-1">
                        <div class="flex items-center gap-3 mb-8">
                            <x-frontend.icon name="database" class="w-5 h-5 text-muted-foreground" />
                            <h2 class="text-[1.125rem]">Technischer Stack</h2>
                        </div>
                        <div class="space-y-3">
                            @foreach($technical as $index => $tech)
                            <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex items-start gap-3 p-4 bg-muted/20 border-l-2 border-foreground">
                                <span class="text-accent mt-0.5">→</span>
                                <span class="text-[0.875rem] font-mono">{{ $tech }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Use Cases & Benefits --}}
    @if(count($useCases) > 0 || count($benefits) > 0)
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="grid md:grid-cols-2 gap-12">
                    @if(count($useCases) > 0)
                    <div class="motion motion-fade-up">
                        <h2 class="mb-6 text-[1.125rem]">Typische Anwendungsfälle</h2>
                        <ul class="space-y-3">
                            @foreach($useCases as $useCase)
                            <li class="flex items-start gap-3 text-[0.9375rem]">
                                <x-frontend.icon name="arrow-right" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                                <span>{{ $useCase }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(count($benefits) > 0)
                    <div class="motion motion-fade-up motion-delay-1">
                        <h2 class="mb-6 text-[1.125rem]">Ihre Vorteile</h2>
                        <ul class="space-y-3">
                            @foreach($benefits as $benefit)
                            <li class="flex items-start gap-3 text-[0.9375rem]">
                                <x-frontend.icon name="zap" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                                <span>{{ $benefit }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    @php
        $settings = \App\Models\Setting::instance();
    @endphp
    <section class="py-20 border-t border-border bg-accent/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up border border-border bg-white overflow-hidden">
                    <div class="p-8 md:p-10 flex flex-col md:flex-row gap-8">
                        {{-- Photo --}}
                        @if($settings->cta_image)
                        <div class="shrink-0">
                            <img
                                src="{{ Storage::url($settings->cta_image) }}"
                                alt="{{ $settings->cta_name ?? $settings->owner_name }}"
                                class="w-40 h-48 object-cover object-top border-2 border-foreground"
                            />
                        </div>
                        @endif

                        {{-- Content --}}
                        <div class="flex-1">
                            <span class="text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-2 block">Ihr Ansprechpartner</span>
                            <h2 class="text-[1.75rem] font-medium mb-1">
                                {{ $settings->cta_name ?? $settings->owner_name }}
                            </h2>
                            <p class="text-[0.9375rem] text-muted-foreground mb-6">{{ $settings->cta_role ?? 'Geschäftsführer' }}</p>

                            <p class="text-[1rem] text-muted-foreground leading-relaxed mb-6 max-w-[600px]">
                                {{ $settings->cta_subtitle ?? 'Ich berate Sie persönlich zu Ihrem Projekt – ehrlich, technisch fundiert und ohne Verkaufsdruck. Gemeinsam finden wir heraus, ob und wie wir Ihre Anforderungen sinnvoll umsetzen können.' }}
                            </p>

                            {{-- Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button
                                    type="button"
                                    onclick="Livewire.dispatch('openContactModal')"
                                    class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all text-[0.9375rem]"
                                >
                                    Jetzt Kontakt aufnehmen
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                                    </svg>
                                </button>
                                @if($settings->phone)
                                <a href="tel:{{ $settings->phone }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 border border-foreground text-foreground hover:bg-foreground hover:text-background transition-all text-[0.9375rem]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    Direkt anrufen
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Modal --}}
    <livewire:contact-modal />

    {{-- Related Solutions --}}
    @if($otherSolutions->count() > 0)
    <section class="py-20 border-t border-border bg-gradient-to-b from-muted/5 to-transparent">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="motion motion-fade-up mb-12">
                <h2 class="text-[1.5rem] mb-3">Das könnte Sie auch interessieren</h2>
                <p class="text-[1.0625rem] text-muted-foreground">Weitere Lösungen für Ihre digitale Infrastruktur</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($otherSolutions as $index => $solution)
                @php
                    $solutionHero = $solution->getSection('hero');
                @endphp
                <a href="{{ route('solutions.show', $solution->slug) }}"
                   class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }} group block border border-border hover:border-foreground hover:shadow-lg transition-all bg-white">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="p-3 border border-border group-hover:border-foreground transition-all shrink-0">
                                <x-frontend.icon :name="$solutionHero['icon'] ?? 'code'" class="w-6 h-6" />
                            </div>
                            <div class="flex-1">
                                <span class="text-[0.75rem] font-mono text-muted-foreground">{{ $solutionHero['number'] ?? '00' }}</span>
                                <h3 class="text-[1rem] leading-tight group-hover:text-accent transition-colors">
                                    {{ $solution->title }}
                                </h3>
                            </div>
                        </div>
                        @if($solutionHero['tagline'] ?? false)
                        <p class="text-[0.875rem] text-muted-foreground leading-relaxed mb-4">
                            {{ $solutionHero['tagline'] }}
                        </p>
                        @endif
                        <span class="inline-flex items-center gap-2 text-[0.875rem] font-medium group-hover:gap-3 transition-all">
                            Mehr erfahren
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</x-layouts.frontend>

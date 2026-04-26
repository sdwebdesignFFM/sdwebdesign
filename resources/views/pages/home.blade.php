<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $approach = $page->getSection('approach');
        $problem = $page->getSection('problem');
        $solutions = $page->getSection('solutions');
        $principles = $page->getSection('principles');
        $whyUs = $page->getSection('why_us');
        $process = $page->getSection('process');
        $cta = $page->getSection('cta');
        $settings = \App\Models\Setting::instance();
    @endphp

    {{-- Hero Section --}}
    <section class="relative max-w-[1400px] mx-auto px-6 pt-40 pb-40 overflow-hidden">
        {{-- Animated background grid --}}
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative grid lg:grid-cols-2 gap-20 items-center">
            <div class="motion motion-fade-up">
                @if($hero['badge'] ?? false)
                <div class="inline-block px-4 py-2 mb-8 border border-border bg-background/50 backdrop-blur-sm">
                    <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">
                        {{ $hero['badge'] }}
                    </p>
                </div>
                @endif

                <h1 class="mb-8 max-w-[650px] text-[2.75rem] lg:text-[3.25rem] leading-[1.1]">
                    {{ $hero['title'] ?? 'Digitale Lösungen, die Abläufe vereinfachen und Wachstum ermöglichen' }}
                </h1>

                @if($hero['subtitle'] ?? false)
                <p class="mb-6 max-w-[600px] text-[1.125rem] leading-relaxed text-muted-foreground">
                    {{ $hero['subtitle'] }}
                </p>
                @endif

                @if($hero['tags'] ?? false)
                <div class="mb-12 flex flex-wrap gap-3">
                    @foreach($hero['tags'] as $tag)
                    <span class="px-4 py-2 text-[0.875rem] border border-border hover:border-foreground transition-colors">
                        {{ $tag }}
                    </span>
                    @endforeach
                </div>
                @endif

                <div class="flex flex-wrap gap-4">
                    <button
                        type="button"
                        onclick="Livewire.dispatch('openContactModal')"
                        class="group px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all flex items-center gap-2"
                    >
                        {{ $hero['cta_primary_text'] ?? 'Projekt besprechen' }}
                        <span class="inline-block animate-bounce-x">→</span>
                    </button>
                    <a href="{{ localized_route('solutions') }}" class="px-8 py-4 border border-border hover:border-foreground transition-colors">
                        {{ $hero['cta_secondary_text'] ?? 'Lösungen entdecken' }}
                    </a>
                </div>
            </div>

            {{-- Technical Architecture Visualization --}}
            <div class="relative hidden lg:block">
                <div class="space-y-6">
                    @php
                        $layers = $hero['layers'] ?? [
                            ['icon' => 'globe', 'label' => 'Frontend Layer', 'desc' => 'React, TypeScript'],
                            ['icon' => 'code', 'label' => 'API Layer', 'desc' => 'REST, GraphQL'],
                            ['icon' => 'layers', 'label' => 'Business Logic', 'desc' => 'Services, Workflows'],
                            ['icon' => 'database', 'label' => 'Data & Integration', 'desc' => 'PostgreSQL, APIs'],
                        ];
                    @endphp

                    @foreach($layers as $index => $layer)
                    <div class="group relative motion motion-fade-left motion-delay-{{ $index + 1 }}">
                        <div class="p-6 border-2 border-border hover:border-foreground transition-all duration-300 bg-background hover:shadow-lg">
                            <div class="flex items-start gap-4">
                                <div class="p-3 border border-border group-hover:border-foreground transition-colors">
                                    <x-frontend.icon :name="$layer['icon']" class="w-6 h-6" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="mb-1 font-mono text-[0.875rem]">{{ $layer['label'] }}</h4>
                                    <p class="text-[0.8125rem] text-muted-foreground">{{ $layer['desc'] }}</p>
                                </div>
                                <div class="text-[1.5rem] font-mono text-muted-foreground/20">
                                    0{{ $index + 1 }}
                                </div>
                            </div>
                        </div>

                        @if($index < 3)
                        <div class="flex justify-center py-2">
                            <div class="text-muted-foreground/40 animate-bounce-y">↓</div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Approach Section — Senior PO claim + lead case teaser --}}
    @if($approach['title'] ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-24 border-t border-border">
        <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-start">

            {{-- Left column: Anspruch + Bio --}}
            <div class="motion motion-fade-up">
                @if($approach['badge'] ?? false)
                <div class="inline-block px-3 py-1.5 mb-6 border border-border">
                    <p class="text-[0.75rem] uppercase tracking-wider text-muted-foreground">{{ $approach['badge'] }}</p>
                </div>
                @endif

                <h2 class="mb-8 text-[1.75rem] leading-tight max-w-[520px]">{{ $approach['title'] }}</h2>

                @if($approach['text'] ?? false)
                <div class="text-[1.0625rem] text-muted-foreground leading-relaxed space-y-4 max-w-[560px]">
                    {!! nl2br(e($approach['text'])) !!}
                </div>
                @endif

                @if(($approach['cta_text'] ?? false) && ($approach['cta_link'] ?? false))
                <a href="{{ $approach['cta_link'] }}"
                   class="inline-flex items-center gap-2 mt-8 text-[0.9375rem] font-medium border-b border-foreground/20 hover:border-foreground transition-colors pb-1">
                    {{ $approach['cta_text'] }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
                @endif
            </div>

            {{-- Right column: lead case teaser --}}
            @if($approach['case_teaser'] ?? false)
            @php $case = $approach['case_teaser']; @endphp
            <div class="motion motion-fade-up motion-delay-1">
                <a href="{{ $case['link'] ?? '#' }}"
                   class="group block p-8 lg:p-10 border-2 border-border hover:border-foreground bg-background transition-all hover:shadow-lg">

                    @if($case['label'] ?? false)
                    <div class="inline-flex items-center gap-2 mb-6 px-2.5 py-1 bg-accent/5 border border-accent/20 rounded-sm">
                        <span class="text-[0.6875rem] text-accent uppercase tracking-wider font-medium">{{ $case['label'] }}</span>
                    </div>
                    @endif

                    @if($case['title'] ?? false)
                    <h3 class="text-[1.375rem] mb-4 leading-tight group-hover:text-accent transition-colors">{{ $case['title'] }}</h3>
                    @endif

                    @if($case['description'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $case['description'] }}
                    </p>
                    @endif

                    @if($case['tags'] ?? false)
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($case['tags'] as $tag)
                        <span class="px-2.5 py-1 text-[0.75rem] font-mono border border-border bg-muted/20">{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif

                    <span class="inline-flex items-center gap-2 text-[0.875rem] font-medium group-hover:gap-3 transition-all">
                        Case Study lesen
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </span>
                </a>
            </div>
            @endif

        </div>
    </section>
    @endif

    {{-- Problem Section --}}
    @if($problem['title'] ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-32 border-t border-border">
        <div class="max-w-[1100px]">
            <div class="motion motion-fade-up">
                <div class="flex items-start gap-4 mb-10">
                    <div class="p-3 border border-border">
                        <x-frontend.icon name="alert-circle" class="w-6 h-6 text-muted-foreground" />
                    </div>
                    <h2 class="flex-1 max-w-[800px]">
                        {{ $problem['title'] }}
                    </h2>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 mb-16">
                {{-- Typische Ausgangssituation --}}
                @if($problem['items'] ?? false)
                <div class="relative motion motion-fade-left motion-delay-2">
                    <div class="absolute -left-6 top-0 bottom-0 w-1 bg-gradient-to-b from-muted-foreground/20 to-transparent"></div>

                    <h4 class="mb-8 text-[0.875rem] uppercase tracking-wider text-muted-foreground flex items-center gap-2">
                        <span class="w-2 h-2 bg-foreground rounded-full"></span>
                        {{ app()->getLocale() === 'en' ? 'Typical Starting Point' : 'Typische Ausgangssituation' }}
                    </h4>

                    <div class="space-y-4">
                        @foreach($problem['items'] as $i => $item)
                        <div class="motion motion-fade-up motion-delay-{{ $i + 3 }}">
                            <div class="group flex items-start justify-between gap-4 p-4 border border-border hover:border-foreground transition-all hover:shadow-md bg-background">
                                <span class="flex-1 text-[0.9375rem]">{{ $item['label'] }}</span>
                                <span class="font-mono text-[1.25rem] text-muted-foreground group-hover:text-foreground transition-colors">
                                    {{ $item['value'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Ergebnis --}}
                @if($problem['results'] ?? false)
                <div class="relative motion motion-fade-right motion-delay-4">
                    <div class="absolute -left-6 top-0 bottom-0 w-1 bg-gradient-to-b from-red-500/20 to-transparent"></div>

                    <h4 class="mb-8 text-[0.875rem] uppercase tracking-wider text-muted-foreground flex items-center gap-2">
                        <x-frontend.icon name="trending-down" class="w-4 h-4" />
                        {{ app()->getLocale() === 'en' ? 'Result' : 'Ergebnis' }}
                    </h4>

                    <div class="space-y-3">
                        @foreach($problem['results'] as $i => $result)
                        <div class="motion motion-fade-up motion-delay-{{ $i + 5 }}">
                            <div class="flex items-start gap-3 p-4 bg-red-500/10 border border-red-500/20 hover:border-red-500/30 transition-colors">
                                <span class="text-red-400 mt-1">×</span>
                                <span class="text-[0.9375rem]">{{ $result }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Unser Ansatz --}}
            @if($problem['approach'] ?? false)
            <div class="motion motion-fade-up motion-delay-8">
                <div class="relative mt-20 p-8 border-2 border-foreground bg-foreground/[0.02]">
                    <div class="absolute -top-3 left-8 px-3 bg-background">
                        <span class="text-[0.75rem] uppercase tracking-wider text-muted-foreground">
                            {{ app()->getLocale() === 'en' ? 'Our Approach' : 'Unser Ansatz' }}
                        </span>
                    </div>
                    <p class="text-[1.25rem] leading-relaxed">
                        {!! $problem['approach'] !!}
                    </p>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Solutions Section - Compact Overview --}}
    <section id="loesungen" class="max-w-[1400px] mx-auto px-6 py-32 border-t border-border">
        <div class="max-w-[1200px]">
            {{-- Header --}}
            <div class="motion motion-fade-up mb-16">
                @if($solutions['badge'] ?? false)
                <div class="inline-block px-4 py-2 mb-6 border border-border">
                    <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">{{ $solutions['badge'] }}</p>
                </div>
                @endif

                <h2 class="mb-6">{{ $solutions['title'] ?? 'Unsere Lösungen – vom Einstieg bis zum digitalen System' }}</h2>

                @if($solutions['subtitle'] ?? false)
                <p class="max-w-[900px] text-[1.0625rem] text-muted-foreground leading-relaxed mb-8">
                    {{ $solutions['subtitle'] }}
                </p>
                @endif

                <a href="{{ localized_route('solutions') }}" class="inline-flex items-center gap-2 text-[0.9375rem] font-medium hover:text-accent transition-colors">
                    {{ app()->getLocale() === 'en' ? 'View all solutions' : 'Alle Lösungen ansehen' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- 4 Primary Solution Accordions --}}
            @php
                $solutionAccordions = $solutions['accordions'] ?? [
                    [
                        'number' => '01',
                        'icon' => 'layout-dashboard',
                        'title' => 'Digitale Plattformen & Webanwendungen',
                        'subtitle' => 'Zentrale Systeme für Prozesse, Daten und Zusammenarbeit',
                        'description' => 'Wenn Standardsoftware an Grenzen stößt, entstehen individuelle Plattformen. Wir entwickeln Webanwendungen, die Geschäftslogik, Daten und Nutzerrollen zentral abbilden – als maßgeschneiderte Werkzeuge für Ihre Abläufe.',
                        'suitable_for' => ['Interne Tools & Verwaltungsplattformen', 'Kunden- & Partnerportale', 'Individuelle Geschäftslogik & Workflows'],
                        'character' => ['Maßgeschneidert auf Ihre Abläufe', 'Saubere Trennung von Oberfläche, Logik & Daten', 'Skalierbar und langfristig wartbar'],
                        'link' => '/loesungen/plattformen',
                    ],
                    [
                        'number' => '02',
                        'icon' => 'shopping-cart',
                        'title' => 'E-Commerce & Online-Shops',
                        'subtitle' => 'Verkaufen – integriert, performant und erweiterbar',
                        'description' => 'Ein Shop ist mehr als ein Produktkatalog. Wir entwickeln E-Commerce-Lösungen, die zuverlässig funktionieren, Prozesse vereinfachen und sich sauber in bestehende Systeme integrieren lassen.',
                        'suitable_for' => ['B2C-Online-Shops', 'B2B-Bestellplattformen', 'Integrierte Shop- & Warenwirtschaftslösungen'],
                        'character' => ['Technische Substanz statt Feature-Overload', 'Skalierbar bei Wachstum', 'Fokus auf Performance & Wartbarkeit'],
                        'link' => '/loesungen/e-commerce',
                    ],
                    [
                        'number' => '03',
                        'icon' => 'device-phone-mobile',
                        'title' => 'Mobile Anwendungen (iOS / Android / PWA)',
                        'subtitle' => 'Mobile Erweiterungen bestehender Systeme',
                        'description' => 'Mobile Anwendungen entfalten ihren Wert, wenn sie Teil eines bestehenden Systems sind. Wir entwickeln mobile Lösungen, die Webanwendungen, Plattformen oder Shops sinnvoll ergänzen – nicht ersetzen.',
                        'suitable_for' => ['Native iOS- oder Android-Apps', 'Progressive Web Apps (PWA)', 'Mobile Companion- & Service-Apps'],
                        'character' => ['Integration statt Insellösung', 'Gemeinsame Datenbasis & Logik', 'Schrittweise ausbaubar'],
                        'link' => '/loesungen/mobile-anwendungen',
                    ],
                    [
                        'number' => '04',
                        'icon' => 'globe',
                        'title' => 'Unternehmenswebsites mit Substanz',
                        'subtitle' => 'Professionelle Webauftritte, die heute passen – und morgen mitwachsen',
                        'description' => 'Eine Unternehmenswebsite ist oft der erste Kontaktpunkt mit Ihrem Unternehmen. Sie soll Vertrauen schaffen, Inhalte klar vermitteln und technisch zuverlässig funktionieren – ohne unnötige Komplexität, aber mit einer Basis, die spätere Erweiterungen ermöglicht.',
                        'suitable_for' => ['Unternehmenswebsites & Leistungsseiten', 'Relaunch bestehender Websites', 'SEO-orientierte Content-Strukturen'],
                        'character' => ['Klarer Einstieg mit überschaubarem Budget', 'Sauber umgesetzt, performant & wartbar', 'Erweiterbar Richtung Shop, Portal oder Plattform'],
                        'link' => '/loesungen/websites',
                    ],
                ];
            @endphp

            <div
                class="space-y-4 mb-16"
                x-data="{
                    openAccordion: 0,
                    accordionCount: {{ count($solutionAccordions) }},
                    focusAccordion(index) {
                        const button = document.getElementById('accordion-header-' + index);
                        if (button) button.focus();
                    },
                    nextAccordion(currentIndex) {
                        const next = (currentIndex + 1) % this.accordionCount;
                        this.focusAccordion(next);
                    },
                    prevAccordion(currentIndex) {
                        const prev = currentIndex <= 0 ? this.accordionCount - 1 : currentIndex - 1;
                        this.focusAccordion(prev);
                    },
                    firstAccordion() {
                        this.focusAccordion(0);
                    },
                    lastAccordion() {
                        this.focusAccordion(this.accordionCount - 1);
                    }
                }"
                role="region"
                aria-label="{{ app()->getLocale() === 'en' ? 'Solution areas' : 'Lösungsbereiche' }}"
            >
                @foreach($solutionAccordions as $index => $accordion)
                <div class="motion motion-fade-up motion-delay-{{ $index + 1 }}">
                    <div class="border-2 border-border bg-background transition-all" :class="openAccordion === {{ $index }} ? 'border-foreground shadow-xl' : 'hover:border-foreground/50'">
                        {{-- Accordion Header --}}
                        <button
                            type="button"
                            class="w-full p-6 md:p-8 text-left focus:outline-none focus:ring-2 focus:ring-accent focus:ring-inset"
                            @click="openAccordion = openAccordion === {{ $index }} ? null : {{ $index }}"
                            @keydown.arrow-down.prevent="nextAccordion({{ $index }})"
                            @keydown.arrow-up.prevent="prevAccordion({{ $index }})"
                            @keydown.home.prevent="firstAccordion()"
                            @keydown.end.prevent="lastAccordion()"
                            :aria-expanded="openAccordion === {{ $index }} ? 'true' : 'false'"
                            aria-controls="accordion-content-{{ $index }}"
                            id="accordion-header-{{ $index }}"
                        >
                            <div class="flex items-start gap-4 md:gap-6">
                                {{-- Icon Box --}}
                                @if($accordion['icon'] ?? false)
                                <div class="p-4 border-2 border-foreground shrink-0 hidden md:block">
                                    <x-frontend.icon :name="$accordion['icon']" class="w-8 h-8" />
                                </div>
                                @endif

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-[0.875rem] font-mono text-muted-foreground">{{ $accordion['number'] ?? str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <h3 class="text-[1.25rem] md:text-[1.5rem] mb-2">{{ $accordion['title'] }}</h3>
                                    @if($accordion['subtitle'] ?? false)
                                    <p class="text-[0.9375rem] text-muted-foreground">{{ $accordion['subtitle'] }}</p>
                                    @endif
                                </div>

                                {{-- Toggle Icon --}}
                                <div class="shrink-0 p-2">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-muted-foreground transition-transform duration-300"
                                        :class="openAccordion === {{ $index }} ? 'rotate-180' : ''"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        aria-hidden="true"
                                    >
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </div>
                            </div>
                        </button>

                        {{-- Accordion Content --}}
                        <div
                            x-show="openAccordion === {{ $index }}"
                            x-collapse
                            x-cloak
                            id="accordion-content-{{ $index }}"
                            role="region"
                            :aria-labelledby="'accordion-header-{{ $index }}'"
                        >
                            <div class="px-6 md:px-8 pb-8 pt-0 md:pl-[7.5rem]">
                                {{-- Description --}}
                                @if($accordion['description'] ?? false)
                                <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-8">
                                    {{ $accordion['description'] }}
                                </p>
                                @endif

                                {{-- Two Column Lists --}}
                                <div class="grid md:grid-cols-2 gap-8 mb-8">
                                    {{-- Typisch geeignet für --}}
                                    @if($accordion['suitable_for'] ?? false)
                                    <div>
                                        <h4 class="text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-4 flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-foreground rounded-full"></span>
                                            {{ app()->getLocale() === 'en' ? 'Typically suited for' : 'Typisch geeignet für' }}
                                        </h4>
                                        <ul class="space-y-2">
                                            @foreach($accordion['suitable_for'] as $item)
                                            <li class="flex items-start gap-3 text-[0.875rem]">
                                                <span class="text-accent mt-0.5">→</span>
                                                <span>{{ $item }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    {{-- Charakter --}}
                                    @if($accordion['character'] ?? false)
                                    <div>
                                        <h4 class="text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-4 flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-foreground rounded-full"></span>
                                            {{ app()->getLocale() === 'en' ? 'Character' : 'Charakter' }}
                                        </h4>
                                        <ul class="space-y-2">
                                            @foreach($accordion['character'] as $item)
                                            <li class="flex items-start gap-3 text-[0.875rem]">
                                                <span class="text-accent mt-0.5">→</span>
                                                <span>{{ $item }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>

                                {{-- Link --}}
                                @if($accordion['link'] ?? false)
                                <a href="{{ $accordion['link'] }}" class="inline-flex items-center gap-2 text-[0.9375rem] font-medium hover:text-accent transition-colors group">
                                    {{ app()->getLocale() === 'en' ? 'Learn more' : 'Mehr erfahren' }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Secondary Block: Wachstum & Sichtbarkeit (SEO/SEA) --}}
            <div class="motion motion-fade-up motion-delay-5 pt-12 border-t border-dashed border-border">
                <h3 class="text-[1.25rem] mb-3">{{ $solutions['growth_title'] ?? 'Wachstum & Sichtbarkeit' }}</h3>
                <p class="text-[0.9375rem] text-muted-foreground mb-8 max-w-[700px]">
                    {{ $solutions['growth_text'] ?? 'Damit Ihre Lösung nicht nur funktioniert, sondern auch gefunden wird: SEO und SEA als integrierte Wachstumsbausteine – technisch sauber umgesetzt.' }}
                </p>

                <div class="grid md:grid-cols-2 gap-6">
                    {{-- SEO Card --}}
                    <a href="{{ route('de.seo') }}" class="group block p-6 border border-border hover:border-foreground bg-muted/5 hover:bg-background transition-all">
                        <div class="flex items-start gap-4">
                            <div class="p-2 border border-border group-hover:border-foreground transition-colors">
                                <x-frontend.icon name="search" class="w-5 h-5" />
                            </div>
                            <div class="flex-1">
                                <h4 class="text-[1rem] mb-2 group-hover:text-accent transition-colors">{{ app()->getLocale() === 'en' ? 'Search Engine Optimization (SEO)' : 'Suchmaschinenoptimierung (SEO)' }}</h4>
                                <p class="text-[0.875rem] text-muted-foreground leading-relaxed mb-4">
                                    {{ app()->getLocale() === 'en' ? 'Sustainable visibility through technical substance, structure and content systems.' : 'Nachhaltige Sichtbarkeit durch technische Substanz, Struktur und Content-Systeme.' }}
                                </p>
                                <span class="inline-flex items-center gap-2 text-[0.8125rem] font-medium group-hover:gap-3 transition-all">
                                    {{ app()->getLocale() === 'en' ? 'Learn more' : 'Mehr erfahren' }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>

                    {{-- SEA Card --}}
                    <a href="{{ route('de.sea') }}" class="group block p-6 border border-border hover:border-foreground bg-muted/5 hover:bg-background transition-all">
                        <div class="flex items-start gap-4">
                            <div class="p-2 border border-border group-hover:border-foreground transition-colors">
                                <x-frontend.icon name="megaphone" class="w-5 h-5" />
                            </div>
                            <div class="flex-1">
                                <h4 class="text-[1rem] mb-2 group-hover:text-accent transition-colors">{{ app()->getLocale() === 'en' ? 'Search Engine Advertising (SEA)' : 'Suchmaschinenwerbung (SEA)' }}</h4>
                                <p class="text-[0.875rem] text-muted-foreground leading-relaxed mb-4">
                                    {{ app()->getLocale() === 'en' ? 'Targeted reach through structured campaigns, landing pages and clean tracking.' : 'Gezielte Reichweite über strukturierte Kampagnen, Landingpages und sauberes Tracking.' }}
                                </p>
                                <span class="inline-flex items-center gap-2 text-[0.8125rem] font-medium group-hover:gap-3 transition-all">
                                    {{ app()->getLocale() === 'en' ? 'Learn more' : 'Mehr erfahren' }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"/>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Betrieb & Wartung Block --}}
            <div class="motion motion-fade-up motion-delay-6 pt-12 border-t border-dashed border-border">
                <h3 class="text-[1.25rem] mb-3">{{ app()->getLocale() === 'en' ? 'Hosting & Maintenance' : 'Betrieb, Hosting & Wartung' }}</h3>
                <p class="text-[0.9375rem] text-muted-foreground mb-8 max-w-[700px]">
                    {{ app()->getLocale() === 'en' ? 'To keep your digital solution stable, secure and up-to-date – we handle the technical operations.' : 'Damit Ihre digitale Lösung dauerhaft stabil, sicher und aktuell bleibt – übernehmen wir den technischen Betrieb.' }}
                </p>

                <a href="{{ localized_route('maintenance') }}" class="group block p-6 border border-border hover:border-foreground bg-muted/5 hover:bg-background transition-all">
                    <div class="flex items-start gap-4">
                        <div class="p-3 border border-border group-hover:border-foreground transition-colors">
                            <x-frontend.icon name="server-stack" class="w-6 h-6" />
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[0.875rem] font-mono text-muted-foreground">07</span>
                                <h4 class="text-[1rem] group-hover:text-accent transition-colors">{{ app()->getLocale() === 'en' ? 'Reliable operations for your solution' : 'Zuverlässiger Betrieb für Ihre Lösung' }}</h4>
                            </div>
                            <p class="text-[0.875rem] text-muted-foreground leading-relaxed mb-4">
                                {{ app()->getLocale() === 'en' ? 'Updates, monitoring, backups and support – we ensure you can focus on your core business.' : 'Updates, Monitoring, Backups und Support – wir sorgen dafür, dass Sie sich auf Ihr Kerngeschäft konzentrieren können.' }}
                            </p>
                            <div class="flex flex-wrap gap-4 text-[0.8125rem] text-muted-foreground mb-4">
                                <span class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-accent rounded-full"></span>
                                    Managed Hosting
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-accent rounded-full"></span>
                                    Updates & Security
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-accent rounded-full"></span>
                                    Monitoring & Backup
                                </span>
                            </div>
                            <span class="inline-flex items-center gap-2 text-[0.8125rem] font-medium group-hover:gap-3 transition-all">
                                {{ app()->getLocale() === 'en' ? 'Learn more' : 'Mehr erfahren' }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Optional Microcopy CTA --}}
            <div class="motion motion-fade-up motion-delay-7 mt-16 p-8 border-2 border-border bg-muted/5 text-center">
                <p class="text-[1rem] text-muted-foreground mb-6">
                    {{ $solutions['microcopy'] ?? (app()->getLocale() === 'en' ? 'Not sure which entry point makes sense? We help with the assessment – no obligation.' : 'Unsicher, welcher Einstieg sinnvoll ist? Wir helfen bei der Einordnung – unverbindlich.') }}
                </p>
                <button
                    type="button"
                    onclick="Livewire.dispatch('openContactModal')"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-foreground text-background hover:bg-foreground/90 transition-all text-[0.9375rem]"
                >
                    {{ $solutions['microcopy_button'] ?? (app()->getLocale() === 'en' ? 'Discuss project' : 'Projekt besprechen') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    {{-- Technical Principles --}}
    @if($principles['items'] ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-32 border-t border-border">
        <div class="max-w-[1100px]">
            <div class="motion motion-fade-up mb-20">
                @if($principles['badge'] ?? false)
                <div class="inline-block px-4 py-2 mb-6 border border-border bg-gradient-to-r from-muted/20 to-transparent">
                    <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">{{ $principles['badge'] }}</p>
                </div>
                @endif

                <h2 class="mb-6">{{ $principles['title'] ?? 'Wie wir denken' }}</h2>

                @if($principles['subtitle'] ?? false)
                <p class="max-w-[750px] text-[1.0625rem] text-muted-foreground leading-relaxed">
                    {{ $principles['subtitle'] }}
                </p>
                @endif
            </div>

            {{-- Principles Grid --}}
            <div class="grid md:grid-cols-2 gap-8 mb-28">
                @foreach($principles['items'] as $index => $principle)
                <div class="motion motion-fade-up motion-delay-{{ $index + 1 }}">
                    <div class="group relative h-full">
                        <div class="absolute inset-0 bg-gradient-to-br from-muted/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative p-8 border-2 border-border group-hover:border-foreground transition-all bg-background h-full">
                            <div class="flex items-start gap-4 mb-4">
                                @if($principle['icon'] ?? false)
                                <div class="p-3 border-2 border-border group-hover:border-foreground transition-colors">
                                    <x-frontend.icon :name="$principle['icon']" class="w-6 h-6" />
                                </div>
                                @endif
                                <div class="text-[2rem] font-mono font-light text-muted-foreground/20">
                                    0{{ $index + 1 }}
                                </div>
                            </div>

                            <h3 class="mb-4 text-[1.125rem]">{{ $principle['title'] }}</h3>
                            <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">{{ $principle['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Tech Stack Overview --}}
            @if(!empty($principles['tech_stack']))
            <div class="motion motion-fade-up pt-16 border-t-2 border-border">
                <div class="mb-12">
                    <h3 class="mb-4 text-[1.25rem]">{{ app()->getLocale() === 'en' ? 'Technical Foundation' : 'Technische Grundlage' }}</h3>
                    <p class="text-[0.9375rem] text-muted-foreground">{{ app()->getLocale() === 'en' ? 'Modern, proven technologies for stable and scalable systems' : 'Moderne, erprobte Technologien für stabile und skalierbare Systeme' }}</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($principles['tech_stack'] as $index => $stack)
                    <div class="motion motion-scale motion-delay-{{ $index + 1 }}">
                        <div class="group relative overflow-hidden h-full">
                            <div class="relative p-6 border border-border group-hover:border-foreground transition-all bg-background h-full">
                                <h4 class="mb-4 font-mono text-[0.875rem] text-foreground uppercase tracking-wider">{{ $stack['category'] }}</h4>
                                <ul class="space-y-2">
                                    @foreach($stack['items'] as $item)
                                    <li class="text-[0.875rem] text-muted-foreground flex items-center gap-2">
                                        <span class="w-1 h-1 bg-muted-foreground rounded-full"></span>
                                        {{ is_array($item) ? ($item['name'] ?? '') : $item }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($principles['additional_tools'] ?? false)
                <div class="motion motion-fade-up motion-delay-4 mt-12">
                    <div class="pl-6 border-l-4 border-foreground">
                        <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground mb-2">{{ app()->getLocale() === 'en' ? 'Additional Technologies & Tools' : 'Weitere Technologien & Tools' }}</p>
                        <p class="text-[1rem] leading-relaxed">{{ $principles['additional_tools'] }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Why Us Section --}}
    @if($whyUs['items'] ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-32 border-t border-border">
        <div class="max-w-[1100px]">
            <div class="motion motion-fade-up">
                <h2 class="mb-24 max-w-[900px]">
                    {{ $whyUs['title'] ?? 'Mehr als Webdesign. Digitale Systeme, die Prozesse wirklich verbessern.' }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24">
                @foreach($whyUs['items'] as $index => $item)
                <div class="motion motion-fade-up motion-delay-{{ $index + 1 }}">
                    <div class="group relative h-full">
                        <div class="absolute inset-0 bg-gradient-to-br from-accent/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="relative h-full p-8 border-2 border-border group-hover:border-foreground transition-all bg-background">
                            @if($item['icon'] ?? false)
                            <div class="mb-6">
                                <div class="inline-flex p-4 border-2 border-border group-hover:border-foreground transition-colors">
                                    <x-frontend.icon :name="$item['icon']" class="w-8 h-8" />
                                </div>
                            </div>
                            @endif

                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-[0.875rem] text-muted-foreground font-mono">0{{ $index + 1 }}</span>
                                <div class="h-px flex-1 bg-border"></div>
                            </div>

                            <h3 class="mb-4 text-[1.125rem] leading-tight">{{ $item['title'] }}</h3>
                            <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">{{ $item['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($whyUs['promise'] ?? false)
            <div class="motion motion-fade-up motion-delay-4">
                <div class="relative p-10 border-2 border-foreground bg-background shadow-lg">
                    <div class="absolute -top-4 left-10 px-4 py-1 bg-foreground text-background text-[0.75rem] uppercase tracking-wider">
                        {{ app()->getLocale() === 'en' ? 'Our Promise' : 'Unser Versprechen' }}
                    </div>

                    <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                        {{ $whyUs['promise'] }}
                    </p>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Process Section --}}
    @if($process['steps'] ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-32 border-t border-border bg-gradient-to-b from-muted/5 to-transparent">
        <div class="max-w-[1100px]">
            <div class="motion motion-fade-up mb-24">
                @if($process['badge'] ?? false)
                <div class="inline-block px-4 py-2 mb-6 border border-border">
                    <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">{{ $process['badge'] }}</p>
                </div>
                @endif

                <h2 class="mb-8">{{ $process['title'] ?? 'Strukturiert. Transparent. Partnerschaftlich.' }}</h2>

                @if($process['subtitle'] ?? false)
                <p class="max-w-[800px] text-[1.0625rem] text-muted-foreground leading-relaxed">
                    {{ $process['subtitle'] }}
                </p>
                @endif
            </div>

            <div class="relative">
                <div class="absolute left-[3rem] top-16 bottom-16 w-0.5 bg-gradient-to-b from-border via-foreground/20 to-border hidden md:block"></div>

                <div class="space-y-12">
                    @foreach($process['steps'] as $index => $step)
                    <div class="motion motion-fade-left motion-delay-{{ $index + 1 }} relative">
                        <div class="flex gap-4 md:gap-8">
                            {{-- Icon Box - hidden on mobile --}}
                            <div class="relative flex-shrink-0 hidden md:block">
                                <div class="relative z-10 w-24 h-24 border-4 border-background bg-background shadow-lg flex items-center justify-center">
                                    <div class="absolute inset-2 border-2 border-foreground flex items-center justify-center">
                                        @if($step['icon'] ?? false)
                                        <x-frontend.icon :name="$step['icon']" class="w-8 h-8" />
                                        @else
                                        <span class="text-2xl font-mono">{{ $step['number'] ?? str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex-1 pb-4 md:pb-8">
                                <div class="group p-6 md:p-8 border-2 border-border hover:border-foreground transition-all bg-background hover:shadow-xl">
                                    <div class="flex items-center gap-4 mb-4">
                                        <span class="text-[0.875rem] font-mono text-muted-foreground">{{ $step['number'] ?? str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <h3 class="text-[1.5rem]">{{ $step['title'] }}</h3>
                                    </div>

                                    <p class="mb-6 text-[1.0625rem] text-muted-foreground leading-relaxed">{{ $step['description'] }}</p>

                                    @if(!empty($step['details']))
                                    <div class="mb-6 p-4 bg-muted/10 border-l-2 border-foreground">
                                        <ul class="space-y-2">
                                            @foreach($step['details'] as $detail)
                                            <li class="flex items-start gap-3 text-[0.9375rem]">
                                                <span class="text-accent mt-1.5">•</span>
                                                <span>{{ is_array($detail) ? ($detail['item'] ?? '') : $detail }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    @if($step['goal'] ?? false)
                                    <div class="pt-4 border-t border-border">
                                        <p class="text-[0.9375rem] italic text-muted-foreground flex items-center gap-2">
                                            <span class="text-accent">→</span>
                                            {{ $step['goal'] }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if($process['conclusion'] ?? false)
            <div class="motion motion-fade-up motion-delay-6 mt-24">
                <div class="p-10 border-2 border-foreground bg-foreground/[0.02] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-muted/20 to-transparent rounded-full -mr-32 -mt-32"></div>
                    <div class="relative">
                        <p class="mb-4 text-[1.375rem] leading-relaxed">{{ $process['conclusion']['title'] ?? '' }}</p>
                        <p class="text-[1.0625rem] text-muted-foreground leading-relaxed max-w-[800px]">{{ $process['conclusion']['text'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    @if($cta['title'] ?? false)
    <section id="kontakt" class="relative max-w-[1400px] mx-auto px-6 py-32 border-t border-border overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-muted/10 via-transparent to-accent/5"></div>

        <div class="motion motion-fade-up relative max-w-[1000px] mx-auto text-center">
            <div class="inline-flex items-center gap-3 px-4 py-2 mb-8 border border-border bg-background">
                <x-frontend.icon name="mail" class="w-4 h-4 text-muted-foreground" />
                <span class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">{{ app()->getLocale() === 'en' ? 'Get in touch' : 'Kontakt aufnehmen' }}</span>
            </div>

            <h2 class="mb-8 max-w-[700px] mx-auto">{{ $cta['title'] }}</h2>

            @if($cta['subtitle'] ?? false)
            <p class="mb-12 text-[1.0625rem] text-muted-foreground leading-relaxed max-w-[650px] mx-auto">
                {{ $cta['subtitle'] }}
            </p>
            @endif

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button
                    type="button"
                    onclick="Livewire.dispatch('openContactModal')"
                    class="group inline-flex items-center gap-3 px-10 py-5 bg-foreground text-background hover:bg-foreground/90 transition-all shadow-lg hover:shadow-xl text-[1.0625rem]"
                >
                    <x-frontend.icon name="mail" class="w-5 h-5" />
                    {{ $cta['button_text'] ?? 'Projekt besprechen' }}
                    <span class="animate-bounce-x">→</span>
                </button>

                @if($settings->phone ?? false)
                <a href="tel:{{ preg_replace('/\s+/', '', $settings->phone) }}" class="inline-flex items-center gap-2 px-8 py-5 border-2 border-border hover:border-foreground transition-all text-[1.0625rem] bg-background">
                    {{ app()->getLocale() === 'en' ? 'Or call directly' : 'Oder direkt anrufen' }}
                </a>
                @endif
            </div>

            <div class="mt-16 pt-12 border-t border-border">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-[0.875rem] text-muted-foreground">
                    <div>
                        <p class="mb-1 font-medium text-foreground">{{ app()->getLocale() === 'en' ? 'Initial consultation' : 'Erstgespräch' }}</p>
                        <p>{{ app()->getLocale() === 'en' ? 'Free & non-binding' : 'Kostenlos & unverbindlich' }}</p>
                    </div>
                    <div>
                        <p class="mb-1 font-medium text-foreground">{{ app()->getLocale() === 'en' ? 'Response time' : 'Reaktionszeit' }}</p>
                        <p>{{ app()->getLocale() === 'en' ? 'Reply within 24h' : 'Antwort innerhalb 24h' }}</p>
                    </div>
                    <div>
                        <p class="mb-1 font-medium text-foreground">{{ app()->getLocale() === 'en' ? 'Location' : 'Standort' }}</p>
                        <p>{{ $settings->city ?? 'Frankfurt am Main' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Organization + WebSite Schema — declares the agency entity and
         makes the site eligible for the Sitelinks Search Box. --}}
    @isset($organizationSchema)
    @push('scripts')
    <script type="application/ld+json">
    {!! json_encode($organizationSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endpush
    @endisset

    @isset($websiteSchema)
    @push('scripts')
    <script type="application/ld+json">
    {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endpush
    @endisset
</x-layouts.frontend>

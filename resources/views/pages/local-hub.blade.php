<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero', []);
        $intro = $page->getSection('intro', []);
        $cta = $page->getSection('cta', []);

        // Group cities by region (slugs remain ASCII for URLs)
        $regions = [
            'Frankfurt & Umgebung' => ['frankfurt-am-main', 'bad-homburg', 'bad-soden', 'bad-vilbel', 'kronberg', 'hanau'],
            'Südhessen' => ['darmstadt', 'offenbach', 'ruesselsheim', 'langen', 'bensheim'],
            'Mainz & Wiesbaden' => ['mainz', 'wiesbaden'],
            'Mittelhessen' => ['giessen', 'marburg'],
            'Nordhessen' => ['kassel'],
            'Osthessen' => ['fulda'],
        ];

        // Map local pages by slug for easy lookup
        $pagesBySlug = $localPages->keyBy(fn($p) => $p->getTranslation('slug', 'de'));
    @endphp

    {{-- Hero Section --}}
    <section class="relative max-w-[1400px] mx-auto px-6 pt-40 pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[900px]">
            <div class="motion motion-fade-up">
                {{-- Badge --}}
                <div class="inline-block px-4 py-2 mb-8 border border-border bg-background/50 backdrop-blur-sm">
                    <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">
                        Lokale Expertise
                    </p>
                </div>

                {{-- H1 --}}
                <h1 class="mb-8">
                    {{ $hero['title'] ?? 'Webagentur im Rhein-Main-Gebiet – regionale Expertise & digitale Systeme' }}
                </h1>

                {{-- Intro --}}
                <div class="text-[1.125rem] leading-relaxed text-muted-foreground max-w-[750px] space-y-4">
                    <p>{{ $intro['text'] ?? 'Wir unterstützen Unternehmen in der gesamten Rhein-Main-Region bei der Umsetzung digitaler Projekte – von Unternehmenswebsites über E-Commerce bis hin zu individuellen Plattformen und Webanwendungen.' }}</p>
                    <p>{{ $intro['text2'] ?? 'Lokale Nähe ermöglicht schnelle, persönliche Abstimmungen. Gleichzeitig entwickeln wir Lösungen, die technisch sauber aufgebaut sind und auch bei Wachstum oder veränderten Anforderungen zuverlässig funktionieren.' }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Regions & Cities --}}
    <section class="max-w-[1400px] mx-auto px-6 py-20 border-t border-border">
        <div class="max-w-[1100px]">
            <div class="motion motion-fade-up mb-12">
                <h2 class="mb-4">Unsere Standorte</h2>
                <p class="text-[1rem] text-muted-foreground max-w-[700px]">
                    Klicken Sie auf eine Stadt, um mehr über unsere lokale Expertise zu erfahren.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($regions as $regionName => $citySlugs)
                    @php
                        $citiesInRegion = collect($citySlugs)->map(fn($slug) => $pagesBySlug->get($slug))->filter();
                    @endphp
                    @if($citiesInRegion->count() > 0)
                    <div class="motion motion-fade-up">
                        <div class="p-6 border border-border bg-background h-full">
                            <h3 class="text-[1.125rem] font-medium mb-4 flex items-center gap-2">
                                <x-frontend.icon name="map-pin" class="w-5 h-5 text-accent" />
                                {{ $regionName }}
                            </h3>
                            <ul class="space-y-2">
                                @foreach($citiesInRegion as $cityPage)
                                <li>
                                    <a href="{{ $cityPage->getUrl() }}"
                                       class="group flex items-center gap-2 text-[0.9375rem] text-muted-foreground hover:text-foreground transition-colors">
                                        <span class="text-accent group-hover:translate-x-1 transition-transform">→</span>
                                        {{ $cityPage->getSection('city', $cityPage->title) }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why Local Section --}}
    <section class="max-w-[1400px] mx-auto px-6 py-20 border-t border-border bg-muted/5">
        <div class="max-w-[900px]">
            <div class="motion motion-fade-up">
                <h2 class="mb-6">Warum lokale Nähe & technische Tiefe?</h2>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <div>
                                <p class="font-medium mb-1">Persönliche Abstimmungen</p>
                                <p class="text-[0.9375rem] text-muted-foreground">Vor-Ort-Termine und kurze Wege für effiziente Kommunikation.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <div>
                                <p class="font-medium mb-1">Regionale Marktkenntnis</p>
                                <p class="text-[0.9375rem] text-muted-foreground">Wir kennen die Anforderungen mittelständischer Unternehmen in der Region.</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <div>
                                <p class="font-medium mb-1">Überregionale Skalierbarkeit</p>
                                <p class="text-[0.9375rem] text-muted-foreground">Lösungen, die technisch sauber aufgebaut sind und mit Ihnen wachsen.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <div>
                                <p class="font-medium mb-1">Langfristige Partnerschaft</p>
                                <p class="text-[0.9375rem] text-muted-foreground">Keine Agentur-Hopping – wir begleiten Projekte über Jahre.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="max-w-[1400px] mx-auto px-6 py-20 border-t border-border">
        <div class="max-w-[1100px]">
            <div class="motion motion-fade-up p-12 border-2 border-foreground text-center bg-background">
                <h2 class="text-[1.5rem] mb-4">
                    {{ $cta['title'] ?? 'Projekt besprechen?' }}
                </h2>
                <p class="text-[1rem] text-muted-foreground mb-8 max-w-[600px] mx-auto">
                    {{ $cta['text'] ?? 'Lassen Sie uns in einem kurzen Gespräch klären, wie wir Sie unterstützen können.' }}
                </p>
                <a href="{{ localized_route('contact') }}"
                   class="inline-flex items-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all">
                    {{ $cta['button_text'] ?? 'Projekt besprechen' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
</x-layouts.frontend>

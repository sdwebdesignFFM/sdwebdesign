<x-layouts.frontend>
    @php
        // Get city and region from page content
        $city = $page->getSection('city', $page->title);
        $settings = \App\Models\Setting::first();
        $region = $page->getSection('region', 'Rhein-Main-Gebiet');

        // Get custom content or use defaults
        $intro = $page->getSection('intro', []);
        $localContext = $page->getSection('local_context', []);
        $solutions = $page->getSection('solutions', []);
        $why = $page->getSection('why', []);
        $localSignal = $page->getSection('local_signal', []);
        $cta = $page->getSection('cta', []);

        // Default texts with variable replacement
        $defaultIntroHeadline = "Webagentur für {$city} – Websites, Shops & digitale Systeme";
        $defaultIntroText = "Wir unterstützen Unternehmen aus {$city} und dem {$region} bei professionellen Unternehmenswebsites, E-Commerce-Lösungen und individuellen Webanwendungen. Unser Fokus liegt auf sauberer Technik, klarer Struktur und Lösungen, die mit Ihren Anforderungen wachsen.";

        $defaultSolutionsHeadline = "Unsere Lösungen für Unternehmen aus {$city}";
        $defaultSolutionsText = "Je nach Zielsetzung starten Projekte oft schlank – und werden bei Bedarf erweitert. Hier finden Sie die passenden Leistungsbereiche:";

        $defaultWhyHeadline = "So arbeiten wir";
        $defaultWhyText = "Wir starten mit einer klaren Einordnung Ihrer Ziele und Rahmenbedingungen. Das Ergebnis ist eine Lösung, die technisch sauber umgesetzt ist, langfristig wartbar bleibt und sich bei Bedarf erweitern lässt – ohne technische Sackgassen.";
        $defaultWhyBullets = [
            "Saubere Technik & Performance als Grundlage",
            "Klare Struktur und nachvollziehbare Umsetzung",
            "Erweiterbar für Wachstum und neue Anforderungen",
        ];

        $defaultLocalSignalHeadline = "Regional verankert, überregional umsetzungsstark";
        $defaultLocalSignalText = "Durch unsere Nähe zu {$city} sind persönliche Abstimmungen unkompliziert möglich. Gleichzeitig entwickeln wir unsere Projekte so, dass sie technisch sauber aufgebaut sind und auch überregional, bei Wachstum oder veränderten Anforderungen, zuverlässig weiterentwickelt werden können.";

        // Solution links data
        $solutionLinks = [
            [
                'title' => 'Unternehmenswebsites',
                'url' => '/loesungen/websites',
                'teaser' => 'Klare, schnelle Websites mit Substanz – vom Einstieg bis zur erweiterbaren Lösung.',
                'icon' => 'globe',
            ],
            [
                'title' => 'Digitale Plattformen',
                'url' => '/loesungen/plattformen',
                'teaser' => 'Interne Tools, Portale und individuelle Geschäftslogik für strukturierte Abläufe.',
                'icon' => 'layers',
            ],
            [
                'title' => 'E-Commerce',
                'url' => '/loesungen/e-commerce',
                'teaser' => 'B2C/B2B-Shops und integrierte Shop-Lösungen mit Fokus auf Performance.',
                'icon' => 'shopping-cart',
            ],
            [
                'title' => 'Mobile Anwendungen',
                'url' => '/loesungen/mobile-anwendungen',
                'teaser' => 'iOS, PWA und mobile Erweiterungen als Teil Ihrer Systemlösung.',
                'icon' => 'device-phone-mobile',
            ],
            [
                'title' => 'SEO',
                'url' => '/suchmaschinenoptimierung',
                'teaser' => 'Technische SEO und Content-Struktur – nachhaltig auffindbar statt kurzfristiger Tricks.',
                'icon' => 'magnifying-glass',
            ],
            [
                'title' => 'SEA',
                'url' => '/suchmaschinenwerbung',
                'teaser' => 'Google Ads als skalierbarer Kanal – mit starken Landingpages und sauberem Tracking.',
                'icon' => 'currency-euro',
            ],
        ];
    @endphp

    {{-- Breadcrumbs --}}
    <nav class="pt-24 pb-4 max-w-[1400px] mx-auto px-6" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 text-[0.875rem] text-muted-foreground" itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ localized_route('home') }}" itemprop="item" class="hover:text-foreground transition-colors">
                    <span itemprop="name">Home</span>
                </a>
                <meta itemprop="position" content="1" />
            </li>
            <li class="text-muted-foreground/50">→</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="/in" itemprop="item" class="hover:text-foreground transition-colors">
                    <span itemprop="name">Standorte</span>
                </a>
                <meta itemprop="position" content="2" />
            </li>
            <li class="text-muted-foreground/50">→</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name" class="text-foreground font-medium">{{ $city }}</span>
                <meta itemprop="position" content="3" />
            </li>
        </ol>
    </nav>

    {{-- Hero / Intro Section --}}
    <section class="relative max-w-[1400px] mx-auto px-6 pt-8 pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[900px]">
            <div class="motion motion-fade-up">
                {{-- Badge --}}
                <div class="inline-block px-4 py-2 mb-8 border border-border bg-background/50 backdrop-blur-sm">
                    <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">
                        Webagentur {{ $city }}
                    </p>
                </div>

                {{-- H1 Headline --}}
                <h1 class="mb-8">
                    {{ $intro['headline'] ?? $defaultIntroHeadline }}
                </h1>

                {{-- Intro Text --}}
                <p class="text-[1.125rem] leading-relaxed text-muted-foreground max-w-[750px]">
                    {{ $intro['text'] ?? $defaultIntroText }}
                </p>

                {{-- Lokaler Bezug / Local Context --}}
                @if($localContext['text'] ?? false)
                <p class="mt-6 text-[1rem] leading-relaxed text-muted-foreground max-w-[750px]">
                    {{ $localContext['text'] }}
                </p>
                @endif
            </div>
        </div>
    </section>

    {{-- Solutions Block --}}
    <section class="max-w-[1400px] mx-auto px-6 py-20 border-t border-border">
        <div class="max-w-[1100px]">
            <div class="motion motion-fade-up mb-12">
                <h2 class="mb-4">
                    {{ $solutions['headline'] ?? $defaultSolutionsHeadline }}
                </h2>
                <p class="text-[1rem] text-muted-foreground max-w-[700px]">
                    {{ $solutions['text'] ?? $defaultSolutionsText }}
                </p>
            </div>

            {{-- Solution Cards Grid --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($solutionLinks as $index => $solution)
                <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }}">
                    <a href="{{ $solution['url'] }}"
                       class="group block h-full p-6 border-2 border-border hover:border-foreground transition-all bg-background hover:shadow-lg">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="p-3 border border-border group-hover:border-foreground transition-colors shrink-0">
                                <x-frontend.icon :name="$solution['icon']" class="w-6 h-6" />
                            </div>
                            <h3 class="text-[1.125rem] font-medium pt-2 group-hover:text-accent transition-colors">
                                {{ $solution['title'] }}
                            </h3>
                        </div>
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-4">
                            {{ $solution['teaser'] }}
                        </p>
                        <span class="inline-flex items-center gap-2 text-[0.875rem] font-medium group-hover:gap-3 transition-all">
                            Mehr erfahren
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why / Vorgehen Section --}}
    <section class="max-w-[1400px] mx-auto px-6 py-20 border-t border-border bg-muted/5">
        <div class="max-w-[900px]">
            <div class="motion motion-fade-up">
                <h2 class="mb-6">
                    {{ $why['headline'] ?? $defaultWhyHeadline }}
                </h2>

                <p class="text-[1rem] text-muted-foreground leading-relaxed mb-8">
                    {{ $why['text'] ?? $defaultWhyText }}
                </p>

                {{-- Bullets --}}
                @php
                    $bullets = !empty($why['bullets']) ? $why['bullets'] : $defaultWhyBullets;
                @endphp
                <div class="space-y-3">
                    @foreach($bullets as $bullet)
                    <div class="flex items-start gap-3">
                        <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                        <span class="text-[0.9375rem]">{{ is_array($bullet) ? ($bullet['bullet'] ?? $bullet) : $bullet }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Local Signal Section --}}
    <section class="max-w-[1400px] mx-auto px-6 py-20 border-t border-border">
        <div class="max-w-[900px]">
            <div class="motion motion-fade-up">
                <div class="flex items-start gap-4 mb-6">
                    <div class="p-3 border border-border shrink-0">
                        <x-frontend.icon name="map-pin" class="w-6 h-6" />
                    </div>
                    <div>
                        <h2 class="text-[1.375rem] mb-4">
                            {{ $localSignal['headline'] ?? $defaultLocalSignalHeadline }}
                        </h2>
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">
                            {{ $localSignal['text'] ?? $defaultLocalSignalText }}
                        </p>
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
                    Projekt besprechen?
                </h2>
                <p class="text-[1rem] text-muted-foreground mb-8 max-w-[600px] mx-auto">
                    Lassen Sie uns in einem kurzen Gespräch klären, wie wir Sie unterstützen können.
                </p>
                <a href="{{ localized_route('contact') }}"
                   class="inline-flex items-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all">
                    {{ $cta['button_text'] ?? 'Projekt besprechen' }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Back to Hub Link --}}
            <div class="mt-8 text-center">
                <a href="/in" class="inline-flex items-center gap-2 text-[0.9375rem] text-muted-foreground hover:text-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Weitere Standorte im Rhein-Main-Gebiet
                </a>
            </div>
        </div>
    </section>

    {{-- LocalBusiness / ProfessionalService Schema --}}
    @isset($localBusinessSchema)
    @push('scripts')
    <script type="application/ld+json">
    {!! json_encode($localBusinessSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endpush
    @endisset

    {{-- FAQ Schema for Local SEO --}}
    @push('scripts')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "FAQPage",
        "mainEntity": [
            {
                "@@type": "Question",
                "name": "Was kostet eine Website für Unternehmen in {{ $city }}?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Die Kosten für eine Unternehmenswebsite variieren je nach Umfang und Anforderungen. Einfache Websites beginnen bei ca. 3.000€, während komplexere Projekte mit E-Commerce oder individuellen Funktionen entsprechend mehr kosten. Wir beraten Sie gerne unverbindlich zu Ihrem konkreten Projekt."
                }
            },
            {
                "@@type": "Question",
                "name": "Wie lange dauert die Entwicklung einer Website?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Eine typische Unternehmenswebsite ist in 4-8 Wochen fertiggestellt. Komplexere Projekte wie Online-Shops oder Plattformen benötigen entsprechend mehr Zeit. Der genaue Zeitrahmen hängt von Umfang, Zulieferung von Inhalten und Abstimmungszyklen ab."
                }
            },
            {
                "@@type": "Question",
                "name": "Bieten Sie auch SEO für Unternehmen in {{ $city }} an?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Ja, wir bieten technische SEO-Optimierung als Teil jeder Website-Entwicklung an. Darüber hinaus unterstützen wir bei Content-Strategie und nachhaltigem Ranking-Aufbau für lokale und überregionale Sichtbarkeit."
                }
            },
            {
                "@@type": "Question",
                "name": "Können wir uns vor Ort in {{ $city }} treffen?",
                "acceptedAnswer": {
                    "@@type": "Answer",
                    "text": "Ja, persönliche Treffen in {{ $city }} und dem {{ $region }} sind jederzeit möglich. Wir schätzen den direkten Austausch, insbesondere in der Konzeptionsphase und bei komplexeren Projekten."
                }
            }
        ]
    }
    </script>
    @endpush
</x-layouts.frontend>

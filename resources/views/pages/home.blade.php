<x-layouts.frontend>
    {{-- Hero Section --}}
    <section class="relative max-w-[1400px] mx-auto px-6 pt-40 pb-40 overflow-hidden">
        {{-- Animated background grid --}}
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative grid lg:grid-cols-2 gap-20 items-center">
            <div class="motion motion-fade-up">
                <div class="inline-block px-4 py-2 mb-8 border border-border bg-background/50 backdrop-blur-sm">
                    <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">
                        System- & Lösungsplattform
                    </p>
                </div>

                <h1 class="mb-8 max-w-[650px]">
                    Digitale Systeme für Unternehmen, die Komplexität beherrschen wollen
                </h1>

                <p class="mb-6 max-w-[600px] text-[1.125rem] leading-relaxed text-muted-foreground">
                    Wir entwickeln maßgeschneiderte Webanwendungen, automatisieren Geschäftsprozesse und integrieren bestehende Systeme zu stabilen, skalierbaren Plattformen.
                </p>

                <div class="mb-12 flex flex-wrap gap-3">
                    @foreach(['Architektur', 'Integration', 'Automatisierung', 'Wartbarkeit'] as $tag)
                    <span class="px-4 py-2 text-[0.875rem] border border-border hover:border-foreground transition-colors">
                        {{ $tag }}
                    </span>
                    @endforeach
                </div>

                <div class="flex flex-wrap gap-4 mb-16">
                    <a href="{{ route('contact') }}" class="group px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all flex items-center gap-2">
                        Projekt besprechen
                        <span class="inline-block animate-bounce-x">→</span>
                    </a>
                    <a href="{{ route('solutions') }}" class="px-8 py-4 border border-border hover:border-foreground transition-colors">
                        Lösungen entdecken
                    </a>
                </div>

                <div class="flex gap-12 pt-8 border-t border-border">
                    <div>
                        <p class="text-[2rem] font-light mb-1">15+</p>
                        <p class="text-[0.875rem] text-muted-foreground">Jahre Erfahrung</p>
                    </div>
                    <div>
                        <p class="text-[2rem] font-light mb-1">50+</p>
                        <p class="text-[0.875rem] text-muted-foreground">Projekte umgesetzt</p>
                    </div>
                </div>
            </div>

            {{-- Technical Architecture Visualization --}}
            <div class="relative hidden lg:block">
                <div class="space-y-6">
                    @php
                        $layers = [
                            ['icon' => 'globe', 'label' => 'Frontend Layer', 'desc' => 'React, TypeScript'],
                            ['icon' => 'code', 'label' => 'API Layer', 'desc' => 'REST, GraphQL'],
                            ['icon' => 'layers', 'label' => 'Business Logic', 'desc' => 'Services, Workflows'],
                            ['icon' => 'database', 'label' => 'Data & Integration', 'desc' => 'PostgreSQL, APIs'],
                        ];
                    @endphp

                    @foreach($layers as $index => $layer)
                    <div class="group relative motion motion-fade-left motion-delay-{{ $index + 1 }}">
                        <div class="p-6 border-2 border-border hover:border-foreground transition-all duration-300 bg-white hover:shadow-lg">
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

    {{-- Problem Section --}}
    <section class="max-w-[1400px] mx-auto px-6 py-32 border-t border-border">
        <div class="max-w-[1100px]">
            <div class="motion motion-fade-up">
                <div class="flex items-start gap-4 mb-10">
                    <div class="p-3 border border-border">
                        <x-frontend.icon name="alert-circle" class="w-6 h-6 text-muted-foreground" />
                    </div>
                    <h2 class="flex-1 max-w-[800px]">
                        Viele Unternehmen sind digital präsent – aber nicht digital effizient.
                    </h2>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 mb-16">
                {{-- Typische Ausgangssituation --}}
                <div class="relative motion motion-fade-left motion-delay-2">
                    <div class="absolute -left-6 top-0 bottom-0 w-1 bg-gradient-to-b from-muted-foreground/20 to-transparent"></div>

                    <h4 class="mb-8 text-[0.875rem] uppercase tracking-wider text-muted-foreground flex items-center gap-2">
                        <span class="w-2 h-2 bg-foreground rounded-full"></span>
                        Typische Ausgangssituation
                    </h4>

                    <div class="space-y-4">
                        @php
                            $problems = [
                                ['label' => '6–10 verschiedene Tools', 'value' => '8'],
                                ['label' => '3–4 isolierte Datenquellen', 'value' => '4'],
                                ['label' => 'Manuelle Exporte & Importe', 'value' => '∞'],
                                ['label' => 'Keine zentrale Geschäftslogik', 'value' => '0'],
                                ['label' => 'Gewachsene Insellösungen', 'value' => '++'],
                            ];
                        @endphp

                        @foreach($problems as $i => $problem)
                        <div class="motion motion-fade-up motion-delay-{{ $i + 3 }}">
                            <div class="group flex items-start justify-between gap-4 p-4 border border-border hover:border-foreground transition-all hover:shadow-md bg-white">
                                <span class="flex-1 text-[0.9375rem]">{{ $problem['label'] }}</span>
                                <span class="font-mono text-[1.25rem] text-muted-foreground group-hover:text-foreground transition-colors">
                                    {{ $problem['value'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Ergebnis --}}
                <div class="relative motion motion-fade-right motion-delay-4">
                    <div class="absolute -left-6 top-0 bottom-0 w-1 bg-gradient-to-b from-red-500/20 to-transparent"></div>

                    <h4 class="mb-8 text-[0.875rem] uppercase tracking-wider text-muted-foreground flex items-center gap-2">
                        <x-frontend.icon name="trending-down" class="w-4 h-4" />
                        Ergebnis
                    </h4>

                    <div class="space-y-3">
                        @php
                            $results = [
                                'Medienbrüche & Doppelerfassung',
                                'Inkonsistente Daten',
                                'Skalierungsprobleme',
                                'Hoher Wartungsaufwand',
                                'Wachsende Komplexität',
                            ];
                        @endphp

                        @foreach($results as $i => $result)
                        <div class="motion motion-fade-up motion-delay-{{ $i + 5 }}">
                            <div class="flex items-start gap-3 p-4 bg-red-50/30 border border-red-100 hover:border-red-200 transition-colors">
                                <span class="text-red-400 mt-1">×</span>
                                <span class="text-[0.9375rem]">{{ $result }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Unser Ansatz --}}
            <div class="motion motion-fade-up motion-delay-8">
                <div class="relative mt-20 p-8 border-2 border-foreground bg-foreground/[0.02]">
                    <div class="absolute -top-3 left-8 px-3 bg-white">
                        <span class="text-[0.75rem] uppercase tracking-wider text-muted-foreground">
                            Unser Ansatz
                        </span>
                    </div>
                    <p class="text-[1.25rem] leading-relaxed">
                        Genau hier setzen wir an: <span class="font-medium">Komplexität strukturieren</span>, Systeme integrieren, Prozesse automatisieren.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @php
        $services = $page->getSection('services');
        $principles = $page->getSection('principles');
        $whyUs = $page->getSection('why_us');
        $process = $page->getSection('process');
        $cta = $page->getSection('cta');

        // Fallback services data matching Figma design
        if (empty($services['items'])) {
            $services = [
                'badge' => 'Leistungsübersicht',
                'title' => 'Unsere Leistungen',
                'subtitle' => 'Von der ersten Analyse bis zur langfristigen Betreuung – wir begleiten Ihr digitales Vorhaben als technischer Partner.',
                'button_text' => 'Alle Lösungen',
                'button_link' => route('solutions'),
                'items' => [
                    [
                        'icon' => 'layout-dashboard',
                        'title' => 'Digitale Plattformen & Webanwendungen',
                        'description' => 'Maßgeschneiderte Softwarelösungen, die Ihre Geschäftslogik abbilden und mit Ihrem Unternehmen wachsen.',
                        'capabilities' => ['Kundenportale & Self-Service', 'Interne Administrationssysteme', 'Multitenancy-Architekturen', 'Dashboards & Reporting'],
                        'technical_focus' => ['Laravel & PHP', 'React & TypeScript', 'PostgreSQL & Redis'],
                    ],
                    [
                        'icon' => 'workflow',
                        'title' => 'Prozessdigitalisierung & Automatisierung',
                        'description' => 'Manuelle, fehleranfällige Abläufe in digitale, nachvollziehbare Prozesse überführen.',
                        'capabilities' => ['Analyse bestehender Prozesse & Engpässe', 'Digitale Workflows & Freigaben', 'Automatisierte Benachrichtigungen', 'Status-Tracking & Auswertungen'],
                        'technical_focus' => ['Workflow-Engines', 'Ereignisbasierte Prozesse', 'Datenvalidierung & -konsistenz'],
                    ],
                    [
                        'icon' => 'git-merge',
                        'title' => 'API- & Systemintegration',
                        'description' => 'Bestehende Systeme verbinden, Datensilos auflösen und eine konsistente Datenbasis schaffen.',
                        'capabilities' => ['ERP-, CRM- & Drittsystem-Anbindungen', 'Bidirektionaler Datenaustausch', 'Middleware & Schnittstellenlogik', 'Datenmigration & Synchronisation'],
                        'technical_focus' => ['REST & GraphQL APIs', 'API-first-Architekturen', 'Sicherheit & Zugriffskonzepte'],
                    ],
                ],
            ];
        }
    @endphp

    {{-- Services Section --}}
    @if($services['items'] ?? false)
    <section id="leistungen" class="max-w-[1400px] mx-auto px-6 py-32 border-t border-border bg-gradient-to-b from-transparent to-muted/10">
        <div class="max-w-[1100px]">
            {{-- Header --}}
            <div class="motion motion-fade-up mb-24">
                @if($services['badge'] ?? false)
                <div class="inline-block px-4 py-2 mb-6 border border-border">
                    <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">{{ $services['badge'] }}</p>
                </div>
                @endif

                <div class="flex items-end justify-between gap-8 mb-6">
                    <h2>{{ $services['title'] ?? 'Unsere Leistungen' }}</h2>

                    @if($services['button_text'] ?? false)
                    <a href="{{ $services['button_link'] ?? route('solutions') }}" class="hidden md:flex items-center gap-2 px-6 py-3 border-2 border-foreground hover:bg-foreground hover:text-background transition-all">
                        {{ $services['button_text'] }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a>
                    @endif
                </div>

                @if($services['subtitle'] ?? false)
                <p class="max-w-[800px] text-[1.0625rem] text-muted-foreground leading-relaxed">
                    {{ $services['subtitle'] }}
                </p>
                @endif
            </div>

            {{-- Core Services - Interactive Cards --}}
            <div class="space-y-6 mb-32" x-data="{ expandedService: 0 }">
                @foreach($services['items'] as $index => $service)
                <div class="motion motion-fade-up motion-delay-{{ $index + 1 }}">
                    <div
                        class="border-2 transition-all duration-300 cursor-pointer bg-white"
                        :class="expandedService === {{ $index }} ? 'border-foreground shadow-xl' : 'border-border hover:border-foreground/40'"
                        @click="expandedService = expandedService === {{ $index }} ? null : {{ $index }}"
                    >
                        <div class="p-8">
                            <div class="flex items-start gap-6 mb-4">
                                @if($service['icon'] ?? false)
                                <div
                                    class="p-4 border-2 transition-all"
                                    :class="expandedService === {{ $index }} ? 'border-foreground' : 'border-border'"
                                >
                                    <x-frontend.icon :name="$service['icon']" class="w-8 h-8" />
                                </div>
                                @endif

                                <div class="flex-1">
                                    <div class="flex items-center gap-4 mb-3">
                                        <span class="text-[0.875rem] font-mono text-muted-foreground">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <h3 class="text-[1.375rem]">{{ $service['title'] }}</h3>
                                    </div>
                                    <p class="text-muted-foreground leading-relaxed">{{ $service['description'] }}</p>
                                </div>

                                <div
                                    class="transition-transform duration-300"
                                    :class="expandedService === {{ $index }} ? 'rotate-90' : ''"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-muted-foreground" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"/>
                                    </svg>
                                </div>
                            </div>

                            <div x-show="expandedService === {{ $index }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                                @if(!empty($service['capabilities']) || !empty($service['technical_focus']))
                                <div class="pt-8 mt-8 border-t border-border grid md:grid-cols-2 gap-12">
                                    @if(!empty($service['capabilities']))
                                    <div>
                                        <h4 class="mb-6 text-[0.875rem] uppercase tracking-wide text-muted-foreground flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-foreground rounded-full"></span>
                                            Systemfähigkeiten
                                        </h4>
                                        <ul class="space-y-3">
                                            @foreach($service['capabilities'] as $capability)
                                            <li class="flex items-start gap-3 text-[0.9375rem]">
                                                <span class="text-accent mt-1">→</span>
                                                <span>{{ is_array($capability) ? ($capability['item'] ?? '') : $capability }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    @if(!empty($service['technical_focus']))
                                    <div>
                                        <h4 class="mb-6 text-[0.875rem] uppercase tracking-wide text-muted-foreground flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-foreground rounded-full"></span>
                                            Technischer Fokus
                                        </h4>
                                        <ul class="space-y-3">
                                            @foreach($service['technical_focus'] as $focus)
                                            <li class="flex items-start gap-3 text-[0.9375rem] font-mono text-sm">
                                                <span class="text-accent mt-1">→</span>
                                                <span>{{ is_array($focus) ? ($focus['item'] ?? '') : $focus }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Additional Services --}}
            @if(!empty($services['additional_items']))
            <div class="motion motion-fade-up pt-12 border-t-2 border-dashed border-border">
                <h3 class="mb-10 text-[1.125rem] text-muted-foreground">Ergänzende Leistungen</h3>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($services['additional_items'] as $index => $service)
                    <div class="motion motion-scale motion-delay-{{ $index + 1 }}">
                        <div class="group p-6 border border-border hover:border-foreground transition-all hover:shadow-lg bg-white h-full">
                            @if($service['icon'] ?? false)
                            <div class="mb-4 p-3 border border-border group-hover:border-foreground transition-colors inline-block">
                                <x-frontend.icon :name="$service['icon']" class="w-5 h-5" />
                            </div>
                            @endif
                            <h4 class="mb-3 text-[1rem]">{{ $service['title'] }}</h4>
                            <p class="text-[0.875rem] text-muted-foreground leading-relaxed">{{ $service['description'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

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
                        <div class="relative p-8 border-2 border-border group-hover:border-foreground transition-all bg-white h-full">
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
                    <h3 class="mb-4 text-[1.25rem]">Technische Grundlage</h3>
                    <p class="text-[0.9375rem] text-muted-foreground">Moderne, erprobte Technologien für stabile und skalierbare Systeme</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($principles['tech_stack'] as $index => $stack)
                    <div class="motion motion-scale motion-delay-{{ $index + 1 }}">
                        <div class="group relative overflow-hidden h-full">
                            <div class="relative p-6 border border-border group-hover:border-foreground transition-all bg-white h-full">
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
                        <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground mb-2">Weitere Technologien & Tools</p>
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

                        <div class="relative h-full p-8 border-2 border-border group-hover:border-foreground transition-all bg-white">
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
                <div class="relative p-10 border-2 border-foreground bg-white shadow-lg">
                    <div class="absolute -top-4 left-10 px-4 py-1 bg-foreground text-background text-[0.75rem] uppercase tracking-wider">
                        Unser Versprechen
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
                        <div class="flex gap-8">
                            <div class="relative flex-shrink-0">
                                <div class="relative z-10 w-24 h-24 border-4 border-background bg-white shadow-lg flex items-center justify-center">
                                    <div class="absolute inset-2 border-2 border-foreground flex items-center justify-center">
                                        @if($step['icon'] ?? false)
                                        <x-frontend.icon :name="$step['icon']" class="w-8 h-8" />
                                        @else
                                        <span class="text-2xl font-mono">{{ $step['number'] ?? str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex-1 pb-8">
                                <div class="group p-8 border-2 border-border hover:border-foreground transition-all bg-white hover:shadow-xl">
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
            <div class="inline-flex items-center gap-3 px-4 py-2 mb-8 border border-border bg-white">
                <x-frontend.icon name="mail" class="w-4 h-4 text-muted-foreground" />
                <span class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">Kontakt aufnehmen</span>
            </div>

            <h2 class="mb-8 max-w-[700px] mx-auto">{{ $cta['title'] }}</h2>

            @if($cta['subtitle'] ?? false)
            <p class="mb-12 text-[1.0625rem] text-muted-foreground leading-relaxed max-w-[650px] mx-auto">
                {{ $cta['subtitle'] }}
            </p>
            @endif

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="mailto:{{ $settings->email ?? 'info@sdwebdesign.de' }}" class="group inline-flex items-center gap-3 px-10 py-5 bg-foreground text-background hover:bg-foreground/90 transition-all shadow-lg hover:shadow-xl text-[1.0625rem]">
                    <x-frontend.icon name="mail" class="w-5 h-5" />
                    {{ $cta['button_text'] ?? 'Projekt besprechen' }}
                    <span class="animate-bounce-x">→</span>
                </a>

                @if($settings->phone ?? false)
                <a href="tel:{{ preg_replace('/\s+/', '', $settings->phone) }}" class="inline-flex items-center gap-2 px-8 py-5 border-2 border-border hover:border-foreground transition-all text-[1.0625rem] bg-white">
                    Oder direkt anrufen
                </a>
                @endif
            </div>

            <div class="mt-16 pt-12 border-t border-border">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-[0.875rem] text-muted-foreground">
                    <div>
                        <p class="mb-1 font-medium text-foreground">Erstgespräch</p>
                        <p>Kostenlos & unverbindlich</p>
                    </div>
                    <div>
                        <p class="mb-1 font-medium text-foreground">Reaktionszeit</p>
                        <p>Antwort innerhalb 24h</p>
                    </div>
                    <div>
                        <p class="mb-1 font-medium text-foreground">Standort</p>
                        <p>{{ $settings->city ?? 'Frankfurt am Main' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
</x-layouts.frontend>

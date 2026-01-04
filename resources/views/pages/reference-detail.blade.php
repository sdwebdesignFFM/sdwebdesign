<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $meta = $page->getSection('meta', []);
        $description = $page->getSection('description');
        $challenge = $page->getSection('challenge');
        $solution = $page->getSection('solution');
        $technologies = $page->getSection('technologies', []);
        $techStack = $page->getSection('tech_stack', []);
        $impactResults = $page->getSection('impact_results', []);
        $features = $page->getSection('features', []);
        $technicalDetails = $page->getSection('technical_details', []);
        $results = $page->getSection('results', []);
        $testimonial = $page->getSection('testimonial');
        $timeline = $page->getSection('timeline', []);
        $cta = $page->getSection('cta');
    @endphp

    {{-- Breadcrumb --}}
    <section class="pt-24 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6 py-6">
            <a href="{{ localized_route('references') }}" class="flex items-center gap-2 text-[0.875rem] text-muted-foreground hover:text-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Zurück zu allen Referenzen
            </a>
        </div>
    </section>

    {{-- Hero Section --}}
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up">
                    {{-- Category Badge --}}
                    @if($hero['category'] ?? false)
                    <div class="inline-flex items-center gap-2 px-4 py-2 mb-8 border border-accent/30 bg-accent/5">
                        <x-frontend.icon name="tag" class="w-3.5 h-3.5 text-accent" />
                        <span class="text-[0.8125rem] uppercase tracking-wider text-accent">{{ $hero['category'] }}</span>
                    </div>
                    @endif

                    {{-- Title & Tagline --}}
                    <h1 class="mb-4">{{ $page->title }}</h1>
                    @if($hero['tagline'] ?? false)
                    <p class="text-[1.25rem] text-muted-foreground leading-relaxed max-w-[900px] mb-10">
                        {{ $hero['tagline'] }}
                    </p>
                    @endif

                    {{-- Meta Grid --}}
                    @if(count($meta) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 p-8 border border-border bg-background">
                        @foreach($meta as $item)
                        <div>
                            <p class="text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">{{ $item['label'] }}</p>
                            @if(isset($item['link']))
                            <a href="{{ $item['link'] }}" target="_blank" rel="noopener" class="text-[0.9375rem] font-medium hover:text-accent transition-colors flex items-center gap-1">
                                {{ $item['value'] }}
                                <x-frontend.icon name="external-link" class="w-3.5 h-3.5" />
                            </a>
                            @else
                            <p class="text-[0.9375rem] font-medium">{{ $item['value'] }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Project Description --}}
    @if($description ?? false)
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up">
                    <h2 class="mb-8 text-[1.375rem]">{{ $description['title'] ?? 'Projektbeschreibung' }}</h2>
                    <div class="prose prose-lg max-w-none text-[1.0625rem] leading-relaxed text-muted-foreground">
                        {!! nl2br(e($description['text'])) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Challenge & Solution --}}
    @if(($challenge ?? false) || ($solution ?? false))
    <section class="py-20 border-t border-border bg-gradient-to-b from-muted/5 to-transparent">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="grid md:grid-cols-2 gap-8">
                    @if($challenge ?? false)
                    <div class="motion motion-fade-up p-8 border-l-4 border-red-500 bg-background">
                        <h3 class="mb-4 text-[1.125rem] flex items-center gap-2">
                            <span class="text-red-500">⚠</span>
                            {{ $challenge['title'] ?? 'Herausforderung' }}
                        </h3>
                        @if($challenge['description'] ?? $challenge['text'] ?? false)
                        <p class="text-[0.9375rem] leading-relaxed text-muted-foreground mb-4">
                            {{ $challenge['description'] ?? $challenge['text'] }}
                        </p>
                        @endif
                        @if($challenge['items'] ?? false)
                        <ul class="space-y-2">
                            @foreach($challenge['items'] as $item)
                            <li class="flex items-start gap-3 text-[0.9375rem] text-muted-foreground">
                                <span class="text-red-500 mt-0.5">×</span>
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endif

                    @if($solution ?? false)
                    <div class="motion motion-fade-up motion-delay-1 p-8 border-l-4 border-green-500 bg-background">
                        <h3 class="mb-4 text-[1.125rem] flex items-center gap-2">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-green-500" />
                            {{ $solution['title'] ?? 'Unsere Lösung' }}
                        </h3>
                        @if($solution['description'] ?? $solution['text'] ?? false)
                        <p class="text-[0.9375rem] leading-relaxed text-muted-foreground mb-4">
                            {{ $solution['description'] ?? $solution['text'] }}
                        </p>
                        @endif
                        @if($solution['items'] ?? false)
                        <ul class="space-y-2">
                            @foreach($solution['items'] as $item)
                            <li class="flex items-start gap-3 text-[0.9375rem]">
                                <x-frontend.icon name="check-circle" class="w-4 h-4 text-green-500 mt-0.5 shrink-0" />
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Tech Stack & Impact Results --}}
    @if(count($techStack) > 0 || count($impactResults) > 0)
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="grid md:grid-cols-2 gap-12">
                    @if(count($techStack) > 0)
                    <div class="motion motion-fade-up">
                        <h3 class="mb-6 text-[1.125rem] flex items-center gap-3">
                            <x-frontend.icon name="layers" class="w-5 h-5 text-accent" />
                            Technischer Stack
                        </h3>
                        <div class="space-y-3">
                            @foreach($techStack as $tech)
                            <div class="flex items-center gap-3 p-3 border-l-2 border-foreground bg-muted/10">
                                <x-frontend.icon name="code" class="w-4 h-4 text-accent shrink-0" />
                                <span class="text-[0.875rem] font-mono">{{ $tech }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(count($impactResults) > 0)
                    <div class="motion motion-fade-up motion-delay-1">
                        <h3 class="mb-6 text-[1.125rem] flex items-center gap-3">
                            <x-frontend.icon name="zap" class="w-5 h-5 text-accent" />
                            Ergebnis & Impact
                        </h3>
                        <div class="space-y-3">
                            @foreach($impactResults as $result)
                            <div class="flex items-start gap-3 p-4 border border-border bg-background">
                                <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                                <span class="text-[0.9375rem]">{{ $result }}</span>
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

    {{-- Technologies Tags --}}
    @if(count($technologies) > 0)
    <section class="py-16 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up">
                    <h3 class="mb-6 text-[1rem] uppercase tracking-wider text-muted-foreground">Eingesetzte Technologien</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach($technologies as $tech)
                        <span class="px-4 py-2 text-[0.875rem] font-mono border border-border bg-background hover:border-foreground transition-colors">
                            {{ $tech }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Features & Functions --}}
    @if(count($features) > 0)
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up mb-16">
                    <h2 class="text-[1.75rem] mb-4">Features & Funktionen</h2>
                    <p class="text-[1.0625rem] text-muted-foreground">Die wichtigsten Funktionen im Überblick</p>
                </div>

                @php
                    $hasMockups = collect($features)->contains(fn($f) => isset($f['mockup']));
                @endphp

                <div class="space-y-20">
                    @foreach($features as $index => $feature)
                    <div class="motion motion-fade-up grid lg:grid-cols-2 gap-12 items-center {{ $index % 2 === 1 ? 'lg:flex-row-reverse' : '' }}">
                        {{-- Image/Mockup --}}
                        <div class="{{ $index % 2 === 1 ? 'lg:order-2' : '' }}">
                            @if($feature['mockup'] ?? false)
                                {{-- Stylized Mockup Component --}}
                                <div class="flex items-center justify-center py-8">
                                    @php
                                        $mockupParts = explode(':', $feature['mockup']);
                                        $mockupType = $mockupParts[0];
                                        $mockupVariant = $mockupParts[1] ?? 'default';
                                    @endphp
                                    @if($mockupType === 'time-tracking')
                                        <x-reference-mockups.time-tracking :variant="$mockupVariant" />
                                    @elseif($mockupType === 'ecommerce-tablet')
                                        <x-reference-mockups.ecommerce-tablet :variant="$mockupVariant" />
                                    @elseif($mockupType === 'cosmetics-crm')
                                        <x-reference-mockups.cosmetics-crm :variant="$mockupVariant" />
                                    @elseif($mockupType === 'cosmetics-shop')
                                        <x-reference-mockups.cosmetics-shop :variant="$mockupVariant" />
                                    @endif
                                </div>
                            @elseif($feature['image'] ?? false)
                                <img src="{{ $feature['image'] }}" alt="{{ $feature['title'] }}" class="w-full border border-border">
                            @else
                                <div class="aspect-[4/3] bg-muted/20 border border-border flex items-center justify-center">
                                    <x-frontend.icon name="image" class="w-16 h-16 text-muted-foreground/30" />
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="{{ $index % 2 === 1 ? 'lg:order-1' : '' }}">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-[0.875rem] font-mono text-accent">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <div class="h-px flex-1 bg-border"></div>
                            </div>
                            <h3 class="text-[1.375rem] mb-4">{{ $feature['title'] }}</h3>
                            @if($feature['description'] ?? false)
                            <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                                {{ $feature['description'] }}
                            </p>
                            @endif
                            @if($feature['items'] ?? false)
                            <ul class="space-y-2">
                                @foreach($feature['items'] as $item)
                                <li class="flex items-start gap-3 text-[0.9375rem]">
                                    <x-frontend.icon name="check-circle" class="w-4 h-4 text-accent shrink-0 mt-0.5" />
                                    <span>{{ $item }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Confidentiality Notice - only show for specific pages --}}
                @php
                    $showConfidentialityNotice = $hasMockups && !in_array($page->getTranslation('slug', 'de'), [
                        'kosmetikerin-ecommerce-app',
                        'gewapur-ecommerce'
                    ]);
                @endphp
                @if($showConfidentialityNotice)
                <div class="motion motion-fade-up mt-16 p-6 bg-muted/10 border border-border">
                    <div class="flex items-start gap-4">
                        <x-frontend.icon name="shield" class="w-5 h-5 text-muted-foreground shrink-0 mt-0.5" />
                        <div>
                            <p class="text-[0.875rem] text-muted-foreground leading-relaxed">
                                {{ app()->getLocale() === 'en'
                                    ? 'To protect our clients\' business processes, we display stylized representations instead of actual screenshots. For detailed information about this project, please '
                                    : 'Zum Schutz der Geschäftsprozesse unserer Kunden zeigen wir stilisierte Darstellungen anstelle von echten Screenshots. Für detaillierte Informationen zu diesem Projekt nehmen Sie bitte ' }}
                                <a href="{{ localized_route('contact') }}" class="text-foreground hover:text-accent underline underline-offset-2">
                                    {{ app()->getLocale() === 'en' ? 'contact us' : 'Kontakt mit uns auf' }}</a>.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Technical Details --}}
    @if(count($technicalDetails) > 0)
    <section class="py-20 border-t border-border bg-gradient-to-b from-muted/5 to-transparent">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up mb-12">
                    <h2 class="text-[1.75rem] mb-4">Technische Details</h2>
                    <p class="text-[1.0625rem] text-muted-foreground">Architektur und Implementierung</p>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($technicalDetails as $index => $detail)
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }} p-8 border border-border bg-background hover:border-foreground hover:shadow-lg transition-all">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-3 border border-border">
                                <x-frontend.icon :name="$detail['icon'] ?? 'code'" class="w-6 h-6" />
                            </div>
                            <h3 class="text-[1.125rem]">{{ $detail['title'] }}</h3>
                        </div>
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                            {{ $detail['description'] }}
                        </p>
                        @if($detail['items'] ?? false)
                        <ul class="space-y-2">
                            @foreach($detail['items'] as $item)
                            <li class="flex items-start gap-2 text-[0.875rem] font-mono">
                                <span class="text-accent">→</span>
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Results --}}
    @if(count($results) > 0)
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up mb-12">
                    <h2 class="text-[1.75rem] mb-4">Ergebnisse</h2>
                    <p class="text-[1.0625rem] text-muted-foreground">Messbare Verbesserungen durch das Projekt</p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($results as $index => $result)
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} p-8 border border-border bg-background text-center">
                        <p class="text-[2.5rem] lg:text-[3rem] font-bold text-accent mb-2">{{ $result['value'] }}</p>
                        <p class="text-[0.9375rem] text-muted-foreground">{{ $result['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Testimonial --}}
    @if($testimonial ?? false)
    <section class="py-20 border-t border-border bg-foreground text-background">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px] mx-auto">
                <div class="motion motion-fade-up text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-8 opacity-30" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.768-.695-1.327-.825-.55-.13-1.07-.14-1.54-.03-.16-.94.1-1.95.76-3.02.66-1.06 1.515-1.86 2.56-2.4L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.003z"/>
                    </svg>
                    <blockquote class="text-[1.5rem] lg:text-[1.75rem] leading-relaxed mb-8 font-light">
                        {{ $testimonial['quote'] }}
                    </blockquote>
                    <div>
                        <p class="text-[1.0625rem] font-medium">{{ $testimonial['author'] }}</p>
                        @if($testimonial['role'] ?? false)
                        <p class="text-[0.9375rem] opacity-70">{{ $testimonial['role'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Timeline / Project Process --}}
    @if(count($timeline) > 0)
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up mb-12">
                    <h2 class="text-[1.75rem] mb-4">Projektverlauf</h2>
                    <p class="text-[1.0625rem] text-muted-foreground">Von der Idee bis zur Umsetzung</p>
                </div>

                <div class="relative">
                    {{-- Timeline Line --}}
                    <div class="absolute left-6 top-0 bottom-0 w-px bg-border hidden md:block"></div>

                    <div class="space-y-8">
                        @foreach($timeline as $index => $step)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} relative pl-0 md:pl-16">
                            {{-- Timeline Dot --}}
                            <div class="absolute left-4 top-8 w-5 h-5 rounded-full border-2 border-foreground bg-background hidden md:flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-accent"></div>
                            </div>

                            <div class="p-8 border border-border bg-background hover:border-foreground transition-colors">
                                <div class="flex items-center gap-4 mb-4">
                                    <span class="text-[0.875rem] font-mono text-accent">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <h3 class="text-[1.125rem]">{{ $step['title'] }}</h3>
                                    @if($step['duration'] ?? false)
                                    <span class="ml-auto text-[0.8125rem] text-muted-foreground">{{ $step['duration'] }}</span>
                                    @endif
                                </div>
                                <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">
                                    {{ $step['description'] }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up p-12 border-2 border-foreground bg-foreground/[0.02] text-center">
                    <h2 class="mb-4 text-[1.375rem]">
                        {{ $cta['title'] ?? 'Ähnliches Projekt geplant?' }}
                    </h2>
                    <p class="mb-8 text-[1.0625rem] text-muted-foreground max-w-[700px] mx-auto">
                        {{ $cta['subtitle'] ?? 'Lassen Sie uns in einem unverbindlichen Gespräch besprechen, wie wir Ihr Projekt umsetzen können.' }}
                    </p>
                    <button
                        type="button"
                        onclick="Livewire.dispatch('openContactModal')"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all"
                    >
                        {{ $cta['button_text'] ?? 'Projekt besprechen' }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Related References --}}
    @if($otherReferences->count() > 0)
    <section class="py-20 border-t border-border bg-gradient-to-b from-muted/5 to-transparent">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="motion motion-fade-up mb-12">
                <h2 class="text-[1.5rem] mb-3">Weitere Projekte</h2>
                <p class="text-[1.0625rem] text-muted-foreground">Das könnte Sie auch interessieren</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($otherReferences as $index => $reference)
                @php
                    $refHero = $reference->getSection('hero');
                @endphp
                <a href="{{ localized_route('references.show', ['slug' => $reference->slug]) }}"
                   class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }} group block border border-border hover:border-foreground hover:shadow-lg transition-all bg-background">
                    <div class="p-6">
                        @if($refHero['category'] ?? false)
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 mb-4 bg-accent/5 border border-accent/20">
                            <x-frontend.icon name="tag" class="w-2.5 h-2.5 text-accent" />
                            <span class="text-[0.6875rem] text-accent uppercase tracking-wider font-medium">{{ $refHero['category'] }}</span>
                        </div>
                        @endif
                        <h3 class="text-[1.125rem] mb-3 leading-tight group-hover:text-accent transition-colors">
                            {{ $reference->title }}
                        </h3>
                        @if($refHero['tagline'] ?? false)
                        <p class="text-[0.875rem] text-muted-foreground leading-relaxed mb-4">
                            {{ Str::limit($refHero['tagline'], 100) }}
                        </p>
                        @endif
                        <span class="inline-flex items-center gap-2 text-[0.875rem] font-medium group-hover:gap-3 transition-all">
                            Projekt ansehen
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

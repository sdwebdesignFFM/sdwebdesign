<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $intro = $page->getSection('intro');
        $whenUseful = $page->getSection('when_useful');
        $useCaseCategories = $page->getSection('use_case_categories', []);
        $challenge = $page->getSection('challenge');
        $approach = $page->getSection('approach');
        $cardsIntro = $page->getSection('cards_intro');
        $process = $page->getSection('process');
        $capabilities = $page->getSection('capabilities');
        $differentiation = $page->getSection('differentiation');
        $growth = $page->getSection('growth');
        $techStack = $page->getSection('tech_stack');
        $useCases = $page->getSection('use_cases');
        $benefits = $page->getSection('benefits');
        $relatedServices = $page->getSection('related_services');
        $relatedGuideSlugs = $page->getSection('related_guides', []);
        $cta = $page->getSection('cta');

        // Optional bundle-hub blocks (used on Gründerpaket etc.) — render only when set.
        $package = $page->getSection('package', []);
        $packageItems = is_array($package['items'] ?? null) ? $package['items'] : [];
        $hasPackage = ! empty($packageItems);

        $pricing = $page->getSection('pricing', []);
        $timeline = $page->getSection('timeline', []);
        $hasPricingOrTimeline = ! empty($pricing['label']) || ! empty($timeline['label']);

        // Fetch related guides
        $relatedGuidePages = collect();
        foreach ($relatedGuideSlugs as $guideSlug) {
            $guide = \App\Models\Page::findBySlug($guideSlug);
            if ($guide && $guide->type === \App\Models\Page::TYPE_GUIDE) {
                $relatedGuidePages->push($guide);
            }
        }
    @endphp

    {{-- Breadcrumb --}}
    <section class="pt-24">
        <div class="max-w-[1400px] mx-auto px-6 py-6">
            <a href="{{ localized_route('solutions') }}" class="flex items-center gap-2 text-[0.875rem] text-muted-foreground hover:text-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                {{ app()->getLocale() === 'en' ? 'Back to all solutions' : 'Zurück zu allen Lösungen' }}
            </a>
        </div>
    </section>

    {{-- Hero Section --}}
    <section class="relative py-12 md:py-16 overflow-hidden border-t border-b border-border">
        <div class="absolute inset-0 opacity-[0.02] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up flex flex-col md:flex-row items-start gap-4 md:gap-8">
                    {{-- Icon Box --}}
                    @if($hero['icon'] ?? false)
                    <div class="p-4 md:p-5 border-2 border-foreground shrink-0">
                        <x-frontend.icon :name="$hero['icon']" class="w-8 h-8 md:w-10 md:h-10" />
                    </div>
                    @endif

                    <div class="flex-1">
                        {{-- Number --}}
                        @if($hero['number'] ?? false)
                        <span class="text-[0.875rem] font-mono text-muted-foreground mb-2 block">
                            {{ $hero['number'] }}
                        </span>
                        @endif

                        <h1 class="mb-4">{{ $page->title }}</h1>

                        @if($hero['subtitle'] ?? false)
                        <p class="text-[1rem] md:text-[1.125rem] text-muted-foreground leading-relaxed">
                            {{ $hero['subtitle'] }}
                        </p>
                        @endif
                    </div>
                </div>

                {{-- Intro Text --}}
                @if($intro['text'] ?? false)
                <div class="motion motion-fade-up mt-10">
                    <p class="text-[1rem] leading-relaxed">
                        {{ $intro['text'] }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Pricing + Timeline signal — renders only when set (bundle hubs like Gründerpaket) --}}
    @if($hasPricingOrTimeline)
    <section class="py-10 border-b border-border bg-muted/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px] flex flex-col md:flex-row gap-6 md:gap-10">
                @if(! empty($pricing['label']))
                <div class="flex-1">
                    <div class="text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Preis</div>
                    <div class="text-[1.25rem] font-medium">{{ $pricing['label'] }}</div>
                    @if(! empty($pricing['note']))
                    <div class="text-[0.875rem] text-muted-foreground mt-1">{{ $pricing['note'] }}</div>
                    @endif
                </div>
                @endif
                @if(! empty($timeline['label']))
                <div class="flex-1">
                    <div class="text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Zeitrahmen</div>
                    <div class="text-[1.25rem] font-medium">{{ $timeline['label'] }}</div>
                    @if(! empty($timeline['note']))
                    <div class="text-[0.875rem] text-muted-foreground mt-1">{{ $timeline['note'] }}</div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Package contents checklist — bundle hubs (Gründerpaket) only --}}
    @if($hasPackage)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                @if(! empty($package['headline']))
                <h2 class="mb-4">{{ $package['headline'] }}</h2>
                @endif
                @if(! empty($package['intro']))
                <p class="text-[1rem] text-muted-foreground mb-10 max-w-[700px]">{{ $package['intro'] }}</p>
                @endif

                <ul class="grid md:grid-cols-2 gap-x-10 gap-y-5">
                    @foreach($packageItems as $item)
                        @php
                            $itemName = $item['name'] ?? null;
                            $itemDescription = $item['description'] ?? null;
                        @endphp
                        @if($itemName)
                        <li class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-accent shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <div>
                                <div class="font-medium">{{ $itemName }}</div>
                                @if($itemDescription)
                                <div class="text-[0.9375rem] text-muted-foreground leading-relaxed mt-1">{{ $itemDescription }}</div>
                                @endif
                            </div>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
    @endif

    {{-- Challenge & Approach Section --}}
    @if(($challenge['title'] ?? false) || ($approach['title'] ?? false))
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-8 max-w-[1100px]">
                {{-- Challenge --}}
                @if($challenge['title'] ?? false)
                <div class="motion motion-fade-up p-6 bg-red-500/10 border border-red-500/30 border-l-4 border-l-red-500">
                    <div class="flex items-center gap-2 mb-3">
                        <x-frontend.icon name="x-circle" class="w-5 h-5 text-red-500" />
                        <h3 class="text-[1.125rem] font-medium">{{ $challenge['title'] }}</h3>
                    </div>
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">
                        {{ $challenge['text'] }}
                    </p>
                </div>
                @endif

                {{-- Approach --}}
                @if($approach['title'] ?? false)
                <div class="motion motion-fade-up motion-delay-1 p-6 bg-green-500/10 border border-green-500/30 border-l-4 border-l-green-500">
                    <div class="flex items-center gap-2 mb-3">
                        <x-frontend.icon name="check-circle" class="w-5 h-5 text-green-500" />
                        <h3 class="text-[1.125rem] font-medium">{{ $approach['title'] }}</h3>
                    </div>
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">
                        {{ $approach['text'] }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- When Useful Section --}}
    @if($whenUseful['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $whenUseful['title'] }}</h2>

                    @if($whenUseful['intro'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground mb-6">
                        {{ $whenUseful['intro'] }}
                    </p>
                    @endif

                    @if($whenUseful['conditions'] ?? false)
                    <div class="space-y-3 mb-6">
                        @foreach($whenUseful['conditions'] as $condition)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span>{{ $condition }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($whenUseful['note'] ?? false)
                    <p class="text-[0.875rem] text-muted-foreground italic">
                        {{ $whenUseful['note'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Use Case Categories Section --}}
    @if(count($useCaseCategories) > 0)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up mb-10">
                    <h2 class="text-[1.375rem]">{{ app()->getLocale() === 'en' ? 'Typical Use Cases' : 'Typische Einsatzbereiche' }}</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($useCaseCategories as $index => $category)
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }}">
                        <div class="p-6 border border-border bg-background h-full">
                            <h3 class="text-[1.125rem] font-medium mb-3">{{ $category['title'] }}</h3>

                            @if($category['description'] ?? false)
                            <p class="text-[0.875rem] text-muted-foreground mb-4">
                                {{ $category['description'] }}
                            </p>
                            @endif

                            @if($category['items'] ?? false)
                            <div class="space-y-2 mb-4">
                                @foreach($category['items'] as $item)
                                <div class="flex items-center gap-2 text-[0.875rem]">
                                    <span class="text-accent">→</span>
                                    <span>{{ $item }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            @if(($category['link_slug'] ?? false) && ($category['link_text'] ?? false))
                            <div class="mt-auto pt-2">
                                <span class="inline-flex items-center gap-2 text-accent text-[0.875rem] font-medium group-hover:underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                                    </svg>
                                    {{ $category['link_text'] }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Cards Intro Section --}}
    @if($cardsIntro['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.5rem] mb-4">{{ $cardsIntro['title'] }}</h2>
                    @if($cardsIntro['text'] ?? false)
                    <p class="text-[1rem] text-muted-foreground leading-relaxed">
                        {{ $cardsIntro['text'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Child Pages (Variant Cards) --}}
    @if($childPages->count() > 0)
    <section class="py-8">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px] space-y-8">
                @foreach($childPages as $index => $child)
                @php
                    $childHero = $child->getSection('hero');
                    $rawFeatures = $child->getSection('features', []);
                    // Support both old format (array of strings) and new format (object with items)
                    $childFeatures = is_array($rawFeatures) && isset($rawFeatures['items'])
                        ? $rawFeatures['items']
                        : (is_array($rawFeatures) && !isset($rawFeatures['title']) ? $rawFeatures : []);
                    $childIdealFor = $child->getSection('ideal_for');
                @endphp
                <a href="{{ $child->getUrl() }}" class="motion motion-fade-up block group border border-border hover:border-foreground hover:shadow-lg transition-all bg-background p-8">
                    {{-- Header --}}
                    <div class="mb-6">
                        <span class="text-[1.25rem] font-mono text-muted-foreground">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <h3 class="text-[1.25rem] md:text-[1.5rem] font-medium mt-1 group-hover:text-accent transition-colors">
                            {{ $child->title }}
                        </h3>
                        @if($childHero['tagline'] ?? false)
                        <p class="text-accent text-[0.9375rem] mt-1">
                            {{ $childHero['tagline'] }}
                        </p>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($childHero['description'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $childHero['description'] }}
                    </p>
                    @endif

                    {{-- Features & Ideal For --}}
                    @if(count($childFeatures) > 0 || ($childIdealFor ?? false))
                    <div class="grid md:grid-cols-2 gap-8 mb-6">
                        {{-- Features --}}
                        @if(count($childFeatures) > 0)
                        <div>
                            <h4 class="text-[0.75rem] uppercase tracking-widest text-muted-foreground mb-4">
                                {{ app()->getLocale() === 'en' ? 'Features' : 'Funktionen' }}
                            </h4>
                            <div class="space-y-2">
                                @foreach($childFeatures as $feature)
                                <div class="flex items-start gap-2 text-[0.875rem]">
                                    <x-frontend.icon name="check-circle" class="w-4 h-4 text-accent shrink-0 mt-0.5" />
                                    <span>{{ $feature }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Ideal For --}}
                        @if($childIdealFor ?? false)
                        <div>
                            <h4 class="text-[0.75rem] uppercase tracking-widest text-muted-foreground mb-4">
                                {{ app()->getLocale() === 'en' ? 'Ideal for' : 'Ideal für' }}
                            </h4>
                            <div class="p-4 bg-muted/30 text-[0.875rem]">
                                {{ $childIdealFor }}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Learn more indicator --}}
                    <span class="inline-flex items-center gap-2 text-[0.9375rem] font-medium group-hover:text-accent group-hover:gap-3 transition-all">
                        {{ app()->getLocale() === 'en' ? 'Learn more' : 'Mehr erfahren' }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Process Section --}}
    @if($process['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-4">{{ $process['title'] }}</h2>

                    @if($process['intro'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground mb-8">
                        {{ $process['intro'] }}
                    </p>
                    @endif

                    @if($process['steps'] ?? false)
                    <div class="space-y-6">
                        @foreach($process['steps'] as $index => $step)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex gap-6 p-6 border border-border bg-background">
                            <div class="shrink-0 w-10 h-10 flex items-center justify-center border-2 border-foreground text-[0.875rem] font-mono font-medium">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h3 class="text-[1rem] font-medium mb-2">{{ $step['title'] }}</h3>
                                @if($step['description'] ?? false)
                                <p class="text-[0.875rem] text-muted-foreground">
                                    {{ $step['description'] }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Differentiation Section --}}
    @if($differentiation['title'] ?? false)
    <section class="py-16 border-b border-border bg-muted/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-4">{{ $differentiation['title'] }}</h2>

                    @if($differentiation['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {!! nl2br(e($differentiation['text'])) !!}
                    </p>
                    @endif

                    @if(($differentiation['link_slug'] ?? false) && ($differentiation['link_text'] ?? false))
                    @php
                        $linkedPage = \App\Models\Page::findBySlug($differentiation['link_slug']);
                    @endphp
                    @if($linkedPage)
                    <a href="{{ $linkedPage->getUrl() }}" class="inline-flex items-center gap-2 text-accent hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                        {{ $differentiation['link_text'] }}
                    </a>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Growth Section --}}
    @if($growth['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up p-8 bg-accent/5 border border-accent/20">
                    <div class="flex items-start gap-3 mb-3">
                        <x-frontend.icon name="zap" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                        <h3 class="text-[1.125rem] font-medium">{{ $growth['title'] }}</h3>
                    </div>
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed pl-8">
                        {!! nl2br(e($growth['text'])) !!}
                    </p>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Legacy Capabilities & Tech Stack --}}
    @if(($capabilities['items'] ?? false) || ($techStack['items'] ?? false))
    <section class="py-16 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="grid md:grid-cols-2 gap-12">
                    {{-- Capabilities --}}
                    @if($capabilities['items'] ?? false)
                    <div class="motion motion-fade-up">
                        <div class="flex items-center gap-2 mb-4">
                            <x-frontend.icon name="settings" class="w-5 h-5 text-muted-foreground" />
                            <h3 class="text-[1.125rem] font-medium">{{ $capabilities['title'] ?? 'Systemfähigkeiten' }}</h3>
                        </div>
                        @if($capabilities['intro'] ?? false)
                        <p class="text-[0.875rem] text-muted-foreground mb-4">{{ $capabilities['intro'] }}</p>
                        @endif
                        <div class="space-y-3">
                            @foreach($capabilities['items'] as $item)
                            <div class="flex items-start gap-3 p-3 border border-border bg-background text-[0.875rem]">
                                <x-frontend.icon name="check-circle" class="w-4 h-4 text-accent shrink-0 mt-0.5" />
                                <span>{{ $item }}</span>
                            </div>
                            @endforeach
                        </div>
                        @if($capabilities['note'] ?? false)
                        <p class="text-[0.8125rem] text-muted-foreground italic mt-4">{{ $capabilities['note'] }}</p>
                        @endif
                    </div>
                    @endif

                    {{-- Tech Stack --}}
                    @if($techStack['items'] ?? false)
                    <div class="motion motion-fade-up motion-delay-1">
                        <div class="flex items-center gap-2 mb-4">
                            <x-frontend.icon name="box" class="w-5 h-5 text-muted-foreground" />
                            <h3 class="text-[1.125rem] font-medium">{{ $techStack['title'] ?? 'Technischer Stack' }}</h3>
                        </div>
                        @if($techStack['intro'] ?? false)
                        <p class="text-[0.875rem] text-muted-foreground mb-4">{{ $techStack['intro'] }}</p>
                        @endif
                        <div class="space-y-3">
                            @foreach($techStack['items'] as $item)
                            <div class="flex items-start gap-3 p-3 border border-border bg-background text-[0.875rem] font-mono">
                                <span class="text-muted-foreground">→</span>
                                <span>{{ $item }}</span>
                            </div>
                            @endforeach
                        </div>
                        @if($techStack['note'] ?? false)
                        <p class="text-[0.8125rem] text-muted-foreground italic mt-4">{{ $techStack['note'] }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Use Cases & Benefits --}}
    @if(($useCases['items'] ?? false) || ($benefits['items'] ?? false))
    <section class="py-16 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="grid md:grid-cols-2 gap-12">
                    {{-- Use Cases --}}
                    @if($useCases['items'] ?? false)
                    <div class="motion motion-fade-up">
                        <h3 class="text-[1.125rem] font-medium mb-6">{{ $useCases['title'] ?? 'Typische Anwendungsfälle' }}</h3>
                        <div class="space-y-3">
                            @foreach($useCases['items'] as $item)
                            <div class="flex items-center gap-3 text-[0.9375rem]">
                                <span class="text-muted-foreground">→</span>
                                <span>{{ $item }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Benefits --}}
                    @if($benefits['items'] ?? false)
                    <div class="motion motion-fade-up motion-delay-1">
                        <h3 class="text-[1.125rem] font-medium mb-6">{{ $benefits['title'] ?? 'Ihre Vorteile' }}</h3>
                        <div class="space-y-3">
                            @foreach($benefits['items'] as $item)
                            <div class="flex items-center gap-3 text-[0.9375rem]">
                                <x-frontend.icon name="zap" class="w-4 h-4 text-accent shrink-0" />
                                <span>{{ $item }}</span>
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

    {{-- Related Services Section (SEO/SEA) --}}
    @if($relatedServices['title'] ?? false)
    <section class="py-16 border-t border-border bg-accent/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <div class="flex items-start gap-4 mb-4">
                        <x-frontend.icon name="link" class="w-5 h-5 text-accent shrink-0 mt-1" />
                        <h3 class="text-[1.125rem] font-medium">{{ $relatedServices['title'] }}</h3>
                    </div>

                    @if($relatedServices['note'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground mb-6 pl-9">
                        {{ $relatedServices['note'] }}
                    </p>
                    @endif

                    @if($relatedServices['links'] ?? false)
                    <div class="flex flex-wrap gap-4 pl-9">
                        @foreach($relatedServices['links'] as $link)
                        @php
                            $linkedPage = \App\Models\Page::findByType($link['type'] ?? '');
                        @endphp
                        @if($linkedPage)
                        <a href="{{ $linkedPage->getUrl() }}" class="inline-flex items-center gap-2 px-4 py-2 border border-accent text-accent hover:bg-accent hover:text-background transition-all text-[0.875rem]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                            {{ $link['title'] }}
                        </a>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Related Guides Section --}}
    @if($relatedGuidePages->count() > 0)
    <section class="py-16 border-t border-border bg-muted/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up mb-10">
                    <div class="flex items-center gap-3 mb-3">
                        <x-frontend.icon name="book-open" class="w-5 h-5 text-accent" />
                        <h2 class="text-[1.375rem]">{{ app()->getLocale() === 'en' ? 'Guides on this topic' : 'Ratgeber zu diesem Thema' }}</h2>
                    </div>
                    <p class="text-[0.9375rem] text-muted-foreground">
                        {{ app()->getLocale() === 'en' ? 'Decision guides for your project' : 'Entscheidungshilfen für Ihr Projekt' }}
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedGuidePages as $index => $guide)
                    @php
                        $guideHero = $guide->getSection('hero');
                        $guideIntro = $guide->getSection('intro');
                    @endphp
                    <a href="{{ $guide->getUrl() }}"
                       class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }} group block border border-border hover:border-foreground hover:shadow-lg transition-all bg-background p-6">
                        @if($guideHero['badge'] ?? false)
                        <span class="inline-block text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-2">
                            {{ $guideHero['badge'] }}
                        </span>
                        @endif
                        <h3 class="text-[1rem] font-medium group-hover:text-accent transition-colors mb-2">
                            {{ $guide->title }}
                        </h3>
                        @if($guideIntro['text'] ?? false)
                        <p class="text-[0.875rem] text-muted-foreground leading-relaxed mb-3 line-clamp-2">
                            {{ Str::limit($guideIntro['text'], 100) }}
                        </p>
                        @endif
                        <span class="inline-flex items-center gap-2 text-[0.875rem] font-medium group-hover:gap-3 transition-all">
                            {{ app()->getLocale() === 'en' ? 'Read guide' : 'Ratgeber lesen' }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Betrieb & Wartung Section --}}
    <x-frontend.maintenance-block variant="compact" />

    {{-- CTA Section --}}
    @if($cta['title'] ?? false)
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up p-12 border-2 border-foreground text-center">
                    <h2 class="text-[1.5rem] mb-4">{{ $cta['title'] }}</h2>
                    @if($cta['subtitle'] ?? false)
                    <p class="text-[1rem] text-muted-foreground mb-4 max-w-[600px] mx-auto">
                        {{ $cta['subtitle'] }}
                    </p>
                    @endif
                    @if($cta['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground mb-8 max-w-[600px] mx-auto text-left">
                        {!! nl2br(e($cta['text'])) !!}
                    </p>
                    @endif
                    <a href="{{ localized_route('contact') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all">
                        {{ $cta['button_text'] ?? (app()->getLocale() === 'en' ? 'Discuss project' : 'Projekt besprechen') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif
</x-layouts.frontend>

<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $solutionsIntro = $page->getSection('solutions_intro');
        $cta = $page->getSection('cta');
        $approach = $page->getSection('approach');
    @endphp

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    @if($hero['badge'] ?? false)
                    <div class="inline-block px-4 py-2 mb-8 border border-border bg-white">
                        <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">{{ $hero['badge'] }}</p>
                    </div>
                    @endif

                    <h1 class="mb-8">{{ $hero['title'] ?? 'Unsere Lösungen' }}</h1>

                    @if($hero['subtitle'] ?? false)
                    <p class="text-[1.125rem] leading-relaxed text-muted-foreground max-w-[700px]">
                        {{ $hero['subtitle'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Solutions Grid --}}
    @if($solutionPages->count() > 0)
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                @if($solutionsIntro['title'] ?? false)
                <div class="motion motion-fade-up mb-16">
                    <h2 class="mb-6">{{ $solutionsIntro['title'] }}</h2>
                    @if($solutionsIntro['subtitle'] ?? false)
                    <p class="text-[1.0625rem] text-muted-foreground leading-relaxed max-w-[800px]">
                        {{ $solutionsIntro['subtitle'] }}
                    </p>
                    @endif
                </div>
                @endif

                <div class="grid lg:grid-cols-2 gap-8">
                    @foreach($solutionPages as $index => $solutionPage)
                    @php
                        $solutionHero = $solutionPage->getSection('hero');
                        $keyFeatures = $solutionPage->getSection('key_features', []);
                        $overviewUseCases = $solutionPage->getSection('overview_use_cases', []);
                    @endphp
                    <article class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} group border-2 border-border hover:border-foreground transition-all bg-white hover:shadow-2xl">
                        <div class="p-10">
                            {{-- Header --}}
                            <div class="flex items-start gap-6 mb-8">
                                <div class="p-5 border-2 border-border group-hover:border-foreground transition-all shrink-0">
                                    <x-frontend.icon :name="$solutionHero['icon'] ?? 'code'" class="w-8 h-8" />
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="text-[0.875rem] font-mono text-muted-foreground">
                                            {{ $solutionHero['number'] ?? str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <div class="h-px flex-1 bg-border group-hover:bg-foreground transition-colors"></div>
                                    </div>
                                    <h3 class="mb-3 text-[1.375rem] leading-tight">
                                        {{ $solutionPage->title }}
                                    </h3>
                                    @if($solutionHero['tagline'] ?? false)
                                    <p class="text-[0.9375rem] text-muted-foreground italic">
                                        {{ $solutionHero['tagline'] }}
                                    </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Description --}}
                            @if($solutionHero['description'] ?? false)
                            <p class="text-[0.9375rem] leading-relaxed mb-8">
                                {{ $solutionHero['description'] }}
                            </p>
                            @endif

                            {{-- Key Features --}}
                            @if(count($keyFeatures) > 0)
                            <div class="mb-8">
                                <h4 class="text-[0.875rem] uppercase tracking-wider text-muted-foreground mb-4">
                                    Kernfunktionen
                                </h4>
                                <div class="space-y-2">
                                    @foreach($keyFeatures as $feature)
                                    <div class="flex items-start gap-2 text-[0.875rem]">
                                        <x-frontend.icon name="check-circle" class="w-4 h-4 text-accent shrink-0 mt-0.5" />
                                        <span>{{ $feature }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Use Cases --}}
                            @if(count($overviewUseCases) > 0)
                            <div class="mb-8 p-4 bg-muted/20 border-l-2 border-accent">
                                <h4 class="text-[0.875rem] uppercase tracking-wider text-muted-foreground mb-3">
                                    Typische Anwendungen
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($overviewUseCases as $useCase)
                                    <span class="text-[0.8125rem] px-3 py-1 bg-white border border-border">
                                        {{ $useCase }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- CTA Button --}}
                            <a href="{{ route('solutions.show', $solutionPage->slug) }}"
                               class="w-full flex items-center justify-between gap-3 px-6 py-4 border-2 border-foreground hover:bg-foreground hover:text-background transition-all">
                                <span class="font-medium">Mehr erfahren</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    @if($cta['title'] ?? false)
    <section class="py-32 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up p-12 border-2 border-foreground bg-foreground/[0.02] text-center">
                    <h2 class="mb-6">{{ $cta['title'] }}</h2>
                    @if($cta['subtitle'] ?? false)
                    <p class="text-[1.0625rem] text-muted-foreground mb-10 max-w-[700px] mx-auto leading-relaxed">
                        {{ $cta['subtitle'] }}
                    </p>
                    @endif
                    @if($cta['button_text'] ?? false)
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-foreground text-background hover:bg-foreground/90 transition-all text-[1.0625rem]">
                        {{ $cta['button_text'] }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Technical Approach --}}
    @if($approach['title'] ?? false)
    <section class="py-32 border-t border-border bg-gradient-to-b from-muted/5 to-transparent">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up mb-16">
                    <h2 class="mb-6">{{ $approach['title'] }}</h2>
                    @if($approach['subtitle'] ?? false)
                    <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                        {{ $approach['subtitle'] }}
                    </p>
                    @endif
                </div>

                @if($approach['principles'] ?? false)
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($approach['principles'] as $index => $principle)
                    <div class="motion motion-fade-up motion-delay-{{ $index + 1 }} p-8 border border-border hover:border-foreground transition-all bg-white">
                        <div class="mb-4 w-12 h-px bg-accent"></div>
                        <h3 class="mb-4 text-[1.125rem]">{{ $principle['title'] }}</h3>
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">
                            {{ $principle['description'] }}
                        </p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif
</x-layouts.frontend>

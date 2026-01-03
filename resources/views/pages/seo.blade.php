<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $problem = $page->getSection('problem');
        $approach = $page->getSection('approach');
        $whenUseful = $page->getSection('when_useful');
        $focusAreas = $page->getSection('focus_areas', []);
        $differentiation = $page->getSection('differentiation');
        $process = $page->getSection('process');
        $closing = $page->getSection('closing');
        $cta = $page->getSection('cta');

        // Fetch SEA page for cross-link
        $seaPage = \App\Models\Page::findBySlug('sea');
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
    <section class="relative py-16 overflow-hidden border-t border-b border-border">
        <div class="absolute inset-0 opacity-[0.02] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up flex items-start gap-8">
                    {{-- Icon Box --}}
                    @if($hero['icon'] ?? false)
                    <div class="p-5 border-2 border-foreground shrink-0 hidden md:block">
                        <x-frontend.icon :name="$hero['icon']" class="w-10 h-10" />
                    </div>
                    @endif

                    <div class="flex-1">
                        <h1 class="mb-6">{{ $hero['title'] ?? $page->title }}</h1>

                        @if($hero['intro'] ?? false)
                        <div class="text-[1.0625rem] text-muted-foreground leading-relaxed space-y-4">
                            @foreach(explode("\n\n", $hero['intro']) as $paragraph)
                            <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Problem Section --}}
    @if($problem['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $problem['title'] }}</h2>

                    @if($problem['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $problem['text'] }}
                    </p>
                    @endif

                    @if($problem['subtitle'] ?? false)
                    <p class="text-[0.9375rem] font-medium mb-4">{{ $problem['subtitle'] }}</p>
                    @endif

                    @if($problem['items'] ?? false)
                    <div class="space-y-3">
                        @foreach($problem['items'] as $item)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <span class="text-muted-foreground shrink-0 mt-0.5">→</span>
                            <span>{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Approach Section --}}
    @if($approach['title'] ?? false)
    <section class="py-16 border-b border-border bg-accent/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $approach['title'] }}</h2>

                    @if($approach['text'] ?? false)
                    <div class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6 space-y-4">
                        @foreach(explode("\n", $approach['text']) as $paragraph)
                        @if(trim($paragraph))
                        <p>{{ $paragraph }}</p>
                        @endif
                        @endforeach
                    </div>
                    @endif

                    @if($approach['items'] ?? false)
                    <div class="space-y-3">
                        @foreach($approach['items'] as $item)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span>{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
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

                    @if($whenUseful['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $whenUseful['text'] }}
                    </p>
                    @endif

                    @if($whenUseful['conditions'] ?? false)
                    <div class="space-y-3">
                        @foreach($whenUseful['conditions'] as $condition)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span>{{ $condition }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Focus Areas Section (4 Cards) --}}
    @if(count($focusAreas) > 0)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1200px]">
                <div class="motion motion-fade-up mb-10">
                    <h2 class="text-[1.375rem]">{{ app()->getLocale() === 'en' ? 'Our Focus Areas' : 'Unsere Schwerpunkte' }}</h2>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($focusAreas as $index => $area)
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }}">
                        <div class="p-6 border border-border bg-background h-full">
                            <div class="flex items-start gap-4 mb-4">
                                @if($area['icon'] ?? false)
                                <div class="p-3 border border-border shrink-0">
                                    <x-frontend.icon :name="$area['icon']" class="w-6 h-6" />
                                </div>
                                @endif
                                <h3 class="text-[1.0625rem] font-medium pt-2">{{ $area['title'] }}</h3>
                            </div>

                            @if($area['text'] ?? false)
                            <p class="text-[0.875rem] text-muted-foreground leading-relaxed mb-4">
                                {{ $area['text'] }}
                            </p>
                            @endif

                            @if($area['items'] ?? false)
                            <div class="space-y-2 mb-4">
                                @foreach($area['items'] as $item)
                                <div class="flex items-center gap-2 text-[0.875rem]">
                                    <span class="text-accent">→</span>
                                    <span>{{ $item }}</span>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            @if($area['relevant_for'] ?? false)
                            <p class="text-[0.8125rem] text-muted-foreground mt-4 pt-4 border-t border-border">
                                <span class="font-medium">Relevant für:</span> {{ $area['relevant_for'] }}
                            </p>
                            @endif

                            @if($area['links'] ?? false)
                            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-border">
                                @foreach($area['links'] as $link)
                                @php
                                    $linkedPage = \App\Models\Page::findBySlug($link['slug'] ?? '');
                                @endphp
                                @if($linkedPage)
                                <a href="{{ $linkedPage->getUrl() }}" class="inline-flex items-center gap-1 text-[0.75rem] text-accent hover:underline">
                                    {{ $link['text'] }}
                                </a>
                                @endif
                                @endforeach
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

    {{-- Differentiation Section --}}
    @if($differentiation['title'] ?? false)
    <section class="py-16 border-b border-border bg-accent/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $differentiation['title'] }}</h2>

                    @if($differentiation['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $differentiation['text'] }}
                    </p>
                    @endif

                    @if($differentiation['items'] ?? false)
                    <div class="space-y-3 mb-6">
                        @foreach($differentiation['items'] as $item)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span>{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($differentiation['note'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">
                        {{ $differentiation['note'] }}
                    </p>
                    @endif

                    {{-- Link to SEA page --}}
                    @if($seaPage)
                    <div class="mt-6">
                        <a href="{{ $seaPage->getUrl() }}" class="inline-flex items-center gap-2 text-accent hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                            {{ app()->getLocale() === 'en' ? 'Search Engine Advertising (SEA)' : 'Suchmaschinenwerbung (SEA)' }}
                        </a>
                    </div>
                    @endif
                </div>
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
                    <h2 class="text-[1.375rem] mb-6">{{ $process['title'] }}</h2>

                    @if($process['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $process['text'] }}
                    </p>
                    @endif

                    @if($process['steps'] ?? false)
                    <div class="space-y-4">
                        @foreach($process['steps'] as $index => $step)
                        <div class="flex items-start gap-4 text-[0.9375rem]">
                            <span class="w-6 h-6 flex items-center justify-center border border-border text-[0.75rem] font-medium shrink-0 mt-0.5">{{ $index + 1 }}</span>
                            <span>{{ $step }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Closing Section --}}
    @if($closing['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $closing['title'] }}</h2>

                    @if($closing['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">
                        {{ $closing['text'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    @if($cta['title'] ?? false)
    <section class="py-20">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up p-12 border-2 border-foreground text-center">
                    <h2 class="text-[1.5rem] mb-4">{{ $cta['title'] }}</h2>
                    @if($cta['text'] ?? false)
                    <p class="text-[1rem] text-muted-foreground mb-8 max-w-[600px] mx-auto">
                        {{ $cta['text'] }}
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

<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $when = $page->getSection('when');
        $features = $page->getSection('features');
        $scenarioCategories = $page->getSection('scenario_categories', []);
        $benefits = $page->getSection('benefits', []);
        $limitations = $page->getSection('limitations');
        $differentiation = $page->getSection('differentiation');
        $integration = $page->getSection('integration');
        $growth = $page->getSection('growth');
        $process = $page->getSection('process');
        $scenarios = $page->getSection('scenarios', []);
        $nextSteps = $page->getSection('next_steps');
        $cta = $page->getSection('cta');

        // Legacy support for old content structure
        $challenge = $page->getSection('challenge');
        $approach = $page->getSection('approach');
        $whyNative = $page->getSection('why_native');
        $capabilities = $page->getSection('capabilities');
        $technical = $page->getSection('technical');
        $useCases = $page->getSection('use_cases');
        $oldBenefits = $page->getSection('old_benefits', []);

        // Determine parent for breadcrumb
        $parent = $page->parent;
    @endphp

    {{-- Breadcrumb --}}
    <section class="pt-24">
        <div class="max-w-[1400px] mx-auto px-6 py-6">
            @if($parent)
            <a href="{{ $parent->getUrl() }}" class="flex items-center gap-2 text-[0.875rem] text-muted-foreground hover:text-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                {{ app()->getLocale() === 'en' ? 'Back to' : 'Zurück zu' }} {{ $parent->title }}
            </a>
            @else
            <a href="{{ localized_route('solutions') }}" class="flex items-center gap-2 text-[0.875rem] text-muted-foreground hover:text-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                {{ app()->getLocale() === 'en' ? 'Back to all solutions' : 'Zurück zu allen Lösungen' }}
            </a>
            @endif
        </div>
    </section>

    {{-- Hero Section --}}
    <section class="relative py-16 overflow-hidden border-t border-b border-border">
        <div class="absolute inset-0 opacity-[0.02] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up flex items-start gap-8">
                    {{-- Icon Box (same as hub pages) --}}
                    @if($hero['icon'] ?? false)
                    <div class="p-5 border-2 border-foreground shrink-0 hidden md:block">
                        <x-frontend.icon :name="$hero['icon']" class="w-10 h-10" />
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

                        @if($hero['tagline'] ?? false)
                        <p class="text-[1.25rem] text-accent font-medium mb-6">
                            {{ $hero['tagline'] }}
                        </p>
                        @endif

                        @if($hero['description'] ?? false)
                        <p class="text-[1rem] text-muted-foreground leading-relaxed">
                            {{ $hero['description'] }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Native Section --}}
    @if($whyNative['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $whyNative['title'] }}</h2>

                    @if($whyNative['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $whyNative['text'] }}
                    </p>
                    @endif

                    @if($whyNative['items'] ?? false)
                    <div class="space-y-3">
                        @foreach($whyNative['items'] as $item)
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

    {{-- When Section (New Structure) --}}
    @if($when['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $when['title'] }}</h2>

                    @if($when['intro'] ?? $when['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground mb-6">
                        {{ $when['intro'] ?? $when['text'] }}
                    </p>
                    @endif

                    @if($when['conditions'] ?? false)
                    <div class="space-y-3 mb-8">
                        @foreach($when['conditions'] as $condition)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span>{{ $condition }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($when['note'] ?? false)
                    <p class="text-[0.875rem] text-muted-foreground italic">
                        {{ $when['note'] }}
                    </p>
                    @endif

                    @if($when['examples'] ?? false)
                    <div class="p-6 bg-muted/30 border-l-4 border-accent">
                        <p class="text-[0.875rem] font-medium mb-3">
                            {{ app()->getLocale() === 'en' ? 'Example:' : 'Beispiel:' }}
                        </p>
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">
                            {{ $when['examples'] }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Features Section --}}
    @if($features['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-4">{{ $features['title'] }}</h2>

                    @if($features['intro'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground mb-6">
                        {{ $features['intro'] }}
                    </p>
                    @endif

                    @if($features['items'] ?? false)
                    <div class="grid md:grid-cols-2 gap-3">
                        @foreach($features['items'] as $index => $item)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex items-start gap-3 p-4 border border-border bg-white">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span class="text-[0.9375rem]">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($features['note'] ?? false)
                    <p class="text-[0.875rem] text-muted-foreground italic mt-6">
                        {{ $features['note'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Scenario Categories Section --}}
    @if(count($scenarioCategories) > 0)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up mb-10">
                    <h2 class="text-[1.375rem]">{{ app()->getLocale() === 'en' ? 'Typical Use Cases' : 'Typische Einsatzszenarien' }}</h2>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($scenarioCategories as $index => $category)
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }}">
                        <div class="p-6 border border-border bg-white h-full">
                            <h3 class="text-[1.125rem] font-medium mb-4">{{ $category['title'] }}</h3>

                            @if($category['items'] ?? false)
                            <div class="space-y-2">
                                @foreach($category['items'] as $item)
                                <div class="flex items-center gap-2 text-[0.875rem]">
                                    <span class="text-accent">→</span>
                                    <span>{{ $item }}</span>
                                </div>
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

    {{-- Integration Section --}}
    @if($integration['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-4">{{ $integration['title'] }}</h2>

                    @if($integration['intro'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground mb-6">
                        {{ $integration['intro'] }}
                    </p>
                    @endif

                    @if($integration['items'] ?? false)
                    <div class="grid md:grid-cols-2 gap-3">
                        @foreach($integration['items'] as $index => $item)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex items-start gap-3 p-4 border border-border bg-white">
                            <x-frontend.icon name="link" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span class="text-[0.9375rem]">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($integration['note'] ?? false)
                    <p class="text-[0.875rem] text-muted-foreground italic mt-6">
                        {{ $integration['note'] }}
                    </p>
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
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed pl-8 mb-4">
                        {!! nl2br(e($growth['text'] ?? '')) !!}
                    </p>

                    @if($growth['items'] ?? false)
                    <div class="pl-8 space-y-2">
                        @foreach($growth['items'] as $item)
                        <div class="flex items-center gap-2 text-[0.875rem]">
                            <span class="text-accent">→</span>
                            <span>{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($growth['note'] ?? false)
                    <p class="text-[0.875rem] text-muted-foreground italic mt-4 pl-8">
                        {{ $growth['note'] }}
                    </p>
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
                    <h2 class="text-[1.375rem] mb-8">{{ $process['title'] }}</h2>

                    @if($process['steps'] ?? false)
                    <div class="space-y-6">
                        @foreach($process['steps'] as $index => $step)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex gap-6 p-6 border border-border bg-white">
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

    {{-- Benefits Section (New Structure) --}}
    @if(count($benefits) > 0)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ app()->getLocale() === 'en' ? 'Benefits' : 'Vorteile' }}</h2>

                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($benefits as $index => $benefit)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex items-start gap-3 p-4 border border-border bg-white">
                            <x-frontend.icon name="zap" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span class="text-[0.9375rem]">{{ $benefit }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Limitations Section (New Structure) --}}
    @if($limitations['title'] ?? false)
    <section class="py-16 border-b border-border bg-muted/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $limitations['title'] }}</h2>

                    @if($limitations['note'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground mb-6 italic">
                        {{ $limitations['note'] }}
                    </p>
                    @endif

                    @if($limitations['items'] ?? false)
                    <div class="space-y-3">
                        @foreach($limitations['items'] as $item)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <x-frontend.icon name="alert-circle" class="w-5 h-5 text-orange-500 shrink-0 mt-0.5" />
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

    {{-- Scenarios Section (New Structure) --}}
    @if(count($scenarios) > 0)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ app()->getLocale() === 'en' ? 'Typical Use Cases' : 'Typische Einsatzszenarien' }}</h2>

                    <div class="space-y-3">
                        @foreach($scenarios as $scenario)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <span class="text-accent font-mono">→</span>
                            <span>{{ $scenario }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Next Steps Section (New Structure) --}}
    @if($nextSteps['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up p-8 bg-accent/5 border border-accent/20">
                    <div class="flex items-start gap-3 mb-4">
                        <x-frontend.icon name="arrow-up-right" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                        <h2 class="text-[1.25rem] font-medium">{{ $nextSteps['title'] }}</h2>
                    </div>

                    @if($nextSteps['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6 pl-8">
                        {{ $nextSteps['text'] }}
                    </p>
                    @endif

                    @if($nextSteps['links'] ?? false)
                    <div class="pl-8 space-y-2">
                        @foreach($nextSteps['links'] as $link)
                        @php
                            $linkedPage = \App\Models\Page::findBySlug($link['slug'] ?? '');
                        @endphp
                        @if($linkedPage)
                        <a href="{{ $linkedPage->getUrl() }}" class="flex items-center gap-2 text-[0.9375rem] text-accent hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                            {{ $link['label'] ?? $linkedPage->title }}
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

    {{-- Challenge Section --}}
    @if($challenge['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $challenge['title'] }}</h2>

                    @if($challenge['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $challenge['text'] }}
                    </p>
                    @endif

                    @if($challenge['items'] ?? false)
                    <div class="space-y-3 mb-6">
                        @foreach($challenge['items'] as $item)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <span class="text-orange-500 shrink-0 mt-0.5">→</span>
                            <span>{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($challenge['note'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground italic">
                        {{ $challenge['note'] }}
                    </p>
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
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $approach['text'] }}
                    </p>
                    @endif

                    @if($approach['results'] ?? false)
                    <p class="text-[0.9375rem] font-medium mb-4">Das Ergebnis:</p>
                    <div class="space-y-3">
                        @foreach($approach['results'] as $result)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span>{{ $result }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Capabilities Section (new object format) --}}
    @if($capabilities['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $capabilities['title'] }}</h2>

                    @if($capabilities['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $capabilities['text'] }}
                    </p>
                    @endif

                    @if($capabilities['items'] ?? false)
                    <div class="grid md:grid-cols-2 gap-3">
                        @foreach($capabilities['items'] as $index => $item)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex items-start gap-3 p-4 border border-border bg-white">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            @if(is_array($item) && isset($item['title']))
                            <div class="text-[0.9375rem]">
                                <span class="font-medium">{{ $item['title'] }}</span>
                                @if($item['text'] ?? false)
                                <span class="text-muted-foreground"> – {{ $item['text'] }}</span>
                                @endif
                            </div>
                            @else
                            <span class="text-[0.9375rem]">{{ $item }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @elseif(is_array($capabilities) && count($capabilities) > 0 && !isset($capabilities['title']))
    {{-- Legacy: Capabilities as array --}}
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ app()->getLocale() === 'en' ? 'Capabilities' : 'Systemfähigkeiten' }}</h2>
                    <div class="grid md:grid-cols-2 gap-3">
                        @foreach($capabilities as $index => $capability)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex items-start gap-3 p-4 border border-border bg-white">
                            <x-frontend.icon name="check-circle" class="w-5 h-5 text-accent shrink-0 mt-0.5" />
                            <span class="text-[0.9375rem]">{{ $capability }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Technical Section (new object format) --}}
    @if($technical['title'] ?? false)
    <section class="py-16 border-b border-border bg-muted/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $technical['title'] }}</h2>

                    @if($technical['text'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                        {{ $technical['text'] }}
                    </p>
                    @endif

                    @if($technical['items'] ?? false)
                    <div class="grid md:grid-cols-2 gap-3 mb-6">
                        @foreach($technical['items'] as $index => $item)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex items-start gap-3 p-4 bg-muted/20 border-l-2 border-foreground">
                            <span class="text-accent mt-0.5">→</span>
                            @if(is_array($item) && isset($item['title']))
                            <div>
                                <span class="text-[0.875rem] font-mono font-medium">{{ $item['title'] }}</span>
                                @if($item['text'] ?? false)
                                <span class="text-[0.875rem] text-muted-foreground"> – {{ $item['text'] }}</span>
                                @endif
                            </div>
                            @else
                            <span class="text-[0.875rem] font-mono">{{ $item }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($technical['note'] ?? false)
                    <p class="text-[0.875rem] text-muted-foreground italic">
                        {{ $technical['note'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @elseif(is_array($technical) && count($technical) > 0 && !isset($technical['title']))
    {{-- Legacy: Technical as array --}}
    <section class="py-16 border-b border-border bg-muted/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ app()->getLocale() === 'en' ? 'Technical Stack' : 'Tech-Stack' }}</h2>
                    <div class="grid md:grid-cols-2 gap-3">
                        @foreach($technical as $index => $tech)
                        <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} flex items-start gap-3 p-4 bg-muted/20 border-l-2 border-foreground">
                            <span class="text-accent mt-0.5">→</span>
                            <span class="text-[0.875rem] font-mono">{{ $tech }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Use Cases Section (new object format) --}}
    @if($useCases['title'] ?? false)
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ $useCases['title'] }}</h2>
                    @if($useCases['items'] ?? false)
                    <div class="space-y-4">
                        @foreach($useCases['items'] as $useCase)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <span class="text-accent shrink-0 mt-0.5">→</span>
                            @if(is_array($useCase) && isset($useCase['title']))
                            <div>
                                <span class="font-medium">{{ $useCase['title'] }}</span>
                                @if($useCase['text'] ?? false)
                                <span class="text-muted-foreground"> – {{ $useCase['text'] }}</span>
                                @endif
                            </div>
                            @else
                            <span>{{ $useCase }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @elseif(is_array($useCases) && count($useCases) > 0 && !isset($useCases['title']))
    {{-- Legacy: Use Cases as array --}}
    <section class="py-16 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.375rem] mb-6">{{ app()->getLocale() === 'en' ? 'Typical Use Cases' : 'Typische Anwendungsfälle' }}</h2>
                    <div class="space-y-3">
                        @foreach($useCases as $useCase)
                        <div class="flex items-start gap-3 text-[0.9375rem]">
                            <span class="text-accent shrink-0 mt-0.5">→</span>
                            <span>{{ $useCase }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Betrieb & Wartung Section --}}
    <x-frontend.maintenance-block variant="compact" />

    {{-- CTA Section --}}
    @php
        $settings = \App\Models\Setting::instance();
    @endphp
    <section class="py-20 border-b border-border bg-accent/5">
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
                            <span class="text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-2 block">
                                {{ app()->getLocale() === 'en' ? 'Your contact' : 'Ihr Ansprechpartner' }}
                            </span>
                            <h2 class="text-[1.75rem] font-medium mb-1">
                                {{ $settings->cta_name ?? $settings->owner_name }}
                            </h2>
                            <p class="text-[0.9375rem] text-muted-foreground mb-6">{{ $settings->cta_role ?? (app()->getLocale() === 'en' ? 'Managing Director' : 'Geschäftsführer') }}</p>

                            <p class="text-[1rem] text-muted-foreground leading-relaxed mb-6 max-w-[600px]">
                                {{ $cta['text'] ?? $settings->cta_subtitle ?? (app()->getLocale() === 'en' ? 'I personally advise you on your project – honest, technically sound and without sales pressure.' : 'Ich berate Sie persönlich zu Ihrem Projekt – ehrlich, technisch fundiert und ohne Verkaufsdruck.') }}
                            </p>

                            {{-- Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button
                                    type="button"
                                    onclick="Livewire.dispatch('openContactModal')"
                                    class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all text-[0.9375rem]"
                                >
                                    {{ $cta['button_text'] ?? (app()->getLocale() === 'en' ? 'Get in touch' : 'Jetzt Kontakt aufnehmen') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                                    </svg>
                                </button>
                                @if($settings->phone)
                                <a href="tel:{{ $settings->phone }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 border border-foreground text-foreground hover:bg-foreground hover:text-background transition-all text-[0.9375rem]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    {{ app()->getLocale() === 'en' ? 'Call directly' : 'Direkt anrufen' }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Solutions --}}
    @if($otherSolutions->count() > 0)
    <section class="py-20 border-t border-border bg-gradient-to-b from-muted/5 to-transparent">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="motion motion-fade-up mb-12">
                <h2 class="text-[1.5rem] mb-3">{{ app()->getLocale() === 'en' ? 'You might also be interested in' : 'Das könnte Sie auch interessieren' }}</h2>
                <p class="text-[1.0625rem] text-muted-foreground">
                    {{ app()->getLocale() === 'en' ? 'More solutions in this category' : 'Weitere Lösungen in dieser Kategorie' }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($otherSolutions as $index => $solution)
                @php
                    $solutionHero = $solution->getSection('hero');
                @endphp
                <a href="{{ $solution->getUrl() }}"
                   class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }} group block border border-border hover:border-foreground hover:shadow-lg transition-all bg-white">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-1">
                                @if($solutionHero['number'] ?? false)
                                <span class="text-[0.75rem] font-mono text-muted-foreground">{{ $solutionHero['number'] }}</span>
                                @endif
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
                            {{ app()->getLocale() === 'en' ? 'Learn more' : 'Mehr erfahren' }}
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

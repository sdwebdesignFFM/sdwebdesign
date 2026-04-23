<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $intro = $page->getSection('intro');
        $sections = $page->getSection('sections', []);
        $comparison = $page->getSection('comparison');
        $recommendations = $page->getSection('recommendations');
        $cta = $page->getSection('cta');
    @endphp

    {{-- Breadcrumb --}}
    <section class="pt-24 border-b border-border">
        <div class="max-w-[1400px] mx-auto px-6 py-6">
            <a href="{{ localized_route('guides') }}" class="flex items-center gap-2 text-[0.875rem] text-muted-foreground hover:text-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                {{ app()->getLocale() === 'en' ? 'Back to guides' : 'Zurück zu allen Ratgebern' }}
            </a>
        </div>
    </section>

    {{-- Hero Section --}}
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    @if($hero['badge'] ?? false)
                    <span class="inline-block text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-4">
                        {{ $hero['badge'] }}
                    </span>
                    @endif

                    <h1 class="mb-6">{{ $page->title }}</h1>

                    @if($hero['subtitle'] ?? false)
                    <p class="text-[1.25rem] text-muted-foreground leading-relaxed">
                        {{ $hero['subtitle'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Intro --}}
    @if($intro['text'] ?? false)
    <section class="pb-16">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[800px]">
                <p class="text-[1.0625rem] leading-relaxed">
                    {{ $intro['text'] }}
                </p>
            </div>
        </div>
    </section>
    @endif

    {{-- Content Sections --}}
    @if(count($sections) > 0)
    <section class="py-16 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[800px]">
                @foreach($sections as $index => $section)
                <div class="motion motion-fade-up {{ !$loop->last ? 'mb-12' : '' }}">
                    @if($section['title'] ?? false)
                    <h2 class="text-[1.5rem] mb-6">{{ $section['title'] }}</h2>
                    @endif

                    @if($section['content'] ?? false)
                    <div class="prose prose-lg max-w-none">
                        {!! $section['content'] !!}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Comparison Table --}}
    @if(($comparison['items'] ?? false) && count($comparison['items']) > 0)
    <section class="py-16 border-t border-border bg-muted/10">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up mb-12">
                    <h2 class="text-[1.5rem] mb-4">
                        {{ $comparison['title'] ?? (app()->getLocale() === 'en' ? 'Comparison at a Glance' : 'Vergleich auf einen Blick') }}
                    </h2>
                </div>

                <div class="grid md:grid-cols-{{ min(count($comparison['items']), 3) }} gap-6">
                    @foreach($comparison['items'] as $index => $item)
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }} bg-background border border-border p-6">
                        <h3 class="text-[1.125rem] font-medium mb-4 pb-4 border-b border-border">
                            {{ $item['name'] }}
                        </h3>

                        {{-- Pros --}}
                        @if($item['pros'] ?? false)
                        <div class="mb-6">
                            <span class="text-[0.75rem] font-semibold tracking-widest text-green-600 uppercase mb-3 block">
                                {{ app()->getLocale() === 'en' ? 'Advantages' : 'Vorteile' }}
                            </span>
                            <ul class="space-y-2">
                                @foreach(explode("\n", trim($item['pros'])) as $pro)
                                @if(trim($pro))
                                <li class="flex items-start gap-2 text-[0.875rem]">
                                    <x-frontend.icon name="check" class="w-4 h-4 text-green-600 shrink-0 mt-0.5" />
                                    <span>{{ trim($pro) }}</span>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Cons --}}
                        @if($item['cons'] ?? false)
                        <div class="mb-6">
                            <span class="text-[0.75rem] font-semibold tracking-widest text-orange-600 uppercase mb-3 block">
                                {{ app()->getLocale() === 'en' ? 'Limitations' : 'Grenzen' }}
                            </span>
                            <ul class="space-y-2">
                                @foreach(explode("\n", trim($item['cons'])) as $con)
                                @if(trim($con))
                                <li class="flex items-start gap-2 text-[0.875rem]">
                                    <x-frontend.icon name="x-mark" class="w-4 h-4 text-orange-600 shrink-0 mt-0.5" />
                                    <span>{{ trim($con) }}</span>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Link to detail page --}}
                        @if($item['link'] ?? false)
                        <a href="{{ $item['link'] }}" class="inline-flex items-center gap-2 text-[0.875rem] font-medium text-accent hover:underline">
                            {{ app()->getLocale() === 'en' ? 'Learn more' : 'Mehr erfahren' }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Related Solutions --}}
    @if($relatedSolutions->count() > 0)
    <section class="py-16 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="motion motion-fade-up mb-12">
                <h2 class="text-[1.5rem] mb-3">
                    {{ $recommendations['title'] ?? (app()->getLocale() === 'en' ? 'Our Solutions in Detail' : 'Unsere Lösungen im Detail') }}
                </h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedSolutions as $index => $solution)
                @php
                    $solutionHero = $solution->getSection('hero');
                @endphp
                <a href="{{ $solution->getUrl() }}"
                   class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }} group block border border-border hover:border-foreground hover:shadow-lg transition-all bg-background">
                    <div class="p-6">
                        <div class="flex items-start gap-4 mb-4">
                            @if($solutionHero['icon'] ?? false)
                            <div class="p-3 border border-border group-hover:border-foreground transition-all shrink-0">
                                <x-frontend.icon :name="$solutionHero['icon']" class="w-6 h-6" />
                            </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-[1rem] leading-tight group-hover:text-accent transition-colors">
                                    {{ $solution->title }}
                                </h3>
                            </div>
                        </div>
                        @if($solutionHero['subtitle'] ?? false)
                        <p class="text-[0.875rem] text-muted-foreground leading-relaxed mb-4">
                            {{ Str::limit($solutionHero['subtitle'], 100) }}
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

    {{-- Other Guides --}}
    @if($otherGuides->count() > 0)
    <section class="py-16 border-t border-border bg-muted/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="motion motion-fade-up mb-12">
                <h2 class="text-[1.5rem] mb-3">
                    {{ app()->getLocale() === 'en' ? 'More Guides' : 'Weitere Ratgeber' }}
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach($otherGuides as $index => $guide)
                @php
                    $guideHero = $guide->getSection('hero');
                @endphp
                <a href="{{ $guide->getUrl() }}"
                   class="motion motion-fade-up motion-delay-{{ ($index % 3) + 1 }} group block border border-border hover:border-foreground transition-all bg-background p-6">
                    @if($guideHero['badge'] ?? false)
                    <span class="inline-block text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-2">
                        {{ $guideHero['badge'] }}
                    </span>
                    @endif
                    <h3 class="text-[1rem] font-medium group-hover:text-accent transition-colors mb-2">
                        {{ $guide->title }}
                    </h3>
                    <span class="text-[0.875rem] text-muted-foreground">
                        {{ app()->getLocale() === 'en' ? 'Read guide' : 'Ratgeber lesen' }} →
                    </span>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    @php
        $settings = \App\Models\Setting::instance();
    @endphp
    <section class="py-20 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[1100px]">
                <div class="motion motion-fade-up border border-border bg-background overflow-hidden">
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
                                {{ app()->getLocale() === 'en' ? 'Your Contact' : 'Ihr Ansprechpartner' }}
                            </span>
                            <h2 class="text-[1.75rem] font-medium mb-1">
                                {{ $settings->cta_name ?? $settings->owner_name }}
                            </h2>
                            <p class="text-[0.9375rem] text-muted-foreground mb-6">{{ $settings->cta_role ?? 'Geschäftsführer' }}</p>

                            <p class="text-[1rem] text-muted-foreground leading-relaxed mb-6 max-w-[600px]">
                                {{ $cta['subtitle'] ?? $settings->cta_subtitle ?? 'Ich berate Sie persönlich zu Ihrem Projekt – ehrlich, technisch fundiert und ohne Verkaufsdruck.' }}
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

    {{-- BlogPosting Schema --}}
    @isset($blogPostingSchema)
    @push('scripts')
    <script type="application/ld+json">
    {!! json_encode($blogPostingSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endpush
    @endisset
</x-layouts.frontend>

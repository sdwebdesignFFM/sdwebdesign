<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $intro = $page->getSection('intro');
        $cta = $page->getSection('cta');
    @endphp

    {{-- Hero Section --}}
    <section class="pt-32 pb-20 relative overflow-hidden">
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
                    <p class="text-[1.25rem] text-muted-foreground leading-relaxed max-w-[700px]">
                        {{ $hero['subtitle'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Intro Text --}}
    @if($intro['text'] ?? false)
    <section class="pb-16">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <p class="text-[1.0625rem] leading-relaxed text-muted-foreground">
                    {{ $intro['text'] }}
                </p>
            </div>
        </div>
    </section>
    @endif

    {{-- Guides Grid --}}
    @if($guides->count() > 0)
    <section class="py-16 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-8">
                @foreach($guides as $index => $guide)
                @php
                    $guideHero = $guide->getSection('hero');
                    $guideIntro = $guide->getSection('intro');
                @endphp
                <a href="{{ $guide->getUrl() }}"
                   class="motion motion-fade-up motion-delay-{{ ($index % 2) + 1 }} group block border-2 border-border hover:border-foreground hover:shadow-xl transition-all bg-white">
                    <div class="p-8">
                        {{-- Badge --}}
                        @if($guideHero['badge'] ?? false)
                        <span class="inline-block text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-4">
                            {{ $guideHero['badge'] }}
                        </span>
                        @endif

                        {{-- Title --}}
                        <h2 class="text-[1.5rem] font-medium mb-4 group-hover:text-accent transition-colors">
                            {{ $guide->title }}
                        </h2>

                        {{-- Description --}}
                        @if($guideIntro['text'] ?? false)
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                            {{ Str::limit($guideIntro['text'], 150) }}
                        </p>
                        @elseif($guide->meta_description)
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">
                            {{ $guide->meta_description }}
                        </p>
                        @endif

                        {{-- CTA --}}
                        <span class="inline-flex items-center gap-2 text-[0.9375rem] font-medium group-hover:gap-3 transition-all">
                            {{ app()->getLocale() === 'en' ? 'Read guide' : 'Ratgeber lesen' }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            @if($guides->hasPages())
            <div class="mt-12">
                {{ $guides->links() }}
            </div>
            @endif
        </div>
    </section>
    @else
    <section class="py-16 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="text-center py-12">
                <p class="text-muted-foreground">
                    {{ app()->getLocale() === 'en' ? 'More guides coming soon.' : 'Weitere Ratgeber folgen in Kürze.' }}
                </p>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    @php
        $settings = \App\Models\Setting::instance();
    @endphp
    <section class="py-20 border-t border-border bg-accent/5">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[700px] mx-auto text-center">
                <div class="motion motion-fade-up">
                    <h2 class="text-[1.5rem] mb-4">
                        {{ $cta['title'] ?? (app()->getLocale() === 'en' ? 'Need personal advice?' : 'Persönliche Beratung gewünscht?') }}
                    </h2>
                    <p class="text-[1rem] text-muted-foreground mb-8">
                        {{ $cta['subtitle'] ?? (app()->getLocale() === 'en' ? 'I\'ll help you find the right solution for your project.' : 'Ich helfe Ihnen, die passende Lösung für Ihr Projekt zu finden.') }}
                    </p>
                    <button
                        type="button"
                        onclick="Livewire.dispatch('openContactModal')"
                        class="inline-flex items-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all text-[0.9375rem]"
                    >
                        {{ $cta['button_text'] ?? (app()->getLocale() === 'en' ? 'Get in touch' : 'Jetzt Kontakt aufnehmen') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.frontend>

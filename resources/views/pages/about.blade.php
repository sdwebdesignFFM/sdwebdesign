<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $haltung = $page->getSection('haltung');
        $arbeitsweise = $page->getSection('arbeitsweise');
        $auszeichnet = $page->getSection('auszeichnet');
        $team = $page->getSection('team');
        $zusammenarbeit = $page->getSection('zusammenarbeit');
        $cta = $page->getSection('cta');
    @endphp

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    @if($hero['badge'] ?? false)
                    <div class="inline-block px-4 py-2 mb-8 border border-border">
                        <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">{{ $hero['badge'] }}</p>
                    </div>
                    @endif

                    <h1>{{ $hero['title'] ?? 'Über uns' }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- Haltung & Anspruch --}}
    @if($haltung ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $haltung['title'] }}</h2>

                @if($haltung['headline'] ?? false)
                <p class="text-[1.0625rem] font-medium mb-6">{{ $haltung['headline'] }}</p>
                @endif

                @if($haltung['paragraphs'] ?? false)
                <div class="space-y-4">
                    @foreach($haltung['paragraphs'] as $paragraph)
                    <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">{{ $paragraph }}</p>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Wie wir arbeiten --}}
    @if($arbeitsweise ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="motion motion-fade-up mb-12">
            <h2 class="text-[1.25rem] mb-6">{{ $arbeitsweise['title'] }}</h2>
            @if($arbeitsweise['intro'] ?? false)
            <p class="text-[1.0625rem] text-muted-foreground leading-relaxed max-w-[800px]">{{ $arbeitsweise['intro'] }}</p>
            @endif
            @if($arbeitsweise['subtitle'] ?? false)
            <p class="text-[1.0625rem] text-muted-foreground mt-4">{{ $arbeitsweise['subtitle'] }}</p>
            @endif
        </div>

        @if($arbeitsweise['principles'] ?? false)
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($arbeitsweise['principles'] as $index => $principle)
            <div class="motion motion-fade-up motion-delay-{{ $index + 1 }} p-8 border border-border bg-background">
                <p class="text-[2.5rem] font-light text-accent mb-6">{{ $principle['number'] }}</p>
                <h3 class="text-[1.125rem] mb-4">{{ $principle['title'] }}</h3>
                <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">{{ $principle['description'] }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </section>
    @endif

    {{-- Was uns auszeichnet --}}
    @if($auszeichnet ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[900px]">
            <div class="motion motion-fade-up mb-10">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem]">{{ $auszeichnet['title'] }}</h2>
            </div>

            @if($auszeichnet['items'] ?? false)
            <div class="space-y-4">
                @foreach($auszeichnet['items'] as $index => $item)
                <div class="motion motion-fade-up motion-delay-{{ $index + 1 }} p-6 border border-border bg-background">
                    <div class="flex items-start gap-5">
                        <div class="p-3 border border-border">
                            <x-frontend.icon :name="$item['icon']" class="w-5 h-5 text-accent" />
                        </div>
                        <div>
                            <h3 class="text-[1rem] font-medium mb-2">{{ $item['title'] }}</h3>
                            <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">{{ $item['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Unser Team --}}
    @if($team ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="motion motion-fade-up mb-12">
            <div class="w-12 h-0.5 bg-foreground mb-8"></div>
            <h2 class="text-[1.25rem] mb-6">{{ $team['title'] }}</h2>
            @if($team['intro'] ?? false)
            <p class="text-[1.0625rem] text-muted-foreground leading-relaxed max-w-[800px]">{{ $team['intro'] }}</p>
            @endif
            @if($team['subtitle'] ?? false)
            <p class="text-[1.0625rem] text-muted-foreground mt-4 max-w-[800px]">{{ $team['subtitle'] }}</p>
            @endif
        </div>

        @if($team['badge'] ?? false)
        <p class="motion motion-fade-up text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-8">{{ $team['badge'] }}</p>
        @endif

        @if($team['members'] ?? false)
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($team['members'] as $index => $member)
            <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} p-6 border border-border bg-background">
                <div class="flex items-start gap-5">
                    <div class="p-4 border border-border">
                        <x-frontend.icon :name="$member['icon']" class="w-6 h-6 text-muted-foreground" />
                    </div>
                    <div>
                        <h3 class="text-[1rem] font-medium mb-1">{{ $member['name'] }}</h3>
                        <p class="text-[0.75rem] uppercase tracking-wider text-accent mb-3">{{ $member['role'] }}</p>
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">{{ $member['description'] }}</p>
                        @if($member['linkedin'] ?? false)
                        <a
                            href="{{ $member['linkedin'] }}"
                            target="_blank"
                            rel="noopener me"
                            class="inline-flex items-center gap-2 mt-4 text-[0.875rem] font-medium hover:text-accent transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.063 2.063 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            {{ app()->getLocale() === 'en' ? 'Follow on LinkedIn' : 'Auf LinkedIn folgen' }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>
    @endif

    {{-- Zusammenarbeit auf Augenhöhe --}}
    @if($zusammenarbeit ?? false)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $zusammenarbeit['title'] }}</h2>

                @if($zusammenarbeit['paragraphs'] ?? false)
                <div class="space-y-4">
                    @foreach($zusammenarbeit['paragraphs'] as $paragraph)
                    <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">{{ $paragraph }}</p>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    @if($cta['title'] ?? false)
    <section class="py-20 lg:py-28 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="motion motion-fade-up max-w-[800px] mx-auto">
                <div class="p-12 border border-border bg-background text-center">
                    <h2 class="mb-4 text-[1.75rem]">{{ $cta['title'] }}</h2>
                    @if($cta['subtitle'] ?? false)
                    <p class="text-[1.0625rem] text-muted-foreground mb-8 leading-relaxed">
                        {{ $cta['subtitle'] }}
                    </p>
                    @endif
                    @if($cta['button_text'] ?? false)
                    <a href="{{ $cta['button_link'] ?? localized_route('contact') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-foreground text-background hover:bg-foreground/90 transition-all">
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

    @isset($personSchema)
    @push('scripts')
    <script type="application/ld+json">
    {!! json_encode($personSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endpush
    @endisset
</x-layouts.frontend>

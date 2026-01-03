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
</x-layouts.frontend>

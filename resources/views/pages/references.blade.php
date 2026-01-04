<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $projects = $page->getSection('projects', []);
        $cta = $page->getSection('cta');
        // $referencePages is passed from controller
    @endphp

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[800px]">
                <div class="motion motion-fade-up">
                    @if($hero['badge'] ?? false)
                    <div class="inline-block px-4 py-2 mb-8 border border-border">
                        <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">{{ $hero['badge'] }}</p>
                    </div>
                    @endif

                    <h1 class="mb-6">{{ $hero['title'] ?? 'Referenzen' }}</h1>

                    @if($hero['subtitle'] ?? false)
                    <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                        {{ $hero['subtitle'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Projects --}}
    @foreach($projects as $index => $project)
    <section class="border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6 py-20 lg:py-28">
            {{-- Project Header --}}
            <div class="motion motion-fade-up mb-16">
                <div class="flex items-start gap-6 mb-6">
                    <div class="p-5 border border-border">
                        <x-frontend.icon :name="$project['icon'] ?? 'folder'" class="w-8 h-8" />
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-2">
                            <span class="text-[0.875rem] text-muted-foreground">{{ $project['number'] ?? str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="h-px flex-1 bg-border"></div>
                        </div>
                        @if($project['categories'] ?? false)
                        <p class="text-[0.75rem] uppercase tracking-wider text-accent mb-2">
                            {{ implode(' · ', $project['categories']) }}
                        </p>
                        @endif
                        <h2 class="text-[2rem] lg:text-[2.5rem] leading-tight mb-3">{{ $project['title'] }}</h2>
                        @if($project['tagline'] ?? false)
                        <p class="text-[1.0625rem] text-muted-foreground">{{ $project['tagline'] }}</p>
                        @endif
                    </div>
                </div>

                @if($project['client'] ?? false)
                <div class="ml-0 lg:ml-[88px] mt-6">
                    <p class="text-[0.8125rem] text-muted-foreground mb-1">Kunde</p>
                    <p class="text-[0.9375rem]">{{ $project['client'] }}</p>
                </div>
                @endif
            </div>

            {{-- Challenge & Solution --}}
            @if(($project['challenge'] ?? false) || ($project['solution'] ?? false))
            <div class="grid md:grid-cols-2 gap-8 mb-16">
                @if($project['challenge'] ?? false)
                <div class="motion motion-fade-up motion-delay-1 p-8 border-l-4 border-red-500 bg-background">
                    <h3 class="mb-4 text-[1.125rem] flex items-center gap-2">
                        <span class="text-red-500">⚠</span>
                        {{ $project['challenge']['title'] ?? 'Die Ausgangssituation' }}
                    </h3>
                    @if($project['challenge']['description'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">{{ $project['challenge']['description'] }}</p>
                    @endif
                    @if($project['challenge']['items'] ?? false)
                    <ul class="space-y-2">
                        @foreach($project['challenge']['items'] as $item)
                        <li class="flex items-start gap-3 text-[0.9375rem] text-muted-foreground">
                            <span class="text-red-500 mt-0.5">×</span>
                            <span>{{ $item }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endif

                @if($project['solution'] ?? false)
                <div class="motion motion-fade-up motion-delay-2 p-8 border-l-4 border-green-500 bg-background">
                    <h3 class="mb-4 text-[1.125rem] flex items-center gap-2">
                        <x-frontend.icon name="check-circle" class="w-5 h-5 text-green-500" />
                        {{ $project['solution']['title'] ?? 'Die entwickelte Lösung' }}
                    </h3>
                    @if($project['solution']['description'] ?? false)
                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6">{{ $project['solution']['description'] }}</p>
                    @endif
                    @if($project['solution']['items'] ?? false)
                    <ul class="space-y-2">
                        @foreach($project['solution']['items'] as $item)
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
            @endif

            {{-- Realisierte Funktionen --}}
            @if($project['features'] ?? false)
            <div class="motion motion-fade-up mb-16">
                <h3 class="mb-8 text-[1.125rem] flex items-center gap-3">
                    <x-frontend.icon name="settings" class="w-5 h-5 text-muted-foreground" />
                    Realisierte Funktionen
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($project['features'] as $fIndex => $feature)
                    <div class="motion motion-fade-up motion-delay-{{ ($fIndex % 4) + 1 }} p-6 border border-border bg-background">
                        <h4 class="mb-4 text-[1rem] font-medium">{{ $feature['title'] }}</h4>
                        <ul class="space-y-2">
                            @foreach($feature['items'] as $item)
                            <li class="flex items-start gap-3 text-[0.9375rem] text-muted-foreground">
                                <span class="text-accent">→</span>
                                <span>{{ $item }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Detail Button --}}
            @if($project['detail_slug'] ?? false)
            @php
                // Find the reference page by its German slug to get the correct localized URL
                $detailPage = $referencePages->first(fn($p) => $p->getTranslation('slug', 'de') === $project['detail_slug']);
            @endphp
            @if($detailPage)
            <div class="motion motion-fade-up mt-12">
                <a href="{{ $detailPage->getUrl() }}"
                   class="inline-flex items-center justify-between gap-4 px-8 py-5 border-2 border-foreground hover:bg-foreground hover:text-background transition-all">
                    <span class="font-medium">{{ app()->getLocale() === 'en' ? 'View project details' : 'Projekt im Detail ansehen' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
            @endif
            @endif
        </div>
    </section>
    @endforeach

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

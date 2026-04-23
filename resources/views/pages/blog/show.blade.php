<x-layouts.frontend>
    {{-- Back Navigation --}}
    <section class="pt-24 border-b border-border bg-muted/20">
        <div class="max-w-4xl mx-auto px-6 py-6">
            <a href="{{ localized_route('blog') }}" class="flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
                Zurück zur Übersicht
            </a>
        </div>
    </section>

    {{-- Article Header --}}
    <section class="max-w-4xl mx-auto px-6 py-20">
        {{-- Category --}}
        <div class="inline-flex items-center gap-1.5 mb-8 px-2.5 py-1 bg-accent/5 border border-accent/20 rounded-sm">
            <x-frontend.icon name="tag" class="w-3 h-3 text-accent" />
            <span class="text-[0.6875rem] text-accent uppercase tracking-wider font-medium">{{ $article->category }}</span>
        </div>

        {{-- Title --}}
        <h1 class="mb-8 text-5xl md:text-6xl leading-[1.1]">{{ $article->title }}</h1>

        {{-- Meta --}}
        <div class="flex flex-wrap items-center gap-5 mb-8 pb-8 border-b border-border">
            <div class="flex items-center gap-1.5 text-[0.8125rem] text-muted-foreground">
                <x-frontend.icon name="calendar" class="w-3.5 h-3.5" />
                <span>{{ $article->formatted_date }}</span>
            </div>
            <div class="flex items-center gap-1.5 text-[0.8125rem] text-muted-foreground">
                <x-frontend.icon name="clock" class="w-3.5 h-3.5" />
                <span>{{ $article->read_time_text }} Lesezeit</span>
            </div>
        </div>

        {{-- Intro --}}
        <div class="text-2xl leading-relaxed text-muted-foreground mb-16 pb-16 border-b border-border">
            {{ $article->intro }}
        </div>
    </section>

    {{-- Article Content --}}
    <section class="max-w-4xl mx-auto px-6 pb-20">
        <div class="prose prose-lg max-w-none">
            @foreach($article->sections as $section)
            <div class="mb-16">
                <h2 class="text-3xl mb-6 leading-tight">{{ $section['heading'] }}</h2>
                <div class="text-lg leading-relaxed text-muted-foreground whitespace-pre-line">{{ $section['content'] }}</div>
            </div>
            @endforeach

            {{-- Conclusion --}}
            <div class="mt-20 p-8 border-2 border-foreground bg-foreground/[0.02]">
                <h2 class="text-3xl mb-6">Fazit</h2>
                <div class="text-lg leading-relaxed text-muted-foreground whitespace-pre-line">{{ $article->conclusion }}</div>
            </div>
        </div>
    </section>

    {{-- Author CTA --}}
    <section class="border-t border-border bg-gradient-to-b from-muted/20 to-transparent">
        <div class="max-w-4xl mx-auto px-6 py-20">
            <div class="text-center">
                <h2 class="mb-6 text-4xl">Fragen zu diesem Thema?</h2>
                <p class="text-lg text-muted-foreground mb-10 max-w-2xl mx-auto leading-relaxed">
                    Wir beraten Sie gerne zu digitalen Systemen, Integrationen und technischen Architekturen –
                    pragmatisch, ohne Marketing-Gerede.
                </p>
                <button
                    type="button"
                    onclick="Livewire.dispatch('openContactModal')"
                    class="btn-primary text-lg py-5 px-10"
                >
                    Projekt besprechen
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    {{-- Related Articles --}}
    @if($relatedArticles->count() > 0)
    <section class="border-t border-border">
        <div class="max-w-8xl mx-auto px-6 py-20">
            <div class="mb-12">
                <h2 class="text-3xl mb-3">Weitere Artikel</h2>
                <p class="text-lg text-muted-foreground">Mehr Fachwissen zu digitalen Systemen und Technologie</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                <a href="{{ localized_route('blog.show', ['slug' => $related->slug]) }}"
                   class="group border border-border hover:border-foreground hover:shadow-lg transition-all bg-background">
                    <div class="p-6">
                        <div class="inline-flex items-center gap-1.5 mb-4 px-2.5 py-1 bg-accent/5 border border-accent/20 rounded-sm">
                            <x-frontend.icon name="tag" class="w-2.5 h-2.5 text-accent" />
                            <span class="text-[0.625rem] text-accent uppercase tracking-wider font-medium">{{ $related->category }}</span>
                        </div>
                        <h3 class="mb-3 text-[0.9375rem] leading-tight group-hover:text-accent transition-colors">
                            {{ $related->title }}
                        </h3>
                        <div class="flex items-center gap-1.5 text-[0.75rem] text-muted-foreground">
                            <x-frontend.icon name="clock" class="w-3 h-3" />
                            <span>{{ $related->read_time_text }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- BlogPosting Schema --}}
    @isset($blogPostingSchema)
    @push('scripts')
    <script type="application/ld+json">
    {!! json_encode($blogPostingSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @endpush
    @endisset
</x-layouts.frontend>

<x-layouts.frontend>
    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-8xl mx-auto px-6">
            <div class="max-w-4xl">
                <div class="section-badge mb-8">
                    <p class="section-badge-text">Wissen & Artikel</p>
                </div>

                <h1 class="mb-8">Fachwissen zu digitalen Systemen und Technologie</h1>

                <p class="text-xl text-muted-foreground leading-relaxed max-w-2xl">
                    Technische Einblicke, Erfahrungen aus der Praxis und fundiertes Wissen zu
                    digitalen Systemen, Integrationen und modernen Architekturen – ohne Marketing-Floskeln.
                </p>
            </div>
        </div>
    </section>

    {{-- Search & Filter --}}
    <section class="py-12 border-t border-border bg-gradient-to-b from-muted/20 to-transparent">
        <div class="max-w-8xl mx-auto px-6">
            <div class="max-w-5xl">
                <form action="{{ localized_route('blog') }}" method="GET" class="flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">
                    {{-- Search --}}
                    <div class="relative flex-1 max-w-md">
                        <x-frontend.icon name="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground" />
                        <input
                            type="text"
                            name="search"
                            placeholder="Artikel durchsuchen..."
                            value="{{ $search ?? '' }}"
                            class="w-full pl-12 pr-4 py-3 border border-border focus:border-foreground outline-none transition-all text-sm"
                        >
                    </div>

                    {{-- Category Filter --}}
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ localized_route('blog') }}"
                           class="px-4 py-2 text-xs border transition-all {{ !$category ? 'bg-foreground text-background border-foreground' : 'bg-background text-foreground border-border hover:border-foreground' }}">
                            Alle Artikel
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ localized_route('blog', ['category' => $cat]) }}"
                           class="px-4 py-2 text-xs border transition-all {{ $category === $cat ? 'bg-foreground text-background border-foreground' : 'bg-background text-foreground border-border hover:border-foreground' }}">
                            {{ $cat }}
                        </a>
                        @endforeach
                    </div>
                </form>

                {{-- Results Count --}}
                <div class="mt-6 text-sm text-muted-foreground">
                    {{ $articles->total() }} {{ $articles->total() === 1 ? 'Artikel' : 'Artikel' }} gefunden
                </div>
            </div>
        </div>
    </section>

    {{-- Articles Grid --}}
    <section class="py-20 border-t border-border">
        <div class="max-w-8xl mx-auto px-6">
            <div class="max-w-5xl">
                @if($articles->count() > 0)
                <div class="grid lg:grid-cols-2 gap-8">
                    @foreach($articles as $article)
                    <article class="group border-2 border-border hover:border-foreground hover:shadow-2xl transition-all bg-background">
                        <a href="{{ localized_route('blog.show', ['slug' => $article->slug]) }}" class="block p-8">
                            {{-- Category & Meta --}}
                            <div class="flex items-center gap-4 mb-6">
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-accent/5 border border-accent/20 rounded-sm">
                                    <x-frontend.icon name="tag" class="w-2.5 h-2.5 text-accent" />
                                    <span class="text-[0.6875rem] text-accent uppercase tracking-wider font-medium">{{ $article->category }}</span>
                                </div>
                                <div class="h-px flex-1 bg-border group-hover:bg-foreground transition-colors"></div>
                            </div>

                            {{-- Title --}}
                            <h2 class="mb-4 text-2xl leading-tight group-hover:text-accent transition-colors">
                                {{ $article->title }}
                            </h2>

                            {{-- Excerpt --}}
                            <p class="text-muted-foreground leading-relaxed mb-6">
                                {{ Str::limit($article->excerpt, 150) }}
                            </p>

                            {{-- Meta Info --}}
                            <div class="flex items-center gap-5 mb-6 text-[0.75rem] text-muted-foreground">
                                <div class="flex items-center gap-1.5">
                                    <x-frontend.icon name="calendar" class="w-3.5 h-3.5" />
                                    <span>{{ $article->formatted_date }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <x-frontend.icon name="clock" class="w-3.5 h-3.5" />
                                    <span>{{ $article->read_time_text }} Lesezeit</span>
                                </div>
                            </div>

                            {{-- CTA --}}
                            <span class="flex items-center gap-2 text-sm font-medium group-hover:gap-3 transition-all">
                                Artikel lesen
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </span>
                        </a>
                    </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
                @else
                <div class="text-center py-20">
                    <p class="text-lg text-muted-foreground mb-4">Keine Artikel gefunden.</p>
                    <a href="{{ localized_route('blog') }}" class="text-sm text-accent hover:underline">Filter zurücksetzen</a>
                </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts.frontend>

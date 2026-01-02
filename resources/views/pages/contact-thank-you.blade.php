<x-layouts.frontend>
    @php
        $settings = \App\Models\Setting::instance();
    @endphp

    <section class="min-h-[80vh] flex items-center justify-center py-20">
        <div class="max-w-[700px] mx-auto px-6 text-center">
            <div class="motion motion-fade-up">
                {{-- Success Icon --}}
                <div class="w-24 h-24 mx-auto mb-8 rounded-full bg-green-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>

                {{-- Headline --}}
                <h1 class="text-[2.5rem] md:text-[3rem] font-medium mb-4">
                    @if(!empty($contactData['name']))
                        Vielen Dank, {{ explode(' ', $contactData['name'])[0] }}!
                    @else
                        Vielen Dank!
                    @endif
                </h1>

                <p class="text-[1.125rem] text-muted-foreground mb-8 max-w-[500px] mx-auto">
                    Ihre Projektanfrage ist bei uns eingegangen. Wir melden uns in der Regel innerhalb von <strong class="text-foreground">24 Stunden</strong> bei Ihnen.
                </p>

                {{-- What happens next --}}
                <div class="bg-muted/30 border border-border rounded-2xl p-8 mb-10 text-left">
                    <h2 class="text-[1rem] font-semibold mb-6 text-center">So geht es weiter</h2>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-foreground text-background flex items-center justify-center shrink-0 text-sm font-semibold">1</div>
                            <div>
                                <p class="font-medium mb-1">Anfrage-Analyse</p>
                                <p class="text-sm text-muted-foreground">Wir sichten Ihre Anforderungen und bereiten passende Losungsvorschlage vor.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-foreground text-background flex items-center justify-center shrink-0 text-sm font-semibold">2</div>
                            <div>
                                <p class="font-medium mb-1">Personliche Kontaktaufnahme</p>
                                <p class="text-sm text-muted-foreground">Wir melden uns in der Regel innerhalb von 24 Stunden per E-Mail oder Telefon.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-foreground text-background flex items-center justify-center shrink-0 text-sm font-semibold">3</div>
                            <div>
                                <p class="font-medium mb-1">Kostenlose Beratung</p>
                                <p class="text-sm text-muted-foreground">In einem unverbindlichen Gesprach besprechen wir Ihr Projekt im Detail.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Person --}}
                <div class="bg-accent/5 border border-border rounded-2xl p-6 mb-10">
                    <div class="flex items-center gap-6">
                        @if($settings->cta_image)
                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($settings->cta_image) }}"
                            alt="{{ $settings->cta_name ?? $settings->owner_name }}"
                            class="w-20 h-24 object-cover object-top rounded-lg border-2 border-foreground shrink-0"
                        />
                        @endif
                        <div class="text-left">
                            <p class="text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-1">Ihr Ansprechpartner</p>
                            <p class="font-semibold text-[1.125rem]">{{ $settings->cta_name ?? $settings->owner_name }}</p>
                            <p class="text-sm text-muted-foreground mb-3">{{ $settings->cta_role ?? 'Geschaftsfuhrer' }}</p>
                            @if($settings->phone)
                            <a href="tel:{{ $settings->phone }}" class="inline-flex items-center gap-2 text-sm text-accent hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                {{ $settings->phone }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Summary of Request --}}
                @if(!empty($contactData))
                <div class="border border-border rounded-2xl overflow-hidden mb-10 text-left">
                    <div class="bg-muted/30 px-6 py-4 border-b border-border">
                        <p class="font-semibold text-sm">Ihre Angaben</p>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        @if(!empty($contactData['projectTypes']))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Projekttyp(en):</span>
                            <span class="font-medium text-right">{{ implode(', ', $contactData['projectTypes']) }}</span>
                        </div>
                        @endif
                        @if(!empty($contactData['budget']))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Budget:</span>
                            <span class="font-medium">{{ $contactData['budget'] }}</span>
                        </div>
                        @endif
                        @if(!empty($contactData['timeline']))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Zeitrahmen:</span>
                            <span class="font-medium">{{ $contactData['timeline'] }}</span>
                        </div>
                        @endif
                        @if(!empty($contactData['callbackDays']) || !empty($contactData['callbackTime']))
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Rückruf:</span>
                            <span class="font-medium text-right">
                                @if(!empty($contactData['callbackDays'])){{ implode(', ', $contactData['callbackDays']) }}@endif
                                @if(!empty($contactData['callbackDays']) && !empty($contactData['callbackTime'])), @endif
                                @if(!empty($contactData['callbackTime'])){{ $contactData['callbackTime'] }}@endif
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a
                        href="{{ localized_route('home') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all rounded-lg font-medium"
                    >
                        Zur Startseite
                    </a>
                    <a
                        href="{{ localized_route('references') }}"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 border border-foreground text-foreground hover:bg-foreground hover:text-background transition-all rounded-lg font-medium"
                    >
                        Referenzen ansehen
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Analytics Event - can be used for conversion tracking --}}
    <script>
        // Google Analytics / Tag Manager Event
        if (typeof gtag !== 'undefined') {
            gtag('event', 'generate_lead', {
                'event_category': 'Contact',
                'event_label': 'Project Request Form'
            });
        }

        // Meta Pixel Event
        if (typeof fbq !== 'undefined') {
            fbq('track', 'Lead');
        }
    </script>
</x-layouts.frontend>

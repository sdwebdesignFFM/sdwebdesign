<x-layouts.frontend>
    @php
        $hero = $page->getSection('hero');
        $form = $page->getSection('form');
        $info = $page->getSection('info');
        $projectTypes = $page->getSection('project_types', []);
        $process = $page->getSection('process');
        $nicht = $page->getSection('nicht');
        $cta = $page->getSection('cta');
    @endphp

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-12 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[800px]">
                <div class="motion motion-fade-up">
                    @if($hero['badge'] ?? false)
                    <div class="inline-block px-4 py-2 mb-8 border border-border">
                        <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground">{{ $hero['badge'] }}</p>
                    </div>
                    @endif

                    <h1 class="mb-6">{{ $hero['title'] ?? 'Kontakt' }}</h1>

                    @if($hero['subtitle'] ?? false)
                    <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                        {{ $hero['subtitle'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Ansprechpartner Card --}}
    @php
        $settings = \App\Models\Setting::instance();
    @endphp
    <section class="py-8 lg:py-12">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="motion motion-fade-up">
                <div class="border border-border bg-sky-50 p-8 md:p-10">
                    <div class="flex flex-col md:flex-row gap-8">
                        {{-- Photo --}}
                        @if($settings->cta_image)
                        <div class="shrink-0">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($settings->cta_image) }}"
                                alt="{{ $settings->cta_name ?? $settings->owner_name }}"
                                class="w-36 h-44 object-cover object-top border-2 border-black"
                            />
                        </div>
                        @endif

                        {{-- Content --}}
                        <div class="flex-1">
                            <p class="text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-2">Ihr Ansprechpartner</p>
                            <h2 class="text-[1.5rem] font-medium mb-1">
                                {{ $settings->cta_name ?? $settings->owner_name }}
                            </h2>
                            <p class="text-[0.9375rem] text-muted-foreground mb-4">{{ $settings->cta_role ?? 'Geschäftsführer' }}</p>

                            <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6 max-w-[550px]">
                                {{ $settings->cta_subtitle ?? 'Ich berate Sie persönlich zu Ihrem Projekt – ehrlich, technisch fundiert und ohne Verkaufsdruck. Gemeinsam finden wir heraus, ob und wie wir Ihre Anforderungen sinnvoll umsetzen können.' }}
                            </p>

                            {{-- Button --}}
                            <div class="flex flex-wrap gap-3">
                                @if($settings->mobile)
                                <x-frontend.obfuscated-contact
                                    type="phone"
                                    :value="$settings->mobile"
                                    class="inline-flex items-center gap-2 px-5 py-3 border border-border bg-white hover:border-foreground transition-all text-[0.875rem]"
                                />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Contact Form & Info --}}
    <section id="formular" class="py-16 lg:py-24 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16">
                {{-- Contact CTA Card --}}
                <div class="motion motion-fade-up">
                    <div class="border border-border bg-white overflow-hidden h-full">
                        {{-- Header --}}
                        <div class="bg-accent text-white p-8">
                            <h2 class="text-[1.5rem] mb-2">{{ $form['title'] ?? 'Projekt anfragen' }}</h2>
                            <p class="text-white/70 text-[0.9375rem]">
                                In nur 3 Schritten zu Ihrem individuellen Angebot
                            </p>
                        </div>

                        {{-- Content --}}
                        <div class="p-8">
                            {{-- Steps Preview --}}
                            <div class="space-y-4 mb-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-accent/10 text-accent flex items-center justify-center text-sm font-semibold">1</div>
                                    <div>
                                        <p class="font-medium text-[0.9375rem]">Projekttyp wählen</p>
                                        <p class="text-[0.8125rem] text-muted-foreground">Webdesign, E-Commerce, App, etc.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-accent/10 text-accent flex items-center justify-center text-sm font-semibold">2</div>
                                    <div>
                                        <p class="font-medium text-[0.9375rem]">Budget & Zeitrahmen</p>
                                        <p class="text-[0.8125rem] text-muted-foreground">Ihre Vorstellungen angeben</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-accent/10 text-accent flex items-center justify-center text-sm font-semibold">3</div>
                                    <div>
                                        <p class="font-medium text-[0.9375rem]">Kontaktdaten</p>
                                        <p class="text-[0.8125rem] text-muted-foreground">Für unser persönliches Gespräch</p>
                                    </div>
                                </div>
                            </div>

                            {{-- CTA Button --}}
                            <button
                                type="button"
                                onclick="Livewire.dispatch('openContactModal')"
                                class="w-full flex items-center justify-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all text-[0.9375rem] rounded-lg font-medium"
                            >
                                Jetzt Projekt anfragen
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                                </svg>
                            </button>

                            @if($form['response_time'] ?? false)
                            <p class="mt-4 text-[0.8125rem] text-muted-foreground text-center">
                                {{ $form['response_time'] }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="motion motion-fade-up motion-delay-1">
                    <h2 class="mb-8 text-[1.5rem]">{{ $info['title'] ?? 'Kontaktinformationen' }}</h2>

                    @php
                        $contactEmail = $info['email'] ?? $settings->email ?? null;
                        $contactPhone = $info['phone'] ?? $settings->mobile ?? $settings->phone ?? null;
                        $phoneHours = $info['phone_hours'] ?? $settings->business_hours ?? null;
                        $contactLocation = $info['location'] ?? (($settings->city || $settings->country)
                            ? trim(($settings->city ?? '') . "\n" . ($settings->country ?? ''))
                            : null);
                    @endphp

                    <div class="space-y-6">
                        @if($contactEmail)
                        <div class="p-6 border border-border bg-white">
                            <div class="flex items-start gap-4">
                                <div class="p-3 border border-border">
                                    <x-frontend.icon name="mail" class="w-5 h-5 text-accent" />
                                </div>
                                <div>
                                    <h3 class="text-[0.9375rem] font-medium mb-1">E-Mail</h3>
                                    <x-frontend.obfuscated-contact
                                        type="email"
                                        :value="$contactEmail"
                                        :showIcon="false"
                                        class="text-[0.9375rem] text-muted-foreground hover:text-accent transition-colors"
                                    />
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($contactPhone)
                        <div class="p-6 border border-border bg-white">
                            <div class="flex items-start gap-4">
                                <div class="p-3 border border-border">
                                    <x-frontend.icon name="phone" class="w-5 h-5 text-accent" />
                                </div>
                                <div>
                                    <h3 class="text-[0.9375rem] font-medium mb-1">Telefon</h3>
                                    <x-frontend.obfuscated-contact
                                        type="phone"
                                        :value="$contactPhone"
                                        :showIcon="false"
                                        class="text-[0.9375rem] text-muted-foreground hover:text-accent transition-colors"
                                    />
                                    @if($phoneHours)
                                    <p class="mt-1 text-[0.8125rem] text-muted-foreground">{{ $phoneHours }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($contactLocation)
                        <div class="p-6 border border-border bg-white">
                            <div class="flex items-start gap-4">
                                <div class="p-3 border border-border">
                                    <x-frontend.icon name="map-pin" class="w-5 h-5 text-accent" />
                                </div>
                                <div>
                                    <h3 class="text-[0.9375rem] font-medium mb-1">Standort</h3>
                                    <p class="text-[0.9375rem] text-muted-foreground leading-relaxed whitespace-pre-line">{{ $contactLocation }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Process Section --}}
    @if($process['title'] ?? false)
    <section class="py-16 lg:py-24 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up mb-12">
                    <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                    <h2 class="text-[1.25rem] mb-4">{{ $process['title'] }}</h2>
                    @if($process['subtitle'] ?? false)
                    <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">{{ $process['subtitle'] }}</p>
                    @endif
                </div>

                @if($process['steps'] ?? false)
                <div class="space-y-6">
                    @foreach($process['steps'] as $index => $step)
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} p-8 border border-border bg-white">
                        <div class="flex items-start gap-6">
                            <div class="p-3 border border-border shrink-0">
                                <x-frontend.icon :name="$step['icon'] ?? 'message-square'" class="w-5 h-5 text-accent" />
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-4 mb-4">
                                    <span class="text-[1.5rem] font-light text-accent">{{ $step['number'] }}</span>
                                    <h3 class="text-[1.0625rem] font-medium">{{ $step['title'] }}</h3>
                                </div>

                                @if($step['intro'] ?? false)
                                <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-4">{{ $step['intro'] }}</p>
                                @endif

                                @if($step['list_intro'] ?? false)
                                <p class="text-[0.9375rem] text-muted-foreground mb-3">{{ $step['list_intro'] }}</p>
                                @endif

                                @if($step['items'] ?? false)
                                <ul class="space-y-2 mb-4">
                                    @foreach($step['items'] as $item)
                                    <li class="flex items-start gap-2 text-[0.9375rem] text-muted-foreground">
                                        <span class="text-foreground mt-1">•</span>
                                        <span>{{ $item }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif

                                @if($step['outro'] ?? false)
                                <p class="text-[0.9375rem] text-muted-foreground leading-relaxed whitespace-pre-line">{{ $step['outro'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Was das Gespräch nicht ist --}}
    @if($nicht['title'] ?? false)
    <section class="py-16 lg:py-24 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up mb-10">
                    <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                    <h2 class="text-[1.25rem] mb-4">{{ $nicht['title'] }}</h2>
                    @if($nicht['subtitle'] ?? false)
                    <p class="text-[1.0625rem] text-muted-foreground">{{ $nicht['subtitle'] }}</p>
                    @endif
                </div>

                @if($nicht['items'] ?? false)
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($nicht['items'] as $index => $item)
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} p-5 border border-border bg-white">
                        <div class="flex items-start gap-3">
                            <x-frontend.icon name="x-circle" class="w-5 h-5 text-muted-foreground shrink-0 mt-0.5" />
                            <span class="text-[0.9375rem]">{{ $item }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    @if($cta['title'] ?? false)
    <section class="py-16 lg:py-24 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="motion motion-fade-up max-w-[900px] mx-auto">
                <div class="p-12 bg-muted/30 text-center">
                    <h2 class="mb-4 text-[1.5rem]">{{ $cta['title'] }}</h2>
                    @if($cta['subtitle'] ?? false)
                    <p class="text-[1.0625rem] text-muted-foreground mb-8 leading-relaxed max-w-[600px] mx-auto">
                        {{ $cta['subtitle'] }}
                    </p>
                    @endif
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <button
                            type="button"
                            onclick="Livewire.dispatch('openContactModal')"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all"
                        >
                            {{ $cta['primary_button'] ?? 'Projekt anfragen' }}
                            <x-frontend.icon name="send" class="w-4 h-4" />
                        </button>
                        @if($info['email'] ?? false)
                        <a href="mailto:{{ $info['email'] }}" class="inline-flex items-center gap-3 px-8 py-4 border border-border bg-white hover:border-foreground transition-all">
                            <x-frontend.icon name="mail" class="w-4 h-4" />
                            {{ $cta['secondary_button'] ?? 'E-Mail schreiben' }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Contact Modal --}}
    <livewire:contact-modal />
</x-layouts.frontend>

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
                            <p class="text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-2">{{ __('contact.your_contact_person') }}</p>
                            <h2 class="text-[1.5rem] font-medium mb-1">
                                {{ $settings->cta_name ?? $settings->owner_name }}
                            </h2>
                            <p class="text-[0.9375rem] text-muted-foreground mb-4">{{ $settings->cta_role ?? __('contact.managing_director') }}</p>

                            <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-6 max-w-[550px]">
                                {{ $settings->cta_subtitle ?? __('contact.contact_person_intro') }}
                            </p>

                            {{-- Button --}}
                            <div class="flex flex-wrap gap-3">
                                @if($settings->mobile)
                                <x-frontend.obfuscated-contact
                                    type="phone"
                                    :value="$settings->mobile"
                                    class="inline-flex items-center gap-2 px-5 py-3 border border-border bg-background hover:border-foreground transition-all text-[0.875rem]"
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
                <div class="motion motion-fade-up" x-data x-intersect:leave="$dispatch('cta-hidden')" x-intersect:enter="$dispatch('cta-visible')">
                    <div class="border border-border bg-background overflow-hidden h-full">
                        {{-- Header --}}
                        <div class="bg-accent text-white p-8">
                            <h2 class="text-[1.5rem] mb-2">{{ $form['title'] ?? __('contact.modal_title') }}</h2>
                            <p class="text-white/70 text-[0.9375rem]">
                                {{ __('contact.form_subtitle') }}
                            </p>
                        </div>

                        {{-- Content --}}
                        <div class="p-8">
                            {{-- Steps Preview --}}
                            <div class="space-y-4 mb-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-accent/10 text-accent flex items-center justify-center text-sm font-semibold">1</div>
                                    <div>
                                        <p class="font-medium text-[0.9375rem]">{{ __('contact.step1_name') }}</p>
                                        <p class="text-[0.8125rem] text-muted-foreground">{{ __('contact.step1_desc') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-accent/10 text-accent flex items-center justify-center text-sm font-semibold">2</div>
                                    <div>
                                        <p class="font-medium text-[0.9375rem]">{{ __('contact.step2_name') }}</p>
                                        <p class="text-[0.8125rem] text-muted-foreground">{{ __('contact.step2_desc') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-accent/10 text-accent flex items-center justify-center text-sm font-semibold">3</div>
                                    <div>
                                        <p class="font-medium text-[0.9375rem]">{{ __('contact.step3_name') }}</p>
                                        <p class="text-[0.8125rem] text-muted-foreground">{{ __('contact.step3_desc') }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- CTA Button --}}
                            <button
                                type="button"
                                onclick="Livewire.dispatch('openContactModal')"
                                class="w-full flex items-center justify-center gap-3 px-8 py-4 bg-foreground text-background hover:bg-foreground/90 transition-all text-[0.9375rem] rounded-lg font-medium"
                            >
                                {{ __('contact.request_project_now') }}
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
                        <div class="p-6 border border-border bg-background">
                            <div class="flex items-start gap-4">
                                <div class="p-3 border border-border">
                                    <x-frontend.icon name="mail" class="w-5 h-5 text-accent" />
                                </div>
                                <div>
                                    <h3 class="text-[0.9375rem] font-medium mb-1">{{ __('contact.label_email') }}</h3>
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
                        <div class="p-6 border border-border bg-background">
                            <div class="flex items-start gap-4">
                                <div class="p-3 border border-border">
                                    <x-frontend.icon name="phone" class="w-5 h-5 text-accent" />
                                </div>
                                <div>
                                    <h3 class="text-[0.9375rem] font-medium mb-1">{{ __('contact.label_phone') }}</h3>
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
                        <div class="p-6 border border-border bg-background">
                            <div class="flex items-start gap-4">
                                <div class="p-3 border border-border">
                                    <x-frontend.icon name="map-pin" class="w-5 h-5 text-accent" />
                                </div>
                                <div>
                                    <h3 class="text-[0.9375rem] font-medium mb-1">{{ __('contact.label_location') }}</h3>
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
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} p-8 border border-border bg-background">
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
                    <div class="motion motion-fade-up motion-delay-{{ ($index % 4) + 1 }} p-5 border border-border bg-background">
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
                        <a href="mailto:{{ $info['email'] }}" class="inline-flex items-center gap-3 px-8 py-4 border border-border bg-background hover:border-foreground transition-all">
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

    {{-- Floating Mini CTA --}}
    <div
        x-data="{ visible: false, dismissed: false }"
        x-on:cta-hidden.window="if (!dismissed) visible = true"
        x-on:cta-visible.window="visible = false"
        class="fixed bottom-6 right-6 z-40"
    >
        <div
            x-show="visible && !dismissed"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            class="relative bg-background rounded-2xl shadow-2xl border border-border overflow-hidden w-[280px]"
        >
            {{-- Close Button --}}
            <button
                type="button"
                x-on:click="dismissed = true"
                class="absolute top-3 right-3 p-1 text-muted-foreground hover:text-foreground hover:bg-muted rounded-full transition-colors z-10"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                </svg>
            </button>

            {{-- Content --}}
            <div class="p-5">
                <div class="flex items-start gap-4">
                    {{-- Photo --}}
                    @if($settings->cta_image)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($settings->cta_image) }}"
                        alt="{{ $settings->cta_name ?? $settings->owner_name }}"
                        class="w-14 h-14 object-cover object-top border-2 border-black shrink-0"
                    />
                    @else
                    <div class="w-14 h-14 bg-accent/10 flex items-center justify-center shrink-0 border-2 border-black">
                        <x-frontend.icon name="user" class="w-6 h-6 text-accent" />
                    </div>
                    @endif

                    {{-- Text --}}
                    <div class="flex-1 min-w-0 pt-1">
                        <p class="font-medium text-[0.9375rem] truncate">{{ $settings->cta_name ?? $settings->owner_name ?? __('contact.your_contact_person') }}</p>
                        <p class="text-[0.8125rem] text-muted-foreground">{{ $settings->cta_role ?? __('contact.managing_director') }}</p>
                    </div>
                </div>

                <p class="mt-4 text-[0.875rem] text-muted-foreground leading-relaxed">
                    {{ __('contact.floating_cta_text') }}
                </p>

                {{-- CTA Button --}}
                <button
                    type="button"
                    onclick="Livewire.dispatch('openContactModal')"
                    class="mt-4 w-full flex items-center justify-center gap-2 px-5 py-3 bg-accent text-white rounded-xl hover:bg-accent/90 transition-all text-[0.875rem] font-medium"
                >
                    {{ __('contact.discuss_project') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</x-layouts.frontend>

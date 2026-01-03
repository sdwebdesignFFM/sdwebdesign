<x-layouts.frontend>
    @php
        $isEnglish = app()->getLocale() === 'en';
    @endphp

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <span class="inline-block text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-4">
                        {{ $isEnglish ? 'Legal Notice' : 'Rechtliches' }}
                    </span>
                    <h1>{{ $isEnglish ? 'Terms and Conditions' : 'Allgemeine Geschäftsbedingungen' }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- AGB Content --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                @if($settings->agb_content)
                    <div class="prose prose-lg max-w-none text-[1.0625rem] text-muted-foreground leading-relaxed [&_h2]:text-foreground [&_h2]:text-[1.25rem] [&_h2]:font-semibold [&_h2]:mt-12 [&_h2]:mb-6 [&_h3]:text-foreground [&_h3]:text-[1.0625rem] [&_h3]:font-medium [&_h3]:mt-8 [&_h3]:mb-4 [&_p]:mb-4 [&_ul]:list-disc [&_ul]:list-inside [&_ul]:space-y-1 [&_ul]:ml-4 [&_ol]:list-decimal [&_ol]:list-inside [&_ol]:space-y-1 [&_ol]:ml-4 [&_a]:text-accent [&_a:hover]:underline">
                        {!! $settings->agb_content !!}
                    </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-muted-foreground">
                            {{ $isEnglish ? 'Terms and conditions are currently being updated.' : 'Die Allgemeinen Geschäftsbedingungen werden derzeit aktualisiert.' }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Company Information --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? 'Provider' : 'Anbieter' }}</h2>
                <div class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <div class="space-y-1">
                        <p class="font-medium text-foreground">{{ $settings->company_name }}</p>
                        @if($settings->owner_name)<p>{{ $settings->owner_name }}</p>@endif
                        @if($settings->street)<p>{{ $settings->street }}</p>@endif
                        @if($settings->postal_code || $settings->city)
                            <p>{{ $settings->postal_code }} {{ $settings->city }}</p>
                        @endif
                    </div>
                    <div class="mt-4 space-y-1">
                        @if($settings->email)<p>{{ $isEnglish ? 'Email' : 'E-Mail' }}: <a href="mailto:{{ $settings->email }}" class="text-accent hover:underline">{{ $settings->email }}</a></p>@endif
                        @if($settings->phone)<p>{{ $isEnglish ? 'Phone' : 'Telefon' }}: {{ $settings->phone }}</p>@endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.frontend>

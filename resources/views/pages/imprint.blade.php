<x-layouts.frontend>
    @php
        $settings = \App\Models\Setting::first();
        $isEnglish = app()->getLocale() === 'en';
    @endphp

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <h1>{{ $page->title }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- Angaben gemaess TMG --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <h2 class="text-[1.25rem] font-semibold mb-8">{{ $isEnglish ? 'Information according to § 5 TMG' : 'Angaben gemäß § 5 TMG' }}</h2>

                <div class="space-y-1 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <p>{{ $settings->company_name }}</p>
                    @if($settings->owner_name)
                    <p>{{ $isEnglish ? 'Owner' : 'Inhaber' }}: {{ $settings->owner_name }}</p>
                    @endif
                    <p>{{ $settings->street }}</p>
                    <p>{{ $settings->postal_code }} {{ $settings->city }}</p>
                    <p>{{ $isEnglish ? 'Germany' : 'Deutschland' }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Kontakt --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <h2 class="text-[1.25rem] font-semibold mb-8">{{ $isEnglish ? 'Contact' : 'Kontakt' }}</h2>

                <div class="space-y-1 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    @if($settings->phone)
                    <p>{{ $isEnglish ? 'Phone' : 'Telefon' }}: {{ $settings->phone }}</p>
                    @endif
                    @if($settings->mobile)
                    <p>{{ $isEnglish ? 'Mobile' : 'Mobil' }}: {{ $settings->mobile }}</p>
                    @endif
                    <p>{{ $isEnglish ? 'Email' : 'E-Mail' }}: {{ $settings->email }}</p>
                    @if($settings->website_url)
                    <p>Website: <a href="{{ $settings->website_url }}" class="text-accent hover:underline">{{ $settings->website_url }}</a></p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Umsatzsteuer --}}
    @if($settings->vat_id)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <h2 class="text-[1.25rem] font-semibold mb-8">{{ $isEnglish ? 'VAT' : 'Umsatzsteuer' }}</h2>

                <div class="space-y-2 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <p>{{ $isEnglish ? 'VAT identification number according to § 27 a of the German VAT Act' : 'Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz' }}:</p>
                    <p>{{ $settings->vat_id }}</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Steuerliche Angaben --}}
    @if($settings->tax_number)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <h2 class="text-[1.25rem] font-semibold mb-8">{{ $isEnglish ? 'Tax Information' : 'Steuerliche Angaben' }}</h2>

                <div class="space-y-1 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <p>{{ $isEnglish ? 'Tax Number' : 'Steuernummer' }}: {{ $settings->tax_number }}</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Verantwortlich fuer den Inhalt --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <h2 class="text-[1.25rem] font-semibold mb-8">{{ $isEnglish ? 'Responsible for content according to § 18 Abs. 2 MStV' : 'Verantwortlich für den Inhalt nach § 18 Abs. 2 MStV' }}</h2>

                <div class="space-y-1 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <p>{{ $settings->owner_name }}</p>
                    <p>{{ $settings->street }}</p>
                    <p>{{ $settings->postal_code }} {{ $settings->city }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Zusaetzliche Impressums-Angaben aus Settings (Haftung, Urheberrecht, etc.) --}}
    @if($settings->imprint_extra)
    <div class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="prose prose-lg prose-gray max-w-none
                    [&_h2]:text-[1.25rem] [&_h2]:font-semibold [&_h2]:mt-16 [&_h2]:mb-6 [&_h2]:pt-16 [&_h2]:border-t [&_h2]:border-border [&_h2]:first:mt-0 [&_h2]:first:pt-0 [&_h2]:first:border-0
                    [&_h3]:text-[1.125rem] [&_h3]:font-semibold [&_h3]:mt-8 [&_h3]:mb-4
                    [&_p]:text-[1.0625rem] [&_p]:text-muted-foreground [&_p]:leading-relaxed [&_p]:mb-4
                    [&_ul]:text-[1.0625rem] [&_ul]:text-muted-foreground [&_ul]:my-4 [&_ul]:pl-6
                    [&_ol]:text-[1.0625rem] [&_ol]:text-muted-foreground [&_ol]:my-4 [&_ol]:pl-6
                    [&_a]:text-accent [&_a]:hover:underline">
                    {!! $settings->imprint_extra !!}
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Stand --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <p class="text-[1.0625rem] text-muted-foreground">
                    {{ $isEnglish ? 'Last updated' : 'Stand' }}: {{ now()->locale($isEnglish ? 'en' : 'de')->translatedFormat('F Y') }}
                </p>
            </div>
        </div>
    </section>
</x-layouts.frontend>

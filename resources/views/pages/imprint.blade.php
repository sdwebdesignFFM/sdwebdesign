<x-layouts.frontend>
    @php
        $settings = \App\Models\Setting::first();
        $sections = $page->getSection('sections', []);
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
                    <h1>{{ $page->title }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- Angaben gemaess TMG --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? 'Information according to § 5 TMG' : 'Angaben gemäß § 5 TMG' }}</h2>

                <div class="space-y-1 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <p>{{ $settings->company_name }}</p>
                    <p>{{ $settings->owner_name }}</p>
                    <p>{{ $settings->street }}</p>
                    <p>{{ $settings->postal_code }} {{ $settings->city }}@if($isEnglish), Germany @endif</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Kontakt --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? 'Contact' : 'Kontakt' }}</h2>

                <div class="space-y-1 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    @if($settings->phone)
                    <p>{{ $isEnglish ? 'Phone' : 'Telefon' }}: {{ $settings->phone }}</p>
                    @endif
                    @if($settings->mobile)
                    <p>{{ $isEnglish ? 'Mobile' : 'Mobil' }}: {{ $settings->mobile }}</p>
                    @endif
                    <p>{{ $isEnglish ? 'Email' : 'E-Mail' }}: {{ $settings->email }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Umsatzsteuer-ID --}}
    @if($settings->vat_id)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? 'VAT ID' : 'Umsatzsteuer-ID' }}</h2>

                <div class="space-y-2 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <p>{{ $isEnglish ? 'VAT identification number according to § 27 a of the German VAT Act' : 'Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz' }}:</p>
                    <p class="font-medium text-foreground">{{ $settings->vat_id }}</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Verantwortlich fuer den Inhalt --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? 'Responsible for content according to § 55 Abs. 2 RStV' : 'Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV' }}</h2>

                <div class="space-y-1 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <p>{{ $settings->owner_name }}</p>
                    <p>{{ $settings->street }}</p>
                    <p>{{ $settings->postal_code }} {{ $settings->city }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- EU-Streitschlichtung --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? 'EU Dispute Resolution' : 'EU-Streitschlichtung' }}</h2>

                <div class="space-y-4 text-[1.0625rem] text-muted-foreground leading-relaxed">
                    <p>
                        {{ $isEnglish ? 'The European Commission provides a platform for online dispute resolution (OS)' : 'Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit' }}:
                        <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener" class="text-accent hover:underline">https://ec.europa.eu/consumers/odr/</a>
                    </p>
                    <p>{{ $isEnglish ? 'You can find our email address above in the legal notice.' : 'Unsere E-Mail-Adresse finden Sie oben im Impressum.' }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Verbraucherstreitbeilegung --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $isEnglish ? 'Consumer Dispute Resolution' : 'Verbraucherstreitbeilegung' }}</h2>

                <p class="text-[1.0625rem] text-muted-foreground leading-relaxed">
                    {{ $isEnglish ? 'We are not willing or obliged to participate in dispute resolution proceedings before a consumer arbitration board.' : 'Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.' }}
                </p>
            </div>
        </div>
    </section>

    {{-- Zusaetzliche Impressums-Angaben aus Settings --}}
    @if($settings->imprint_extra)
    <div class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="prose prose-lg prose-gray max-w-none
                    [&_h2]:text-[1.25rem] [&_h2]:font-semibold [&_h2]:mt-12 [&_h2]:mb-6 [&_h2]:first:mt-0
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

    {{-- Zusaetzliche Abschnitte aus dem CMS --}}
    @foreach($sections as $section)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $section['heading'] }}</h2>
                <div class="text-[1.0625rem] text-muted-foreground leading-relaxed prose-links:text-accent prose-links:hover:underline">
                    {!! $section['content'] !!}
                </div>
            </div>
        </div>
    </section>
    @endforeach
</x-layouts.frontend>

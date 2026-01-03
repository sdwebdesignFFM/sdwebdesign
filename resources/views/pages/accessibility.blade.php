<x-layouts.frontend>
    @php
        $settings = \App\Models\Setting::first();
        $content = $page->content ?? [];
        $isEnglish = app()->getLocale() === 'en';
    @endphp

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-16 lg:pt-40 lg:pb-20 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>

        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <div class="motion motion-fade-up">
                    <span class="inline-block text-[0.75rem] font-semibold tracking-widest text-accent uppercase mb-4">
                        {{ $isEnglish ? 'Accessibility' : 'Barrierefreiheit' }}
                    </span>
                    <h1>{{ $page->title }}</h1>
                </div>
            </div>
        </div>
    </section>

    {{-- Content Sections --}}
    @foreach($content['sections'] ?? [] as $index => $section)
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up">
                <div class="w-12 h-0.5 bg-foreground mb-8"></div>
                <h2 class="text-[1.25rem] mb-8">{{ $section['heading'] }}</h2>
                <div class="prose prose-lg max-w-none text-[1.0625rem] text-muted-foreground leading-relaxed
                    [&_h3]:text-[1.0625rem] [&_h3]:font-medium [&_h3]:text-foreground [&_h3]:mb-4 [&_h3]:mt-8
                    [&_p]:mb-4
                    [&_ul]:list-disc [&_ul]:ml-6 [&_ul]:space-y-2
                    [&_li]:text-muted-foreground
                    [&_a]:text-accent [&_a:hover]:underline
                    [&_strong]:text-foreground [&_strong]:font-medium">
                    {!! $section['content'] !!}
                </div>
            </div>
        </div>
    </section>
    @endforeach

    {{-- Contact Section --}}
    <section class="max-w-[1400px] mx-auto px-6 py-16 lg:py-24 border-t border-border">
        <div class="max-w-[800px]">
            <div class="motion motion-fade-up p-8 bg-muted/30 border border-border rounded-xl">
                <h2 class="text-[1.25rem] mb-6">{{ $isEnglish ? 'Feedback and Contact' : 'Feedback und Kontakt' }}</h2>
                <p class="text-[1.0625rem] text-muted-foreground leading-relaxed mb-6">
                    {{ $isEnglish
                        ? 'If you notice any barriers on our website or are unable to use content, we welcome your feedback.'
                        : 'Sollten Ihnen Barrieren auf unserer Website auffallen oder sollten Sie Inhalte nicht nutzen können, freuen wir uns über Ihre Rückmeldung.' }}
                </p>
                <div class="space-y-2 text-[1.0625rem]">
                    <p class="font-medium">{{ $settings->company_name }} {{ $settings->owner_name ? '– '.$settings->owner_name : '' }}</p>
                    <p class="text-muted-foreground">{{ $settings->street }}</p>
                    <p class="text-muted-foreground">{{ $settings->postal_code }} {{ $settings->city }}</p>
                    @if($settings->phone)
                    <p class="text-muted-foreground mt-4">{{ $isEnglish ? 'Phone' : 'Telefon' }}: {{ $settings->phone }}</p>
                    @endif
                    <p class="text-muted-foreground">{{ $isEnglish ? 'Email' : 'E-Mail' }}: {{ $settings->email }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Last Updated --}}
    <section class="max-w-[1400px] mx-auto px-6 pb-16 lg:pb-24">
        <div class="max-w-[800px]">
            <p class="text-sm text-muted-foreground">
                {{ $isEnglish ? 'Last updated' : 'Stand der Erklärung' }}: {{ $content['last_updated'] ?? ($isEnglish ? 'January 2026' : 'Januar 2026') }}
            </p>
        </div>
    </section>
</x-layouts.frontend>

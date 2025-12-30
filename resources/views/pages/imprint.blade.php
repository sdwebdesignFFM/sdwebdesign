<x-layouts.frontend>
    @php
        $sections = $page->getSection('sections', []);
        $company = $page->getSection('company', []);
    @endphp

    <section class="pt-32 pb-20 lg:pt-40 lg:pb-32">
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="mb-12">{{ $page->title }}</h1>

            <div class="prose prose-lg max-w-none">
                @if($company['name'] ?? false)
                <h2>Angaben gemäß § 5 TMG</h2>
                <p>
                    {{ $company['name'] }}<br>
                    @if($company['owner'] ?? false){{ $company['owner'] }}<br>@endif
                    @if($company['street'] ?? false){{ $company['street'] }}<br>@endif
                    @if(($company['zip'] ?? false) || ($company['city'] ?? false)){{ $company['zip'] }} {{ $company['city'] }}@endif
                </p>

                @if(($company['phone'] ?? false) || ($company['email'] ?? false))
                <h2>Kontakt</h2>
                <p>
                    @if($company['phone'] ?? false)Telefon: {{ $company['phone'] }}<br>@endif
                    @if($company['email'] ?? false)E-Mail: {{ $company['email'] }}@endif
                </p>
                @endif

                @if($company['vat_id'] ?? false)
                <h2>Umsatzsteuer-ID</h2>
                <p>
                    Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:<br>
                    {{ $company['vat_id'] }}
                </p>
                @endif
                @endif

                @foreach($sections as $section)
                <h2>{{ $section['heading'] }}</h2>
                {!! $section['content'] !!}
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.frontend>

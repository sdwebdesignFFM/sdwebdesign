<x-layouts.frontend>
    @php
        $sections = $page->getSection('sections', []);
        $company = $page->getSection('company', []);
    @endphp

    <section class="pt-32 pb-20 lg:pt-40 lg:pb-32">
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="mb-12">{{ $page->title }}</h1>

            <div class="prose prose-lg max-w-none">
                @foreach($sections as $section)
                <h2>{{ $section['heading'] }}</h2>
                {!! $section['content'] !!}
                @endforeach

                @if($company['name'] ?? false)
                <h3>Hinweis zur verantwortlichen Stelle</h3>
                <p>
                    Die verantwortliche Stelle für die Datenverarbeitung auf dieser Website ist:<br><br>
                    {{ $company['name'] }}<br>
                    @if($company['owner'] ?? false){{ $company['owner'] }}<br>@endif
                    @if($company['street'] ?? false){{ $company['street'] }}<br>@endif
                    @if(($company['zip'] ?? false) || ($company['city'] ?? false)){{ $company['zip'] }} {{ $company['city'] }}<br>@endif
                    @if($company['email'] ?? false)<br>E-Mail: {{ $company['email'] }}@endif
                </p>
                @endif
            </div>
        </div>
    </section>
</x-layouts.frontend>

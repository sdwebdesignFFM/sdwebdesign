@php
    $currentLocale = app()->getLocale();
@endphp

<div class="flex items-center gap-1 text-sm">
    <a
        href="{{ $currentLocale === 'de' ? '#' : alternate_locale_url('de') }}"
        @class([
            'font-medium transition-colors',
            'text-foreground' => $currentLocale === 'de',
            'text-muted-foreground hover:text-foreground' => $currentLocale !== 'de',
        ])
        @if($currentLocale === 'de') aria-current="true" @endif
    >
        DE
    </a>
    <span class="text-muted-foreground">|</span>
    <a
        href="{{ $currentLocale === 'en' ? '#' : alternate_locale_url('en') }}"
        @class([
            'font-medium transition-colors',
            'text-foreground' => $currentLocale === 'en',
            'text-muted-foreground hover:text-foreground' => $currentLocale !== 'en',
        ])
        @if($currentLocale === 'en') aria-current="true" @endif
    >
        EN
    </a>
</div>

@props(['page'])

@php
    $breadcrumbs = $page->getBreadcrumbs();
    $locale = app()->getLocale();
    $homeLabel = $locale === 'en' ? 'Home' : 'Startseite';
    $solutionsLabel = $locale === 'en' ? 'Solutions' : 'Lösungen';
    $solutionsUrl = $locale === 'en' ? '/en/solutions' : '/loesungen';
@endphp

@if(count($breadcrumbs) > 0)
<nav aria-label="Breadcrumb" class="py-4">
    <ol class="flex flex-wrap items-center gap-2 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
        {{-- Home --}}
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="{{ localized_route('home') }}" itemprop="item" class="text-muted-foreground hover:text-foreground transition-colors">
                <span itemprop="name">{{ $homeLabel }}</span>
            </a>
            <meta itemprop="position" content="1" />
        </li>

        <li class="text-muted-foreground">/</li>

        {{-- Solutions Overview --}}
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="{{ $solutionsUrl }}" itemprop="item" class="text-muted-foreground hover:text-foreground transition-colors">
                <span itemprop="name">{{ $solutionsLabel }}</span>
            </a>
            <meta itemprop="position" content="2" />
        </li>

        {{-- Breadcrumb items --}}
        @foreach($breadcrumbs as $url => $name)
            <li class="text-muted-foreground">/</li>

            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                @if(!$loop->last)
                    <a href="{{ $url }}" itemprop="item" class="text-muted-foreground hover:text-foreground transition-colors">
                        <span itemprop="name">{{ $name }}</span>
                    </a>
                @else
                    <span itemprop="name" class="text-foreground font-medium">{{ $name }}</span>
                @endif
                <meta itemprop="position" content="{{ $loop->iteration + 2 }}" />
            </li>
        @endforeach
    </ol>
</nav>
@endif

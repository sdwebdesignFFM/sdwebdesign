@php
    $hubPages = \App\Models\Page::getHubPagesForMenu();
@endphp

<div
    x-data="{
        open: false,
        focusedIndex: -1,
        menuItems: [],
        init() {
            this.$watch('open', (value) => {
                if (value) {
                    this.$nextTick(() => {
                        this.menuItems = [...this.$refs.menuContent.querySelectorAll('a[role=menuitem]')];
                        this.focusedIndex = -1;
                    });
                }
            });
        },
        focusNext() {
            if (this.menuItems.length === 0) return;
            this.focusedIndex = (this.focusedIndex + 1) % this.menuItems.length;
            this.menuItems[this.focusedIndex]?.focus();
        },
        focusPrev() {
            if (this.menuItems.length === 0) return;
            this.focusedIndex = this.focusedIndex <= 0 ? this.menuItems.length - 1 : this.focusedIndex - 1;
            this.menuItems[this.focusedIndex]?.focus();
        },
        closeAndFocusTrigger() {
            this.open = false;
            this.$refs.trigger.focus();
        }
    }"
    @mouseenter="open = true"
    @mouseleave="open = false"
    @keydown.escape.prevent="closeAndFocusTrigger()"
    class="relative"
>
    {{-- Trigger --}}
    <button
        type="button"
        x-ref="trigger"
        class="text-sm hover:text-accent transition-colors flex items-center gap-1 focus:outline-none focus:text-accent {{ request()->routeIs('*.solutions*') ? 'text-accent' : '' }}"
        @click="open = !open"
        @keydown.arrow-down.prevent="open = true; $nextTick(() => focusNext())"
        @keydown.arrow-up.prevent="open = true; $nextTick(() => focusPrev())"
        @keydown.enter.prevent="open = !open"
        @keydown.space.prevent="open = !open"
        :aria-expanded="open.toString()"
        aria-haspopup="true"
        aria-controls="solutions-menu"
        data-nav-item
        role="menuitem"
    >
        {{ __('navigation.solutions') }}
        <svg
            class="w-4 h-4 transition-transform duration-200"
            :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <path d="m6 9 6 6 6-6"/>
        </svg>
    </button>

    {{-- Dropdown --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        x-cloak
        class="absolute left-0 top-full pt-4 z-50"
        id="solutions-menu"
        role="menu"
        aria-label="{{ __('accessibility.solutions_menu') }}"
        @keydown.arrow-down.prevent="focusNext()"
        @keydown.arrow-up.prevent="focusPrev()"
        @keydown.home.prevent="focusedIndex = 0; menuItems[0]?.focus()"
        @keydown.end.prevent="focusedIndex = menuItems.length - 1; menuItems[focusedIndex]?.focus()"
        @keydown.tab="closeAndFocusTrigger()"
    >
        <div x-ref="menuContent" class="bg-background border border-border shadow-xl rounded-lg p-8 w-[720px]">
            {{-- Header with link to overview --}}
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-border">
                <span class="text-sm font-medium text-muted-foreground">{{ __('navigation.solutions') }}</span>
                <a
                    href="{{ localized_route('solutions') }}"
                    class="text-xs text-accent hover:underline flex items-center gap-1"
                    role="menuitem"
                >
                    {{ app()->getLocale() === 'en' ? 'View all' : 'Alle ansehen' }}
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Hub pages grid --}}
            @if($hubPages->isNotEmpty())
                <div class="grid grid-cols-2 gap-x-12 gap-y-8">
                    @foreach($hubPages as $hub)
                        <div>
                            <a
                                href="{{ $hub->getUrl() }}"
                                class="font-semibold text-sm hover:text-accent transition-colors flex items-center gap-3 mb-3"
                                role="menuitem"
                            >
                                @if($hub->getSection('hero.icon'))
                                    <span class="text-accent flex-shrink-0">
                                        <x-frontend.icon :name="$hub->getSection('hero.icon')" class="w-5 h-5" />
                                    </span>
                                @endif
                                {{ $hub->title }}
                            </a>

                            @if($hub->children->isNotEmpty())
                                <ul class="space-y-2 ml-8">
                                    @foreach($hub->children as $child)
                                        <li>
                                            <a
                                                href="{{ $child->getUrl() }}"
                                                class="text-sm text-muted-foreground hover:text-foreground transition-colors"
                                                role="menuitem"
                                            >
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Fallback if no hub pages exist yet --}}
                <div class="text-sm text-muted-foreground">
                    <a href="{{ localized_route('solutions') }}" class="hover:text-accent transition-colors" role="menuitem">
                        {{ app()->getLocale() === 'en' ? 'View our solutions' : 'Unsere Lösungen ansehen' }}
                    </a>
                </div>
            @endif

            {{-- Footer Links --}}
            <div class="mt-6 pt-4 border-t border-border flex flex-col gap-3">
                {{-- Ratgeber Link --}}
                <a
                    href="{{ localized_route('guides') }}"
                    class="flex items-center gap-2 text-sm font-medium hover:text-accent transition-colors"
                    role="menuitem"
                >
                    <x-frontend.icon name="book-open" class="w-4 h-4 text-accent" />
                    {{ app()->getLocale() === 'en' ? 'Guides' : 'Ratgeber' }}
                    <span class="text-xs text-muted-foreground ml-1">
                        {{ app()->getLocale() === 'en' ? 'Decision guides for your project' : 'Entscheidungshilfen für Ihr Projekt' }}
                    </span>
                </a>

                {{-- Betrieb & Wartung Link --}}
                <a
                    href="{{ localized_route('maintenance') }}"
                    class="flex items-center gap-2 text-sm font-medium hover:text-accent transition-colors"
                    role="menuitem"
                >
                    <x-frontend.icon name="server-stack" class="w-4 h-4 text-accent" />
                    {{ app()->getLocale() === 'en' ? 'Hosting & Maintenance' : 'Betrieb & Wartung' }}
                    <span class="text-xs text-muted-foreground ml-1">
                        {{ app()->getLocale() === 'en' ? 'Reliable operations for your project' : 'Zuverlässiger Betrieb für Ihr Projekt' }}
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>

<x-filament-panels::page>
    <div class="flex flex-col xl:flex-row gap-6">
        {{-- Left Column: Unbilled Summary --}}
        <div class="xl:w-80 xl:shrink-0">
            <x-filament::section>
                <x-slot name="heading">
                    Offene Abrechnungen
                </x-slot>

                @php
                    $summary = $this->getUnbilledSummary();
                @endphp

                @if($summary->isEmpty())
                    <div class="py-6 text-center">
                        <x-filament::icon
                            icon="heroicon-o-check-circle"
                            class="mx-auto h-8 w-8 text-success-500 mb-2"
                        />
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Keine offenen Arbeitszeiten vorhanden.
                        </p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($summary as $item)
                            @php
                                $isSelected = $selectedClientId === $item['client']->id
                                    && $selectedMonth === $item['month']->format('Y-m');
                            @endphp
                            <button
                                type="button"
                                wire:click="selectGroup({{ $item['client']->id }}, '{{ $item['month']->format('Y-m') }}')"
                                style="{{ $isSelected ? 'border-color: rgb(245 158 11); background-color: rgb(254 252 232);' : 'border-color: rgb(229 231 235);' }}"
                                class="block w-full text-left p-4 rounded-xl border-2 transition-all duration-150 hover:shadow-md"
                            >
                                <div class="flex justify-between items-start gap-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $item['client']->display_name }}
                                    </span>
                                    <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $isSelected ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $item['entries']->count() }} {{ $item['entries']->count() === 1 ? 'Eintrag' : 'Einträge' }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $item['month']->translatedFormat('F Y') }}
                                </div>
                                <div class="mt-3 flex justify-between items-center text-sm border-t border-gray-200 dark:border-gray-700 pt-3">
                                    <span class="text-gray-600 dark:text-gray-400 flex items-center gap-1">
                                        <x-filament::icon icon="heroicon-m-clock" class="w-4 h-4" />
                                        {{ $this->formatMinutes($item['total_minutes']) }} Std.
                                    </span>
                                    <span class="font-bold text-gray-900 dark:text-white">
                                        {{ $this->formatMoney($item['total_amount']) }}
                                    </span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </x-filament::section>
        </div>

        {{-- Right Column: Selected Group Details --}}
        <div class="flex-1 min-w-0">
            @if($selectedClientId && $selectedMonth)
                @php
                    $client = $this->getSelectedClient();
                    $month = $this->getSelectedMonthCarbon();
                    $entries = $this->getSelectedEntries();
                    $totals = $this->getBillingTotals();
                @endphp

                <x-filament::section>
                    <x-slot name="heading">
                        {{ $client?->display_name }} – {{ $month?->translatedFormat('F Y') }}
                    </x-slot>

                    <x-slot name="headerEnd">
                        <x-filament::icon-button
                            wire:click="clearSelection"
                            icon="heroicon-m-x-mark"
                            color="gray"
                            size="sm"
                            label="Schließen"
                        />
                    </x-slot>

                    {{-- Entry List Header --}}
                    <div class="mb-4 flex flex-wrap justify-between items-center gap-2 pb-3 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span class="text-amber-600 dark:text-amber-400 font-bold">{{ count($selectedWorkLogIds) }}</span> von {{ $entries->count() }} ausgewählt
                        </span>
                        <div class="flex gap-3">
                            <button
                                type="button"
                                wire:click="selectAllEntries"
                                class="text-xs font-medium text-amber-600 hover:text-amber-800 hover:underline"
                            >
                                Alle
                            </button>
                            <span class="text-gray-300">|</span>
                            <button
                                type="button"
                                wire:click="deselectAllEntries"
                                class="text-xs font-medium text-gray-500 hover:text-gray-700 hover:underline"
                            >
                                Keine
                            </button>
                        </div>
                    </div>

                    {{-- Entry List --}}
                    <div class="space-y-2 mb-6">
                        @foreach($entries as $entry)
                            @php
                                $isEntrySelected = in_array($entry->id, $selectedWorkLogIds);
                            @endphp
                            <div
                                wire:click="toggleEntry({{ $entry->id }})"
                                wire:key="entry-{{ $entry->id }}"
                                style="{{ $isEntrySelected ? 'background-color: rgb(254 252 232); border-color: rgb(245 158 11);' : 'border-color: rgb(229 231 235);' }}"
                                class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer hover:shadow-sm transition-all"
                            >
                                <input
                                    type="checkbox"
                                    {{ $isEntrySelected ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-amber-600 focus:ring-amber-500"
                                    onclick="event.stopPropagation()"
                                    wire:click="toggleEntry({{ $entry->id }})"
                                >
                                <div class="w-16 shrink-0 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $entry->worked_on->format('d.m.') }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm text-gray-900 dark:text-white truncate">{{ $entry->title }}</div>
                                    @if($entry->task)
                                        <div class="text-xs text-gray-500 truncate flex items-center gap-1 mt-0.5">
                                            <x-filament::icon icon="heroicon-m-clipboard-document-list" class="w-3 h-3 shrink-0" />
                                            {{ $entry->task->title }}
                                        </div>
                                    @endif
                                </div>
                                <div class="w-14 shrink-0 text-right font-mono font-semibold text-gray-900 dark:text-white">
                                    {{ $entry->duration_formatted }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div style="background-color: rgb(249 250 251);" class="rounded-xl p-4 dark:bg-gray-800/50">
                        <div class="grid grid-cols-2 gap-x-6 gap-y-1 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Stunden</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $totals['total_hours'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Netto</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $this->formatMoney($totals['subtotal']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">× {{ $this->formatMoney($totals['hourly_rate']) }}/Std.</span>
                                <span></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">+ {{ number_format($totals['tax_rate'], 0) }}% MwSt.</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $this->formatMoney($totals['tax_amount']) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-3 pt-3 border-t-2 border-gray-300 dark:border-gray-600">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Brutto</span>
                            <span class="text-xl font-bold text-amber-600 dark:text-amber-400">{{ $this->formatMoney($totals['total']) }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 flex flex-wrap gap-3">
                        <x-filament::button
                            wire:click="createInvoice"
                            wire:loading.attr="disabled"
                            :disabled="empty($selectedWorkLogIds)"
                            size="lg"
                            icon="heroicon-o-document-text"
                        >
                            <span wire:loading.remove wire:target="createInvoice">
                                Rechnung erstellen
                            </span>
                            <span wire:loading wire:target="createInvoice">
                                Erstelle...
                            </span>
                        </x-filament::button>

                        <x-filament::button
                            color="gray"
                            disabled
                            title="Demnächst verfügbar"
                            size="lg"
                        >
                            sevDesk Export
                        </x-filament::button>
                    </div>
                </x-filament::section>
            @else
                {{-- Empty State --}}
                <x-filament::section>
                    <div class="py-16 text-center">
                        <div style="background-color: rgb(254 252 232);" class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4">
                            <x-filament::icon
                                icon="heroicon-o-cursor-arrow-rays"
                                class="h-8 w-8 text-amber-600"
                            />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Kunde und Monat auswählen
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                            Wählen Sie links einen Eintrag aus, um die Arbeitszeiten zu sehen und eine Rechnung zu erstellen.
                        </p>
                    </div>
                </x-filament::section>
            @endif
        </div>
    </div>
</x-filament-panels::page>

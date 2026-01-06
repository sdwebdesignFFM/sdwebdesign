<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Unbilled Summary --}}
        <div class="lg:col-span-1">
            <x-filament::section>
                <x-slot name="heading">
                    Offene Abrechnungen
                </x-slot>

                @php
                    $summary = $this->getUnbilledSummary();
                @endphp

                @if($summary->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Keine offenen Arbeitszeiten vorhanden.
                    </p>
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
                                class="w-full text-left p-4 rounded-lg border transition-colors {{ $isSelected ? 'border-primary-500 bg-primary-50 dark:bg-primary-500/10' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                            >
                                <div class="flex justify-between items-start gap-2">
                                    <span class="font-medium text-gray-900 dark:text-white text-sm">
                                        {{ $item['client']->display_name }}
                                    </span>
                                    <span class="shrink-0 text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                                        {{ $item['entries']->count() }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $item['month']->translatedFormat('F Y') }}
                                </div>
                                <div class="mt-3 flex justify-between items-center text-sm border-t border-gray-100 dark:border-gray-700 pt-2">
                                    <span class="text-gray-500 dark:text-gray-400">
                                        {{ $this->formatMinutes($item['total_minutes']) }} Std.
                                    </span>
                                    <span class="font-semibold text-gray-900 dark:text-white">
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
        <div class="lg:col-span-2">
            @if($selectedClientId && $selectedMonth)
                @php
                    $client = $this->getSelectedClient();
                    $month = $this->getSelectedMonthCarbon();
                    $entries = $this->getSelectedEntries();
                    $totals = $this->getBillingTotals();
                @endphp

                <x-filament::section>
                    <x-slot name="heading">
                        <div class="flex items-center justify-between w-full">
                            <div>
                                <span>{{ $client?->display_name }}</span>
                                <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">
                                    {{ $month?->translatedFormat('F Y') }}
                                </span>
                            </div>
                            <button
                                type="button"
                                wire:click="clearSelection"
                                class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                            >
                                &times; Schließen
                            </button>
                        </div>
                    </x-slot>

                    {{-- Entry List Header --}}
                    <div class="mb-4 flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ count($selectedWorkLogIds) }} von {{ $entries->count() }} ausgewählt
                        </span>
                        <div class="flex gap-3">
                            <button
                                type="button"
                                wire:click="selectAllEntries"
                                class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 hover:underline"
                            >
                                Alle auswählen
                            </button>
                            <button
                                type="button"
                                wire:click="deselectAllEntries"
                                class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 hover:underline"
                            >
                                Keine
                            </button>
                        </div>
                    </div>

                    {{-- Entry List --}}
                    <div class="space-y-2 mb-6">
                        @foreach($entries as $entry)
                            <label
                                class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition-colors {{ in_array($entry->id, $selectedWorkLogIds) ? 'border-primary-500 bg-primary-50 dark:bg-primary-500/10' : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800' }}"
                            >
                                <input
                                    type="checkbox"
                                    wire:click="toggleEntry({{ $entry->id }})"
                                    {{ in_array($entry->id, $selectedWorkLogIds) ? 'checked' : '' }}
                                    class="mt-0.5 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700"
                                >
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start gap-2">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $entry->worked_on->format('d.m.Y') }}
                                            </span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">
                                                {{ $entry->title }}
                                            </span>
                                        </div>
                                        <span class="shrink-0 text-sm font-semibold text-gray-900 dark:text-white tabular-nums">
                                            {{ $entry->duration_formatted }}
                                        </span>
                                    </div>
                                    @if($entry->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1 line-clamp-2">
                                            {{ $entry->description }}
                                        </p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Stunden gesamt</span>
                            <span class="text-gray-900 dark:text-white tabular-nums">{{ $totals['total_hours'] }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Stundensatz</span>
                            <span class="text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['hourly_rate']) }}</span>
                        </div>
                        <div class="flex justify-between text-sm font-medium">
                            <span class="text-gray-600 dark:text-gray-400">Netto</span>
                            <span class="text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['subtotal']) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">MwSt. {{ number_format($totals['tax_rate'], 0) }}%</span>
                            <span class="text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['tax_amount']) }}</span>
                        </div>
                        <div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-900 dark:text-white">Brutto</span>
                            <span class="text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['total']) }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 flex flex-wrap gap-3">
                        <x-filament::button
                            wire:click="createInvoice"
                            wire:loading.attr="disabled"
                            :disabled="empty($selectedWorkLogIds)"
                        >
                            <span wire:loading.remove wire:target="createInvoice">
                                Rechnung erstellen
                            </span>
                            <span wire:loading wire:target="createInvoice">
                                Erstelle Rechnung...
                            </span>
                        </x-filament::button>

                        <x-filament::button
                            color="gray"
                            disabled
                            title="Demnächst verfügbar"
                        >
                            sevDesk Export
                        </x-filament::button>
                    </div>
                </x-filament::section>
            @else
                {{-- Empty State --}}
                <x-filament::section>
                    <div class="py-12 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                            <x-filament::icon
                                icon="heroicon-o-banknotes"
                                class="h-6 w-6 text-gray-400"
                            />
                        </div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2">
                            Kunde und Monat auswählen
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                            Wählen Sie links einen Eintrag aus, um die Details zu sehen und eine Rechnung zu erstellen.
                        </p>
                    </div>
                </x-filament::section>
            @endif
        </div>
    </div>
</x-filament-panels::page>

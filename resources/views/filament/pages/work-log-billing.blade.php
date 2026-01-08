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
                    <div class="flex flex-col gap-3">
                        @foreach($summary as $item)
                            @php
                                $isSelected = $selectedClientId === $item['client']->id
                                    && $selectedMonth === $item['month']->format('Y-m');
                            @endphp
                            <button
                                type="button"
                                wire:click="selectGroup({{ $item['client']->id }}, '{{ $item['month']->format('Y-m') }}')"
                                @class([
                                    'block w-full text-left p-4 rounded-lg border-2 transition-all duration-150',
                                    'border-primary-500 bg-primary-50 dark:bg-primary-500/10 ring-1 ring-primary-500' => $isSelected,
                                    'border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 hover:bg-gray-50 dark:hover:bg-gray-800/50' => !$isSelected,
                                ])
                            >
                                <div class="flex justify-between items-start gap-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $item['client']->display_name }}
                                    </span>
                                    <span @class([
                                        'shrink-0 text-xs font-medium px-2 py-0.5 rounded-full',
                                        'bg-primary-100 text-primary-700 dark:bg-primary-500/20 dark:text-primary-400' => $isSelected,
                                        'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400' => !$isSelected,
                                    ])>
                                        {{ $item['entries']->count() }} {{ $item['entries']->count() === 1 ? 'Eintrag' : 'Einträge' }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $item['month']->translatedFormat('F Y') }}
                                </div>
                                <div class="mt-3 flex justify-between items-center text-sm border-t border-gray-100 dark:border-gray-700 pt-3">
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
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-500/20">
                                    <x-filament::icon icon="heroicon-o-user" class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                                </div>
                                <div>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $client?->display_name }}</span>
                                    <span class="block text-sm font-normal text-gray-500 dark:text-gray-400">
                                        {{ $month?->translatedFormat('F Y') }}
                                    </span>
                                </div>
                            </div>
                            <button
                                type="button"
                                wire:click="clearSelection"
                                class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                            >
                                <x-filament::icon icon="heroicon-m-x-mark" class="w-4 h-4" />
                                Schließen
                            </button>
                        </div>
                    </x-slot>

                    {{-- Entry List Header --}}
                    <div class="mb-4 flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            <span class="text-primary-600 dark:text-primary-400">{{ count($selectedWorkLogIds) }}</span> von {{ $entries->count() }} Einträgen ausgewählt
                        </span>
                        <div class="flex gap-3">
                            <button
                                type="button"
                                wire:click="selectAllEntries"
                                class="text-xs font-medium text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 hover:underline"
                            >
                                Alle auswählen
                            </button>
                            <span class="text-gray-300 dark:text-gray-600">|</span>
                            <button
                                type="button"
                                wire:click="deselectAllEntries"
                                class="text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:underline"
                            >
                                Keine auswählen
                            </button>
                        </div>
                    </div>

                    {{-- Entry List --}}
                    <div class="flex flex-col gap-2 mb-6 max-h-96 overflow-y-auto pr-1">
                        @foreach($entries as $entry)
                            <label
                                @class([
                                    'flex items-start gap-3 p-4 rounded-lg border cursor-pointer transition-all duration-150',
                                    'border-primary-500 bg-primary-50 dark:bg-primary-500/10' => in_array($entry->id, $selectedWorkLogIds),
                                    'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800/50' => !in_array($entry->id, $selectedWorkLogIds),
                                ])
                            >
                                <input
                                    type="checkbox"
                                    wire:click="toggleEntry({{ $entry->id }})"
                                    {{ in_array($entry->id, $selectedWorkLogIds) ? 'checked' : '' }}
                                    class="mt-1 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700"
                                >
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start gap-2">
                                        <div>
                                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-white">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-gray-100 dark:bg-gray-800 text-xs text-gray-600 dark:text-gray-400">
                                                    {{ $entry->worked_on->format('d') }}
                                                </span>
                                                {{ $entry->worked_on->translatedFormat('M') }}
                                            </span>
                                            <span class="block sm:inline sm:ml-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $entry->title }}
                                            </span>
                                        </div>
                                        <span class="shrink-0 text-sm font-bold text-gray-900 dark:text-white tabular-nums bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded">
                                            {{ $entry->duration_formatted }}
                                        </span>
                                    </div>
                                    @if($entry->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2 line-clamp-2 pl-8">
                                            {{ $entry->description }}
                                        </p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Totals --}}
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Stunden gesamt</span>
                            <span class="font-medium text-gray-900 dark:text-white tabular-nums">{{ $totals['total_hours'] }} Std.</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Stundensatz</span>
                            <span class="font-medium text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['hourly_rate']) }}</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Netto</span>
                                <span class="font-semibold text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['subtotal']) }}</span>
                            </div>
                            <div class="flex justify-between text-sm mt-1">
                                <span class="text-gray-600 dark:text-gray-400">MwSt. {{ number_format($totals['tax_rate'], 0) }}%</span>
                                <span class="text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['tax_amount']) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between text-lg font-bold pt-3 border-t-2 border-gray-300 dark:border-gray-600">
                            <span class="text-gray-900 dark:text-white">Brutto</span>
                            <span class="text-primary-600 dark:text-primary-400 tabular-nums">{{ $this->formatMoney($totals['total']) }}</span>
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
                                Erstelle Rechnung...
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
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-500/20 mb-4">
                            <x-filament::icon
                                icon="heroicon-o-cursor-arrow-rays"
                                class="h-8 w-8 text-primary-600 dark:text-primary-400"
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

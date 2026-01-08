<x-filament-panels::page>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Left Column: Unbilled Summary --}}
        <div>
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
                                class="block w-full text-left p-4 rounded-lg border-2 transition-all duration-150 {{ $isSelected ? 'border-primary-500 bg-primary-50 dark:bg-primary-500/10 ring-1 ring-primary-500' : 'border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 hover:bg-gray-50 dark:hover:bg-gray-800/50' }}"
                            >
                                <div class="flex justify-between items-start gap-2">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        {{ $item['client']->display_name }}
                                    </span>
                                    <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full {{ $isSelected ? 'bg-primary-100 text-primary-700 dark:bg-primary-500/20 dark:text-primary-400' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400' }}">
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
        <div class="xl:col-span-2">
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
                            <span class="text-primary-600 dark:text-primary-400 font-bold">{{ count($selectedWorkLogIds) }}</span> von {{ $entries->count() }} ausgewählt
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

                    {{-- Entry List as Table --}}
                    <div class="mb-6 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-800 text-left">
                                <tr>
                                    <th class="w-10 px-3 py-2"></th>
                                    <th class="px-3 py-2 text-gray-600 dark:text-gray-400 font-medium">Datum</th>
                                    <th class="px-3 py-2 text-gray-600 dark:text-gray-400 font-medium">Beschreibung</th>
                                    <th class="px-3 py-2 text-gray-600 dark:text-gray-400 font-medium text-right">Dauer</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($entries as $entry)
                                    @php
                                        $isEntrySelected = in_array($entry->id, $selectedWorkLogIds);
                                    @endphp
                                    <tr
                                        wire:click="toggleEntry({{ $entry->id }})"
                                        wire:key="entry-{{ $entry->id }}"
                                        class="cursor-pointer transition-colors {{ $isEntrySelected ? 'bg-primary-50 dark:bg-primary-500/10' : 'hover:bg-gray-50 dark:hover:bg-gray-800/50' }}"
                                    >
                                        <td class="px-3 py-3">
                                            <input
                                                type="checkbox"
                                                {{ $isEntrySelected ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700"
                                                onclick="event.stopPropagation()"
                                                wire:click="toggleEntry({{ $entry->id }})"
                                            >
                                        </td>
                                        <td class="px-3 py-3 text-gray-900 dark:text-white font-medium whitespace-nowrap">
                                            {{ $entry->worked_on->format('d.m.') }}
                                        </td>
                                        <td class="px-3 py-3 text-gray-700 dark:text-gray-300">
                                            <div class="line-clamp-1">{{ $entry->title }}</div>
                                            @if($entry->task)
                                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-0.5 flex items-center gap-1">
                                                    <x-filament::icon icon="heroicon-m-clipboard-document-list" class="w-3 h-3" />
                                                    {{ $entry->task->title }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-right font-mono font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $entry->duration_formatted }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Totals --}}
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Stunden</span>
                                <span class="font-medium text-gray-900 dark:text-white tabular-nums">{{ $totals['total_hours'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Netto</span>
                                <span class="font-semibold text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['subtotal']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">× Stundensatz</span>
                                <span class="font-medium text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['hourly_rate']) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">+ MwSt. {{ number_format($totals['tax_rate'], 0) }}%</span>
                                <span class="text-gray-900 dark:text-white tabular-nums">{{ $this->formatMoney($totals['tax_amount']) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center text-lg font-bold mt-4 pt-3 border-t-2 border-gray-300 dark:border-gray-600">
                            <span class="text-gray-900 dark:text-white">Gesamt (Brutto)</span>
                            <span class="text-primary-600 dark:text-primary-400 tabular-nums text-xl">{{ $this->formatMoney($totals['total']) }}</span>
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

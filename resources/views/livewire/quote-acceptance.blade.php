<div>
    {{-- Price Summary Sticky (top-20 to account for fixed navigation header) --}}
    <div class="bg-background border-b border-gray-200 sticky top-20 z-10 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <div class="text-sm text-gray-600">
                        Netto: <span class="font-medium text-gray-900">{{ number_format($this->currentTotals['subtotal'], 2, ',', '.') }} &euro;</span>
                    </div>
                    <div class="text-sm text-gray-600">
                        MwSt. ({{ number_format($quote->tax_rate, 0) }}%): <span class="font-medium text-gray-900">{{ number_format($this->currentTotals['tax_amount'], 2, ',', '.') }} &euro;</span>
                    </div>
                    <div class="text-lg font-semibold text-gray-900">
                        Gesamt: {{ number_format($this->currentTotals['total'], 2, ',', '.') }} &euro;
                    </div>
                </div>

                @if($quote->canBeAccepted())
                    <button
                        type="button"
                        wire:click="showAcceptForm"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                    >
                        Angebot annehmen
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Quote Content --}}
    <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        {{-- Quote Header --}}
        <div class="bg-background rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $quote->title }}</h1>
                    <p class="text-gray-600">{{ $quote->quote_number }}</p>
                </div>
                <div class="text-right text-sm text-gray-600">
                    <p>Erstellt am: {{ $quote->created_at->format('d.m.Y') }}</p>
                    <p>Gültig bis: <span class="{{ $quote->isExpired() ? 'text-red-600 font-medium' : '' }}">{{ $quote->valid_until->format('d.m.Y') }}</span></p>
                </div>
            </div>

            {{-- Greeting and Intro Text --}}
            <div class="mt-4 pt-4 border-t border-gray-200 text-sm text-gray-700 space-y-2">
                <p class="font-medium text-gray-900">{{ $quote->getGreeting() }}</p>
                @if($quote->intro_text)
                    <div class="[&_p]:mb-2 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_li]:mb-1">
                        {!! $quote->intro_text !!}
                    </div>
                @endif
            </div>
        </div>

        {{-- Quote Items --}}
        <div class="bg-background rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Leistungen</h2>
            </div>

            <div class="divide-y divide-gray-200">
                {{-- Required Items --}}
                @foreach($this->groupedItems['required'] as $item)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->name }}</h3>
                                @if($item->description)
                                    <div class="mt-1 text-xs text-gray-600 [&_p]:mb-1 [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-0.5 [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-0.5 [&_li]:ml-0">{!! $item->description !!}</div>
                                @endif
                                @if($item->hasDetailedTerms())
                                    <button
                                        type="button"
                                        wire:click="showTerms({{ $item->id }})"
                                        class="mt-2 inline-flex items-center text-xs text-blue-600 hover:text-blue-700 font-medium"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Leistungsvereinbarung anzeigen
                                    </button>
                                @endif
                            </div>
                            <div class="ml-4 text-right whitespace-nowrap">
                                @if($item->quantity > 1)
                                    <p class="text-sm text-gray-500">{{ number_format($item->quantity, 0) }} {{ $item->unit }} &times; {{ number_format($item->unit_price, 2, ',', '.') }} &euro;</p>
                                @endif
                                <p class="font-medium text-gray-900">
                                    {{ number_format($item->total_price, 2, ',', '.') }} &euro;
                                    @if($item->billing_cycle)
                                        <span class="text-sm font-normal text-gray-500">{{ $item->billing_cycle->getPeriodLabel() }}</span>
                                    @endif
                                </p>
                                @if($item->invoice_interval && $item->invoice_interval !== $item->billing_cycle)
                                    <p class="text-xs text-gray-500">Abrechnung: {{ $item->invoice_interval->getLabel() }}</p>
                                @endif
                                @if($item->hasPaymentTerms())
                                    <p class="text-xs text-gray-500">Zahlung: {{ $item->payment_terms }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Optional Items --}}
                @if($this->groupedItems['optional']->isNotEmpty())
                    <div class="px-6 py-3 bg-gray-50 border-t border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Optionale Leistungen</h3>
                    </div>

                    @foreach($this->groupedItems['optional'] as $item)
                        <div class="px-6 py-4 {{ ($selectedOptions[$item->id] ?? false) ? 'bg-primary-50' : 'bg-gray-50' }}">
                            <div class="flex items-start gap-4">
                                @if($quote->canBeAccepted())
                                    <button
                                        type="button"
                                        wire:click="toggleOption({{ $item->id }})"
                                        class="mt-0.5 flex-shrink-0 w-5 h-5 rounded border-2 {{ ($selectedOptions[$item->id] ?? false) ? 'bg-green-600 border-green-600' : 'border-gray-300 bg-background hover:border-gray-400' }} flex items-center justify-center transition-colors cursor-pointer"
                                    >
                                        @if($selectedOptions[$item->id] ?? false)
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 12 12">
                                                <path d="M10.28 2.28L3.989 8.575 1.695 6.28A1 1 0 00.28 7.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 2.28z"/>
                                            </svg>
                                        @endif
                                    </button>
                                @else
                                    <div class="mt-0.5 flex-shrink-0 w-5 h-5 rounded border-2 {{ ($selectedOptions[$item->id] ?? false) ? 'bg-green-600 border-green-600' : 'border-gray-300' }} flex items-center justify-center">
                                        @if($selectedOptions[$item->id] ?? false)
                                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 12 12">
                                                <path d="M10.28 2.28L3.989 8.575 1.695 6.28A1 1 0 00.28 7.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 2.28z"/>
                                            </svg>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $item->name }}</h3>
                                    @if($item->description)
                                        <div class="mt-1 text-xs text-gray-600 [&_p]:mb-1 [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-0.5 [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-0.5 [&_li]:ml-0">{!! $item->description !!}</div>
                                    @endif
                                    @if($item->hasDetailedTerms())
                                        <button
                                            type="button"
                                            wire:click="showTerms({{ $item->id }})"
                                            class="mt-2 inline-flex items-center text-xs text-blue-600 hover:text-blue-700 font-medium"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Leistungsvereinbarung anzeigen
                                        </button>
                                    @endif
                                </div>
                                <div class="ml-4 text-right whitespace-nowrap">
                                    @if($item->quantity > 1)
                                        <p class="text-sm text-gray-500">{{ number_format($item->quantity, 0) }} {{ $item->unit }} &times; {{ number_format($item->unit_price, 2, ',', '.') }} &euro;</p>
                                    @endif
                                    <p class="font-medium {{ ($selectedOptions[$item->id] ?? false) ? 'text-primary-700' : 'text-gray-500' }}">
                                        +{{ number_format($item->total_price, 2, ',', '.') }} &euro;
                                        @if($item->billing_cycle)
                                            <span class="text-sm font-normal">{{ $item->billing_cycle->getPeriodLabel() }}</span>
                                        @endif
                                    </p>
                                    @if($item->invoice_interval && $item->invoice_interval !== $item->billing_cycle)
                                        <p class="text-xs text-gray-500">Abrechnung: {{ $item->invoice_interval->getLabel() }}</p>
                                    @endif
                                    @if($item->hasPaymentTerms())
                                        <p class="text-xs text-gray-500">Zahlung: {{ $item->payment_terms }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Option Groups (A/B Selection) --}}
                @foreach($this->groupedItems['option_groups'] as $group => $items)
                    <div class="px-6 py-3 bg-gray-50 border-t border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Option wählen: {{ ucfirst(str_replace('_', ' ', $group)) }}</h3>
                    </div>

                    @foreach($items as $item)
                        <div class="px-6 py-4 {{ ($optionGroupSelections[$group] ?? null) === $item->id ? 'bg-primary-50' : 'bg-gray-50' }}">
                            <div class="flex items-start gap-4">
                                @if($quote->canBeAccepted())
                                    <button
                                        type="button"
                                        wire:click="selectOptionGroup('{{ $group }}', {{ $item->id }})"
                                        class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full border-2 {{ ($optionGroupSelections[$group] ?? null) === $item->id ? 'border-green-600' : 'border-gray-300 bg-background hover:border-gray-400' }} flex items-center justify-center transition-colors cursor-pointer"
                                    >
                                        @if(($optionGroupSelections[$group] ?? null) === $item->id)
                                            <div class="w-2.5 h-2.5 rounded-full bg-green-600"></div>
                                        @endif
                                    </button>
                                @else
                                    <div class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full border-2 {{ ($optionGroupSelections[$group] ?? null) === $item->id ? 'border-green-600' : 'border-gray-300' }} flex items-center justify-center">
                                        @if(($optionGroupSelections[$group] ?? null) === $item->id)
                                            <div class="w-2.5 h-2.5 rounded-full bg-green-600"></div>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900">{{ $item->name }}</h3>
                                    @if($item->description)
                                        <div class="mt-1 text-xs text-gray-600 [&_p]:mb-1 [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-0.5 [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-0.5 [&_li]:ml-0">{!! $item->description !!}</div>
                                    @endif
                                    @if($item->hasDetailedTerms())
                                        <button
                                            type="button"
                                            wire:click="showTerms({{ $item->id }})"
                                            class="mt-2 inline-flex items-center text-xs text-blue-600 hover:text-blue-700 font-medium"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Leistungsvereinbarung anzeigen
                                        </button>
                                    @endif
                                </div>
                                <div class="ml-4 text-right whitespace-nowrap">
                                    @if($item->quantity > 1)
                                        <p class="text-sm text-gray-500">{{ number_format($item->quantity, 0) }} {{ $item->unit }} &times; {{ number_format($item->unit_price, 2, ',', '.') }} &euro;</p>
                                    @endif
                                    <p class="font-medium {{ ($optionGroupSelections[$group] ?? null) === $item->id ? 'text-primary-700' : 'text-gray-500' }}">
                                        {{ number_format($item->total_price, 2, ',', '.') }} &euro;
                                        @if($item->billing_cycle)
                                            <span class="text-sm font-normal">{{ $item->billing_cycle->getPeriodLabel() }}</span>
                                        @endif
                                    </p>
                                    @if($item->invoice_interval && $item->invoice_interval !== $item->billing_cycle)
                                        <p class="text-xs text-gray-500">Abrechnung: {{ $item->invoice_interval->getLabel() }}</p>
                                    @endif
                                    @if($item->hasPaymentTerms())
                                        <p class="text-xs text-gray-500">Zahlung: {{ $item->payment_terms }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            {{-- Total Summary --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-end">
                    <div class="w-64 space-y-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Netto</span>
                            <span>{{ number_format($this->currentTotals['subtotal'], 2, ',', '.') }} &euro;</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>MwSt. ({{ number_format($quote->tax_rate, 0) }}%)</span>
                            <span>{{ number_format($this->currentTotals['tax_amount'], 2, ',', '.') }} &euro;</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold text-gray-900 pt-2 border-t border-gray-300">
                            <span>Gesamt</span>
                            <span>{{ number_format($this->currentTotals['total'], 2, ',', '.') }} &euro;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contract Duration (for recurring) --}}
        @if($quote->isRecurring() && ($quote->billing_cycle || $quote->min_term_months || $quote->notice_period_days))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Vertragsinformationen</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    @if($quote->billing_cycle)
                        <div>
                            <span class="text-blue-700">Zahlungsweise:</span>
                            <span class="ml-2 font-medium text-blue-900">{{ $quote->billing_cycle->getLabel() }}</span>
                        </div>
                    @endif
                    @if($quote->min_term_months)
                        <div>
                            <span class="text-blue-700">Mindestlaufzeit:</span>
                            <span class="ml-2 font-medium text-blue-900">{{ $quote->min_term_months }} Monate</span>
                        </div>
                    @endif
                    @if($quote->notice_period_days)
                        <div>
                            <span class="text-blue-700">Kündigungsfrist:</span>
                            <span class="ml-2 font-medium text-blue-900">{{ $quote->notice_period_days }} Tage</span>
                        </div>
                    @endif
                    @if($quote->auto_renewal)
                        <div>
                            <span class="text-blue-700">Automatische Verlängerung:</span>
                            <span class="ml-2 font-medium text-blue-900">Ja</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Footer Text --}}
        @if($quote->footer_text)
            <div class="prose prose-gray max-w-none mb-8">
                {!! nl2br(e($quote->footer_text)) !!}
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-wrap items-center justify-between gap-4">
            <a
                href="{{ route('quotes.pdf', ['token' => $quote->token]) }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                target="_blank"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                PDF herunterladen
            </a>

            @if($quote->canBeAccepted())
                <button
                    type="button"
                    wire:click="showAcceptForm"
                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Angebot verbindlich annehmen
                </button>
            @endif
        </div>
    </div>

    {{-- Acceptance Modal --}}
    @if($showAcceptanceForm)
        <div
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="hideAcceptForm"
                ></div>

                {{-- Modal panel --}}
                <div class="inline-block align-bottom bg-background rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    {{-- Header --}}
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Angebot annehmen
                            </h3>
                            <button
                                type="button"
                                wire:click="hideAcceptForm"
                                class="text-gray-400 hover:text-gray-500"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="px-6 py-5">
                        <p class="text-sm text-gray-500 mb-4">
                            Angebot <strong>{{ $quote->quote_number }}</strong> &mdash; <strong>{{ number_format($this->currentTotals['total'], 2, ',', '.') }} &euro;</strong>
                        </p>

                        @if($errorMessage)
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                                {{ $errorMessage }}
                            </div>
                        @endif

                        {{-- Combined Form --}}
                        <form wire:submit="accept">
                            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                                {{-- Billing Section --}}
                                <div class="space-y-3">
                                    <h4 class="text-sm font-semibold text-gray-900">Rechnungsadresse</h4>

                                    {{-- Company (optional) --}}
                                    <div>
                                        <label for="billingCompany" class="block text-sm font-medium text-gray-700 mb-1">
                                            Firma
                                        </label>
                                        <input
                                            type="text"
                                            id="billingCompany"
                                            wire:model="billingCompany"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                            placeholder="Firmenname (optional)"
                                        >
                                    </div>

                                    {{-- First Name & Last Name --}}
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="billingFirstName" class="block text-sm font-medium text-gray-700 mb-1">
                                                Vorname *
                                            </label>
                                            <input
                                                type="text"
                                                id="billingFirstName"
                                                wire:model="billingFirstName"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                                placeholder="Vorname"
                                            >
                                            @error('billingFirstName')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="billingLastName" class="block text-sm font-medium text-gray-700 mb-1">
                                                Nachname *
                                            </label>
                                            <input
                                                type="text"
                                                id="billingLastName"
                                                wire:model="billingLastName"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                                placeholder="Nachname"
                                            >
                                            @error('billingLastName')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Street --}}
                                    <div>
                                        <label for="billingStreet" class="block text-sm font-medium text-gray-700 mb-1">
                                            Straße und Hausnummer *
                                        </label>
                                        <input
                                            type="text"
                                            id="billingStreet"
                                            wire:model="billingStreet"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                            placeholder="Musterstraße 123"
                                        >
                                        @error('billingStreet')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Zip + City + Country --}}
                                    <div class="grid grid-cols-4 gap-3">
                                        <div>
                                            <label for="billingZip" class="block text-sm font-medium text-gray-700 mb-1">
                                                PLZ *
                                            </label>
                                            <input
                                                type="text"
                                                id="billingZip"
                                                wire:model="billingZip"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                                placeholder="12345"
                                            >
                                            @error('billingZip')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-span-2">
                                            <label for="billingCity" class="block text-sm font-medium text-gray-700 mb-1">
                                                Stadt *
                                            </label>
                                            <input
                                                type="text"
                                                id="billingCity"
                                                wire:model="billingCity"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                                placeholder="Berlin"
                                            >
                                            @error('billingCity')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="billingCountry" class="block text-sm font-medium text-gray-700 mb-1">
                                                Land
                                            </label>
                                            <select
                                                id="billingCountry"
                                                wire:model="billingCountry"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                            >
                                                <option value="Deutschland">DE</option>
                                                <option value="Österreich">AT</option>
                                                <option value="Schweiz">CH</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                {{-- Terms Section --}}
                                <div class="space-y-3 pt-4 border-t border-gray-200">
                                    {{-- Terms Checkbox --}}
                                    <div class="pt-2">
                                        <div class="flex items-start gap-3">
                                            <input
                                                type="checkbox"
                                                wire:model.live="termsAccepted"
                                                id="termsAccepted"
                                                class="mt-0.5 h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                            >
                                            <label for="termsAccepted" class="text-sm text-gray-700">
                                                Ich habe die <button type="button" wire:click="$set('showAgbModal', true)" class="text-blue-600 hover:text-blue-700 underline">Allgemeinen Geschäftsbedingungen (AGB)</button>
                                                @php
                                                    $termsItems = $quote->items->filter(fn($item) => $item->hasDetailedTerms() && (!$item->is_optional || ($selectedOptions[$item->id] ?? false)));
                                                @endphp
                                                @if($termsItems->isNotEmpty())
                                                    sowie die Leistungsvereinbarungen für
                                                    @foreach($termsItems as $index => $item)
                                                        <button type="button" wire:click="showTerms({{ $item->id }})" class="text-blue-600 hover:text-blue-700 underline">{{ $item->name }}</button>@if(!$loop->last), @endif
                                                    @endforeach
                                                @endif
                                                gelesen und akzeptiere diese als Vertragsbestandteil. *
                                            </label>
                                        </div>
                                        <p class="mt-1 ml-7 text-xs text-gray-500">
                                            Mit dem Absenden dieses Formulars kommt ein rechtsverbindlicher Vertrag zustande.
                                        </p>
                                        @error('termsAccepted')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-3 justify-end mt-6 pt-4 border-t border-gray-200">
                                <button
                                    type="button"
                                    wire:click="hideAcceptForm"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    Abbrechen
                                </button>
                                <button
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    @if(!$termsAccepted) disabled @endif
                                    class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span wire:loading.remove wire:target="accept">Verbindlich annehmen</span>
                                    <span wire:loading wire:target="accept">Wird verarbeitet...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Terms Modal --}}
    @if($showTermsModal && $this->termsItem)
        <div
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="terms-modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="hideTerms"
                ></div>

                {{-- Modal panel --}}
                <div class="inline-block align-bottom bg-background rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    {{-- Header --}}
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Leistungsvereinbarung: {{ $this->termsItem->name }}
                            </h3>
                            <button
                                type="button"
                                wire:click="hideTerms"
                                class="text-gray-400 hover:text-gray-500"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="px-6 py-5 max-h-[60vh] overflow-y-auto">
                        <div class="prose prose-sm prose-gray max-w-none
                            [&_h2]:text-base [&_h2]:font-semibold [&_h2]:mt-6 [&_h2]:mb-3
                            [&_h3]:text-sm [&_h3]:font-semibold [&_h3]:mt-4 [&_h3]:mb-2
                            [&_p]:text-sm [&_p]:leading-relaxed [&_p]:mb-3
                            [&_ul]:text-sm [&_ul]:my-2 [&_ul]:pl-6 [&_ul]:list-disc
                            [&_ol]:text-sm [&_ol]:my-2 [&_ol]:pl-6 [&_ol]:list-decimal
                            [&_li]:mb-1">
                            {!! $this->termsItem->detailed_terms !!}
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                        <button
                            type="button"
                            wire:click="hideTerms"
                            class="px-4 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors"
                        >
                            Schließen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- AGB Modal --}}
    @if($showAgbModal)
        <div
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="agb-modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    wire:click="$set('showAgbModal', false)"
                ></div>

                {{-- Modal panel --}}
                <div class="inline-block align-bottom bg-background rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    {{-- Header --}}
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Allgemeine Geschäftsbedingungen (AGB)
                            </h3>
                            <button
                                type="button"
                                wire:click="$set('showAgbModal', false)"
                                class="text-gray-400 hover:text-gray-500"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="px-6 py-5 max-h-[60vh] overflow-y-auto">
                        <div class="prose prose-sm prose-gray max-w-none
                            [&_h2]:text-base [&_h2]:font-semibold [&_h2]:mt-6 [&_h2]:mb-3
                            [&_h3]:text-sm [&_h3]:font-semibold [&_h3]:mt-4 [&_h3]:mb-2
                            [&_p]:text-sm [&_p]:leading-relaxed [&_p]:mb-3
                            [&_ul]:text-sm [&_ul]:my-2 [&_ul]:pl-6 [&_ul]:list-disc
                            [&_ol]:text-sm [&_ol]:my-2 [&_ol]:pl-6 [&_ol]:list-decimal
                            [&_li]:mb-1">
                            @if($settings->agb_content)
                                {!! $settings->agb_content !!}
                            @else
                                <p class="text-gray-500">Keine AGB hinterlegt.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                        <button
                            type="button"
                            wire:click="$set('showAgbModal', false)"
                            class="px-4 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors"
                        >
                            Schließen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

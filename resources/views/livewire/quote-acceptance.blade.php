<div>
    {{-- Price Summary Sticky (top-20 to account for fixed navigation header) --}}
    <div class="bg-white border-b border-gray-200 sticky top-20 z-10 shadow-sm">
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
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
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

            @if($quote->subject)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700 mb-1">Vertragsgegenstand</h3>
                    <p class="text-gray-900">{{ $quote->subject }}</p>
                </div>
            @endif
        </div>

        {{-- Intro Text --}}
        @if($quote->intro_text)
            <div class="prose prose-gray max-w-none mb-8">
                {!! $quote->intro_text !!}
            </div>
        @endif

        {{-- Quote Items --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
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
                                    <p class="mt-1 text-sm text-gray-600">{!! nl2br(e($item->description)) !!}</p>
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
                                        class="mt-0.5 flex-shrink-0 w-5 h-5 rounded border-2 {{ ($selectedOptions[$item->id] ?? false) ? 'bg-green-600 border-green-600' : 'border-gray-300 bg-white hover:border-gray-400' }} flex items-center justify-center transition-colors cursor-pointer"
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
                                        <p class="mt-1 text-sm text-gray-600">{!! nl2br(e($item->description)) !!}</p>
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
                                        class="mt-0.5 flex-shrink-0 w-5 h-5 rounded-full border-2 {{ ($optionGroupSelections[$group] ?? null) === $item->id ? 'border-green-600' : 'border-gray-300 bg-white hover:border-gray-400' }} flex items-center justify-center transition-colors cursor-pointer"
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
                                        <p class="mt-1 text-sm text-gray-600">{!! nl2br(e($item->description)) !!}</p>
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
        @if($quote->isRecurring())
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">Vertragsinformationen</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700">Abrechnungszyklus:</span>
                        <span class="ml-2 font-medium text-blue-900">{{ $quote->billing_cycle->getLabel() }}</span>
                    </div>
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
                    <div>
                        <span class="text-blue-700">Automatische Verlängerung:</span>
                        <span class="ml-2 font-medium text-blue-900">{{ $quote->auto_renewal ? 'Ja' : 'Nein' }}</span>
                    </div>
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
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    {{-- Header with Steps --}}
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

                        {{-- Step Indicator --}}
                        <div class="flex items-center justify-center mt-4">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $currentStep >= 1 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                    @if($currentStep > 1)
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        1
                                    @endif
                                </div>
                                <span class="ml-2 text-sm font-medium {{ $currentStep >= 1 ? 'text-green-600' : 'text-gray-500' }}">Rechnungsadresse</span>
                            </div>
                            <div class="w-12 h-0.5 mx-4 {{ $currentStep > 1 ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $currentStep >= 2 ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                    2
                                </div>
                                <span class="ml-2 text-sm font-medium {{ $currentStep >= 2 ? 'text-green-600' : 'text-gray-500' }}">Unterschrift</span>
                            </div>
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

                        {{-- Step 1: Billing Details --}}
                        @if($currentStep === 1)
                            <form wire:submit="nextStep">
                                <div class="space-y-4">
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

                                    {{-- Name --}}
                                    <div>
                                        <label for="billingName" class="block text-sm font-medium text-gray-700 mb-1">
                                            Name *
                                        </label>
                                        <input
                                            type="text"
                                            id="billingName"
                                            wire:model="billingName"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                            placeholder="Vor- und Nachname"
                                            required
                                        >
                                        @error('billingName')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
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
                                            required
                                        >
                                        @error('billingStreet')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Zip + City --}}
                                    <div class="grid grid-cols-3 gap-4">
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
                                                required
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
                                                required
                                            >
                                            @error('billingCity')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Country --}}
                                    <div>
                                        <label for="billingCountry" class="block text-sm font-medium text-gray-700 mb-1">
                                            Land
                                        </label>
                                        <select
                                            id="billingCountry"
                                            wire:model="billingCountry"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                        >
                                            <option value="Deutschland">Deutschland</option>
                                            <option value="Österreich">Österreich</option>
                                            <option value="Schweiz">Schweiz</option>
                                        </select>
                                    </div>

                                    {{-- VAT ID (optional) --}}
                                    <div>
                                        <label for="billingVatId" class="block text-sm font-medium text-gray-700 mb-1">
                                            USt-IdNr.
                                        </label>
                                        <input
                                            type="text"
                                            id="billingVatId"
                                            wire:model="billingVatId"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                            placeholder="DE123456789 (optional)"
                                        >
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
                                        class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                                    >
                                        Weiter
                                    </button>
                                </div>
                            </form>
                        @endif

                        {{-- Step 2: Signature Method Selection --}}
                        @if($currentStep === 2)
                            <div x-data="{ signatureMethod: 'digital' }">
                                {{-- Signature Method Selection --}}
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Wie möchten Sie unterschreiben?
                                    </label>
                                    <div class="grid grid-cols-1 gap-3">
                                        {{-- Digital Signature Option --}}
                                        <label
                                            class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-colors"
                                            :class="signatureMethod === 'digital' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300'"
                                        >
                                            <input
                                                type="radio"
                                                x-model="signatureMethod"
                                                value="digital"
                                                class="mt-0.5 h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500"
                                            >
                                            <div class="ml-3">
                                                <span class="block text-sm font-medium text-gray-900">Online unterschreiben</span>
                                                <span class="block text-sm text-gray-500">Direkt hier mit Maus oder Finger unterschreiben</span>
                                            </div>
                                        </label>

                                        {{-- Manual/PDF Option --}}
                                        <label
                                            class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer transition-colors"
                                            :class="signatureMethod === 'manual' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-gray-300'"
                                        >
                                            <input
                                                type="radio"
                                                x-model="signatureMethod"
                                                value="manual"
                                                class="mt-0.5 h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500"
                                            >
                                            <div class="ml-3">
                                                <span class="block text-sm font-medium text-gray-900">PDF herunterladen & per E-Mail senden</span>
                                                <span class="block text-sm text-gray-500">PDF ausdrucken, unterschreiben und an uns zurücksenden</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Digital Signature Form --}}
                                <div x-show="signatureMethod === 'digital'" x-cloak>
                                    <form wire:submit="accept">
                                        <div class="space-y-4">
                                            {{-- Accepted Name --}}
                                            <div>
                                                <label for="acceptedName" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Vollständiger Name des Unterzeichners *
                                                </label>
                                                <input
                                                    type="text"
                                                    id="acceptedName"
                                                    wire:model="acceptedName"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                                    placeholder="Vor- und Nachname"
                                                    required
                                                >
                                                @error('acceptedName')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- Signature Pad --}}
                                            <div
                                                x-data="signaturePadComponent()"
                                                x-init="$nextTick(() => setupCanvas())"
                                                @signature-cleared.window="clearCanvas()"
                                            >
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Unterschrift *
                                                </label>
                                                <div class="relative border-2 border-dashed border-gray-300 rounded-lg bg-white">
                                                    <canvas
                                                        x-ref="signatureCanvas"
                                                        class="w-full rounded-lg touch-none cursor-crosshair"
                                                        style="height: 150px; display: block;"
                                                    ></canvas>
                                                    <div
                                                        x-show="!hasSignature"
                                                        class="absolute inset-0 flex items-center justify-center pointer-events-none"
                                                    >
                                                        <p class="text-gray-400 text-sm">Hier unterschreiben (mit Maus oder Finger)</p>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end mt-2">
                                                    <button
                                                        type="button"
                                                        @click="clearCanvas()"
                                                        class="text-sm text-gray-500 hover:text-gray-700"
                                                    >
                                                        Unterschrift löschen
                                                    </button>
                                                </div>
                                                @error('signatureData')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            {{-- Terms Checkbox --}}
                                            <div class="pt-2">
                                                <div class="flex items-start gap-3">
                                                    <input
                                                        type="checkbox"
                                                        wire:model.live="termsAccepted"
                                                        id="termsAccepted"
                                                        class="mt-0.5 h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                                        required
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
                                                <p class="mt-2 ml-7 text-xs text-gray-500">
                                                    Mit dem Absenden dieses Formulars kommt ein rechtsverbindlicher Vertrag zustande.
                                                </p>
                                                @error('termsAccepted')
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex gap-3 justify-between mt-6 pt-4 border-t border-gray-200">
                                            <button
                                                type="button"
                                                wire:click="previousStep"
                                                class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                                            >
                                                Zurück
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

                                {{-- Manual Signature / PDF Download --}}
                                <div x-show="signatureMethod === 'manual'" x-cloak>
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                        <h4 class="font-medium text-blue-900 mb-2">So funktioniert's:</h4>
                                        <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                                            <li>Laden Sie das Angebot als PDF herunter</li>
                                            <li>Drucken Sie das Dokument aus</li>
                                            <li>Unterschreiben Sie auf der letzten Seite</li>
                                            <li>Scannen oder fotografieren Sie das unterschriebene Dokument</li>
                                            <li>Senden Sie es per E-Mail an: <a href="mailto:{{ $settings->email ?? 'info@sdwebdesign.de' }}" class="font-medium underline">{{ $settings->email ?? 'info@sdwebdesign.de' }}</a></li>
                                        </ol>
                                    </div>

                                    <div class="space-y-4">
                                        {{-- Accepted Name for PDF --}}
                                        <div>
                                            <label for="acceptedNamePdf" class="block text-sm font-medium text-gray-700 mb-1">
                                                Vollständiger Name *
                                            </label>
                                            <input
                                                type="text"
                                                id="acceptedNamePdf"
                                                wire:model="acceptedName"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                                                placeholder="Vor- und Nachname"
                                            >
                                            @error('acceptedName')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Terms Checkbox for PDF --}}
                                        <div>
                                            <div class="flex items-start gap-3">
                                                <input
                                                    type="checkbox"
                                                    wire:model.live="termsAccepted"
                                                    id="termsAcceptedPdf"
                                                    class="mt-0.5 h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                                >
                                                <label for="termsAcceptedPdf" class="text-sm text-gray-700">
                                                    Ich habe die <button type="button" wire:click="$set('showAgbModal', true)" class="text-blue-600 hover:text-blue-700 underline">Allgemeinen Geschäftsbedingungen (AGB)</button>
                                                    @php
                                                        $termsItemsPdf = $quote->items->filter(fn($item) => $item->hasDetailedTerms() && (!$item->is_optional || ($selectedOptions[$item->id] ?? false)));
                                                    @endphp
                                                    @if($termsItemsPdf->isNotEmpty())
                                                        sowie die Leistungsvereinbarungen für
                                                        @foreach($termsItemsPdf as $index => $item)
                                                            <button type="button" wire:click="showTerms({{ $item->id }})" class="text-blue-600 hover:text-blue-700 underline">{{ $item->name }}</button>@if(!$loop->last), @endif
                                                        @endforeach
                                                    @endif
                                                    gelesen und akzeptiere diese als Vertragsbestandteil. *
                                                </label>
                                            </div>
                                            @error('termsAccepted')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex gap-3 justify-between mt-6 pt-4 border-t border-gray-200">
                                        <button
                                            type="button"
                                            wire:click="previousStep"
                                            class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                                        >
                                            Zurück
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="downloadForSigning"
                                            wire:loading.attr="disabled"
                                            @if(!$termsAccepted) disabled @endif
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <span wire:loading.remove wire:target="downloadForSigning">PDF herunterladen</span>
                                            <span wire:loading wire:target="downloadForSigning">Wird erstellt...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
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
                            [&_ul]:text-sm [&_ul]:my-2 [&_ul]:pl-4
                            [&_ol]:text-sm [&_ol]:my-2 [&_ol]:pl-4
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
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
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
                            [&_ul]:text-sm [&_ul]:my-2 [&_ul]:pl-4
                            [&_ol]:text-sm [&_ol]:my-2 [&_ol]:pl-4
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

@script
<script>
    Alpine.data('signaturePadComponent', () => ({
        canvas: null,
        ctx: null,
        isDrawing: false,
        hasSignature: false,
        lastX: 0,
        lastY: 0,
        initialized: false,

        setupCanvas() {
            // Wait for next tick to ensure canvas is in DOM
            setTimeout(() => {
                this.canvas = this.$refs.signatureCanvas;
                if (!this.canvas) {
                    console.warn('Canvas not found');
                    return;
                }

                if (this.initialized) return;

                // Get the actual display size
                const rect = this.canvas.getBoundingClientRect();
                if (rect.width === 0 || rect.height === 0) {
                    // Canvas not visible yet, retry later
                    setTimeout(() => this.setupCanvas(), 100);
                    return;
                }

                // Set canvas size to match display size (1:1 ratio, no DPR scaling)
                // This ensures coordinates match exactly
                this.canvas.width = rect.width;
                this.canvas.height = rect.height;

                this.ctx = this.canvas.getContext('2d');

                // Style
                this.ctx.strokeStyle = '#000';
                this.ctx.lineWidth = 2;
                this.ctx.lineCap = 'round';
                this.ctx.lineJoin = 'round';

                // Mouse events
                this.canvas.addEventListener('mousedown', (e) => this.startDrawing(e));
                this.canvas.addEventListener('mousemove', (e) => this.draw(e));
                this.canvas.addEventListener('mouseup', () => this.stopDrawing());
                this.canvas.addEventListener('mouseleave', () => this.stopDrawing());

                // Touch events
                this.canvas.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: false });
                this.canvas.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: false });
                this.canvas.addEventListener('touchend', () => this.stopDrawing());
                this.canvas.addEventListener('touchcancel', () => this.stopDrawing());

                this.initialized = true;
            }, 100);
        },

        getPosition(e) {
            const rect = this.canvas.getBoundingClientRect();
            const scaleX = this.canvas.width / rect.width;
            const scaleY = this.canvas.height / rect.height;
            return {
                x: (e.clientX - rect.left) * scaleX,
                y: (e.clientY - rect.top) * scaleY
            };
        },

        getTouchPosition(e) {
            const rect = this.canvas.getBoundingClientRect();
            const touch = e.touches[0];
            const scaleX = this.canvas.width / rect.width;
            const scaleY = this.canvas.height / rect.height;
            return {
                x: (touch.clientX - rect.left) * scaleX,
                y: (touch.clientY - rect.top) * scaleY
            };
        },

        startDrawing(e) {
            e.preventDefault();
            this.isDrawing = true;
            const pos = this.getPosition(e);
            this.lastX = pos.x;
            this.lastY = pos.y;
            // Draw a dot for single clicks
            this.ctx.beginPath();
            this.ctx.arc(pos.x, pos.y, 1, 0, Math.PI * 2);
            this.ctx.fill();
        },

        handleTouchStart(e) {
            e.preventDefault();
            this.isDrawing = true;
            const pos = this.getTouchPosition(e);
            this.lastX = pos.x;
            this.lastY = pos.y;
            // Draw a dot for single taps
            this.ctx.beginPath();
            this.ctx.arc(pos.x, pos.y, 1, 0, Math.PI * 2);
            this.ctx.fill();
        },

        draw(e) {
            if (!this.isDrawing || !this.ctx) return;
            e.preventDefault();

            const pos = this.getPosition(e);
            this.drawLine(pos.x, pos.y);
        },

        handleTouchMove(e) {
            e.preventDefault();
            if (!this.isDrawing || !this.ctx) return;

            const pos = this.getTouchPosition(e);
            this.drawLine(pos.x, pos.y);
        },

        drawLine(x, y) {
            if (!this.ctx) return;

            this.ctx.beginPath();
            this.ctx.moveTo(this.lastX, this.lastY);
            this.ctx.lineTo(x, y);
            this.ctx.stroke();

            this.lastX = x;
            this.lastY = y;
            this.hasSignature = true;
        },

        stopDrawing() {
            if (this.isDrawing && this.hasSignature) {
                this.saveSignature();
            }
            this.isDrawing = false;
        },

        saveSignature() {
            if (!this.canvas) return;
            const dataUrl = this.canvas.toDataURL('image/png');
            @this.set('signatureData', dataUrl);
        },

        clearCanvas() {
            if (!this.ctx || !this.canvas) return;
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.hasSignature = false;
            @this.set('signatureData', '');
        }
    }));
</script>
@endscript

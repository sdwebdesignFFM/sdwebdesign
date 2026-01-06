<x-layouts.frontend>
    @section('title', 'Angebot angenommen - ' . $quote->quote_number)

    <div class="min-h-screen bg-gray-100">
        {{-- Content --}}
        <div class="max-w-2xl mx-auto px-4 pt-20 pb-12 sm:px-6 lg:px-8">

            {{-- Success Banner (contained) --}}
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg p-8 mb-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-background/20 mb-6">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2">Vielen Dank!</h1>
                <p class="text-green-100 text-lg">Ihr Angebot wurde erfolgreich angenommen.</p>
            </div>

            {{-- Confirmation Details --}}
            <div class="bg-background rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Vertragsdetails</h2>

                <dl class="divide-y divide-gray-200">
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-500">Angebotsnummer</dt>
                        <dd class="font-medium text-gray-900">{{ $quote->quote_number }}</dd>
                    </div>

                    @if($quote->contract)
                        <div class="py-3 flex justify-between">
                            <dt class="text-gray-500">Vertragsnummer</dt>
                            <dd class="font-medium text-gray-900">{{ $quote->contract->contract_number }}</dd>
                        </div>
                    @endif

                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-500">Titel</dt>
                        <dd class="font-medium text-gray-900">{{ $quote->title }}</dd>
                    </div>

                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-500">Angenommen am</dt>
                        <dd class="font-medium text-gray-900">{{ $quote->accepted_at->format('d.m.Y \u\m H:i') }} Uhr</dd>
                    </div>

                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-500">Angenommen von</dt>
                        <dd class="font-medium text-gray-900">{{ $quote->accepted_name }}</dd>
                    </div>

                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-500">Gesamtbetrag</dt>
                        <dd class="font-medium text-gray-900">{{ number_format($quote->total, 2, ',', '.') }} &euro;</dd>
                    </div>

                    @if($quote->isRecurring() && $quote->billing_cycle)
                        <div class="py-3 flex justify-between">
                            <dt class="text-gray-500">Zahlungsweise</dt>
                            <dd class="font-medium text-gray-900">{{ $quote->billing_cycle->getLabel() }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Next Steps --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Wie geht es weiter?</h3>
                <ul class="space-y-2 text-blue-800">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Sie erhalten eine Bestätigungs-E-Mail mit allen Details.</span>
                    </li>
                    @if($quote->isRecurring())
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Die erste Rechnung wird Ihnen in Kürze zugestellt.</span>
                        </li>
                    @else
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Wir werden uns zeitnah bei Ihnen melden, um das Projekt zu starten.</span>
                        </li>
                    @endif
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Bei Fragen stehen wir Ihnen jederzeit zur Verfügung.</span>
                    </li>
                </ul>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a
                    href="{{ route('quotes.pdf', ['token' => $quote->token]) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 bg-background text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                    target="_blank"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Angebot als PDF
                </a>

                <a
                    href="{{ route('de.home') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors"
                >
                    Zur Startseite
                </a>
            </div>
        </div>
    </div>
</x-layouts.frontend>

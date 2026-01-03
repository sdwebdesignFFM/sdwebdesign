<x-layouts.frontend>
    @section('title', 'Angebot abgelaufen - ' . $quote->quote_number)

    <div class="min-h-screen bg-gray-100">
        {{-- Warning Banner --}}
        <div class="bg-gradient-to-r from-amber-500 to-amber-700 text-white">
            <div class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-background/20 mb-6">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-2">Angebot abgelaufen</h1>
                <p class="text-amber-100 text-lg">Dieses Angebot ist leider nicht mehr gültig.</p>
            </div>
        </div>

        {{-- Content --}}
        <div class="max-w-2xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
            {{-- Quote Details --}}
            <div class="bg-background rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Angebotsdetails</h2>

                <dl class="divide-y divide-gray-200">
                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-500">Angebotsnummer</dt>
                        <dd class="font-medium text-gray-900">{{ $quote->quote_number }}</dd>
                    </div>

                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-500">Titel</dt>
                        <dd class="font-medium text-gray-900">{{ $quote->title }}</dd>
                    </div>

                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-500">Gültig bis</dt>
                        <dd class="font-medium text-red-600">{{ $quote->valid_until->format('d.m.Y') }}</dd>
                    </div>

                    <div class="py-3 flex justify-between">
                        <dt class="text-gray-500">Gesamtbetrag</dt>
                        <dd class="font-medium text-gray-900">{{ number_format($quote->total, 2, ',', '.') }} &euro;</dd>
                    </div>
                </dl>
            </div>

            {{-- Contact Info --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Sie haben weiterhin Interesse?</h3>
                <p class="text-blue-800 mb-4">
                    Kein Problem! Kontaktieren Sie uns und wir erstellen Ihnen gerne ein neues Angebot mit aktuellen Konditionen.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a
                        href="{{ route('de.contact') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Kontakt aufnehmen
                    </a>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a
                    href="{{ route('quotes.pdf', ['token' => $quote->token]) }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors"
                    target="_blank"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Angebot ansehen (PDF)
                </a>

                <a
                    href="{{ route('de.home') }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                >
                    Zur Startseite
                </a>
            </div>
        </div>
    </div>
</x-layouts.frontend>

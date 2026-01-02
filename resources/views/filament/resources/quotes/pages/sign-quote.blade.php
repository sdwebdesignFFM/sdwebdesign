<x-filament-panels::page>
    <div class="max-w-3xl mx-auto">
        {{-- Quote Info --}}
        <div class="p-6 mb-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-950 dark:text-white">{{ $record->title }}</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $record->quote_number }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400">
                    Vom Kunden unterschrieben
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Kunde</p>
                    <p class="mt-1 text-sm text-gray-950 dark:text-white">{{ $record->client_name }}</p>
                    @if($record->client_company)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->client_company }}</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Unterschrieben von</p>
                    <p class="mt-1 text-sm text-gray-950 dark:text-white">{{ $record->accepted_name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $record->signature_at?->format('d.m.Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-4">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Gesamtbetrag</p>
                <p class="mt-1 text-lg font-semibold text-gray-950 dark:text-white">{{ number_format($record->total, 2, ',', '.') }} EUR</p>
            </div>
        </div>

        {{-- Customer Signature --}}
        <div class="p-6 mb-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h3 class="text-sm font-medium text-gray-950 dark:text-white mb-4">Kundenunterschrift</h3>
            @if($record->signature_data)
                <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                    <img src="{{ $record->signature_data }}" alt="Kundenunterschrift" class="max-h-24 mx-auto">
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $record->accepted_name }}</p>
                </div>
            @endif
        </div>

        {{-- Admin Signature Form --}}
        <form wire:submit="sign">
            <div class="p-6 bg-white rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <h3 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">Gegenzeichnung</h3>

                <div class="space-y-4">
                    {{-- Name --}}
                    <div>
                        <label for="adminSignatureName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Ihr Name <span class="text-danger-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="adminSignatureName"
                            wire:model="adminSignatureName"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                            placeholder="Max Mustermann"
                        >
                        @error('adminSignatureName')
                            <p class="mt-1 text-sm text-danger-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Position --}}
                    <div>
                        <label for="adminSignaturePosition" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Position <span class="text-danger-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="adminSignaturePosition"
                            wire:model="adminSignaturePosition"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white sm:text-sm"
                            placeholder="Geschäftsführer"
                        >
                        @error('adminSignaturePosition')
                            <p class="mt-1 text-sm text-danger-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Signature Pad --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Unterschrift <span class="text-danger-500">*</span>
                        </label>
                        <div
                            x-data="{
                                signaturePad: null,
                                init() {
                                    const canvas = this.$refs.canvas;
                                    this.signaturePad = new SignaturePad(canvas, {
                                        backgroundColor: 'rgb(255, 255, 255)',
                                        penColor: 'rgb(0, 0, 0)'
                                    });

                                    this.resizeCanvas();
                                    window.addEventListener('resize', () => this.resizeCanvas());

                                    this.signaturePad.addEventListener('endStroke', () => {
                                        @this.set('signatureData', this.signaturePad.toDataURL());
                                    });

                                    Livewire.on('signature-cleared', () => {
                                        this.signaturePad.clear();
                                    });
                                },
                                resizeCanvas() {
                                    const canvas = this.$refs.canvas;
                                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                    const rect = canvas.getBoundingClientRect();
                                    canvas.width = rect.width * ratio;
                                    canvas.height = rect.height * ratio;
                                    canvas.getContext('2d').scale(ratio, ratio);
                                    this.signaturePad.clear();
                                },
                                clear() {
                                    this.signaturePad.clear();
                                    @this.call('clearSignature');
                                }
                            }"
                            class="space-y-2"
                        >
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white">
                                <canvas
                                    x-ref="canvas"
                                    class="w-full"
                                    style="height: 150px; touch-action: none;"
                                ></canvas>
                            </div>
                            <button
                                type="button"
                                @click="clear()"
                                class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                            >
                                Unterschrift löschen
                            </button>
                        </div>
                        @error('signatureData')
                            <p class="mt-1 text-sm text-danger-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="mt-6">
                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Angebot gegenzeichnen
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    @endpush
</x-filament-panels::page>

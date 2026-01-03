<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        {{-- Signature Pad Section --}}
        <div class="mt-6 fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <header class="fi-section-header flex flex-col gap-3 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="grid flex-1 gap-y-1">
                        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            Unterschrift zeichnen
                        </h3>
                        <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                            Diese Unterschrift wird automatisch verwendet, wenn ein Kunde ein Angebot online annimmt.
                        </p>
                    </div>
                </div>
            </header>

            <div class="fi-section-content-ctn border-t border-gray-200 dark:border-white/10">
                <div class="fi-section-content p-6">
                    <div
                        x-data="{
                            signaturePad: null,
                            isEmpty: true,
                            init() {
                                this.$nextTick(() => {
                                    const canvas = this.$refs.canvas;
                                    if (canvas) {
                                        this.signaturePad = new SignaturePad(canvas, {
                                            backgroundColor: 'rgb(255, 255, 255)',
                                            penColor: 'rgb(0, 0, 0)'
                                        });
                                        this.signaturePad.addEventListener('beginStroke', () => {
                                            this.isEmpty = false;
                                        });
                                        this.resizeCanvas();
                                        window.addEventListener('resize', () => this.resizeCanvas());
                                    }
                                });
                            },
                            resizeCanvas() {
                                const canvas = this.$refs.canvas;
                                if (!canvas) return;
                                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                                const rect = canvas.getBoundingClientRect();
                                canvas.width = rect.width * ratio;
                                canvas.height = rect.height * ratio;
                                canvas.getContext('2d').scale(ratio, ratio);
                                this.signaturePad.clear();
                                this.isEmpty = true;
                            },
                            saveSignature() {
                                if (this.signaturePad && !this.signaturePad.isEmpty()) {
                                    $wire.set('data.admin_signature_data', this.signaturePad.toDataURL());
                                    this.signaturePad.clear();
                                    this.isEmpty = true;
                                }
                            },
                            clear() {
                                if (this.signaturePad) {
                                    this.signaturePad.clear();
                                    this.isEmpty = true;
                                }
                            },
                            deleteSignature() {
                                $wire.set('data.admin_signature_data', null);
                                this.clear();
                            }
                        }"
                        class="space-y-6"
                    >
                        {{-- Current Signature Preview --}}
                        @if($this->data['admin_signature_data'] ?? false)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Aktuelle Unterschrift
                                </label>
                                <div class="inline-block border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                                    <img src="{{ $this->data['admin_signature_data'] }}" alt="Admin Unterschrift" class="h-20">
                                </div>
                            </div>
                        @endif

                        {{-- Signature Pad --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                @if($this->data['admin_signature_data'] ?? false)
                                    Neue Unterschrift zeichnen
                                @else
                                    Unterschrift zeichnen
                                @endif
                            </label>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden bg-white" style="max-width: 500px;">
                                <canvas
                                    x-ref="canvas"
                                    class="w-full cursor-crosshair"
                                    style="height: 150px; touch-action: none;"
                                ></canvas>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Zeichnen Sie Ihre Unterschrift im Feld oben</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <x-filament::button
                                    type="button"
                                    size="sm"
                                    x-on:click="saveSignature()"
                                    x-bind:disabled="isEmpty"
                                >
                                    Übernehmen
                                </x-filament::button>
                                <x-filament::button
                                    type="button"
                                    size="sm"
                                    color="gray"
                                    x-on:click="clear()"
                                >
                                    Leeren
                                </x-filament::button>
                                @if($this->data['admin_signature_data'] ?? false)
                                    <x-filament::button
                                        type="button"
                                        size="sm"
                                        color="danger"
                                        x-on:click="deleteSignature()"
                                    >
                                        Unterschrift löschen
                                    </x-filament::button>
                                @endif
                            </div>
                        </div>

                        {{-- Status Messages --}}
                        @if(!($this->data['admin_signer_name'] ?? false) || !($this->data['admin_signer_position'] ?? false))
                            <div class="p-4 bg-amber-50 dark:bg-amber-500/10 rounded-lg border border-amber-200 dark:border-amber-500/20">
                                <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                    ⚠️ Unvollständige Konfiguration
                                </p>
                                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                                    Für die automatische Gegenzeichnung müssen <strong>Name</strong> und <strong>Position</strong> oben im Abschnitt "Unterschrift für Angebote" ausgefüllt werden.
                                </p>
                            </div>
                        @elseif($this->data['admin_signature_data'] ?? false)
                            <div class="p-4 bg-green-50 dark:bg-green-500/10 rounded-lg border border-green-200 dark:border-green-500/20">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                    ✓ Automatische Gegenzeichnung aktiviert
                                </p>
                                <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                                    Angebote werden nach Kundenannahme automatisch mit dieser Unterschrift gegengezeichnet.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <x-filament::button type="submit" size="lg">
                Einstellungen speichern
            </x-filament::button>
        </div>
    </form>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    @endpush
</x-filament-panels::page>

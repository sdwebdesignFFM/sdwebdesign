<div>
    @if($isOpen)
    <div
        class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center bg-black/60 p-0 sm:p-6 overflow-y-auto"
        wire:click.self="close"
        x-data
        x-trap.inert.noscroll="true"
        x-on:keydown.escape.window="$wire.close()"
    >
        <div class="bg-background w-full sm:max-w-[720px] sm:rounded-lg shadow-2xl border-t sm:border border-border max-h-screen sm:max-h-[90vh] overflow-y-auto">

            {{-- Header with progress --}}
            <div class="sticky top-0 bg-foreground text-background px-6 py-5 z-10">
                <div class="flex items-start justify-between gap-4 mb-3">
                    <div>
                        <p class="text-[0.6875rem] uppercase tracking-widest opacity-75">Plattform-Discovery · 990 € · 2-Stunden-Workshop</p>
                        <h2 class="text-[1.125rem] font-medium mt-1">Workshop anfragen</h2>
                    </div>
                    <button type="button" wire:click="close" class="p-1 -mt-1 -mr-1 hover:opacity-75" aria-label="Schließen">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                @if(! $isSubmitted)
                <div class="flex items-center gap-1.5">
                    @for($i = 1; $i <= $totalSteps; $i++)
                    <div class="flex-1 h-1 rounded-full transition-colors {{ $i <= $currentStep ? 'bg-background' : 'bg-background/30' }}"></div>
                    @endfor
                </div>
                <p class="text-[0.75rem] opacity-75 mt-2">Schritt {{ $currentStep }} von {{ $totalSteps }}</p>
                @endif
            </div>

            <div class="p-6 sm:p-8">

                {{-- Success state --}}
                @if($isSubmitted)
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-12 h-12 border-2 border-foreground mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <h3 class="text-[1.25rem] mb-3">Anfrage ist eingegangen</h3>
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed max-w-[480px] mx-auto">
                            Steffen meldet sich innerhalb von <strong>1–2 Werktagen</strong> persönlich bei Ihnen — typisch mit 2–3 Termin-Vorschlägen. Eine Bestätigung haben wir an <strong>{{ $email }}</strong> geschickt.
                        </p>
                        <button type="button" wire:click="close" class="inline-flex items-center gap-2 mt-8 px-6 py-3 border border-border text-foreground hover:bg-muted/30 transition-all text-[0.9375rem]">
                            Schließen
                        </button>
                    </div>

                {{-- Step 1: Vorhaben --}}
                @elseif($currentStep === 1)
                    <h3 class="text-[1.125rem] font-medium mb-1">Worum geht es?</h3>
                    <p class="text-[0.875rem] text-muted-foreground mb-6">
                        Damit Steffen den Workshop fokussieren kann. Alles ist freiwillig — auch knapp ist OK.
                    </p>

                    <div class="space-y-5">
                        <div>
                            <label for="trigger" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">
                                Was ist der Anlass / die Ausgangsfrage?
                            </label>
                            <textarea
                                id="trigger"
                                wire:model="triggerQuestion"
                                rows="3"
                                placeholder="z. B. „Standard-Software passt nicht mehr für unsere Disposition. Wir wissen nicht, ob wir umstellen, ergänzen oder eigen bauen sollen."
                                class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground"
                            ></textarea>
                        </div>

                        <div>
                            <label for="industry" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">
                                Branche
                            </label>
                            <select
                                id="industry"
                                wire:model="industry"
                                class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground"
                            >
                                <option value="">– bitte wählen –</option>
                                @foreach($this->industryOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <p class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">
                                Welche Workflow-Bereiche soll die Plattform tragen? <span class="normal-case opacity-75">(Mehrfachauswahl)</span>
                            </p>
                            <div class="grid sm:grid-cols-2 gap-2">
                                @foreach($this->workflowAreaOptions as $key => $label)
                                <button
                                    type="button"
                                    wire:click="toggleArrayValue('workflowAreas', '{{ $key }}')"
                                    class="text-left px-4 py-3 border text-[0.875rem] transition-colors {{ in_array($key, $workflowAreas) ? 'border-foreground bg-muted/30 font-medium' : 'border-border hover:border-foreground/50' }}"
                                >
                                    {{ $label }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                {{-- Step 2: Stand & Bestand --}}
                @elseif($currentStep === 2)
                    <h3 class="text-[1.125rem] font-medium mb-1">Stand &amp; Bestand</h3>
                    <p class="text-[0.875rem] text-muted-foreground mb-6">
                        Hilft uns, die richtigen Tech-Optionen vorzubereiten — und einzuschätzen, welche Schnittstellen relevant werden.
                    </p>

                    <div class="space-y-5">
                        <div>
                            <p class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">
                                Welche Systeme sind heute im Einsatz? <span class="normal-case opacity-75">(Mehrfachauswahl)</span>
                            </p>
                            <div class="grid sm:grid-cols-2 gap-2">
                                @foreach($this->existingSystemsOptions as $key => $label)
                                <button
                                    type="button"
                                    wire:click="toggleArrayValue('existingSystems', '{{ $key }}')"
                                    class="text-left px-3 py-2.5 border text-[0.875rem] transition-colors {{ in_array($key, $existingSystems) ? 'border-foreground bg-muted/30 font-medium' : 'border-border hover:border-foreground/50' }}"
                                >
                                    {{ $label }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <p class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">
                                Wo stehen Sie in der Anbieter-Recherche?
                            </p>
                            <div class="space-y-2">
                                @foreach($this->procurementStageOptions as $key => $label)
                                <button
                                    type="button"
                                    wire:click="setSingleValue('procurementStage', '{{ $key }}')"
                                    class="block w-full text-left px-4 py-3 border text-[0.875rem] transition-colors {{ $procurementStage === $key ? 'border-foreground bg-muted/30 font-medium' : 'border-border hover:border-foreground/50' }}"
                                >
                                    {{ $label }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-5">
                            <div>
                                <p class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Budget-Indikation</p>
                                <div class="space-y-2">
                                    @foreach($this->budgetOptions as $key => $label)
                                    <button
                                        type="button"
                                        wire:click="setSingleValue('budgetIndication', '{{ $key }}')"
                                        class="block w-full text-left px-3 py-2 border text-[0.8125rem] transition-colors {{ $budgetIndication === $key ? 'border-foreground bg-muted/30 font-medium' : 'border-border hover:border-foreground/50' }}"
                                    >
                                        {{ $label }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <p class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Wann produktiv?</p>
                                <div class="space-y-2">
                                    @foreach($this->goLiveOptions as $key => $label)
                                    <button
                                        type="button"
                                        wire:click="setSingleValue('goLiveTimeline', '{{ $key }}')"
                                        class="block w-full text-left px-3 py-2 border text-[0.8125rem] transition-colors {{ $goLiveTimeline === $key ? 'border-foreground bg-muted/30 font-medium' : 'border-border hover:border-foreground/50' }}"
                                    >
                                        {{ $label }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                {{-- Step 3: Workshop-Format --}}
                @elseif($currentStep === 3)
                    <h3 class="text-[1.125rem] font-medium mb-1">Workshop-Format</h3>
                    <p class="text-[0.875rem] text-muted-foreground mb-6">
                        Damit Steffen passende Termine vorschlagen kann.
                    </p>

                    <div class="space-y-5">
                        <div>
                            <p class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Format</p>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($this->workshopFormatOptions as $key => $label)
                                <button
                                    type="button"
                                    wire:click="setSingleValue('workshopFormat', '{{ $key }}')"
                                    class="px-3 py-3 border text-[0.875rem] transition-colors {{ $workshopFormat === $key ? 'border-foreground bg-muted/30 font-medium' : 'border-border hover:border-foreground/50' }}"
                                >
                                    {{ $label }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <p class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Termin-Wunsch</p>
                            <div class="grid sm:grid-cols-2 gap-2">
                                @foreach($this->timingOptions as $key => $label)
                                <button
                                    type="button"
                                    wire:click="setSingleValue('preferredTiming', '{{ $key }}')"
                                    class="px-3 py-2.5 border text-[0.875rem] transition-colors text-left {{ $preferredTiming === $key ? 'border-foreground bg-muted/30 font-medium' : 'border-border hover:border-foreground/50' }}"
                                >
                                    {{ $label }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <p class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Bevorzugte Tageszeit</p>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($this->daytimeOptions as $key => $label)
                                <button
                                    type="button"
                                    wire:click="setSingleValue('preferredDaytime', '{{ $key }}')"
                                    class="px-3 py-2.5 border text-[0.8125rem] transition-colors {{ $preferredDaytime === $key ? 'border-foreground bg-muted/30 font-medium' : 'border-border hover:border-foreground/50' }}"
                                >
                                    {{ $label }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                {{-- Step 4: Kontakt --}}
                @elseif($currentStep === 4)
                    <h3 class="text-[1.125rem] font-medium mb-1">Ihre Kontaktdaten</h3>
                    <p class="text-[0.875rem] text-muted-foreground mb-6">
                        Steffen meldet sich innerhalb von 1–2 Werktagen mit Termin-Vorschlägen.
                    </p>

                    <div class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="ws-name" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Name <span class="text-accent">*</span></label>
                                <input id="ws-name" type="text" wire:model.blur="name" required autocomplete="name"
                                    class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground" />
                                @error('name') <p class="text-[0.75rem] text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="ws-company" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Unternehmen</label>
                                <input id="ws-company" type="text" wire:model.blur="company" autocomplete="organization"
                                    class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground" />
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="ws-email" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">E-Mail <span class="text-accent">*</span></label>
                                <input id="ws-email" type="email" wire:model.blur="email" required autocomplete="email"
                                    class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground" />
                                @error('email') <p class="text-[0.75rem] text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="ws-phone" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Telefon <span class="text-accent">*</span></label>
                                <input id="ws-phone" type="tel" wire:model.blur="phone" required autocomplete="tel"
                                    class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground" />
                                @error('phone') <p class="text-[0.75rem] text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="ws-role" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Rolle / Funktion</label>
                                <input id="ws-role" type="text" wire:model.blur="role" autocomplete="organization-title"
                                    placeholder="z. B. Geschäftsführung, IT-Leitung"
                                    class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground" />
                            </div>
                            <div>
                                <label for="ws-size" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Unternehmensgröße</label>
                                <select id="ws-size" wire:model="companySize"
                                    class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground">
                                    <option value="">– bitte wählen –</option>
                                    @foreach($this->companySizeOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="ws-notes" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">Vorab-Briefing / Notizen <span class="normal-case opacity-75">(optional)</span></label>
                            <textarea id="ws-notes" wire:model.blur="briefingNotes" rows="3"
                                placeholder="Kontext, Stakeholder, frühere Versuche, Fragen — was Steffen vorab wissen sollte."
                                class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground"></textarea>
                        </div>
                        <label class="flex items-start gap-3 cursor-pointer pt-2">
                            <input type="checkbox" wire:model="consent" required class="mt-1 shrink-0" />
                            <span class="text-[0.8125rem] text-muted-foreground leading-relaxed">
                                Ich bin damit einverstanden, dass meine Angaben zur Bearbeitung der Workshop-Anfrage gespeichert und verarbeitet werden. <a href="/datenschutz" class="underline hover:text-foreground">Datenschutzerklärung</a>.
                            </span>
                        </label>
                        @error('consent') <p class="text-[0.75rem] text-red-600">{{ $message }}</p> @enderror

                        @if($rateLimitError)
                        <p class="text-[0.875rem] text-red-600 bg-red-50 p-3">{{ $rateLimitError }}</p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Footer / Navigation --}}
            @if(! $isSubmitted)
            <div class="sticky bottom-0 bg-background border-t border-border px-6 py-4 flex items-center justify-between gap-3">
                @if($currentStep > 1)
                <button type="button" wire:click="previousStep"
                    class="text-[0.875rem] text-muted-foreground hover:text-foreground transition-colors">
                    ← Zurück
                </button>
                @else
                <span></span>
                @endif

                @if($currentStep < $totalSteps)
                <button type="button" wire:click="nextStep"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-foreground text-background hover:bg-foreground/90 transition-all text-[0.9375rem]">
                    Weiter
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
                @else
                <button type="button" wire:click="submit" wire:loading.attr="disabled" wire:target="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-foreground text-background hover:bg-foreground/90 disabled:opacity-50 transition-all text-[0.9375rem]">
                    <span wire:loading.remove wire:target="submit">Workshop-Anfrage absenden</span>
                    <span wire:loading wire:target="submit">Wird gesendet …</span>
                </button>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif
</div>

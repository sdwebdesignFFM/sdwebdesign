<div>
    {{-- Modal Backdrop --}}
    <div
        x-data="{ show: @entangle('isOpen') }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" wire:click="close"></div>

        {{-- Modal Container --}}
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-3xl bg-white shadow-2xl rounded-2xl overflow-hidden"
            >
                @if($isSubmitted)
                    {{-- Success State --}}
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold mb-3">Vielen Dank für Ihre Anfrage!</h2>
                        <p class="text-muted-foreground mb-8 max-w-md mx-auto">
                            Wir haben Ihre Nachricht erhalten und melden uns innerhalb von 24 Stunden bei Ihnen.
                        </p>
                        <button
                            type="button"
                            wire:click="close"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-foreground text-background hover:bg-foreground/90 transition-all rounded-lg"
                        >
                            Schliessen
                        </button>
                    </div>
                @else
                    {{-- Header --}}
                    <div class="bg-accent text-white p-6 pb-8">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-2xl font-semibold mb-1">Angebot anfragen</h2>
                                <p class="text-white/70 text-sm">Schritt {{ $currentStep }} von {{ $totalSteps }}</p>
                            </div>
                            <button
                                type="button"
                                wire:click="close"
                                class="text-white/70 hover:text-white transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="flex gap-2 mt-6">
                            @for($i = 1; $i <= $totalSteps; $i++)
                                <div class="flex-1 h-1 rounded-full {{ $i <= $currentStep ? 'bg-white' : 'bg-white/30' }}"></div>
                            @endfor
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        {{-- Step 1: Project Types --}}
                        @if($currentStep === 1)
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Welche Art von Projekt planen Sie?</h3>
                                <p class="text-muted-foreground text-sm mb-6">Mehrfachauswahl möglich</p>

                                <div class="grid sm:grid-cols-2 gap-3">
                                    @foreach($projectTypes as $key => $type)
                                        @php $isSelected = in_array($key, $selectedProjectTypes); @endphp
                                        <button
                                            type="button"
                                            wire:click="toggleProjectType('{{ $key }}')"
                                            class="relative flex items-start gap-4 p-4 border-2 rounded-xl text-left transition-all {{ $isSelected ? 'border-accent bg-accent/5' : 'border-border hover:border-muted-foreground' }}"
                                        >
                                            {{-- Checkbox indicator --}}
                                            @if($isSelected)
                                                <div class="absolute top-3 right-3 w-5 h-5 bg-accent rounded-full flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M20 6 9 17l-5-5"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            {{-- Icon --}}
                                            <div class="w-10 h-10 rounded-lg {{ $isSelected ? 'bg-accent/10 text-accent' : 'bg-muted text-muted-foreground' }} flex items-center justify-center shrink-0">
                                                <x-frontend.icon :name="$type['icon']" class="w-5 h-5" />
                                            </div>

                                            {{-- Content --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-[0.9375rem]">{{ $type['label'] }}</p>
                                                <p class="text-muted-foreground text-sm">{{ $type['description'] }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Step 2: Budget & Timeline --}}
                        @if($currentStep === 2)
                            <div class="space-y-8">
                                <div>
                                    <h3 class="text-lg font-semibold mb-4">Budget & Zeitrahmen</h3>

                                    <div class="mb-6">
                                        <p class="text-sm font-medium mb-3">Geplantes Budget</p>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($budgets as $key => $label)
                                                <button
                                                    type="button"
                                                    wire:click="selectBudget('{{ $key }}')"
                                                    class="p-3 border-2 rounded-xl text-sm transition-all {{ $budget === $key ? 'border-accent bg-accent/5 font-medium' : 'border-border hover:border-muted-foreground' }}"
                                                >
                                                    {{ $label }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium mb-3">Gewünschter Zeitrahmen</p>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($timelines as $key => $label)
                                                <button
                                                    type="button"
                                                    wire:click="selectTimeline('{{ $key }}')"
                                                    class="p-3 border-2 rounded-xl text-sm transition-all {{ $timeline === $key ? 'border-accent bg-accent/5 font-medium' : 'border-border hover:border-muted-foreground' }}"
                                                >
                                                    {{ $label }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Step 3: Contact Information --}}
                        @if($currentStep === 3)
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold">Ihre Kontaktdaten</h3>

                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Name <span class="text-red-500">*</span></label>
                                        <input
                                            type="text"
                                            wire:model="name"
                                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none transition-colors"
                                        >
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Unternehmen</label>
                                        <input
                                            type="text"
                                            wire:model="company"
                                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none transition-colors"
                                        >
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">E-Mail <span class="text-red-500">*</span></label>
                                        <input
                                            type="email"
                                            wire:model="email"
                                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none transition-colors"
                                        >
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Telefon</label>
                                        <input
                                            type="tel"
                                            wire:model="phone"
                                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none transition-colors"
                                        >
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Projektbeschreibung</label>
                                    <textarea
                                        wire:model="projectDescription"
                                        rows="4"
                                        placeholder="Beschreiben Sie kurz Ihr Projekt und Ihre Anforderungen..."
                                        class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none transition-colors resize-none"
                                    ></textarea>
                                </div>

                                {{-- Callback Preferences --}}
                                <div class="pt-4 border-t border-border">
                                    <p class="text-sm font-medium mb-4">Gewünschte Rückrufzeit <span class="text-muted-foreground font-normal">(optional)</span></p>

                                    {{-- Weekdays --}}
                                    <div class="mb-4">
                                        <p class="text-xs text-muted-foreground mb-2">Bevorzugte Wochentage</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($callbackDays as $key => $label)
                                                @php $isSelected = in_array($key, $selectedCallbackDays); @endphp
                                                <button
                                                    type="button"
                                                    wire:click="toggleCallbackDay('{{ $key }}')"
                                                    class="px-4 py-2 text-sm border-2 rounded-lg transition-all {{ $isSelected ? 'border-accent bg-accent/5 font-medium' : 'border-border hover:border-muted-foreground' }}"
                                                >
                                                    {{ $label }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- Time Slots --}}
                                    <div>
                                        <p class="text-xs text-muted-foreground mb-2">Bevorzugtes Zeitfenster</p>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($callbackTimes as $key => $label)
                                                <button
                                                    type="button"
                                                    wire:click="selectCallbackTime('{{ $key }}')"
                                                    class="px-3 py-2 text-sm border-2 rounded-lg transition-all {{ $callbackTime === $key ? 'border-accent bg-accent/5 font-medium' : 'border-border hover:border-muted-foreground' }}"
                                                >
                                                    {{ $label }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Summary --}}
                                <div class="bg-muted/30 rounded-xl p-4">
                                    <p class="font-semibold mb-2">Zusammenfassung</p>
                                    <div class="text-sm text-muted-foreground space-y-1">
                                        <p><span class="text-foreground">Projekttyp(en):</span> {{ implode(', ', $this->getSelectedProjectTypesLabels()) }}</p>
                                        @if($budget)
                                            <p><span class="text-foreground">Budget:</span> {{ $budgets[$budget] }}</p>
                                        @endif
                                        @if($timeline)
                                            <p><span class="text-foreground">Zeitrahmen:</span> {{ $timelines[$timeline] }}</p>
                                        @endif
                                        @if(count($selectedCallbackDays) > 0 || $callbackTime)
                                            <p>
                                                <span class="text-foreground">Rückruf:</span>
                                                @if(count($selectedCallbackDays) > 0)
                                                    {{ implode(', ', $this->getSelectedCallbackDaysLabels()) }}
                                                @endif
                                                @if(count($selectedCallbackDays) > 0 && $callbackTime), @endif
                                                @if($callbackTime)
                                                    {{ $callbackTimes[$callbackTime] }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-xs text-muted-foreground">
                                    Mit dem Absenden stimmen Sie unserer
                                    <a href="{{ route('privacy') }}" target="_blank" class="underline hover:text-foreground">Datenschutzerklärung</a>
                                    zu.
                                </p>
                            </div>
                        @endif

                        {{-- Rate Limit Error --}}
                        @if($rateLimitError)
                            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-600">{{ $rateLimitError }}</p>
                            </div>
                        @endif

                        {{-- Navigation Buttons --}}
                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-border">
                            @if($currentStep > 1)
                                <button
                                    type="button"
                                    wire:click="previousStep"
                                    class="text-muted-foreground hover:text-foreground transition-colors font-medium"
                                >
                                    Zurück
                                </button>
                            @else
                                <div></div>
                            @endif

                            @if($currentStep < $totalSteps)
                                <button
                                    type="button"
                                    wire:click="nextStep"
                                    @if($currentStep === 1 && empty($selectedProjectTypes)) disabled @endif
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-foreground text-background hover:bg-foreground/90 transition-all rounded-lg font-medium disabled:opacity-40 disabled:cursor-not-allowed"
                                >
                                    Weiter
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m9 18 6-6-6-6"/>
                                    </svg>
                                </button>
                            @else
                                <button
                                    type="button"
                                    wire:click="submit"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-white hover:bg-accent/90 transition-all rounded-lg font-medium disabled:opacity-50"
                                >
                                    <span wire:loading.remove wire:target="submit">Anfrage absenden</span>
                                    <span wire:loading wire:target="submit">Wird gesendet...</span>
                                    <svg wire:loading.remove wire:target="submit" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

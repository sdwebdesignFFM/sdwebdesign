<div>
    {{-- Modal Backdrop --}}
    <div
        x-data="{ show: @entangle('isOpen') }"
        x-show="show"
        x-effect="if (show) { $nextTick(() => { setTimeout(() => { const firstFocusable = document.getElementById('first-project-type'); if (firstFocusable) firstFocusable.focus(); }, 100); }); }"
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
                x-trap.inert.noscroll="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                @keydown.escape.window="$wire.close()"
                role="dialog"
                aria-modal="true"
                aria-labelledby="contact-modal-title"
                class="relative w-full max-w-3xl bg-background shadow-2xl rounded-2xl overflow-hidden"
            >
                @if($isSubmitted)
                    {{-- Success State --}}
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold mb-3">{{ __('contact.success_title') }}</h2>
                        <p class="text-muted-foreground mb-8 max-w-md mx-auto">
                            {{ __('contact.success_message') }}
                        </p>
                        <button
                            type="button"
                            wire:click="close"
                            class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-foreground text-background hover:bg-foreground/90 transition-all rounded-lg"
                        >
                            {{ __('contact.close') }}
                        </button>
                    </div>
                @else
                    {{-- Header --}}
                    <div class="bg-accent text-white p-6 pb-8">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 id="contact-modal-title" class="text-2xl font-semibold mb-1">{{ __('contact.modal_title') }}</h2>
                                <p class="text-white/70 text-sm">{{ __('contact.step_of', ['current' => $currentStep, 'total' => $totalSteps]) }}</p>
                            </div>
                            <button
                                type="button"
                                wire:click="close"
                                class="text-white/70 hover:text-white transition-colors"
                                aria-label="{{ __('accessibility.close_modal') }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Progress Bar --}}
                        <div
                            class="flex gap-2 mt-6"
                            role="progressbar"
                            aria-valuenow="{{ $currentStep }}"
                            aria-valuemin="1"
                            aria-valuemax="{{ $totalSteps }}"
                            aria-label="{{ __('accessibility.step_progress', ['current' => $currentStep, 'total' => $totalSteps]) }}"
                        >
                            @for($i = 1; $i <= $totalSteps; $i++)
                                <div class="flex-1 h-1 rounded-full {{ $i <= $currentStep ? 'bg-background' : 'bg-background/30' }}" aria-hidden="true"></div>
                            @endfor
                        </div>
                    </div>

                    <div class="p-6 md:p-8">
                        {{-- Step 1: Project Types --}}
                        @if($currentStep === 1)
                            <div>
                                <h3 class="text-lg font-semibold mb-1">{{ __('contact.step1_title') }}</h3>
                                <p class="text-muted-foreground text-sm mb-6">{{ __('contact.step1_subtitle') }}</p>

                                <div class="grid sm:grid-cols-2 gap-3">
                                    @foreach($this->projectTypes as $key => $type)
                                        @php
                                            $isSelected = in_array($key, $selectedProjectTypes);
                                            $isFirst = $loop->first;
                                        @endphp
                                        <button
                                            type="button"
                                            @if($isFirst) id="first-project-type" @endif
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
                                    <h3 class="text-lg font-semibold mb-4">{{ __('contact.step2_title') }}</h3>

                                    <div class="mb-6">
                                        <p class="text-sm font-medium mb-3">{{ __('contact.planned_budget') }}</p>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($this->budgets as $key => $label)
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
                                        <p class="text-sm font-medium mb-3">{{ __('contact.desired_timeline') }}</p>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($this->timelines as $key => $label)
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
                                <h3 class="text-lg font-semibold">{{ __('contact.step3_title') }}</h3>

                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="contact-name" class="block text-sm font-medium mb-2">
                                            {{ __('contact.name') }}
                                            <span class="text-red-500" aria-hidden="true">*</span>
                                            <span class="sr-only">{{ __('accessibility.required_field') }}</span>
                                        </label>
                                        <input
                                            type="text"
                                            id="contact-name"
                                            wire:model="name"
                                            aria-required="true"
                                            aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                                            @if($errors->has('name')) aria-describedby="name-error" @endif
                                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20 transition-colors"
                                        >
                                        @error('name')
                                            <p id="name-error" class="mt-1 text-sm text-red-500" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="contact-company" class="block text-sm font-medium mb-2">{{ __('contact.company') }}</label>
                                        <input
                                            type="text"
                                            id="contact-company"
                                            wire:model="company"
                                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20 transition-colors"
                                        >
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="contact-email" class="block text-sm font-medium mb-2">
                                            {{ __('contact.email') }}
                                            <span class="text-red-500" aria-hidden="true">*</span>
                                            <span class="sr-only">{{ __('accessibility.required_field') }}</span>
                                        </label>
                                        <input
                                            type="email"
                                            id="contact-email"
                                            wire:model="email"
                                            aria-required="true"
                                            aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                            @if($errors->has('email')) aria-describedby="email-error" @endif
                                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20 transition-colors"
                                        >
                                        @error('email')
                                            <p id="email-error" class="mt-1 text-sm text-red-500" role="alert">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="contact-phone" class="block text-sm font-medium mb-2">{{ __('contact.phone') }}</label>
                                        <input
                                            type="tel"
                                            id="contact-phone"
                                            wire:model="phone"
                                            class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20 transition-colors"
                                        >
                                    </div>
                                </div>

                                <div>
                                    <label for="contact-description" class="block text-sm font-medium mb-2">{{ __('contact.project_description') }}</label>
                                    <textarea
                                        id="contact-description"
                                        wire:model="projectDescription"
                                        rows="4"
                                        placeholder="{{ __('contact.project_description_placeholder') }}"
                                        class="w-full px-4 py-3 border-2 border-border rounded-xl focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20 transition-colors resize-none"
                                    ></textarea>
                                </div>

                                {{-- Callback Preferences --}}
                                <div class="pt-4 border-t border-border">
                                    <p class="text-sm font-medium mb-4">{{ __('contact.callback_time') }} <span class="text-muted-foreground font-normal">{{ __('contact.callback_optional') }}</span></p>

                                    {{-- Weekdays --}}
                                    <div class="mb-4">
                                        <p class="text-xs text-muted-foreground mb-2">{{ __('contact.preferred_weekdays') }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($this->callbackDays as $key => $label)
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
                                        <p class="text-xs text-muted-foreground mb-2">{{ __('contact.preferred_time_slot') }}</p>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($this->callbackTimes as $key => $label)
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
                                    <p class="font-semibold mb-2">{{ __('contact.summary') }}</p>
                                    <div class="text-sm text-muted-foreground space-y-1">
                                        <p><span class="text-foreground">{{ __('contact.summary_project_types') }}:</span> {{ implode(', ', $this->getSelectedProjectTypesLabels()) }}</p>
                                        @if($budget)
                                            <p><span class="text-foreground">{{ __('contact.summary_budget') }}:</span> {{ $this->budgets[$budget] }}</p>
                                        @endif
                                        @if($timeline)
                                            <p><span class="text-foreground">{{ __('contact.summary_timeline') }}:</span> {{ $this->timelines[$timeline] }}</p>
                                        @endif
                                        @if(count($selectedCallbackDays) > 0 || $callbackTime)
                                            <p>
                                                <span class="text-foreground">{{ __('contact.summary_callback') }}:</span>
                                                @if(count($selectedCallbackDays) > 0)
                                                    {{ implode(', ', $this->getSelectedCallbackDaysLabels()) }}
                                                @endif
                                                @if(count($selectedCallbackDays) > 0 && $callbackTime), @endif
                                                @if($callbackTime)
                                                    {{ $this->callbackTimes[$callbackTime] }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-xs text-muted-foreground">
                                    {{ __('contact.privacy_notice') }}
                                    <a href="{{ localized_route('privacy') }}" target="_blank" class="underline hover:text-foreground">{{ __('contact.privacy_policy') }}</a>@if(__('contact.privacy_suffix')) {{ __('contact.privacy_suffix') }}@endif.
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
                                    {{ __('contact.back') }}
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
                                    {{ __('contact.next') }}
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
                                    <span wire:loading.remove wire:target="submit">{{ __('contact.submit_request') }}</span>
                                    <span wire:loading wire:target="submit">{{ __('contact.sending') }}</span>
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

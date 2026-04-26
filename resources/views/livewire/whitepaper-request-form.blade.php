<div class="border border-border bg-background p-8 lg:p-10">
    @if($submitted)
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 border-2 border-foreground mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3 class="text-[1.25rem] mb-3">Whitepaper ist unterwegs</h3>
            <p class="text-[0.9375rem] text-muted-foreground leading-relaxed max-w-[480px] mx-auto">
                Wir haben das PDF an <strong>{{ $email }}</strong> verschickt. Falls es nicht in den nächsten Minuten ankommt, prüfen Sie bitte den Spam-Ordner.
            </p>
            <a
                href="/loesungen/plattformen/plattform-discovery"
                class="inline-flex items-center gap-2 mt-8 px-6 py-3 bg-foreground text-background hover:bg-foreground/90 transition-all text-[0.9375rem]"
            >
                Discovery-Workshop ansehen
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    @else
        <h3 class="text-[1.25rem] mb-2">Whitepaper kostenlos anfordern</h3>
        <p class="text-[0.9375rem] text-muted-foreground mb-6">
            E-Mail eintragen — wir senden Ihnen das PDF direkt zu. Kein Newsletter, keine automatische Folge-Werbung.
        </p>

        <form wire:submit="submit" class="space-y-4">
            <div>
                <label for="wp-email" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">
                    E-Mail-Adresse <span class="text-accent">*</span>
                </label>
                <input
                    type="email"
                    id="wp-email"
                    wire:model="email"
                    required
                    autocomplete="email"
                    class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground"
                    placeholder="ihre@firma.de"
                />
                @error('email') <p class="text-[0.8125rem] text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="wp-name" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">
                        Name (optional)
                    </label>
                    <input
                        type="text"
                        id="wp-name"
                        wire:model="name"
                        autocomplete="name"
                        class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground"
                    />
                    @error('name') <p class="text-[0.8125rem] text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="wp-company" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">
                        Unternehmen (optional)
                    </label>
                    <input
                        type="text"
                        id="wp-company"
                        wire:model="company"
                        autocomplete="organization"
                        class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground"
                    />
                    @error('company') <p class="text-[0.8125rem] text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="wp-role" class="block text-[0.75rem] uppercase tracking-wider text-muted-foreground mb-2">
                    Rolle (optional)
                </label>
                <input
                    type="text"
                    id="wp-role"
                    wire:model="role"
                    autocomplete="organization-title"
                    placeholder="z. B. Geschäftsführung, IT-Leitung, Operations"
                    class="w-full px-4 py-3 border border-border bg-background text-[0.9375rem] focus:outline-none focus:border-foreground"
                />
                @error('role') <p class="text-[0.8125rem] text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-2 space-y-3">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        wire:model="consent"
                        required
                        class="mt-1 shrink-0"
                    />
                    <span class="text-[0.875rem] text-muted-foreground leading-relaxed">
                        Ich bin damit einverstanden, dass meine Angaben zur Auslieferung des Whitepapers gespeichert werden. Hinweise zum Datenschutz: <a href="/datenschutz" class="underline hover:text-foreground">Datenschutzerklärung</a>.
                    </span>
                </label>
                @error('consent') <p class="text-[0.8125rem] text-red-600">{{ $message }}</p> @enderror

                <label class="flex items-start gap-3 cursor-pointer">
                    <input
                        type="checkbox"
                        wire:model="newsletter_opt_in"
                        class="mt-1 shrink-0"
                    />
                    <span class="text-[0.875rem] text-muted-foreground leading-relaxed">
                        Optional: Ich möchte gelegentlich (max. 1× im Quartal) Updates zu neuen Whitepapers und Plattform-Themen erhalten. Jederzeit abbestellbar.
                    </span>
                </label>
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="submit"
                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-foreground text-background hover:bg-foreground/90 disabled:opacity-50 transition-all text-[0.9375rem] mt-2"
            >
                <span wire:loading.remove wire:target="submit">Whitepaper als PDF anfordern</span>
                <span wire:loading wire:target="submit">Wird versendet …</span>
                <svg wire:loading.remove wire:target="submit" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </form>
    @endif
</div>

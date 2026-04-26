<x-layouts.frontend>
    <section class="relative pt-32 pb-12 lg:pt-40 lg:pb-16 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] grid-background"></div>
        <div class="relative max-w-[1400px] mx-auto px-6">
            <div class="max-w-[900px]">
                <p class="text-[0.8125rem] uppercase tracking-wider text-muted-foreground mb-6">
                    Whitepaper · Mittelstand · Plattform-Strategie
                </p>
                <h1 class="text-[2rem] md:text-[2.75rem] lg:text-[3.25rem] leading-tight mb-6">
                    Eigene Plattform oder Standard-Software?
                </h1>
                <p class="text-[1.125rem] md:text-[1.25rem] text-muted-foreground leading-relaxed max-w-[800px]">
                    Ein Entscheidungsleitfaden für Mittelständler, die ihre operativen Workflows digitalisieren wollen — bevor das nächste Tool dazukommt. Strukturiert, neutral, ohne Sales-Pitch.
                </p>
            </div>
        </div>
    </section>

    <section class="py-12 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="grid lg:grid-cols-5 gap-12 max-w-[1200px]">
                <div class="lg:col-span-3">
                    <h2 class="text-[1.375rem] mb-6">Was im Whitepaper steht</h2>
                    <ol class="space-y-3 list-decimal list-outside ml-5 text-[0.9375rem] text-muted-foreground leading-relaxed">
                        <li>Warum die Frage zu spät gestellt wird</li>
                        <li>Drei Schichten der Software-Entscheidung</li>
                        <li>Standard-Software: wann sie reicht</li>
                        <li>DIY- &amp; AI-Builder: wann sie tragen — und wann nicht</li>
                        <li>Eigene Plattform: wann sie sich rechnet</li>
                        <li>Entscheidungs-Framework in vier Fragen</li>
                        <li>Pilot statt Komplettlösung</li>
                        <li>Risiken und Mitigation</li>
                        <li>Roadmap-Schablone für die ersten 12 Monate</li>
                        <li>Über sdwebdesign &amp; den Discovery-Workshop</li>
                    </ol>

                    <div class="mt-12 p-6 border border-border bg-muted/20">
                        <h3 class="text-[1rem] font-medium mb-3">Für wen das Whitepaper geschrieben ist</h3>
                        <ul class="space-y-2 text-[0.9375rem] text-muted-foreground">
                            <li>• Geschäftsführung und IT-Verantwortliche im Mittelstand (5–500 Mitarbeiter)</li>
                            <li>• Operations-Leitung, die Standard-Software ausreizt und nach Alternativen sucht</li>
                            <li>• Entscheider, die zwischen Eigenentwicklung, DIY-Tools und Standard-Suite stehen</li>
                            <li>• Wer schon Angebote von Agenturen erhalten hat und eine fundierte zweite Meinung sucht</li>
                        </ul>
                    </div>

                    <div class="mt-8 text-[0.875rem] text-muted-foreground">
                        Format: PDF · ca. 12–14 Seiten · Erstausgabe v1.0 · {{ date('Y') }}
                    </div>
                </div>

                <div class="lg:col-span-2">
                    @livewire('whitepaper-request-form', [
                        'whitepaperSlug' => 'platform-vs-standard',
                        'whitepaperTitle' => 'Eigene Plattform oder Standard-Software?',
                        'pdfView' => 'pdfs.whitepaper.platform-vs-standard',
                        'pdfFilename' => 'sdwebdesign-whitepaper-eigene-plattform-oder-standard-software.pdf',
                    ])
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 border-t border-border">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="max-w-[800px]">
                <h2 class="text-[1.375rem] mb-4">Warum dieses Whitepaper?</h2>
                <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-4">
                    Die meisten Plattform-Projekte im Mittelstand scheitern nicht an der Technik, sondern an unklaren Anforderungen, die zu spät auffallen. Bevor die ersten Angebote eingeholt werden, muss eine Entscheidung fallen: Standard-Software ausreizen, DIY-/AI-Builder einsetzen oder eine eigene Plattform bauen.
                </p>
                <p class="text-[0.9375rem] text-muted-foreground leading-relaxed mb-4">
                    Dieses Whitepaper ist als <strong>Entscheidungs-Leitfaden</strong> geschrieben, nicht als Sales-Pitch. Es soll Sie in die Lage versetzen, die Frage selbst zu beantworten — auch dann, wenn Sie am Ende nicht mit uns arbeiten.
                </p>
                <p class="text-[0.9375rem] text-muted-foreground leading-relaxed">
                    Wenn Sie nach dem Lesen konkret weiterdenken möchten, ist der Discovery-Workshop der natürliche nächste Schritt. Aber ohne Druck — Sie behalten das Whitepaper unabhängig von einem Folgeprojekt.
                </p>
            </div>
        </div>
    </section>
</x-layouts.frontend>

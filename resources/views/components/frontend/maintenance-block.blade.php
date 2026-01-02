@props([
    'variant' => 'default', // default, compact, minimal
    'showModels' => false,
])

@php
    $maintenancePage = \App\Models\Page::findByType(\App\Models\Page::TYPE_MAINTENANCE);
    $locale = app()->getLocale();
@endphp

@if($maintenancePage)
<section {{ $attributes->merge(['class' => 'py-16 border-t border-border bg-accent/5']) }}>
    <div class="max-w-[1400px] mx-auto px-6">
        <div class="max-w-[1100px]">
            <div class="motion motion-fade-up">
                {{-- Header --}}
                <div class="flex flex-col md:flex-row md:items-start gap-6 mb-8">
                    <div class="p-4 border-2 border-foreground shrink-0 hidden md:block">
                        <x-frontend.icon name="server-stack" class="w-8 h-8" />
                    </div>
                    <div>
                        <span class="text-[0.8125rem] font-mono text-muted-foreground block mb-2">07</span>
                        <h2 class="text-[1.5rem] mb-3">
                            {{ $locale === 'en' ? 'Hosting & Maintenance' : 'Betrieb, Hosting & Wartung' }}
                        </h2>
                        <p class="text-[0.9375rem] text-muted-foreground leading-relaxed max-w-[700px]">
                            {{ $locale === 'en'
                                ? 'We ensure your digital solution remains permanently stable, secure and up-to-date – so you can focus on your core business.'
                                : 'Wir sorgen dafür, dass Ihre digitale Lösung dauerhaft stabil, sicher und aktuell bleibt – damit Sie sich auf Ihr Kerngeschäft konzentrieren können.'
                            }}
                        </p>
                    </div>
                </div>

                @if($variant !== 'minimal')
                {{-- Key Services --}}
                <div class="grid md:grid-cols-3 gap-4 mb-8">
                    <div class="p-4 border border-border bg-white">
                        <h3 class="text-[0.9375rem] font-medium mb-2">
                            {{ $locale === 'en' ? 'Updates & Security' : 'Updates & Sicherheit' }}
                        </h3>
                        <p class="text-[0.8125rem] text-muted-foreground">
                            {{ $locale === 'en'
                                ? 'Regular updates, security patches and vulnerability scans'
                                : 'Regelmäßige Updates, Sicherheits-Patches und Vulnerability-Scans'
                            }}
                        </p>
                    </div>
                    <div class="p-4 border border-border bg-white">
                        <h3 class="text-[0.9375rem] font-medium mb-2">
                            {{ $locale === 'en' ? 'Monitoring & Backup' : 'Monitoring & Backup' }}
                        </h3>
                        <p class="text-[0.8125rem] text-muted-foreground">
                            {{ $locale === 'en'
                                ? 'Uptime monitoring, daily backups and disaster recovery'
                                : 'Uptime-Monitoring, tägliche Backups und Disaster Recovery'
                            }}
                        </p>
                    </div>
                    <div class="p-4 border border-border bg-white">
                        <h3 class="text-[0.9375rem] font-medium mb-2">
                            {{ $locale === 'en' ? 'Support' : 'Support' }}
                        </h3>
                        <p class="text-[0.8125rem] text-muted-foreground">
                            {{ $locale === 'en'
                                ? 'Technical support, troubleshooting and adjustments'
                                : 'Technischer Support, Fehlerbehebung und Anpassungen'
                            }}
                        </p>
                    </div>
                </div>
                @endif

                @if($showModels && $variant === 'default')
                {{-- Service Models --}}
                <div class="grid md:grid-cols-3 gap-4 mb-8">
                    <div class="p-4 border border-border bg-white">
                        <span class="text-[0.75rem] font-mono text-muted-foreground">Basis</span>
                        <p class="text-[0.8125rem] text-muted-foreground mt-1">
                            {{ $locale === 'en'
                                ? 'For smaller websites'
                                : 'Für kleinere Websites'
                            }}
                        </p>
                    </div>
                    <div class="p-4 border-2 border-foreground bg-white">
                        <span class="text-[0.75rem] font-mono text-foreground font-medium">Pro</span>
                        <p class="text-[0.8125rem] text-muted-foreground mt-1">
                            {{ $locale === 'en'
                                ? 'For business-critical sites'
                                : 'Für geschäftskritische Sites'
                            }}
                        </p>
                    </div>
                    <div class="p-4 border border-border bg-white">
                        <span class="text-[0.75rem] font-mono text-muted-foreground">{{ $locale === 'en' ? 'Enterprise' : 'Systembetrieb' }}</span>
                        <p class="text-[0.8125rem] text-muted-foreground mt-1">
                            {{ $locale === 'en'
                                ? 'For complex applications'
                                : 'Für komplexe Anwendungen'
                            }}
                        </p>
                    </div>
                </div>
                @endif

                {{-- CTA --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ $maintenancePage->getUrl() }}" class="inline-flex items-center gap-2 px-6 py-3 bg-foreground text-background hover:bg-foreground/90 transition-all">
                        {{ $locale === 'en' ? 'Learn more about operations' : 'Mehr zu Betrieb & Wartung' }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ localized_route('contact') }}" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-foreground text-foreground hover:bg-foreground hover:text-background transition-all">
                        {{ $locale === 'en' ? 'Discuss your project' : 'Projekt besprechen' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

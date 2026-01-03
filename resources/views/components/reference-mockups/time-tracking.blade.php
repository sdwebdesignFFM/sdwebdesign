{{-- Stylized Time Tracking App Mockup --}}
@props(['variant' => 'timer'])

<div class="bg-gray-900 rounded-[2.5rem] p-3 shadow-2xl max-w-[320px] mx-auto border border-border dark:border-foreground/20">
    <div class="bg-background rounded-[2rem] overflow-hidden flex flex-col" style="height: 480px;">
        {{-- Phone Notch --}}
        <div class="bg-gray-900 h-6 flex items-center justify-center flex-shrink-0">
            <div class="w-20 h-4 bg-gray-900 rounded-b-xl"></div>
        </div>

        {{-- Status Bar --}}
        <div class="px-5 py-2 flex items-center justify-between text-[10px] text-gray-500 flex-shrink-0">
            <span>09:41</span>
            <div class="flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zm6-4a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zm6-3a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M17.778 8.222c-4.296-4.296-11.26-4.296-15.556 0A1 1 0 01.808 6.808c5.076-5.077 13.308-5.077 18.384 0a1 1 0 01-1.414 1.414zM14.95 11.05a7 7 0 00-9.9 0 1 1 0 01-1.414-1.414 9 9 0 0112.728 0 1 1 0 01-1.414 1.414zM12.12 13.88a3 3 0 00-4.242 0 1 1 0 01-1.415-1.415 5 5 0 017.072 0 1 1 0 01-1.415 1.415zM9 16a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="flex items-center">
                    <div class="w-5 h-2.5 border border-gray-400 rounded-sm">
                        <div class="w-4 h-full bg-green-500 rounded-sm"></div>
                    </div>
                </div>
            </div>
        </div>

        @if($variant === 'timer')
        {{-- Timer View --}}
        <div class="px-5 pt-2 pb-4 flex-1 overflow-y-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Zeiterfassung</h3>
                <div class="w-6 h-6 rounded-full bg-accent/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
            </div>

            {{-- Current Project --}}
            <div class="bg-gray-50 rounded-xl p-3 mb-4">
                <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-1">Aktuelles Projekt</p>
                <p class="text-xs font-medium text-gray-900">Website Redesign</p>
                <p class="text-[10px] text-gray-500">Frontend Entwicklung</p>
            </div>

            {{-- Timer Display --}}
            <div class="text-center py-6">
                <div class="text-4xl font-bold text-gray-900 font-mono tracking-tight">02:34:17</div>
                <p class="text-[10px] text-gray-500 mt-1">Heute geloggt: 5h 42min</p>
            </div>

            {{-- Control Buttons --}}
            <div class="flex items-center justify-center gap-3 mb-4">
                <button class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <rect x="6" y="6" width="12" height="12" rx="1"/>
                    </svg>
                </button>
                <button class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                </button>
            </div>

            {{-- Recent Entries --}}
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Letzte Einträge</p>
                <div class="space-y-2">
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <div>
                            <p class="text-xs font-medium text-gray-900">API Integration</p>
                            <p class="text-[10px] text-gray-500">Heute, 08:15 - 10:45</p>
                        </div>
                        <span class="text-xs font-mono text-accent">2:30h</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <div>
                            <p class="text-xs font-medium text-gray-900">UI Components</p>
                            <p class="text-[10px] text-gray-500">Gestern, 14:00 - 17:30</p>
                        </div>
                        <span class="text-xs font-mono text-accent">3:30h</span>
                    </div>
                </div>
            </div>
        </div>

        @elseif($variant === 'approval')
        {{-- Approval Workflow View --}}
        <div class="px-5 pt-2 pb-4 flex-1 overflow-y-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Freigaben</h3>
                <div class="flex items-center gap-1 bg-orange-100 px-2 py-0.5 rounded-full">
                    <div class="w-1.5 h-1.5 bg-orange-500 rounded-full"></div>
                    <span class="text-[10px] text-orange-700 font-medium">3 offen</span>
                </div>
            </div>

            {{-- Approval List --}}
            <div class="space-y-3">
                <div class="bg-gray-50 rounded-xl p-3 border-l-3 border-orange-500">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-xs font-medium text-gray-900">Max Mustermann</p>
                            <p class="text-[10px] text-gray-500">KW 48 - 42,5 Stunden</p>
                        </div>
                        <span class="text-[9px] bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded">Ausstehend</span>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <button class="flex-1 text-[10px] bg-green-500 text-white py-1.5 rounded-lg font-medium">Freigeben</button>
                        <button class="flex-1 text-[10px] bg-background border border-gray-200 py-1.5 rounded-lg font-medium">Ablehnen</button>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-3 border-l-3 border-green-500">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-xs font-medium text-gray-900">Anna Schmidt</p>
                            <p class="text-[10px] text-gray-500">KW 48 - 38,0 Stunden</p>
                        </div>
                        <span class="text-[9px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded">Freigegeben</span>
                    </div>
                    <p class="text-[10px] text-gray-500">Freigegeben am 02.12.2024</p>
                </div>

                <div class="bg-gray-50 rounded-xl p-3 border-l-3 border-orange-500">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="text-xs font-medium text-gray-900">Tom Weber</p>
                            <p class="text-[10px] text-gray-500">KW 48 - 40,0 Stunden</p>
                        </div>
                        <span class="text-[9px] bg-orange-100 text-orange-700 px-1.5 py-0.5 rounded">Ausstehend</span>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <button class="flex-1 text-[10px] bg-green-500 text-white py-1.5 rounded-lg font-medium">Freigeben</button>
                        <button class="flex-1 text-[10px] bg-background border border-gray-200 py-1.5 rounded-lg font-medium">Ablehnen</button>
                    </div>
                </div>
            </div>
        </div>

        @elseif($variant === 'projects')
        {{-- Project Selection View --}}
        <div class="px-5 pt-2 pb-4 flex-1 overflow-y-auto">
            {{-- Header with Search --}}
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Projekte</h3>
                <div class="relative">
                    <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" placeholder="Projekt suchen..." class="w-full text-xs bg-gray-50 border-0 rounded-lg pl-8 pr-3 py-2 placeholder-gray-400">
                </div>
            </div>

            {{-- Project List --}}
            <div class="space-y-2">
                <div class="flex items-center gap-3 p-3 bg-accent/5 border border-accent/20 rounded-xl">
                    <div class="w-8 h-8 rounded-lg bg-accent/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-900">Website Redesign</p>
                        <p class="text-[10px] text-gray-500">12 Aufgaben aktiv</p>
                    </div>
                    <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-900">Mobile App v2</p>
                        <p class="text-[10px] text-gray-500">8 Aufgaben aktiv</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-900">API Migration</p>
                        <p class="text-[10px] text-gray-500">5 Aufgaben aktiv</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-900">Reporting Dashboard</p>
                        <p class="text-[10px] text-gray-500">3 Aufgaben aktiv</p>
                    </div>
                </div>
            </div>
        </div>

        @elseif($variant === 'audit')
        {{-- Audit Log View --}}
        <div class="px-5 pt-2 pb-4 flex-1 overflow-y-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Änderungsprotokoll</h3>
                <button class="text-[10px] text-accent font-medium">Filter</button>
            </div>

            {{-- Date Header --}}
            <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Heute</p>

            {{-- Audit Entries --}}
            <div class="space-y-3">
                <div class="relative pl-4 border-l-2 border-blue-200">
                    <div class="absolute -left-1.5 top-0 w-3 h-3 bg-blue-500 rounded-full"></div>
                    <div class="pb-3">
                        <p class="text-[10px] text-gray-500">14:23</p>
                        <p class="text-xs font-medium text-gray-900">Korrektur: +0:30h</p>
                        <p class="text-[10px] text-gray-500">API Integration - Max M.</p>
                        <p class="text-[10px] text-gray-400 italic mt-1">"Pause nicht erfasst"</p>
                    </div>
                </div>

                <div class="relative pl-4 border-l-2 border-green-200">
                    <div class="absolute -left-1.5 top-0 w-3 h-3 bg-green-500 rounded-full"></div>
                    <div class="pb-3">
                        <p class="text-[10px] text-gray-500">11:45</p>
                        <p class="text-xs font-medium text-gray-900">Freigabe erteilt</p>
                        <p class="text-[10px] text-gray-500">KW 47 - Anna S.</p>
                    </div>
                </div>

                <div class="relative pl-4 border-l-2 border-orange-200">
                    <div class="absolute -left-1.5 top-0 w-3 h-3 bg-orange-500 rounded-full"></div>
                    <div class="pb-3">
                        <p class="text-[10px] text-gray-500">09:12</p>
                        <p class="text-xs font-medium text-gray-900">Projekt gewechselt</p>
                        <p class="text-[10px] text-gray-500">Mobile App → Website - Tom W.</p>
                    </div>
                </div>

                {{-- Yesterday --}}
                <p class="text-[10px] text-gray-500 uppercase tracking-wide mt-4 mb-2">Gestern</p>

                <div class="relative pl-4 border-l-2 border-red-200">
                    <div class="absolute -left-1.5 top-0 w-3 h-3 bg-red-500 rounded-full"></div>
                    <div class="pb-3">
                        <p class="text-[10px] text-gray-500">17:30</p>
                        <p class="text-xs font-medium text-gray-900">Eintrag gelöscht</p>
                        <p class="text-[10px] text-gray-500">Doppelbuchung entfernt - Admin</p>
                    </div>
                </div>

                <div class="relative pl-4 border-l-2 border-blue-200">
                    <div class="absolute -left-1.5 top-0 w-3 h-3 bg-blue-500 rounded-full"></div>
                    <div class="pb-3">
                        <p class="text-[10px] text-gray-500">15:20</p>
                        <p class="text-xs font-medium text-gray-900">Korrektur: -1:00h</p>
                        <p class="text-[10px] text-gray-500">UI Components - Max M.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Bottom Navigation --}}
        <div class="bg-background border-t border-gray-100 px-6 py-3 flex-shrink-0">
            <div class="flex items-center justify-around">
                <button class="{{ $variant === 'timer' ? 'text-accent' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[9px] block mt-0.5">Timer</span>
                </button>
                <button class="{{ $variant === 'projects' ? 'text-accent' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="text-[9px] block mt-0.5">Projekte</span>
                </button>
                <button class="{{ $variant === 'approval' ? 'text-accent' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[9px] block mt-0.5">Freigaben</span>
                </button>
                <button class="{{ $variant === 'audit' ? 'text-accent' : 'text-gray-400' }}">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="text-[9px] block mt-0.5">Protokoll</span>
                </button>
            </div>
        </div>
    </div>
</div>

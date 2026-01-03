{{-- Stylized iOS CRM App Mockup for Cosmeticians --}}
@props(['variant' => 'dashboard'])

<div class="bg-gray-900 rounded-[2.5rem] p-3 shadow-2xl max-w-[320px] mx-auto border border-border dark:border-foreground/20">
    <div class="bg-background rounded-[2rem] overflow-hidden flex flex-col" style="height: 520px;">
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

        @if($variant === 'dashboard')
        {{-- Dashboard View - Customer Overview --}}
        <div class="px-4 pt-2 pb-4 flex-1 overflow-y-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-900">Meine Kunden</h3>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-pink-100 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="w-6 h-6 rounded-full bg-pink-500 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Scoring Overview --}}
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-2 text-center">
                    <div class="w-5 h-5 mx-auto mb-1 rounded-full bg-amber-400 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <p class="text-lg font-bold text-amber-700">12</p>
                    <p class="text-[8px] text-amber-600 uppercase">VIP</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-2 text-center">
                    <div class="w-5 h-5 mx-auto mb-1 rounded-full bg-purple-400 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-lg font-bold text-purple-700">28</p>
                    <p class="text-[8px] text-purple-600 uppercase">Premium</p>
                </div>
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-2 text-center">
                    <div class="w-5 h-5 mx-auto mb-1 rounded-full bg-gray-400 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-lg font-bold text-gray-700">156</p>
                    <p class="text-[8px] text-gray-600 uppercase">Standard</p>
                </div>
            </div>

            {{-- Customer List --}}
            <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Letzte Kunden</p>
            <div class="space-y-2">
                <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-xl">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white text-xs font-medium">SM</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <p class="text-xs font-medium text-gray-900 truncate">Sabine Müller</p>
                            <span class="flex-shrink-0 px-1.5 py-0.5 text-[8px] font-medium bg-amber-100 text-amber-700 rounded-full">VIP</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Letzter Termin: 15.12.</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-xl">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-xs font-medium">LK</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <p class="text-xs font-medium text-gray-900 truncate">Lisa Klein</p>
                            <span class="flex-shrink-0 px-1.5 py-0.5 text-[8px] font-medium bg-purple-100 text-purple-700 rounded-full">Premium</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Letzter Termin: 14.12.</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <div class="flex items-center gap-3 p-2 bg-gray-50 rounded-xl">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-xs font-medium">MH</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <p class="text-xs font-medium text-gray-900 truncate">Maria Hoffmann</p>
                        </div>
                        <p class="text-[10px] text-gray-500">Letzter Termin: 12.12.</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </div>

        @elseif($variant === 'calendar')
        {{-- Calendar View - Appointments --}}
        <div class="px-4 pt-2 pb-4 flex-1 overflow-y-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-900">Dezember 2024</h3>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                <div class="w-6 h-6 rounded-full bg-pink-500 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
            </div>

            {{-- Mini Calendar --}}
            <div class="bg-gray-50 rounded-xl p-2 mb-3">
                <div class="grid grid-cols-7 gap-1 text-center text-[8px] text-gray-500 mb-1">
                    <span>Mo</span><span>Di</span><span>Mi</span><span>Do</span><span>Fr</span><span>Sa</span><span>So</span>
                </div>
                <div class="grid grid-cols-7 gap-1 text-center text-[10px]">
                    <span class="text-gray-300">25</span><span class="text-gray-300">26</span><span class="text-gray-300">27</span><span class="text-gray-300">28</span><span class="text-gray-300">29</span><span class="text-gray-300">30</span><span>1</span>
                    <span>2</span><span>3</span><span>4</span><span>5</span><span>6</span><span class="text-gray-300">7</span><span class="text-gray-300">8</span>
                    <span>9</span><span>10</span><span>11</span><span>12</span><span>13</span><span class="text-gray-300">14</span><span class="text-gray-300">15</span>
                    <span class="w-5 h-5 mx-auto rounded-full bg-pink-500 text-white flex items-center justify-center">16</span><span>17</span><span class="relative">18<span class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-pink-400 rounded-full"></span></span><span class="relative">19<span class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 bg-pink-400 rounded-full"></span></span><span>20</span><span class="text-gray-300">21</span><span class="text-gray-300">22</span>
                    <span>23</span><span>24</span><span>25</span><span>26</span><span>27</span><span class="text-gray-300">28</span><span class="text-gray-300">29</span>
                </div>
            </div>

            {{-- Today's Appointments --}}
            <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Heute, 16. Dezember</p>
            <div class="space-y-2">
                <div class="flex gap-2">
                    <div class="w-1 rounded-full bg-pink-400 flex-shrink-0"></div>
                    <div class="flex-1 bg-pink-50 rounded-lg p-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium text-gray-900">09:00 - 10:30</p>
                            <span class="px-1.5 py-0.5 text-[8px] font-medium bg-amber-100 text-amber-700 rounded-full">VIP</span>
                        </div>
                        <p class="text-[10px] text-gray-700">Sabine Müller</p>
                        <p class="text-[10px] text-gray-500">Anti-Aging Behandlung</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1 rounded-full bg-purple-400 flex-shrink-0"></div>
                    <div class="flex-1 bg-purple-50 rounded-lg p-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium text-gray-900">11:00 - 12:00</p>
                            <span class="px-1.5 py-0.5 text-[8px] font-medium bg-purple-100 text-purple-700 rounded-full">Premium</span>
                        </div>
                        <p class="text-[10px] text-gray-700">Lisa Klein</p>
                        <p class="text-[10px] text-gray-500">Maniküre & Pediküre</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1 rounded-full bg-gray-300 flex-shrink-0"></div>
                    <div class="flex-1 bg-gray-50 rounded-lg p-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium text-gray-900">14:00 - 15:00</p>
                        </div>
                        <p class="text-[10px] text-gray-700">Neue Kundin</p>
                        <p class="text-[10px] text-gray-500">Erstberatung + Gesichtsbehandlung</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="w-1 rounded-full bg-emerald-400 flex-shrink-0"></div>
                    <div class="flex-1 bg-emerald-50 rounded-lg p-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-medium text-gray-900">16:00 - 17:30</p>
                            <span class="text-[8px] text-emerald-600">↻ Serientermin</span>
                        </div>
                        <p class="text-[10px] text-gray-700">Maria Hoffmann</p>
                        <p class="text-[10px] text-gray-500">Wimpernverlängerung Auffüllen</p>
                    </div>
                </div>
            </div>
        </div>

        @elseif($variant === 'customer')
        {{-- Customer Detail View --}}
        <div class="px-4 pt-2 pb-4 flex-1 overflow-y-auto">
            {{-- Back + Title --}}
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <h3 class="text-sm font-semibold text-gray-900">Kundenprofil</h3>
            </div>

            {{-- Profile Header --}}
            <div class="text-center mb-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white text-xl font-medium mb-2">SM</div>
                <h4 class="text-base font-semibold text-gray-900">Sabine Müller</h4>
                <div class="flex items-center justify-center gap-1 mt-1">
                    <span class="px-2 py-0.5 text-[9px] font-medium bg-amber-100 text-amber-700 rounded-full flex items-center gap-1">
                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        VIP-Kundin
                    </span>
                    <span class="text-[9px] text-gray-500">Score: 92</span>
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="bg-gray-50 rounded-lg p-2 text-center">
                    <p class="text-base font-bold text-gray-900">47</p>
                    <p class="text-[8px] text-gray-500 uppercase">Termine</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-2 text-center">
                    <p class="text-base font-bold text-gray-900">€2.840</p>
                    <p class="text-[8px] text-gray-500 uppercase">Umsatz</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-2 text-center">
                    <p class="text-base font-bold text-gray-900">3 J.</p>
                    <p class="text-[8px] text-gray-500 uppercase">Kundin seit</p>
                </div>
            </div>

            {{-- Treatment History --}}
            <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Behandlungshistorie</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-900">Anti-Aging Behandlung</p>
                        <p class="text-[10px] text-gray-500">15.12.2024 • €89</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-900">Maniküre Deluxe</p>
                        <p class="text-[10px] text-gray-500">01.12.2024 • €45</p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-2 gap-2">
                <button class="flex items-center justify-center gap-1.5 px-3 py-2 bg-pink-500 text-white rounded-xl text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Termin
                </button>
                <button class="flex items-center justify-center gap-1.5 px-3 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Shop
                </button>
            </div>
        </div>

        @elseif($variant === 'treatment')
        {{-- Treatment Documentation View --}}
        <div class="px-4 pt-2 pb-4 flex-1 overflow-y-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-900">Behandlung</h3>
                </div>
                <span class="px-2 py-0.5 text-[9px] font-medium bg-green-100 text-green-700 rounded-full">Aktiv</span>
            </div>

            {{-- Treatment Info --}}
            <div class="bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl p-3 mb-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white text-xs font-medium">SM</div>
                    <div>
                        <p class="text-xs font-medium text-gray-900">Sabine Müller</p>
                        <p class="text-[10px] text-gray-500">Anti-Aging Gesichtsbehandlung</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-[10px] text-gray-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        16.12.2024
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        09:00 - 10:30
                    </span>
                </div>
            </div>

            {{-- Documentation Steps --}}
            <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Dokumentation</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-700">Anamnese ausgefüllt</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-700">DSGVO-Einwilligung</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-5 h-5 rounded-full bg-pink-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-[8px] text-white font-bold">3</span>
                    </div>
                    <p class="text-xs text-gray-700 font-medium">Behandlungsnotizen</p>
                </div>
            </div>

            {{-- Notes Input --}}
            <div class="bg-gray-50 rounded-xl p-3 mb-3">
                <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Notizen hinzufügen</p>
                <div class="bg-background border border-gray-200 rounded-lg p-2 min-h-[60px]">
                    <p class="text-[10px] text-gray-400">Hautbild: leichte Trockenheit an Wangen, Verbesserung seit letztem Termin...</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-2 gap-2">
                <button class="flex items-center justify-center gap-1.5 px-3 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Foto
                </button>
                <button class="flex items-center justify-center gap-1.5 px-3 py-2 bg-pink-500 text-white rounded-xl text-xs font-medium">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Speichern
                </button>
            </div>
        </div>

        @elseif($variant === 'shop')
        {{-- Shop Integration View --}}
        <div class="px-4 pt-2 pb-4 flex-1 overflow-y-auto">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-900">Shop-Bestellung</h3>
                </div>
                <div class="relative">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-pink-500 text-white text-[8px] rounded-full flex items-center justify-center">3</span>
                </div>
            </div>

            {{-- Customer Order Context --}}
            <div class="bg-pink-50 rounded-xl p-3 mb-4">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white text-[10px] font-medium">SM</div>
                    <p class="text-xs font-medium text-gray-900">Bestellung für Sabine Müller</p>
                </div>
                <p class="text-[10px] text-gray-500">Basierend auf Behandlungshistorie</p>
            </div>

            {{-- Product Suggestions --}}
            <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Empfohlene Produkte</p>
            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-xl">
                    <div class="w-12 h-12 bg-gradient-to-br from-rose-100 to-pink-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-900">Anti-Aging Serum</p>
                        <p class="text-[10px] text-gray-500">30ml • Vorrat: 12</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold text-gray-900">€49,90</p>
                        <div class="flex items-center gap-1 mt-1">
                            <button class="w-5 h-5 bg-gray-200 rounded text-gray-600 text-xs">-</button>
                            <span class="text-xs w-4 text-center">1</span>
                            <button class="w-5 h-5 bg-pink-500 rounded text-white text-xs">+</button>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-xl">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-medium text-gray-900">Nachtcreme Sensitiv</p>
                        <p class="text-[10px] text-gray-500">50ml • Vorrat: 8</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold text-gray-900">€34,90</p>
                        <div class="flex items-center gap-1 mt-1">
                            <button class="w-5 h-5 bg-gray-200 rounded text-gray-600 text-xs">-</button>
                            <span class="text-xs w-4 text-center">1</span>
                            <button class="w-5 h-5 bg-pink-500 rounded text-white text-xs">+</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Barcode Scanner --}}
            <button class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-gray-100 rounded-xl text-xs font-medium text-gray-700 mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Barcode scannen
            </button>

            {{-- Order Summary --}}
            <div class="bg-gray-900 text-white rounded-xl p-3">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] text-gray-400">3 Produkte</span>
                    <span class="text-sm font-bold">€134,70</span>
                </div>
                <button class="w-full py-2 bg-pink-500 rounded-lg text-xs font-medium">
                    Zur Kasse für Kundin
                </button>
            </div>
        </div>

        @endif

        {{-- Bottom Navigation --}}
        <div class="px-4 py-2 border-t border-gray-100 flex items-center justify-around flex-shrink-0 bg-background">
            <button class="flex flex-col items-center gap-0.5 {{ $variant === 'dashboard' ? 'text-pink-500' : 'text-gray-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="text-[8px]">Kunden</span>
            </button>
            <button class="flex flex-col items-center gap-0.5 {{ $variant === 'calendar' ? 'text-pink-500' : 'text-gray-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-[8px]">Kalender</span>
            </button>
            <button class="flex flex-col items-center gap-0.5 {{ in_array($variant, ['treatment', 'customer']) ? 'text-pink-500' : 'text-gray-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-[8px]">Doku</span>
            </button>
            <button class="flex flex-col items-center gap-0.5 {{ $variant === 'shop' ? 'text-pink-500' : 'text-gray-400' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span class="text-[8px]">Shop</span>
            </button>
        </div>
    </div>
</div>

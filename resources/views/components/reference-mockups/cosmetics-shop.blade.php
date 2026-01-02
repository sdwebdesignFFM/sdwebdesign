{{-- Stylized B2B Cosmetics Shop Mockup (Browser Style) --}}
@props(['variant' => 'catalog'])

<div class="bg-gray-100 rounded-lg shadow-2xl max-w-[480px] mx-auto overflow-hidden">
    {{-- Browser Chrome --}}
    <div class="bg-gray-200 px-3 py-2 flex items-center gap-2">
        <div class="flex items-center gap-1.5">
            <div class="w-3 h-3 rounded-full bg-red-400"></div>
            <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
            <div class="w-3 h-3 rounded-full bg-green-400"></div>
        </div>
        <div class="flex-1 flex items-center gap-2 ml-2">
            <div class="flex items-center gap-1 text-gray-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
            <div class="flex-1 bg-white rounded-md px-3 py-1 flex items-center gap-2">
                <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-[10px] text-gray-600 truncate">kosmetikerin.org/shop</span>
            </div>
        </div>
    </div>

    {{-- Website Content --}}
    <div class="bg-white" style="height: 380px; overflow-y: auto;">
        {{-- Shop Header --}}
        <div class="bg-gradient-to-r from-pink-600 to-rose-500 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                    <span class="text-pink-600 font-bold text-xs">K</span>
                </div>
                <span class="text-white font-semibold text-sm">Kosmetikerin Shop</span>
            </div>
            <div class="flex items-center gap-3 text-white/90">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <div class="relative">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-amber-400 text-[8px] text-gray-900 rounded-full flex items-center justify-center font-bold">3</span>
                </div>
                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-[10px] font-medium">SM</div>
            </div>
        </div>

        @if($variant === 'catalog')
        {{-- B2B Product Catalog --}}
        <div class="px-4 py-3">
            {{-- Category Navigation --}}
            <div class="flex items-center gap-2 mb-3 overflow-x-auto">
                <span class="px-2.5 py-1 bg-pink-100 text-pink-700 rounded-full text-[10px] font-medium whitespace-nowrap">Alle Produkte</span>
                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] whitespace-nowrap">Gesichtspflege</span>
                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] whitespace-nowrap">Körperpflege</span>
                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] whitespace-nowrap">Apparate</span>
            </div>

            {{-- B2B Notice --}}
            <div class="bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <p class="text-[10px] text-amber-800">B2B-Preise nach Login • Mengenrabatte ab 10 Stück</p>
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="aspect-square bg-gradient-to-br from-pink-50 to-rose-50 flex items-center justify-center relative">
                        <svg class="w-12 h-12 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 bg-pink-500 text-white text-[8px] rounded font-medium">NEU</span>
                    </div>
                    <div class="p-2">
                        <p class="text-[9px] text-gray-500 uppercase">Dermalogica</p>
                        <p class="text-[11px] font-medium text-gray-900 line-clamp-2">Anti-Aging Serum Pro</p>
                        <div class="mt-1 flex items-baseline gap-1.5">
                            <span class="text-xs font-bold text-pink-600">€39,90</span>
                            <span class="text-[9px] text-gray-400 line-through">€49,90</span>
                        </div>
                        <p class="text-[8px] text-green-600 mt-0.5">B2B: €32,90</p>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="aspect-square bg-gradient-to-br from-amber-50 to-yellow-50 flex items-center justify-center relative">
                        <svg class="w-12 h-12 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 bg-amber-500 text-white text-[8px] rounded font-medium">-20%</span>
                    </div>
                    <div class="p-2">
                        <p class="text-[9px] text-gray-500 uppercase">Babor</p>
                        <p class="text-[11px] font-medium text-gray-900 line-clamp-2">Nachtcreme Sensitiv</p>
                        <div class="mt-1 flex items-baseline gap-1.5">
                            <span class="text-xs font-bold text-pink-600">€27,92</span>
                            <span class="text-[9px] text-gray-400 line-through">€34,90</span>
                        </div>
                        <p class="text-[8px] text-green-600 mt-0.5">B2B: €22,90</p>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="aspect-square bg-gradient-to-br from-purple-50 to-indigo-50 flex items-center justify-center">
                        <svg class="w-12 h-12 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                    </div>
                    <div class="p-2">
                        <p class="text-[9px] text-gray-500 uppercase">CND</p>
                        <p class="text-[11px] font-medium text-gray-900 line-clamp-2">Shellac Starter Set</p>
                        <div class="mt-1">
                            <span class="text-xs font-bold text-pink-600">€189,00</span>
                        </div>
                        <p class="text-[8px] text-green-600 mt-0.5">B2B: €149,00</p>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="aspect-square bg-gradient-to-br from-emerald-50 to-teal-50 flex items-center justify-center">
                        <svg class="w-12 h-12 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <div class="p-2">
                        <p class="text-[9px] text-gray-500 uppercase">Eigenmarke</p>
                        <p class="text-[11px] font-medium text-gray-900 line-clamp-2">Massage Öl Set 3x</p>
                        <div class="mt-1">
                            <span class="text-xs font-bold text-pink-600">€24,90</span>
                        </div>
                        <p class="text-[8px] text-green-600 mt-0.5">B2B: €18,90</p>
                    </div>
                </div>
            </div>
        </div>

        @elseif($variant === 'checkout')
        {{-- Checkout with Payment Methods --}}
        <div class="px-4 py-3">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Kasse</h2>

            {{-- Progress Steps --}}
            <div class="flex items-center gap-2 mb-4">
                <div class="flex items-center gap-1.5">
                    <div class="w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-gray-600">Warenkorb</span>
                </div>
                <div class="flex-1 h-px bg-gray-200"></div>
                <div class="flex items-center gap-1.5">
                    <div class="w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-[10px] text-gray-600">Adresse</span>
                </div>
                <div class="flex-1 h-px bg-gray-200"></div>
                <div class="flex items-center gap-1.5">
                    <div class="w-5 h-5 rounded-full bg-pink-500 text-white flex items-center justify-center text-[10px] font-bold">3</div>
                    <span class="text-[10px] font-medium text-gray-900">Zahlung</span>
                </div>
            </div>

            {{-- Payment Methods --}}
            <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Zahlungsart wählen</p>
            <div class="space-y-2 mb-4">
                <label class="flex items-center gap-3 p-2.5 border-2 border-pink-500 bg-pink-50 rounded-lg cursor-pointer">
                    <input type="radio" name="payment" checked class="w-4 h-4 text-pink-500">
                    <div class="w-10 h-6 bg-[#003087] rounded flex items-center justify-center">
                        <span class="text-white text-[8px] font-bold italic">PayPal</span>
                    </div>
                    <span class="text-xs text-gray-900">PayPal</span>
                </label>
                <label class="flex items-center gap-3 p-2.5 border border-gray-200 rounded-lg cursor-pointer">
                    <input type="radio" name="payment" class="w-4 h-4 text-pink-500">
                    <div class="w-10 h-6 bg-gradient-to-r from-indigo-500 to-purple-600 rounded flex items-center justify-center">
                        <span class="text-white text-[8px] font-bold">Stripe</span>
                    </div>
                    <span class="text-xs text-gray-700">Kreditkarte</span>
                </label>
                <label class="flex items-center gap-3 p-2.5 border border-gray-200 rounded-lg cursor-pointer">
                    <input type="radio" name="payment" class="w-4 h-4 text-pink-500">
                    <div class="w-10 h-6 bg-gray-700 rounded flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs text-gray-700">Rechnung (B2B)</span>
                    <span class="ml-auto px-1.5 py-0.5 bg-green-100 text-green-700 text-[8px] rounded">Verifiziert</span>
                </label>
                <label class="flex items-center gap-3 p-2.5 border border-gray-200 rounded-lg cursor-pointer">
                    <input type="radio" name="payment" class="w-4 h-4 text-pink-500">
                    <div class="w-10 h-6 bg-orange-500 rounded flex items-center justify-center">
                        <span class="text-white text-[7px] font-bold">SEPA</span>
                    </div>
                    <span class="text-xs text-gray-700">Lastschrift</span>
                </label>
            </div>

            {{-- Order Summary --}}
            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                <div class="flex items-center justify-between text-[11px] text-gray-600 mb-1">
                    <span>Zwischensumme (3 Artikel)</span>
                    <span>€142,70</span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-gray-600 mb-1">
                    <span>B2B-Rabatt</span>
                    <span class="text-green-600">-€21,00</span>
                </div>
                <div class="flex items-center justify-between text-[11px] text-gray-600 mb-2">
                    <span>Versand</span>
                    <span>€4,90</span>
                </div>
                <div class="border-t border-gray-200 pt-2 flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-900">Gesamt (inkl. MwSt.)</span>
                    <span class="text-sm font-bold text-pink-600">€126,60</span>
                </div>
            </div>

            <button class="w-full py-2.5 bg-pink-500 hover:bg-pink-600 text-white rounded-lg text-xs font-medium flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Jetzt bezahlen
            </button>
        </div>

        @elseif($variant === 'orders')
        {{-- Order History with Reorder --}}
        <div class="px-4 py-3">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-900">Meine Bestellungen</h2>
                <select class="text-[10px] border border-gray-200 rounded px-2 py-1">
                    <option>Alle Bestellungen</option>
                    <option>Offene</option>
                    <option>Abgeschlossen</option>
                </select>
            </div>

            {{-- Order Cards --}}
            <div class="space-y-3">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-3 py-2 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-gray-500">Bestellung #1247</p>
                            <p class="text-xs font-medium text-gray-900">15. Dezember 2024</p>
                        </div>
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[9px] rounded-full font-medium">Geliefert</span>
                    </div>
                    <div class="p-3">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-10 h-10 bg-pink-50 rounded flex items-center justify-center">
                                <svg class="w-5 h-5 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-[11px] font-medium text-gray-900">Anti-Aging Serum Pro + 2 weitere</p>
                                <p class="text-[10px] text-gray-500">3 Artikel • €126,60</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="flex-1 py-1.5 bg-pink-500 text-white rounded text-[10px] font-medium flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Erneut bestellen
                            </button>
                            <button class="py-1.5 px-3 border border-gray-200 rounded text-[10px] text-gray-600">
                                Rechnung
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-3 py-2 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-gray-500">Bestellung #1243</p>
                            <p class="text-xs font-medium text-gray-900">8. Dezember 2024</p>
                        </div>
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[9px] rounded-full font-medium">Geliefert</span>
                    </div>
                    <div class="p-3">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-10 h-10 bg-purple-50 rounded flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-[11px] font-medium text-gray-900">Shellac Starter Set</p>
                                <p class="text-[10px] text-gray-500">1 Artikel • €149,00</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="flex-1 py-1.5 bg-pink-500 text-white rounded text-[10px] font-medium flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Erneut bestellen
                            </button>
                            <button class="py-1.5 px-3 border border-gray-200 rounded text-[10px] text-gray-600">
                                Rechnung
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border border-amber-200 bg-amber-50 rounded-lg overflow-hidden">
                    <div class="bg-amber-100 px-3 py-2 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-amber-700">Bestellung #1248</p>
                            <p class="text-xs font-medium text-gray-900">16. Dezember 2024</p>
                        </div>
                        <span class="px-2 py-0.5 bg-amber-200 text-amber-800 text-[9px] rounded-full font-medium">In Bearbeitung</span>
                    </div>
                    <div class="p-3">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-10 h-10 bg-amber-100 rounded flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-[11px] font-medium text-gray-900">Massage Öl Set + 4 weitere</p>
                                <p class="text-[10px] text-gray-500">5 Artikel • €89,50</p>
                            </div>
                        </div>
                        <button class="w-full py-1.5 border border-amber-300 text-amber-700 rounded text-[10px] font-medium">
                            Sendungsverfolgung
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @elseif($variant === 'invoice')
        {{-- Invoice Management --}}
        <div class="px-4 py-3">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold text-gray-900">Rechnungen</h2>
                <button class="text-[10px] text-pink-600 font-medium">Alle exportieren</button>
            </div>

            {{-- Invoice Stats --}}
            <div class="grid grid-cols-3 gap-2 mb-4">
                <div class="bg-green-50 rounded-lg p-2 text-center">
                    <p class="text-lg font-bold text-green-700">12</p>
                    <p class="text-[8px] text-green-600 uppercase">Bezahlt</p>
                </div>
                <div class="bg-amber-50 rounded-lg p-2 text-center">
                    <p class="text-lg font-bold text-amber-700">2</p>
                    <p class="text-[8px] text-amber-600 uppercase">Offen</p>
                </div>
                <div class="bg-red-50 rounded-lg p-2 text-center">
                    <p class="text-lg font-bold text-red-700">1</p>
                    <p class="text-[8px] text-red-600 uppercase">Storniert</p>
                </div>
            </div>

            {{-- Invoice List --}}
            <p class="text-[10px] text-gray-500 uppercase tracking-wide mb-2">Letzte Rechnungen</p>
            <div class="space-y-2">
                <div class="flex items-center gap-3 p-2.5 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-medium text-gray-900">RE-2024-1247</p>
                            <span class="text-[10px] font-semibold text-gray-900">€126,60</span>
                        </div>
                        <p class="text-[10px] text-gray-500">15. Dez 2024 • Bezahlt</p>
                    </div>
                    <button class="p-1.5 hover:bg-gray-200 rounded">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-3 p-2.5 bg-amber-50 rounded-lg border border-amber-200">
                    <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-medium text-gray-900">RE-2024-1248</p>
                            <span class="text-[10px] font-semibold text-amber-700">€89,50</span>
                        </div>
                        <p class="text-[10px] text-amber-600">Fällig: 30. Dez 2024</p>
                    </div>
                    <button class="px-2 py-1 bg-amber-500 text-white rounded text-[9px] font-medium">
                        Zahlen
                    </button>
                </div>
                <div class="flex items-center gap-3 p-2.5 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-medium text-gray-900">RE-2024-1243</p>
                            <span class="text-[10px] font-semibold text-gray-900">€149,00</span>
                        </div>
                        <p class="text-[10px] text-gray-500">8. Dez 2024 • Bezahlt</p>
                    </div>
                    <button class="p-1.5 hover:bg-gray-200 rounded">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-3 p-2.5 bg-red-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-[11px] font-medium text-gray-900 line-through">RE-2024-1240</p>
                            <span class="text-[10px] text-gray-400 line-through">€45,00</span>
                        </div>
                        <p class="text-[10px] text-red-600">Storniert am 5. Dez</p>
                    </div>
                    <button class="p-1.5 hover:bg-red-100 rounded">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

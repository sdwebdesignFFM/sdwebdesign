{{-- Stylized E-Commerce iPad Mockup --}}
@props(['variant' => 'catalog'])

<div class="bg-gray-800 rounded-[1.5rem] p-3 shadow-2xl max-w-[580px] mx-auto">
    <div class="bg-white rounded-[1rem] overflow-hidden flex flex-col" style="height: 420px;">
        {{-- Browser Chrome --}}
        <div class="bg-gray-100 px-4 py-2 flex items-center gap-3 border-b border-gray-200 flex-shrink-0">
            {{-- Window Controls --}}
            <div class="flex items-center gap-1.5">
                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
            </div>
            {{-- URL Bar --}}
            <div class="flex-1 flex items-center justify-center">
                <div class="bg-white rounded-md px-4 py-1 flex items-center gap-2 text-[10px] text-gray-500 border border-gray-200 w-64">
                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>gewapur.de</span>
                </div>
            </div>
            <div class="w-16"></div>
        </div>

        @if($variant === 'catalog')
        {{-- Shop Catalog View --}}
        <div class="flex-1 overflow-hidden flex flex-col">
            {{-- Shop Header --}}
            <div class="bg-sky-600 px-4 py-3 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                        <span class="text-white text-xs font-bold">GP</span>
                    </div>
                    <span class="text-white text-sm font-medium">GeWaPur</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="relative">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-orange-500 rounded-full text-[9px] text-white flex items-center justify-center">3</span>
                    </div>
                </div>
            </div>

            {{-- Category Navigation --}}
            <div class="bg-gray-50 px-4 py-2 flex items-center gap-4 text-[10px] border-b border-gray-200 flex-shrink-0 overflow-x-auto">
                <span class="text-sky-600 font-medium whitespace-nowrap">Osmoseanlagen</span>
                <span class="text-gray-600 whitespace-nowrap">Wasserfilter</span>
                <span class="text-gray-600 whitespace-nowrap">Enthärtung</span>
                <span class="text-gray-600 whitespace-nowrap">UV-Desinfektion</span>
                <span class="text-gray-600 whitespace-nowrap">Zubehör</span>
            </div>

            {{-- Products Grid --}}
            <div class="flex-1 p-4 overflow-y-auto">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-900">Osmoseanlagen für Privat</h2>
                    <span class="text-[10px] text-gray-500">24 Produkte</span>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    {{-- Product Card 1 --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-2 hover:shadow-md transition-shadow">
                        <div class="aspect-square bg-gradient-to-br from-sky-50 to-sky-100 rounded mb-2 flex items-center justify-center">
                            <svg class="w-10 h-10 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <p class="text-[10px] text-gray-900 font-medium leading-tight mb-1">Osmoseanlage 190 Liter</p>
                        <p class="text-[9px] text-gray-500 mb-2">Direktflow-System</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-sky-600">€ 349,00</span>
                            <button class="w-6 h-6 bg-sky-600 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Product Card 2 --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-2 hover:shadow-md transition-shadow">
                        <div class="aspect-square bg-gradient-to-br from-sky-50 to-sky-100 rounded mb-2 flex items-center justify-center relative">
                            <span class="absolute top-1 left-1 bg-orange-500 text-white text-[8px] px-1.5 py-0.5 rounded">-15%</span>
                            <svg class="w-10 h-10 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <p class="text-[10px] text-gray-900 font-medium leading-tight mb-1">Osmoseanlage 380 Liter</p>
                        <p class="text-[9px] text-gray-500 mb-2">mit Drucktank</p>
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-[9px] text-gray-400 line-through">€ 529,00</span>
                                <span class="text-xs font-bold text-orange-600 block">€ 449,00</span>
                            </div>
                            <button class="w-6 h-6 bg-sky-600 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Product Card 3 - B2B --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-2 hover:shadow-md transition-shadow">
                        <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded mb-2 flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <p class="text-[10px] text-gray-900 font-medium leading-tight mb-1">Industrieanlage 2000L/h</p>
                        <p class="text-[9px] text-gray-500 mb-2">Gewerbliche Nutzung</p>
                        <div class="flex items-center justify-between">
                            <span class="text-[9px] text-gray-600 font-medium">Preis auf Anfrage</span>
                            <button class="px-2 py-1 bg-gray-100 rounded text-[8px] text-gray-700 font-medium">
                                Anfragen
                            </button>
                        </div>
                    </div>

                    {{-- Product Card 4 --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-2 hover:shadow-md transition-shadow">
                        <div class="aspect-square bg-gradient-to-br from-sky-50 to-sky-100 rounded mb-2 flex items-center justify-center">
                            <svg class="w-10 h-10 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                        </div>
                        <p class="text-[10px] text-gray-900 font-medium leading-tight mb-1">Ersatzfilter 3er-Set</p>
                        <p class="text-[9px] text-gray-500 mb-2">Universal passend</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-sky-600">€ 29,90</span>
                            <button class="w-6 h-6 bg-sky-600 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Product Card 5 --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-2 hover:shadow-md transition-shadow">
                        <div class="aspect-square bg-gradient-to-br from-sky-50 to-sky-100 rounded mb-2 flex items-center justify-center">
                            <svg class="w-10 h-10 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <p class="text-[10px] text-gray-900 font-medium leading-tight mb-1">Kompaktanlage 75 GPD</p>
                        <p class="text-[9px] text-gray-500 mb-2">Untertisch-Montage</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-sky-600">€ 199,00</span>
                            <button class="w-6 h-6 bg-sky-600 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Product Card 6 --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-2 hover:shadow-md transition-shadow">
                        <div class="aspect-square bg-gradient-to-br from-sky-50 to-sky-100 rounded mb-2 flex items-center justify-center">
                            <svg class="w-10 h-10 text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <p class="text-[10px] text-gray-900 font-medium leading-tight mb-1">Wasserenthärter 25L</p>
                        <p class="text-[9px] text-gray-500 mb-2">Einfamilienhaus</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-sky-600">€ 849,00</span>
                            <button class="w-6 h-6 bg-sky-600 rounded flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @elseif($variant === 'shipping')
        {{-- Shipping Calculator View --}}
        <div class="flex-1 overflow-hidden flex flex-col">
            {{-- Shop Header --}}
            <div class="bg-sky-600 px-4 py-3 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                        <span class="text-white text-xs font-bold">GP</span>
                    </div>
                    <span class="text-white text-sm font-medium">GeWaPur</span>
                </div>
                <span class="text-white/80 text-xs">Warenkorb</span>
            </div>

            {{-- Cart Content --}}
            <div class="flex-1 p-4 overflow-y-auto">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Ihr Warenkorb (3 Artikel)</h2>

                {{-- Cart Items --}}
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-14 h-14 bg-sky-100 rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-900">Osmoseanlage 380 Liter</p>
                            <p class="text-[10px] text-gray-500">Gewicht: 12 kg</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-900">€ 449,00</p>
                            <p class="text-[10px] text-gray-500">Menge: 1</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-14 h-14 bg-sky-100 rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-900">Ersatzfilter 3er-Set</p>
                            <p class="text-[10px] text-gray-500">Gewicht: 0.5 kg</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-900">€ 59,80</p>
                            <p class="text-[10px] text-gray-500">Menge: 2</p>
                        </div>
                    </div>
                </div>

                {{-- Shipping Calculation --}}
                <div class="bg-sky-50 border border-sky-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs font-medium text-sky-800">Versandberechnung</span>
                    </div>

                    <div class="space-y-2 text-[11px]">
                        <div class="flex items-center justify-between py-1 border-b border-sky-200/50">
                            <span class="text-gray-600">Gesamtgewicht:</span>
                            <span class="font-medium text-gray-900">13 kg</span>
                        </div>
                        <div class="flex items-center justify-between py-1 border-b border-sky-200/50">
                            <span class="text-gray-600">Versandart:</span>
                            <span class="font-medium text-gray-900">Paketversand (DHL)</span>
                        </div>
                        <div class="flex items-center justify-between py-1">
                            <span class="text-gray-600">Lieferland:</span>
                            <div class="flex items-center gap-1">
                                <span class="font-medium text-gray-900">Deutschland</span>
                                <button class="text-sky-600 text-[10px]">ändern</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-sky-200">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-700">Versandkosten:</span>
                            <span class="text-sm font-bold text-sky-600">€ 8,90</span>
                        </div>
                        <p class="text-[9px] text-gray-500 mt-1">Bei Paketgewicht unter 30 kg</p>
                    </div>
                </div>

                {{-- Totals --}}
                <div class="bg-gray-900 rounded-lg p-4 text-white">
                    <div class="space-y-2 text-xs mb-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Zwischensumme:</span>
                            <span>€ 508,80</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Versand:</span>
                            <span>€ 8,90</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">MwSt. (19%):</span>
                            <span>€ 82,62</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-700">
                        <span class="font-medium">Gesamtsumme:</span>
                        <span class="text-lg font-bold">€ 517,70</span>
                    </div>
                </div>
            </div>
        </div>

        @elseif($variant === 'service')
        {{-- Regeneration Product with Pickup Service --}}
        <div class="flex-1 overflow-hidden flex flex-col">
            {{-- Shop Header --}}
            <div class="bg-sky-600 px-4 py-3 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                        <span class="text-white text-xs font-bold">GP</span>
                    </div>
                    <span class="text-white text-sm font-medium">GeWaPur</span>
                </div>
                <span class="text-white/80 text-xs">Produktseite</span>
            </div>

            {{-- Product Page Content --}}
            <div class="flex-1 p-4 overflow-y-auto">
                {{-- Product Info --}}
                <p class="text-[10px] text-gray-500 leading-relaxed mb-4">
                    Gewapur bietet einen umfassenden Regenerationsservice für Vollentsalzungspatronen und Vollentsalzer. Umweltschonend und kosteneffizient, mit Abholung, professioneller Aufbereitung und Qualitätskontrolle im Labor.
                </p>

                {{-- Variation Select --}}
                <div class="mb-4">
                    <label class="text-xs font-medium text-gray-700 block mb-2">Patrone wählen:</label>
                    <div class="relative">
                        <select class="w-full text-xs bg-white border border-gray-300 rounded px-3 py-2.5 appearance-none pr-8">
                            <option>Meladem 53 (17-20 Liter Patrone)</option>
                            <option>Meladem 53 C (13-15 Liter Patrone)</option>
                            <option>Miele VE P 2000</option>
                            <option>Vollentsalzungspatrone (22-25 Liter)</option>
                            <option>Vollentsalzungspatrone (30 Liter)</option>
                        </select>
                        <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                {{-- Price --}}
                <div class="mb-4">
                    <p class="text-xl font-bold text-green-600">114,10 €</p>
                    <p class="text-[10px] text-gray-500">Enthält 19% MwSt.</p>
                    <p class="text-[10px] text-gray-500">Lieferzeit: Nach telefonischer Vereinbarung</p>
                </div>

                {{-- Quantity & Add to Cart --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex items-center border border-gray-300 rounded">
                        <button class="px-3 py-2 text-gray-500 hover:bg-gray-50">−</button>
                        <span class="px-3 py-2 text-xs font-medium border-x border-gray-300">1</span>
                        <button class="px-3 py-2 text-gray-500 hover:bg-gray-50">+</button>
                    </div>
                    <button class="flex-1 py-2.5 bg-sky-600 text-white rounded text-xs font-medium">
                        IN DEN WARENKORB
                    </button>
                </div>

                {{-- Pickup Service Options --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
                    <p class="text-xs font-medium text-gray-900 mb-2">Abholservice:</p>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pickup" class="w-3.5 h-3.5 text-sky-600" />
                            <span class="text-[11px] text-gray-700">Nein (Standard)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pickup" class="w-3.5 h-3.5 text-sky-600" checked />
                            <span class="text-[11px] text-gray-700">Deutschland (+15,10 €)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="pickup" class="w-3.5 h-3.5 text-sky-600" />
                            <span class="text-[11px] text-gray-700">Österreich (+29,90 €)</span>
                        </label>
                    </div>
                </div>

                {{-- Warning Box --}}
                <div class="bg-amber-50 border-2 border-amber-400 rounded-lg p-3 relative pl-10">
                    <span class="absolute left-3 top-3 text-amber-500 text-lg">⚠</span>
                    <p class="text-[10px] font-semibold text-amber-800 mb-1">Wichtiger Hinweis zur Abholung:</p>
                    <p class="text-[10px] text-amber-700 leading-relaxed">
                        Bitte entleeren Sie die Patrone vor der Abholung vollständig, um Transportschäden zu vermeiden. Eine nicht entleerte Patrone kann während des Transports beschädigt werden und zu Leckagen führen.
                    </p>
                </div>
            </div>
        </div>

        @elseif($variant === 'b2b')
        {{-- B2B Quote Request --}}
        <div class="flex-1 overflow-hidden flex flex-col">
            {{-- Shop Header --}}
            <div class="bg-sky-600 px-4 py-3 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                        <span class="text-white text-xs font-bold">GP</span>
                    </div>
                    <span class="text-white text-sm font-medium">GeWaPur</span>
                </div>
                <span class="text-white/80 text-xs">Angebot anfragen</span>
            </div>

            {{-- B2B Form Content --}}
            <div class="flex-1 p-4 overflow-y-auto">
                {{-- Product Info --}}
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg mb-4">
                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[9px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded uppercase font-medium">B2B</span>
                        <h3 class="text-xs font-semibold text-gray-900 mt-1">Industrieanlage RO-2000</h3>
                        <p class="text-[10px] text-gray-500">2000 Liter/Stunde Kapazität</p>
                    </div>
                </div>

                {{-- Quote Form --}}
                <div class="space-y-3">
                    <div>
                        <label class="text-[10px] text-gray-600 block mb-1">Firma *</label>
                        <input type="text" class="w-full text-xs bg-white border border-gray-200 rounded px-3 py-2" placeholder="Musterfirma GmbH">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] text-gray-600 block mb-1">Ansprechpartner *</label>
                            <input type="text" class="w-full text-xs bg-white border border-gray-200 rounded px-3 py-2" placeholder="Max Mustermann">
                        </div>
                        <div>
                            <label class="text-[10px] text-gray-600 block mb-1">Telefon</label>
                            <input type="text" class="w-full text-xs bg-white border border-gray-200 rounded px-3 py-2" placeholder="+49 89 123456">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] text-gray-600 block mb-1">E-Mail *</label>
                        <input type="email" class="w-full text-xs bg-white border border-gray-200 rounded px-3 py-2" placeholder="max@musterfirma.de">
                    </div>

                    <div>
                        <label class="text-[10px] text-gray-600 block mb-1">USt-IdNr. (optional)</label>
                        <input type="text" class="w-full text-xs bg-white border border-gray-200 rounded px-3 py-2" placeholder="DE123456789">
                    </div>

                    <div>
                        <label class="text-[10px] text-gray-600 block mb-1">Gewünschte Menge</label>
                        <select class="w-full text-xs bg-white border border-gray-200 rounded px-3 py-2">
                            <option>1 Anlage</option>
                            <option>2-5 Anlagen</option>
                            <option>5+ Anlagen</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] text-gray-600 block mb-1">Nachricht / Anforderungen</label>
                        <textarea class="w-full text-xs bg-white border border-gray-200 rounded px-3 py-2 h-16 resize-none" placeholder="Beschreiben Sie Ihre Anforderungen..."></textarea>
                    </div>

                    <button class="w-full py-3 bg-sky-600 text-white rounded-lg text-xs font-medium">
                        Unverbindlich anfragen
                    </button>

                    <p class="text-[9px] text-gray-500 text-center">
                        Wir melden uns innerhalb von 24h bei Ihnen
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

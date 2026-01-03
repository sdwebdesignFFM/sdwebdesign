<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $quote->isFullySigned() ? 'Auftragsbestätigung' : 'Angebot' }} {{ $quote->quote_number }}</title>
    <style>
        @page {
            margin: 2cm 2cm 3cm 2cm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }

        .header {
            margin-bottom: 30px;
        }

        .header-row {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .header-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #1a365d;
            margin-bottom: 5px;
        }

        .quote-number {
            font-size: 11pt;
            color: #666;
            margin-bottom: 20px;
        }

        .client-box {
            background-color: #f7f7f7;
            padding: 15px;
            margin-bottom: 30px;
        }

        .client-label {
            font-size: 8pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .client-name {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .title {
            font-size: 14pt;
            font-weight: bold;
            color: #1a365d;
            margin-bottom: 10px;
        }

        .subject {
            font-size: 10pt;
            color: #666;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .intro-text {
            margin-bottom: 30px;
            white-space: pre-line;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            background-color: #1a365d;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 9pt;
        }

        .items-table th:last-child {
            text-align: right;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        .items-table td:last-child {
            text-align: right;
            white-space: nowrap;
        }

        .item-name {
            font-weight: bold;
        }

        .item-description {
            font-size: 9pt;
            color: #666;
            margin-top: 3px;
        }

        .item-description p {
            margin: 0 0 5px 0;
        }

        .item-description ul,
        .item-description ol {
            margin: 5px 0;
            padding-left: 15px;
        }

        .item-description li {
            margin-bottom: 2px;
        }

        .item-optional {
            color: #3182ce;
            font-size: 8pt;
            font-style: italic;
        }

        .totals-container {
            margin-left: auto;
            width: 250px;
        }

        .totals-row {
            display: table;
            width: 100%;
            padding: 5px 0;
        }

        .totals-label {
            display: table-cell;
            text-align: left;
        }

        .totals-value {
            display: table-cell;
            text-align: right;
        }

        .totals-total {
            font-weight: bold;
            font-size: 12pt;
            border-top: 2px solid #1a365d;
            padding-top: 8px;
            margin-top: 5px;
        }

        .contract-info {
            background-color: #ebf8ff;
            padding: 15px;
            margin: 30px 0;
            border-left: 3px solid #3182ce;
        }

        .contract-info-title {
            font-weight: bold;
            color: #2c5282;
            margin-bottom: 10px;
        }

        .terms-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .terms-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .terms-text {
            font-size: 9pt;
            color: #666;
        }

        .validity {
            margin-top: 30px;
            padding: 15px;
            background-color: #fff5f5;
            border-left: 3px solid #c53030;
            font-size: 9pt;
        }

        .acceptance-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #f0fff4;
            border: 1px solid #9ae6b4;
            page-break-inside: avoid;
        }

        .acceptance-title {
            font-weight: bold;
            color: #22543d;
            font-size: 12pt;
            margin-bottom: 15px;
        }

        .acceptance-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .acceptance-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .acceptance-label {
            font-size: 8pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .acceptance-value {
            font-size: 10pt;
            color: #333;
        }

        .signature-box {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .signature-label {
            font-size: 8pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .signature-image {
            max-width: 300px;
            max-height: 100px;
        }

        .signature-meta {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }

        .signatures-row {
            display: table;
            width: 100%;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .signature-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }

        .signature-col:last-child {
            padding-right: 0;
            padding-left: 15px;
        }

        .signature-name {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 3px;
        }

        .signature-position {
            font-size: 9pt;
            color: #666;
            margin-bottom: 10px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2cm;
            font-size: 8pt;
            color: #666;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="header-row">
            <div class="header-left">
                <div class="company-name">{{ config('app.name', 'SD Webdesign') }}</div>
            </div>
            <div class="header-right">
                <div class="quote-number">{{ $quote->isFullySigned() ? 'Auftragsbestätigung' : 'Angebot' }} {{ $quote->quote_number }}</div>
                <div>Erstellt: {{ $quote->created_at->format('d.m.Y') }}</div>
                @if(!$quote->accepted_at)
                    <div>Gültig bis: {{ $quote->valid_until->format('d.m.Y') }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Client Info --}}
    <div class="client-box">
        <div class="client-label">{{ $quote->isFullySigned() ? 'Auftragsbestätigung für' : 'Angebot für' }}</div>
        <div class="client-name">{{ $quote->client_name }}</div>
        @if($quote->client_company)
            <div>{{ $quote->client_company }}</div>
        @endif
        @if($quote->hasBillingDetails())
            <div>{{ $quote->billing_street }}</div>
            <div>{{ $quote->billing_zip }} {{ $quote->billing_city }}</div>
            @if($quote->billing_country && $quote->billing_country !== 'Deutschland')
                <div>{{ $quote->billing_country }}</div>
            @endif
        @endif
        @if($quote->client_email)
            <div style="margin-top: 5px;">{{ $quote->client_email }}</div>
        @endif
    </div>

    {{-- Title & Subject --}}
    <div class="title">{{ $quote->title }}</div>
    @if($quote->subject)
        <div class="subject"><strong>Vertragsgegenstand:</strong> {{ $quote->subject }}</div>
    @endif

    {{-- Intro Text --}}
    @if($quote->intro_text)
        <div class="intro-text">{!! $quote->intro_text !!}</div>
    @endif

    {{-- Items Table --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 60%">Leistung</th>
                <th style="width: 15%">Menge</th>
                <th style="width: 25%">Betrag</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items()->orderBy('sort_order')->get() as $item)
                @if(!$item->is_optional || $item->is_selected)
                    <tr>
                        <td>
                            <div class="item-name">{{ $item->name }}</div>
                            @if($item->description)
                                <div class="item-description">{!! $item->description !!}</div>
                            @endif
                            @if($item->is_optional)
                                <div class="item-optional">(Optional)</div>
                            @endif
                        </td>
                        <td>{{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit }}</td>
                        <td>{{ number_format($item->total_price, 2, ',', '.') }} &euro;</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals-container">
        <div class="totals-row">
            <div class="totals-label">Netto</div>
            <div class="totals-value">{{ number_format($quote->subtotal, 2, ',', '.') }} &euro;</div>
        </div>
        <div class="totals-row">
            <div class="totals-label">MwSt. ({{ number_format($quote->tax_rate, 0) }}%)</div>
            <div class="totals-value">{{ number_format($quote->tax_amount, 2, ',', '.') }} &euro;</div>
        </div>
        <div class="totals-row totals-total">
            <div class="totals-label">Gesamtbetrag</div>
            <div class="totals-value">{{ number_format($quote->total, 2, ',', '.') }} &euro;</div>
        </div>
    </div>

    {{-- Contract Info (for recurring) --}}
    @if($quote->isRecurring())
        <div class="contract-info">
            <div class="contract-info-title">Vertragsinformationen</div>
            <div>Abrechnungszyklus: {{ $quote->billing_cycle->getLabel() }}</div>
            @if($quote->min_term_months)
                <div>Mindestlaufzeit: {{ $quote->min_term_months }} Monate</div>
            @endif
            @if($quote->notice_period_days)
                <div>Kündigungsfrist: {{ $quote->notice_period_days }} Tage</div>
            @endif
            <div>Automatische Verlängerung: {{ $quote->auto_renewal ? 'Ja' : 'Nein' }}</div>
        </div>
    @endif

    {{-- Terms --}}
    @if($quote->terms_text)
        <div class="terms-section">
            <div class="terms-title">Vertragsbedingungen</div>
            <div class="terms-text">{!! $quote->terms_text !!}</div>
        </div>
    @endif

    {{-- Validity Notice (only show if not yet accepted) --}}
    @if(!$quote->accepted_at)
        <div class="validity">
            <strong>Hinweis:</strong> Dieses Angebot ist gültig bis zum {{ $quote->valid_until->format('d.m.Y') }}.
            Nach Ablauf dieser Frist können wir die genannten Preise und Leistungen nicht mehr garantieren.
        </div>
    @endif

    {{-- Acceptance Section (only show if quote was accepted) --}}
    @if($quote->accepted_at)
        <div class="acceptance-section">
            <div class="acceptance-title">{{ $quote->isFullySigned() ? 'Vertragsabschluss' : 'Vertragsannahme' }}</div>

            {{-- Contract Status --}}
            <div style="margin-bottom: 15px;">
                <strong>Beauftragt am:</strong> {{ $quote->accepted_at->format('d.m.Y') }}
            </div>

            {{-- Electronic Acceptance Note --}}
            <div style="margin-bottom: 15px; font-size: 9pt; color: #666;">
                Annahme erfolgte elektronisch über das Angebotssystem von {{ config('app.name', 'SD Webdesign') }}.
            </div>

            {{-- Contract Components --}}
            <div style="margin-bottom: 20px; font-size: 9pt; padding: 10px; background-color: #f8f8f8; border-left: 2px solid #22543d;">
                <strong>Vertragsbestandteile:</strong><br>
                Allgemeine Geschäftsbedingungen (AGB) sowie die zugehörigen Leistungsvereinbarungen in der bei Annahme gültigen Fassung.
            </div>

            <div class="acceptance-row">
                {{-- Billing Address --}}
                @if($quote->hasBillingDetails())
                    <div class="acceptance-col">
                        <div class="acceptance-label">Rechnungsadresse</div>
                        <div class="acceptance-value">
                            @if($quote->billing_company)
                                {{ $quote->billing_company }}<br>
                            @endif
                            {{ $quote->billing_name }}<br>
                            {{ $quote->billing_street }}<br>
                            {{ $quote->billing_zip }} {{ $quote->billing_city }}
                            @if($quote->billing_country && $quote->billing_country !== 'Deutschland')
                                <br>{{ $quote->billing_country }}
                            @endif
                            @if($quote->billing_vat_id)
                                <br><small>USt-IdNr.: {{ $quote->billing_vat_id }}</small>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Acceptance Details --}}
                <div class="acceptance-col">
                    <div class="acceptance-label">Angenommen von</div>
                    <div class="acceptance-value">
                        {{ $quote->accepted_name }}
                    </div>
                </div>
            </div>

            {{-- Digital Signature Block --}}
            @if($quote->hasSignature())
                <div class="signatures-row">
                    {{-- Customer Signature --}}
                    <div class="signature-col">
                        <div class="signature-label">Digitale Annahme - Auftraggeber</div>
                        <div class="signature-name">{{ $quote->accepted_name }}</div>
                        <img src="{{ $quote->signature_data }}" alt="Kundenunterschrift" class="signature-image">
                        <div class="signature-meta">
                            Datum & Uhrzeit: {{ $quote->signature_at?->format('d.m.Y, H:i') ?? $quote->accepted_at->format('d.m.Y, H:i') }} Uhr<br>
                            Art der Signatur: Elektronische Signatur
                        </div>
                    </div>

                    {{-- Admin Signature --}}
                    @if($quote->hasAdminSignature())
                        <div class="signature-col">
                            <div class="signature-label">Digitale Bestätigung - Auftragnehmer</div>
                            <div class="signature-name">{{ $quote->admin_signature_name }}</div>
                            @if($quote->admin_signature_position)
                                <div class="signature-position">{{ $quote->admin_signature_position }}</div>
                            @endif
                            <img src="{{ $quote->admin_signature_data }}" alt="Unterschrift Auftragnehmer" class="signature-image">
                            <div class="signature-meta">
                                Datum & Uhrzeit: {{ $quote->admin_signed_at?->format('d.m.Y, H:i') }} Uhr<br>
                                Art der Signatur: Elektronische Signatur
                            </div>
                        </div>
                    @else
                        <div class="signature-col">
                            <div class="signature-label">Auftragnehmer</div>
                            <div style="padding: 30px 0; color: #999; font-style: italic; font-size: 9pt;">
                                Gegenzeichnung ausstehend
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        {{ config('app.name', 'SD Webdesign') }} | {{ $quote->isFullySigned() ? 'Auftragsbestätigung' : 'Angebot' }} {{ $quote->quote_number }} | Seite <span class="page-number"></span>
    </div>
</body>
</html>

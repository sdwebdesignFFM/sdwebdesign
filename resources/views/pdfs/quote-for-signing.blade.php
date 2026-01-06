<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Angebot {{ $quote->quote_number }} - Zur Unterschrift</title>
    <style>
        @page {
            margin: 2cm 2cm 4cm 2cm;
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

        .signing-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #f0fff4;
            border: 2px solid #38a169;
            page-break-inside: avoid;
        }

        .signing-title {
            font-weight: bold;
            color: #22543d;
            font-size: 14pt;
            margin-bottom: 20px;
            text-align: center;
        }

        .signing-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .signing-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .signing-col:last-child {
            padding-right: 0;
            padding-left: 20px;
        }

        .field-label {
            font-size: 8pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .field-value {
            font-size: 10pt;
            color: #333;
            padding: 8px;
            background-color: #fff;
            border: 1px solid #ddd;
            min-height: 20px;
        }

        .signature-box {
            margin-top: 30px;
        }

        .signature-field {
            border-bottom: 2px solid #333;
            height: 80px;
            margin-bottom: 5px;
            background-color: #fff;
        }

        .signature-hint {
            font-size: 8pt;
            color: #666;
            text-align: center;
        }

        .date-field {
            width: 200px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-top: 20px;
        }

        .return-info {
            margin-top: 30px;
            padding: 15px;
            background-color: #ebf8ff;
            border-left: 3px solid #3182ce;
            font-size: 9pt;
        }

        .return-info-title {
            font-weight: bold;
            color: #2c5282;
            margin-bottom: 8px;
        }

        .footer {
            position: fixed;
            bottom: -3cm;
            left: 0;
            right: 0;
            height: 2cm;
            font-size: 8pt;
            color: #666;
            text-align: right;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-number {
            /* Page numbers will be added via PHP script */
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="header-row">
            <div class="header-left">
                <div class="company-name">{{ config('app.name', 'SD Webdesign') }}</div>
                @if(isset($settings) && $settings->owner_name)
                    <div>{{ $settings->owner_name }}</div>
                @endif
            </div>
            <div class="header-right">
                <div class="quote-number">Angebot {{ $quote->quote_number }}</div>
                <div>Erstellt: {{ $quote->created_at->format('d.m.Y') }}</div>
                <div>Gültig bis: {{ $quote->valid_until->format('d.m.Y') }}</div>
            </div>
        </div>
    </div>

    {{-- Client Info --}}
    <div class="client-box">
        <div class="client-label">Angebot für</div>
        <div class="client-name">{{ $quote->client_name }}</div>
        @if($quote->client_company)
            <div>{{ $quote->client_company }}</div>
        @endif
        @if($quote->client_email)
            <div>{{ $quote->client_email }}</div>
        @endif
    </div>

    {{-- Title & Subject --}}
    <div class="title">{{ $quote->title }}</div>

    {{-- Greeting and Intro Text --}}
    <div class="intro-text">
        <p style="margin-bottom: 10px;"><strong>{{ $quote->getGreeting() }}</strong></p>
        @if($quote->intro_text)
            {!! $quote->intro_text !!}
        @endif
    </div>

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
                        <td>
                            {{ number_format($item->total_price, 2, ',', '.') }} &euro;
                            @if($item->billing_cycle)
                                <div style="font-size: 8pt; color: #666;">{{ $item->billing_cycle->getPeriodLabel() }}</div>
                            @endif
                            @if($item->invoice_interval && $item->invoice_interval !== $item->billing_cycle)
                                <div style="font-size: 7pt; color: #888;">Abrechnung: {{ $item->invoice_interval->getLabel() }}</div>
                            @endif
                            @if($item->hasPaymentTerms())
                                <div style="font-size: 7pt; color: #888;">Zahlung: {{ $item->payment_terms }}</div>
                            @endif
                        </td>
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
    @if($quote->isRecurring() && $quote->billing_cycle)
        <div class="contract-info">
            <div class="contract-info-title">Vertragsinformationen</div>
            <div>Zahlungsweise: {{ $quote->billing_cycle->getLabel() }}</div>
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

    {{-- Signing Section --}}
    <div class="signing-section">
        <div class="signing-title">Vertragsannahme</div>

        <p style="font-size: 9pt; margin-bottom: 20px; text-align: center;">
            Hiermit nehme ich das obige Angebot verbindlich an und akzeptiere die Vertragsbedingungen.
        </p>

        <div class="signing-row">
            {{-- Billing Address --}}
            <div class="signing-col">
                <div class="field-label">Rechnungsadresse</div>
                <div class="field-value">
                    @if($quote->hasBillingDetails())
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
                            <br>USt-IdNr.: {{ $quote->billing_vat_id }}
                        @endif
                    @else
                        <em style="color: #999;">Bitte hier eintragen</em>
                    @endif
                </div>
            </div>

            {{-- Name --}}
            <div class="signing-col">
                <div class="field-label">Name des Unterzeichners</div>
                <div class="field-value">
                    @if(!empty($acceptedName))
                        {{ $acceptedName }}
                    @else
                        <em style="color: #999;">Bitte hier eintragen</em>
                    @endif
                </div>
            </div>
        </div>

        {{-- Signature --}}
        <div class="signature-box">
            <div class="field-label">Unterschrift</div>
            <div class="signature-field"></div>
            <div class="signature-hint">Bitte hier unterschreiben</div>
        </div>

        {{-- Date --}}
        <div class="date-field">
            <div class="field-label">Ort, Datum</div>
        </div>
    </div>

    {{-- Return Info --}}
    <div class="return-info">
        <div class="return-info-title">Bitte senden Sie das unterschriebene Dokument an:</div>
        <p>
            <strong>E-Mail:</strong> {{ $settings->email ?? 'info@sdwebdesign.de' }}<br>
            Alternativ per Post an die im Briefkopf angegebene Adresse.
        </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <span class="page-number"></span>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Seite {PAGE_NUM} von {PAGE_COUNT}";
            $size = 7;
            $font = $fontMetrics->getFont("DejaVu Sans");
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = $pdf->get_width() - $width - 56.7; // 2cm margin in points
            $y = $pdf->get_height() - 30;
            $pdf->page_text($x, $y, $text, $font, $size, [0.6, 0.6, 0.6]);
        }
    </script>
</body>
</html>

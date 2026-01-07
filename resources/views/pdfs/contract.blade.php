<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vertrag {{ $contract->contract_number }}</title>
    <style>
        @page {
            margin: 2cm 2cm 4.5cm 2cm;
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

        .contract-number {
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

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            table-layout: fixed;
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

        .signature-label {
            font-size: 8pt;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
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

        .signature-image {
            max-width: 300px;
            max-height: 100px;
        }

        .signature-meta {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }

        .footer {
            position: fixed;
            bottom: -3.5cm;
            left: 0;
            right: 0;
            height: 3cm;
            padding-top: 3mm;
            font-size: 7pt;
            color: #666;
            border-top: 1px solid #ccc;
        }

        .footer-table {
            width: 100%;
        }

        .footer-table td {
            vertical-align: top;
            padding-right: 5mm;
            line-height: 1.5;
        }

        .footer-table td:last-child {
            padding-right: 0;
        }

        .footer-bottom {
            margin-top: 2mm;
            text-align: right;
            font-size: 7pt;
            color: #999;
        }

        .page-number {
            /* Page numbers will be added via PHP script */
        }

        .page-break {
            page-break-before: always;
        }

        .attachment-page {
            padding-top: 20px;
        }

        .attachment-header {
            font-size: 14pt;
            font-weight: bold;
            color: #1a365d;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1a365d;
        }

        .attachment-subheader {
            font-size: 11pt;
            font-weight: bold;
            color: #333;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .attachment-content {
            font-size: 9pt;
            line-height: 1.5;
            color: #333;
        }

        .attachment-content p {
            margin: 0 0 8px 0;
        }

        .attachment-content h2 {
            font-size: 11pt;
            font-weight: bold;
            color: #1a365d;
            margin: 20px 0 10px 0;
        }

        .attachment-content h3 {
            font-size: 10pt;
            font-weight: bold;
            color: #333;
            margin: 15px 0 8px 0;
        }

        .attachment-content ul,
        .attachment-content ol {
            margin: 8px 0;
            padding-left: 20px;
        }

        .attachment-content li {
            margin-bottom: 4px;
        }

        .attachment-content strong {
            color: #1a365d;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="header-row">
            <div class="header-left">
                <div class="company-name">{{ config('app.name', 'SD Webdesign') }}</div>
                @if($company['owner'])
                    <div>{{ $company['owner'] }}</div>
                @endif
            </div>
            <div class="header-right">
                <div class="contract-number">Vertrag {{ $contract->contract_number }}</div>
                <div>Vertragsbeginn: {{ $contract->start_date->format('d.m.Y') }}</div>
                @if($contract->quote)
                    <div style="font-size: 9pt; color: #888;">Angebot: {{ $contract->quote->quote_number }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Client Info --}}
    <div class="client-box">
        <div class="client-label">Vertragspartner</div>
        <div class="client-name">{{ $contract->client_name }}</div>
        @if($contract->client_company)
            <div>{{ $contract->client_company }}</div>
        @endif
        @if($contract->client_address)
            <div style="white-space: pre-line;">{{ $contract->client_address }}</div>
        @endif
        @if($contract->client_email)
            <div style="margin-top: 5px;">{{ $contract->client_email }}</div>
        @endif
    </div>

    {{-- Title --}}
    <div class="title">{{ $contract->title }}</div>

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
            @foreach($contract->items as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item->name }}</div>
                        @if($item->description)
                            <div class="item-description">{!! $item->description !!}</div>
                        @endif
                    </td>
                    <td>{{ number_format($item->quantity, 0, ',', '.') }} {{ $item->unit }}</td>
                    <td>{{ number_format($item->total_price, 2, ',', '.') }} &euro;</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals --}}
    <div class="totals-container">
        <div class="totals-row">
            <div class="totals-label">Netto</div>
            <div class="totals-value">{{ number_format($contract->subtotal, 2, ',', '.') }} &euro;</div>
        </div>
        <div class="totals-row">
            <div class="totals-label">MwSt. ({{ number_format($contract->tax_rate, 0) }}%)</div>
            <div class="totals-value">{{ number_format($contract->tax_amount, 2, ',', '.') }} &euro;</div>
        </div>
        <div class="totals-row totals-total">
            <div class="totals-label">Gesamtbetrag</div>
            <div class="totals-value">{{ number_format($contract->total, 2, ',', '.') }} &euro;</div>
        </div>
    </div>

    {{-- Contract Info (for recurring) --}}
    @if($contract->isRecurring() && $contract->billing_cycle)
        <div class="contract-info">
            <div class="contract-info-title">Vertragsinformationen</div>
            <div>Zahlungsweise: {{ $contract->billing_cycle->getLabel() }}</div>
            @if($contract->min_term_months)
                <div>Mindestlaufzeit: {{ $contract->min_term_months }} Monate</div>
            @endif
            @if($contract->notice_period_days)
                <div>Kündigungsfrist: {{ $contract->notice_period_days }} Tage</div>
            @endif
            <div>Automatische Verlängerung: {{ $contract->auto_renewal ? 'Ja' : 'Nein' }}</div>
            @if($contract->min_term_end_date)
                <div>Ende Mindestlaufzeit: {{ $contract->min_term_end_date->format('d.m.Y') }}</div>
            @endif
        </div>
    @endif

    {{-- Acceptance Section --}}
    <div class="acceptance-section">
        <div class="acceptance-title">Vertragsabschluss</div>

        {{-- Contract Status --}}
        <div style="margin-bottom: 15px;">
            <strong>Beauftragt am:</strong> {{ $contract->accepted_at?->format('d.m.Y') ?? $contract->created_at->format('d.m.Y') }}
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
            {{-- Acceptance Details --}}
            <div class="acceptance-col">
                <div class="acceptance-label">Angenommen von</div>
                <div class="acceptance-value">
                    {{ $contract->accepted_name }}
                </div>
            </div>

            {{-- IP Address --}}
            <div class="acceptance-col">
                <div class="acceptance-label">IP-Adresse</div>
                <div class="acceptance-value">
                    {{ $contract->accepted_ip ?? ($contract->quote?->accepted_ip ?? '-') }}
                </div>
            </div>
        </div>

        {{-- Digital Signature Block --}}
        @if($contract->quote && $contract->quote->hasSignature())
            <div class="signatures-row">
                {{-- Customer Signature --}}
                <div class="signature-col">
                    <div class="signature-label">Digitale Annahme - Auftraggeber</div>
                    <div class="signature-name">{{ $contract->quote->accepted_name }}</div>
                    <img src="{{ $contract->quote->signature_data }}" alt="Kundenunterschrift" class="signature-image">
                    <div class="signature-meta">
                        Datum & Uhrzeit: {{ $contract->quote->signature_at?->format('d.m.Y, H:i') ?? $contract->quote->accepted_at->format('d.m.Y, H:i') }} Uhr<br>
                        Art der Signatur: Elektronische Signatur
                    </div>
                </div>

                {{-- Admin Signature --}}
                @if($contract->quote->hasAdminSignature())
                    <div class="signature-col">
                        <div class="signature-label">Digitale Bestätigung - Auftragnehmer</div>
                        <div class="signature-name">{{ $contract->quote->admin_signature_name }}</div>
                        @if($contract->quote->admin_signature_position)
                            <div class="signature-position">{{ $contract->quote->admin_signature_position }}</div>
                        @endif
                        <img src="{{ $contract->quote->admin_signature_data }}" alt="Unterschrift Auftragnehmer" class="signature-image">
                        <div class="signature-meta">
                            Datum & Uhrzeit: {{ $contract->quote->admin_signed_at?->format('d.m.Y, H:i') }} Uhr<br>
                            Art der Signatur: Elektronische Signatur
                        </div>
                    </div>
                @elseif(isset($settings) && $settings->hasAdminSignature())
                    <div class="signature-col">
                        <div class="signature-label">Digitale Bestätigung - Auftragnehmer</div>
                        <div class="signature-name">{{ $settings->admin_signer_name }}</div>
                        @if($settings->admin_signer_position)
                            <div class="signature-position">{{ $settings->admin_signer_position }}</div>
                        @endif
                        <img src="{{ $settings->admin_signature_data }}" alt="Unterschrift Auftragnehmer" class="signature-image">
                        <div class="signature-meta">
                            Datum & Uhrzeit: {{ $contract->accepted_at?->format('d.m.Y, H:i') ?? $contract->created_at->format('d.m.Y, H:i') }} Uhr<br>
                            Art der Signatur: Elektronische Signatur
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Leistungsvereinbarungen Page --}}
    @php
        $itemsWithTerms = $contract->items->filter(fn($item) => $item->hasDetailedTerms());
    @endphp
    @if($itemsWithTerms->count() > 0)
        <div class="page-break attachment-page">
            <div class="attachment-header">Leistungsvereinbarungen</div>
            <p style="font-size: 9pt; color: #666; margin-bottom: 20px;">
                Die folgenden Leistungsvereinbarungen sind Bestandteil dieses Vertrags und definieren den Umfang der beauftragten Leistungen.
            </p>

            @foreach($itemsWithTerms as $item)
                <div class="attachment-subheader">{{ $item->name }}</div>
                <div class="attachment-content">
                    {!! $item->detailed_terms !!}
                </div>
            @endforeach
        </div>
    @endif

    {{-- AGB Page --}}
    @if(isset($settings) && $settings->agb_content)
        <div class="page-break attachment-page">
            <div class="attachment-header">Allgemeine Geschäftsbedingungen (AGB)</div>
            <div class="attachment-content">
                {!! $settings->agb_content !!}
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td style="width: 22%">
                    {{ $company['name'] }}<br>
                    {{ $company['street'] }}<br>
                    {{ $company['postal_code'] }} {{ $company['city'] }}
                </td>
                <td style="width: 24%">
                    @if($company['phone'])Tel.: {{ $company['phone'] }}<br>@endif
                    E-Mail: {{ $company['email'] }}
                    @if($company['website'])<br>{{ $company['website'] }}@endif
                </td>
                <td style="width: 24%">
                    @if($company['vat_id'])USt-IdNr.: {{ $company['vat_id'] }}<br>@endif
                    @if($company['tax_number'])Steuer-Nr.: {{ $company['tax_number'] }}<br>@endif
                    @if($company['owner'])Inhaber: {{ $company['owner'] }}@endif
                </td>
                <td style="width: 30%">
                    @if($company['bank_name']){{ $company['bank_name'] }}<br>@endif
                    @if($company['bank_iban'])IBAN: {{ $company['bank_iban'] }}<br>@endif
                    @if($company['bank_bic'])BIC: {{ $company['bank_bic'] }}@endif
                </td>
            </tr>
        </table>
        <div class="footer-bottom">
            <span class="page-number"></span>
        </div>
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

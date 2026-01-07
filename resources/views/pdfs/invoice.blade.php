<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rechnung {{ $invoice->invoice_number }}</title>
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

        .invoice-number {
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

        .period-info {
            margin-bottom: 20px;
            padding: 10px 15px;
            background-color: #ebf8ff;
            border-left: 3px solid #3182ce;
            font-size: 9pt;
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

        .payment-info {
            margin-top: 30px;
            padding: 15px;
            background-color: #f0fff4;
            border-left: 3px solid #38a169;
        }

        .payment-info-title {
            font-weight: bold;
            color: #22543d;
            margin-bottom: 10px;
        }

        .due-notice {
            margin-top: 30px;
            padding: 15px;
            background-color: #fff5f5;
            border-left: 3px solid #c53030;
            font-size: 9pt;
        }

        .notes-section {
            margin-top: 30px;
            font-size: 9pt;
            color: #666;
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

        .cancelled-watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72pt;
            color: rgba(200, 0, 0, 0.15);
            font-weight: bold;
            z-index: 1000;
        }
    </style>
</head>
<body>
    @if($invoice->isCancelled())
        <div class="cancelled-watermark">STORNIERT</div>
    @endif

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
                <div class="invoice-number">Rechnung {{ $invoice->invoice_number }}</div>
                <div>Rechnungsdatum: {{ $invoice->issue_date->format('d.m.Y') }}</div>
                <div>Fällig bis: {{ $invoice->due_date->format('d.m.Y') }}</div>
            </div>
        </div>
    </div>

    {{-- Client Info --}}
    <div class="client-box">
        <div class="client-label">Rechnung an</div>
        <div class="client-name">{{ $invoice->client_name }}</div>
        @if($invoice->client_company)
            <div>{{ $invoice->client_company }}</div>
        @endif
        @if($invoice->client_address)
            <div style="white-space: pre-line;">{{ $invoice->client_address }}</div>
        @endif
        @if($invoice->client_email)
            <div style="margin-top: 5px;">{{ $invoice->client_email }}</div>
        @endif
    </div>

    {{-- Period Info --}}
    @if($invoice->getPeriodLabel())
        <div class="period-info">
            <strong>Leistungszeitraum:</strong> {{ $invoice->getPeriodLabel() }}
        </div>
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
            @foreach($invoice->items as $item)
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
            <div class="totals-value">{{ number_format($invoice->subtotal, 2, ',', '.') }} &euro;</div>
        </div>
        <div class="totals-row">
            <div class="totals-label">MwSt. ({{ number_format($invoice->tax_rate, 0) }}%)</div>
            <div class="totals-value">{{ number_format($invoice->tax_amount, 2, ',', '.') }} &euro;</div>
        </div>
        <div class="totals-row totals-total">
            <div class="totals-label">Gesamtbetrag</div>
            <div class="totals-value">{{ number_format($invoice->total, 2, ',', '.') }} &euro;</div>
        </div>
    </div>

    {{-- Payment Info --}}
    <div class="payment-info">
        <div class="payment-info-title">Zahlungsinformationen</div>
        <div>Bitte überweisen Sie den Rechnungsbetrag unter Angabe der Rechnungsnummer <strong>{{ $invoice->invoice_number }}</strong> auf folgendes Konto:</div>
        <div style="margin-top: 10px;">
            @if($company['bank_name'])<div>{{ $company['bank_name'] }}</div>@endif
            @if($company['bank_iban'])<div>IBAN: {{ $company['bank_iban'] }}</div>@endif
            @if($company['bank_bic'])<div>BIC: {{ $company['bank_bic'] }}</div>@endif
        </div>
    </div>

    {{-- Due Notice --}}
    @if(!$invoice->isPaid() && !$invoice->isCancelled())
        <div class="due-notice">
            <strong>Hinweis:</strong> Bitte begleichen Sie diese Rechnung bis zum {{ $invoice->due_date->format('d.m.Y') }}.
            Bei Fragen zu dieser Rechnung kontaktieren Sie uns bitte unter {{ $company['email'] }}.
        </div>
    @endif

    {{-- Paid Notice --}}
    @if($invoice->isPaid())
        <div class="payment-info" style="background-color: #c6f6d5; border-color: #22543d;">
            <div class="payment-info-title" style="color: #22543d;">Bezahlt</div>
            <div>Diese Rechnung wurde am {{ $invoice->paid_at->format('d.m.Y') }} beglichen.</div>
            @if($invoice->payment_method)
                <div>Zahlungsweise: {{ $invoice->payment_method }}</div>
            @endif
        </div>
    @endif

    {{-- Notes --}}
    @if($invoice->notes)
        <div class="notes-section">
            <strong>Hinweis:</strong><br>
            {{ $invoice->notes }}
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

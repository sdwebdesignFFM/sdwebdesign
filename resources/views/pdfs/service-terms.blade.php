<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Leistungsvereinbarungen - {{ $quote->quote_number }}</title>
    <style>
        @page {
            margin: 2cm 2cm 3cm 2cm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #1a365d;
            padding-bottom: 20px;
        }

        .header-row {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .header-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #1a365d;
            margin-bottom: 5px;
        }

        .document-title {
            font-size: 14pt;
            color: #666;
            margin-top: 10px;
        }

        .quote-info {
            font-size: 9pt;
            color: #666;
        }

        .intro {
            background-color: #f0f9ff;
            padding: 15px;
            border-left: 3px solid #3182ce;
            margin-bottom: 30px;
        }

        .intro-text {
            font-size: 9pt;
            color: #2c5282;
        }

        .service-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .service-header {
            background-color: #1a365d;
            color: white;
            padding: 10px 15px;
            margin-bottom: 0;
        }

        .service-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
        }

        .service-content {
            background-color: #f7f7f7;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
        }

        .service-content h2 {
            font-size: 12pt;
            color: #1a365d;
            margin: 15px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .service-content h2:first-child {
            margin-top: 0;
        }

        .service-content h3 {
            font-size: 11pt;
            color: #333;
            margin: 12px 0 8px 0;
        }

        .service-content p {
            margin: 0 0 10px 0;
        }

        .service-content ul,
        .service-content ol {
            margin: 10px 0;
            padding-left: 25px;
        }

        .service-content li {
            margin-bottom: 5px;
        }

        .service-content strong {
            color: #1a365d;
        }

        .toc {
            background-color: #f7f7f7;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #ddd;
        }

        .toc-title {
            font-size: 12pt;
            font-weight: bold;
            color: #1a365d;
            margin-bottom: 15px;
        }

        .toc-item {
            padding: 5px 0;
            border-bottom: 1px dotted #ddd;
        }

        .toc-item:last-child {
            border-bottom: none;
        }

        .toc-number {
            display: inline-block;
            width: 30px;
            color: #666;
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
                <div class="document-title">Leistungsvereinbarungen</div>
            </div>
            <div class="header-right">
                <div class="quote-info">
                    <p>Angebot: {{ $quote->quote_number }}</p>
                    <p>Kunde: {{ $quote->client_name }}</p>
                    <p>Datum: {{ now()->format('d.m.Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Intro --}}
    <div class="intro">
        <div class="intro-text">
            Dieses Dokument enthält die detaillierten Leistungsvereinbarungen für die im Angebot {{ $quote->quote_number }} enthaltenen Dienstleistungen.
            Diese Vereinbarungen sind Bestandteil des Vertrags und regeln die genauen Leistungsumfänge, Verantwortlichkeiten und Bedingungen.
        </div>
    </div>

    {{-- Table of Contents --}}
    @php
        $itemsWithTerms = $quote->items->filter(fn($item) => $item->hasDetailedTerms() && (!$item->is_optional || $item->is_selected));
    @endphp

    @if($itemsWithTerms->count() > 1)
        <div class="toc">
            <div class="toc-title">Inhalt</div>
            @foreach($itemsWithTerms as $index => $item)
                <div class="toc-item">
                    <span class="toc-number">{{ $index + 1 }}.</span>
                    {{ $item->name }}
                </div>
            @endforeach
        </div>
    @endif

    {{-- Service Terms Sections --}}
    @foreach($itemsWithTerms as $index => $item)
        <div class="service-section">
            <div class="service-header">
                <h1 class="service-title">{{ $index + 1 }}. {{ $item->name }}</h1>
            </div>
            <div class="service-content">
                {!! $item->detailed_terms !!}
            </div>
        </div>
    @endforeach

    {{-- Footer --}}
    <div class="footer">
        {{ config('app.name', 'SD Webdesign') }} | Leistungsvereinbarungen zu Angebot {{ $quote->quote_number }} | Seite <span class="page-number"></span>
    </div>
</body>
</html>

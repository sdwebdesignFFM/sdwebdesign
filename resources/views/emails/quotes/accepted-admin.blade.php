<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angebot angenommen</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #16a34a; padding: 32px 40px;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 600;">
                                Angebot angenommen!
                            </h1>
                            <p style="margin: 8px 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">
                                {{ $quote->accepted_at->format('d.m.Y \u\m H:i') }} Uhr
                            </p>
                        </td>
                    </tr>

                    {{-- Summary --}}
                    <tr>
                        <td style="padding: 32px 40px 24px;">
                            <p style="margin: 0; font-size: 16px; color: #171717; line-height: 1.6;">
                                Das Angebot <strong>{{ $quote->quote_number }}</strong> wurde soeben vom Kunden angenommen.
                            </p>
                        </td>
                    </tr>

                    {{-- Customer Details --}}
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <h3 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Kunde
                            </h3>
                            <div style="background-color: #f9fafb; border-radius: 6px; padding: 16px;">
                                <p style="margin: 0; font-size: 16px; font-weight: 600; color: #171717;">{{ $quote->client_name }}</p>
                                @if($quote->client_company)
                                    <p style="margin: 4px 0 0; font-size: 14px; color: #6b7280;">{{ $quote->client_company }}</p>
                                @endif
                                <p style="margin: 8px 0 0; font-size: 14px;">
                                    <a href="mailto:{{ $quote->client_email }}" style="color: #2563eb; text-decoration: none;">{{ $quote->client_email }}</a>
                                    @if($quote->client_phone)
                                        <br><a href="tel:{{ $quote->client_phone }}" style="color: #2563eb; text-decoration: none;">{{ $quote->client_phone }}</a>
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>

                    {{-- Quote Details --}}
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <h3 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Angebotsdetails
                            </h3>
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f0fdf4; border-radius: 6px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; border-bottom: 1px solid #bbf7d0;">Angebotsnummer</td>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; text-align: right; font-weight: 500; border-bottom: 1px solid #bbf7d0;">{{ $quote->quote_number }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; border-bottom: 1px solid #bbf7d0;">Vertragsnummer</td>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; text-align: right; font-weight: 500; border-bottom: 1px solid #bbf7d0;">{{ $contract->contract_number }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; border-bottom: 1px solid #bbf7d0;">Titel</td>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; text-align: right; font-weight: 500; border-bottom: 1px solid #bbf7d0;">{{ $quote->title }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; border-bottom: 1px solid #bbf7d0;">Typ</td>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; text-align: right; font-weight: 500; border-bottom: 1px solid #bbf7d0;">{{ $quote->type->getLabel() }}</td>
                                </tr>
                                @if($quote->isRecurring())
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; border-bottom: 1px solid #bbf7d0;">Abrechnungszyklus</td>
                                    <td style="padding: 12px 16px; font-size: 14px; color: #166534; text-align: right; font-weight: 500; border-bottom: 1px solid #bbf7d0;">{{ $quote->billing_cycle->getLabel() }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 16px; font-weight: 600; color: #166534;">Gesamtbetrag</td>
                                    <td style="padding: 12px 16px; font-size: 16px; font-weight: 600; color: #166534; text-align: right;">{{ number_format($quote->total, 2, ',', '.') }} &euro;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Billing Address (if provided) --}}
                    @if($quote->hasBillingDetails())
                    <tr>
                        <td style="padding: 0 40px 24px;">
                            <h3 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Rechnungsadresse
                            </h3>
                            <div style="background-color: #f9fafb; border-radius: 6px; padding: 16px;">
                                <p style="margin: 0; font-size: 14px; color: #171717; line-height: 1.6;">
                                    @if($quote->billing_company)
                                        <strong>{{ $quote->billing_company }}</strong><br>
                                    @endif
                                    {{ $quote->billing_name }}<br>
                                    {{ $quote->billing_street }}<br>
                                    {{ $quote->billing_zip }} {{ $quote->billing_city }}
                                    @if($quote->billing_country && $quote->billing_country !== 'Deutschland')
                                        <br>{{ $quote->billing_country }}
                                    @endif
                                    @if($quote->billing_vat_id)
                                        <br><span style="color: #6b7280; font-size: 12px;">USt-IdNr.: {{ $quote->billing_vat_id }}</span>
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endif

                    {{-- Acceptance Details --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <h3 style="margin: 0 0 12px; font-size: 14px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Annahme-Details
                            </h3>
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">Angenommen von:</td>
                                    <td style="padding: 8px 0; font-size: 14px; color: #171717; font-weight: 500;">{{ $quote->accepted_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">Zeitpunkt:</td>
                                    <td style="padding: 8px 0; font-size: 14px; color: #171717;">{{ $quote->accepted_at->format('d.m.Y \u\m H:i') }} Uhr</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">IP-Adresse:</td>
                                    <td style="padding: 8px 0; font-size: 14px; color: #171717;">{{ $quote->accepted_ip }}</td>
                                </tr>
                                @if($quote->hasSignature())
                                <tr>
                                    <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">Unterschrift:</td>
                                    <td style="padding: 8px 0; font-size: 14px; color: #16a34a; font-weight: 500;">Digital unterzeichnet</td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ url('/admin/quotes/quotes/' . $quote->id . '/edit') }}"
                               style="display: inline-block; padding: 14px 28px; background-color: #171717; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500;">
                                Angebot im Admin öffnen
                            </a>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f3f4f6; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                                Diese E-Mail wurde automatisch generiert.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

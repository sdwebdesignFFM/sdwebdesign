<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auftragsbestätigung</title>
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
                                Ihre Auftragsbestätigung
                            </h1>
                            <p style="margin: 8px 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">
                                Der Vertrag ist vollständig unterzeichnet.
                            </p>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding: 32px 40px 24px;">
                            <p style="margin: 0; font-size: 16px; color: #171717; line-height: 1.6;">
                                @if($quote->client_company)
                                    Guten Tag {{ $quote->client_name }} ({{ $quote->client_company }}),
                                @else
                                    Guten Tag {{ $quote->client_name }},
                                @endif
                            </p>
                            <p style="margin: 16px 0 0; font-size: 14px; color: #404040; line-height: 1.6;">
                                vielen Dank für Ihren Auftrag! Im Anhang finden Sie Ihre vollständig unterzeichnete Auftragsbestätigung als PDF-Dokument.
                            </p>
                        </td>
                    </tr>

                    {{-- Contract Details --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background-color: #f0fdf4; border-radius: 8px; padding: 24px; border: 1px solid #bbf7d0;">
                                <h2 style="margin: 0 0 16px; font-size: 18px; font-weight: 600; color: #166534;">
                                    Vertragsdetails
                                </h2>

                                <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #166534;">Vertragsnummer:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #166534; text-align: right; font-weight: 500;">{{ $contract->contract_number }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #166534;">Titel:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #166534; text-align: right; font-weight: 500;">{{ $quote->title }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #166534;">Unterzeichnet am:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #166534; text-align: right; font-weight: 500;">{{ $quote->admin_signed_at->format('d.m.Y \u\m H:i') }} Uhr</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="border-top: 1px solid #bbf7d0; padding-top: 12px;"></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 16px; font-weight: 600; color: #166534;">Gesamtbetrag:</td>
                                        <td style="padding: 8px 0; font-size: 16px; font-weight: 600; color: #166534; text-align: right;">{{ number_format($quote->total, 2, ',', '.') }} &euro;</td>
                                    </tr>
                                    @if($quote->isRecurring())
                                    <tr>
                                        <td style="padding: 8px 0; font-size: 14px; color: #166534;">Abrechnungszyklus:</td>
                                        <td style="padding: 8px 0; font-size: 14px; color: #166534; text-align: right; font-weight: 500;">{{ $quote->billing_cycle->getLabel() }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- Signature Confirmation --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="display: flex; gap: 16px;">
                                <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="width: 50%; padding: 12px; background-color: #f9fafb; border-radius: 6px; vertical-align: top;">
                                            <p style="margin: 0 0 4px; font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Auftraggeber</p>
                                            <p style="margin: 0; font-size: 14px; font-weight: 500; color: #171717;">{{ $quote->accepted_name }}</p>
                                            <p style="margin: 4px 0 0; font-size: 12px; color: #6b7280;">{{ $quote->signature_at->format('d.m.Y') }}</p>
                                        </td>
                                        <td style="width: 8px;"></td>
                                        <td style="width: 50%; padding: 12px; background-color: #f9fafb; border-radius: 6px; vertical-align: top;">
                                            <p style="margin: 0 0 4px; font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Auftragnehmer</p>
                                            <p style="margin: 0; font-size: 14px; font-weight: 500; color: #171717;">{{ $quote->admin_signature_name }}</p>
                                            <p style="margin: 4px 0 0; font-size: 12px; color: #6b7280;">{{ $quote->admin_signature_position }} &bull; {{ $quote->admin_signed_at->format('d.m.Y') }}</p>
                                        </td>
                                    </tr>
                                </table>
                            </div>
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

                    {{-- PDF Attachment Note --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <div style="background-color: #f0fdf4; border-radius: 6px; padding: 16px; border-left: 4px solid #16a34a;">
                                <p style="margin: 0; font-size: 14px; color: #166534;">
                                    <strong>Anhang:</strong> Im Anhang dieser E-Mail finden Sie Ihre beidseitig unterschriebene Auftragsbestätigung als PDF-Dokument zur Aufbewahrung.
                                </p>
                            </div>
                        </td>
                    </tr>

                    {{-- Next Steps --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <h3 style="margin: 0 0 16px; font-size: 14px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Wie geht es weiter?
                            </h3>
                            <ol style="margin: 0; padding-left: 20px; font-size: 14px; color: #404040; line-height: 1.8;">
                                @if($quote->isRecurring())
                                    <li>Die erste Rechnung wird Ihnen in Kürze zugestellt.</li>
                                    <li>Nach Zahlungseingang werden wir die vereinbarten Leistungen umgehend aktivieren.</li>
                                @else
                                    <li>Wir werden uns in Kürze bei Ihnen melden, um das Projekt zu starten.</li>
                                    <li>Falls gewünscht, vereinbaren wir gerne einen Termin für ein Kick-off-Gespräch.</li>
                                @endif
                                <li>Bei Fragen stehen wir Ihnen jederzeit zur Verfügung.</li>
                            </ol>
                        </td>
                    </tr>

                    {{-- Contact Info --}}
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f9fafb; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; font-size: 14px; color: #6b7280;">
                                Haben Sie Fragen?
                            </p>
                            <p style="margin: 8px 0 0; font-size: 14px; color: #171717;">
                                Kontaktieren Sie uns gerne:
                                @if($settings->email)
                                    <a href="mailto:{{ $settings->email }}" style="color: #2563eb; text-decoration: none;">{{ $settings->email }}</a>
                                @endif
                                @if($settings->phone)
                                    | <a href="tel:{{ $settings->phone }}" style="color: #2563eb; text-decoration: none;">{{ $settings->phone }}</a>
                                @endif
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f3f4f6; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                                {{ $settings->company_name ?? 'sdwebdesign' }}<br>
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

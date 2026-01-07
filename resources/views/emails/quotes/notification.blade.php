<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihr Angebot von {{ $settings->company_name ?? 'sdwebdesign' }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #171717; padding: 32px 40px;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 600;">
                                Ihr Angebot ist fertig
                            </h1>
                            <p style="margin: 8px 0 0; color: rgba(255,255,255,0.7); font-size: 14px;">
                                {{ $quote->quote_number }} | Erstellt am {{ $quote->created_at->format('d.m.Y') }}
                            </p>
                        </td>
                    </tr>

                    {{-- Greeting --}}
                    <tr>
                        <td style="padding: 32px 40px 24px;">
                            <p style="margin: 0; font-size: 16px; color: #171717; line-height: 1.6;">
                                {{ $quote->getEmailGreeting() }}
                            </p>
                            <p style="margin: 16px 0 0; font-size: 14px; color: #404040; line-height: 1.6;">
                                vielen Dank für Ihr Interesse an unseren Leistungen. Ihr individuelles Angebot liegt für Sie bereit.
                            </p>
                        </td>
                    </tr>

                    {{-- Validity Notice --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <p style="margin: 0; font-size: 14px; color: #404040;">
                                Das Angebot ist bis einschließlich {{ $quote->valid_until->format('d.m.Y') }} gültig.
                            </p>
                        </td>
                    </tr>

                    {{-- CTA Button --}}
                    <tr>
                        <td style="padding: 0 40px 32px; text-align: center;">
                            <a href="{{ $quoteUrl }}"
                               style="display: inline-block; padding: 16px 32px; background-color: #16a34a; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: 600;">
                                Angebot ansehen & annehmen
                            </a>
                            <p style="margin: 16px 0 0; font-size: 12px; color: #9ca3af;">
                                Oder kopieren Sie diesen Link:<br>
                                <a href="{{ $quoteUrl }}" style="color: #2563eb; word-break: break-all;">{{ $quoteUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    {{-- What's Next --}}
                    <tr>
                        <td style="padding: 0 40px 32px;">
                            <h3 style="margin: 0 0 16px; font-size: 14px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">
                                Wie geht es weiter?
                            </h3>
                            <ol style="margin: 0; padding-left: 20px; font-size: 14px; color: #404040; line-height: 1.8;">
                                <li>Klicken Sie auf den Button oben, um das vollständige Angebot anzusehen</li>
                                <li>Passen Sie optionale Leistungen nach Ihren Wünschen an</li>
                                <li>Akzeptieren Sie das Angebot mit Ihrem Namen</li>
                                <li>Sie erhalten eine Bestätigung und wir starten gemeinsam</li>
                            </ol>
                        </td>
                    </tr>

                    {{-- Contact Info --}}
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f9fafb; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; font-size: 14px; color: #6b7280;">
                                Haben Sie Fragen zu diesem Angebot?
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
                                Diese E-Mail wurde automatisch von {{ $settings->company_name ?? 'sdwebdesign' }} generiert.
                            </p>
                            <p style="margin: 8px 0 0; font-size: 11px; color: #9ca3af;">
                                Dieser Link ist persönlich für Sie. Bitte geben Sie ihn nicht weiter.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

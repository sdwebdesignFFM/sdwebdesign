<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ihre Anfrage bei {{ $settings->company_name ?? 'sdwebdesign' }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f5f5f5;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color: #171717; padding: 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 600;">
                                Vielen Dank, {{ $data['name'] }}!
                            </h1>
                            <p style="margin: 12px 0 0; color: rgba(255,255,255,0.7); font-size: 15px;">
                                Ihre Projektanfrage ist bei uns eingegangen.
                            </p>
                        </td>
                    </tr>

                    {{-- Main Message --}}
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.7; color: #404040;">
                                wir haben Ihre Anfrage erhalten und werden uns innerhalb von <strong>24 Stunden</strong> bei Ihnen melden.
                            </p>

                            <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.7; color: #404040;">
                                In der Zwischenzeit bereiten wir uns auf unser Gesprach vor und analysieren Ihre Anforderungen.
                            </p>

                            {{-- What happens next --}}
                            <div style="margin: 32px 0; padding: 24px; background-color: #fafafa; border-radius: 8px;">
                                <h2 style="margin: 0 0 16px; font-size: 14px; font-weight: 600; color: #737373; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Wie es weitergeht
                                </h2>
                                <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                    <tr>
                                        <td style="padding: 10px 0; vertical-align: top; width: 40px;">
                                            <span style="display: inline-block; width: 24px; height: 24px; background-color: #171717; color: #fff; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: 600;">1</span>
                                        </td>
                                        <td style="padding: 10px 0; vertical-align: top; font-size: 14px; color: #404040;">
                                            <strong>Anfrage-Analyse:</strong> Wir sichten Ihre Anforderungen und bereiten passende Losungsvorschlage vor.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; vertical-align: top;">
                                            <span style="display: inline-block; width: 24px; height: 24px; background-color: #171717; color: #fff; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: 600;">2</span>
                                        </td>
                                        <td style="padding: 10px 0; vertical-align: top; font-size: 14px; color: #404040;">
                                            <strong>Kontaktaufnahme:</strong> Wir melden uns in der Regel innerhalb von 24 Stunden per E-Mail oder Telefon.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 0; vertical-align: top;">
                                            <span style="display: inline-block; width: 24px; height: 24px; background-color: #171717; color: #fff; border-radius: 50%; text-align: center; line-height: 24px; font-size: 12px; font-weight: 600;">3</span>
                                        </td>
                                        <td style="padding: 10px 0; vertical-align: top; font-size: 14px; color: #404040;">
                                            <strong>Kostenlose Beratung:</strong> In einem unverbindlichen Gesprach besprechen wir Ihr Projekt im Detail.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>

                    {{-- Summary --}}
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <div style="border: 1px solid #e5e5e5; border-radius: 8px; overflow: hidden;">
                                <div style="padding: 16px 20px; background-color: #fafafa; border-bottom: 1px solid #e5e5e5;">
                                    <h3 style="margin: 0; font-size: 14px; font-weight: 600; color: #171717;">
                                        Ihre Angaben im Uberblick
                                    </h3>
                                </div>
                                <div style="padding: 20px;">
                                    <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding: 8px 0; vertical-align: top; width: 120px; color: #737373; font-size: 13px;">Projekttyp(en):</td>
                                            <td style="padding: 8px 0; vertical-align: top; font-size: 13px; color: #171717;">
                                                {{ implode(', ', $data['projectTypes']) }}
                                            </td>
                                        </tr>
                                        @if($data['budget'])
                                        <tr>
                                            <td style="padding: 8px 0; vertical-align: top; color: #737373; font-size: 13px;">Budget:</td>
                                            <td style="padding: 8px 0; vertical-align: top; font-size: 13px; color: #171717;">{{ $data['budget'] }}</td>
                                        </tr>
                                        @endif
                                        @if($data['timeline'])
                                        <tr>
                                            <td style="padding: 8px 0; vertical-align: top; color: #737373; font-size: 13px;">Zeitrahmen:</td>
                                            <td style="padding: 8px 0; vertical-align: top; font-size: 13px; color: #171717;">{{ $data['timeline'] }}</td>
                                        </tr>
                                        @endif
                                        @if(!empty($data['callbackDays']) || !empty($data['callbackTime']))
                                        <tr>
                                            <td style="padding: 8px 0; vertical-align: top; color: #737373; font-size: 13px;">Rückruf:</td>
                                            <td style="padding: 8px 0; vertical-align: top; font-size: 13px; color: #171717;">
                                                @if(!empty($data['callbackDays'])){{ implode(', ', $data['callbackDays']) }}@endif
                                                @if(!empty($data['callbackDays']) && !empty($data['callbackTime'])), @endif
                                                @if(!empty($data['callbackTime'])){{ $data['callbackTime'] }}@endif
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Contact Person --}}
                    <tr>
                        <td style="padding: 0 40px 40px;">
                            <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f0f9ff; border-radius: 8px; padding: 24px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <p style="margin: 0 0 4px; font-size: 12px; font-weight: 600; color: #0369a1; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Ihr Ansprechpartner
                                        </p>
                                        <p style="margin: 0 0 4px; font-size: 16px; font-weight: 600; color: #171717;">
                                            {{ $settings->cta_name ?? $settings->owner_name ?? 'Steffen Fasselt' }}
                                        </p>
                                        <p style="margin: 0 0 16px; font-size: 13px; color: #737373;">
                                            {{ $settings->cta_role ?? 'Geschaftsfuhrer' }}
                                        </p>
                                        @if($settings->phone || $settings->mobile)
                                        <p style="margin: 0; font-size: 14px;">
                                            <a href="tel:{{ $settings->mobile ?? $settings->phone }}" style="color: #0369a1; text-decoration: none;">
                                                {{ $settings->mobile ?? $settings->phone }}
                                            </a>
                                        </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 40px; background-color: #171717; text-align: center;">
                            <p style="margin: 0 0 8px; font-size: 14px; font-weight: 500; color: #ffffff;">
                                {{ $settings->company_name ?? 'sdwebdesign' }}
                            </p>
                            @if($settings->street || $settings->city)
                            <p style="margin: 0 0 8px; font-size: 13px; color: rgba(255,255,255,0.6);">
                                {{ $settings->street }}@if($settings->street && $settings->city), @endif{{ $settings->postal_code }} {{ $settings->city }}
                            </p>
                            @endif
                            <p style="margin: 16px 0 0; font-size: 12px; color: rgba(255,255,255,0.5);">
                                @if($settings->email)
                                <a href="mailto:{{ $settings->email }}" style="color: rgba(255,255,255,0.6); text-decoration: none;">{{ $settings->email }}</a>
                                @endif
                                @if($settings->email && $settings->phone) &nbsp;|&nbsp; @endif
                                @if($settings->phone)
                                <a href="tel:{{ $settings->phone }}" style="color: rgba(255,255,255,0.6); text-decoration: none;">{{ $settings->phone }}</a>
                                @endif
                            </p>
                        </td>
                    </tr>
                </table>

                {{-- Legal Footer --}}
                <table role="presentation" style="max-width: 600px; margin: 24px auto 0;">
                    <tr>
                        <td style="text-align: center;">
                            <p style="margin: 0; font-size: 11px; color: #a3a3a3; line-height: 1.6;">
                                Diese E-Mail wurde automatisch versendet. Bitte antworten Sie nicht direkt auf diese Nachricht.
                                <br>
                                &copy; {{ date('Y') }} {{ $settings->company_name ?? 'sdwebdesign' }}. Alle Rechte vorbehalten.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

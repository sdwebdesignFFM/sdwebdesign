<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neue Projektanfrage</title>
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
                                Neue Projektanfrage
                            </h1>
                            <p style="margin: 8px 0 0; color: rgba(255,255,255,0.7); font-size: 14px;">
                                Eingang: {{ now()->format('d.m.Y \u\m H:i') }} Uhr
                            </p>
                        </td>
                    </tr>

                    {{-- Contact Info --}}
                    <tr>
                        <td style="padding: 32px 40px; border-bottom: 1px solid #e5e5e5;">
                            <h2 style="margin: 0 0 16px; font-size: 14px; font-weight: 600; color: #737373; text-transform: uppercase; letter-spacing: 0.5px;">
                                Kontaktdaten
                            </h2>
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; width: 120px; color: #737373; font-size: 14px;">Name:</td>
                                    <td style="padding: 8px 0; vertical-align: top; font-size: 14px; font-weight: 500; color: #171717;">{{ $data['name'] }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; color: #737373; font-size: 14px;">E-Mail:</td>
                                    <td style="padding: 8px 0; vertical-align: top; font-size: 14px;">
                                        <a href="mailto:{{ $data['email'] }}" style="color: #2563eb; text-decoration: none;">{{ $data['email'] }}</a>
                                    </td>
                                </tr>
                                @if($data['company'])
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; color: #737373; font-size: 14px;">Unternehmen:</td>
                                    <td style="padding: 8px 0; vertical-align: top; font-size: 14px; color: #171717;">{{ $data['company'] }}</td>
                                </tr>
                                @endif
                                @if($data['phone'])
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; color: #737373; font-size: 14px;">Telefon:</td>
                                    <td style="padding: 8px 0; vertical-align: top; font-size: 14px;">
                                        <a href="tel:{{ $data['phone'] }}" style="color: #2563eb; text-decoration: none;">{{ $data['phone'] }}</a>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    {{-- Project Details --}}
                    <tr>
                        <td style="padding: 32px 40px; border-bottom: 1px solid #e5e5e5;">
                            <h2 style="margin: 0 0 16px; font-size: 14px; font-weight: 600; color: #737373; text-transform: uppercase; letter-spacing: 0.5px;">
                                Projektdetails
                            </h2>

                            {{-- Project Types --}}
                            <div style="margin-bottom: 20px;">
                                <p style="margin: 0 0 8px; font-size: 13px; color: #737373;">Projekttyp(en):</p>
                                @foreach($data['projectTypes'] as $type)
                                <span style="display: inline-block; padding: 6px 12px; margin: 0 6px 6px 0; background-color: #f5f5f5; border-radius: 4px; font-size: 13px; color: #171717;">
                                    {{ $type }}
                                </span>
                                @endforeach
                            </div>

                            {{-- Budget & Timeline --}}
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                @if($data['budget'])
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; width: 120px; color: #737373; font-size: 14px;">Budget:</td>
                                    <td style="padding: 8px 0; vertical-align: top; font-size: 14px; color: #171717;">
                                        <span style="display: inline-block; padding: 4px 10px; background-color: #dcfce7; color: #166534; border-radius: 4px; font-weight: 500;">
                                            {{ $data['budget'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                @if($data['timeline'])
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; color: #737373; font-size: 14px;">Zeitrahmen:</td>
                                    <td style="padding: 8px 0; vertical-align: top; font-size: 14px; color: #171717;">{{ $data['timeline'] }}</td>
                                </tr>
                                @endif
                                @if(!empty($data['callbackDays']) || !empty($data['callbackTime']))
                                <tr>
                                    <td style="padding: 8px 0; vertical-align: top; color: #737373; font-size: 14px;">Rückruf:</td>
                                    <td style="padding: 8px 0; vertical-align: top; font-size: 14px; color: #171717;">
                                        <span style="display: inline-block; padding: 4px 10px; background-color: #dbeafe; color: #1e40af; border-radius: 4px; font-weight: 500;">
                                            @if(!empty($data['callbackDays'])){{ implode(', ', $data['callbackDays']) }}@endif
                                            @if(!empty($data['callbackDays']) && !empty($data['callbackTime'])), @endif
                                            @if(!empty($data['callbackTime'])){{ $data['callbackTime'] }}@endif
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    {{-- Project Description --}}
                    @if($data['projectDescription'])
                    <tr>
                        <td style="padding: 32px 40px; border-bottom: 1px solid #e5e5e5;">
                            <h2 style="margin: 0 0 16px; font-size: 14px; font-weight: 600; color: #737373; text-transform: uppercase; letter-spacing: 0.5px;">
                                Projektbeschreibung
                            </h2>
                            <div style="padding: 16px; background-color: #fafafa; border-radius: 6px; border-left: 3px solid #171717;">
                                <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #404040; white-space: pre-wrap;">{{ $data['projectDescription'] }}</p>
                            </div>
                        </td>
                    </tr>
                    @endif

                    {{-- Quick Actions --}}
                    <tr>
                        <td style="padding: 32px 40px; text-align: center;">
                            <a href="mailto:{{ $data['email'] }}?subject=Re: Ihre Projektanfrage bei {{ $settings->company_name ?? 'sdwebdesign' }}"
                               style="display: inline-block; padding: 14px 28px; background-color: #171717; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500;">
                                Direkt antworten
                            </a>
                            @if($data['phone'])
                            <a href="tel:{{ $data['phone'] }}"
                               style="display: inline-block; padding: 14px 28px; margin-left: 12px; background-color: #ffffff; color: #171717; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500; border: 1px solid #e5e5e5;">
                                Anrufen
                            </a>
                            @endif
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 40px; background-color: #fafafa; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #737373;">
                                Diese E-Mail wurde automatisch von {{ $settings->company_name ?? 'sdwebdesign' }} generiert.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

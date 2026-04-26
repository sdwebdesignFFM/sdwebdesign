<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Workshop-Anfrage Plattform-Discovery</title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;background:#f5f5f5;color:#1a202c;">
<table role="presentation" style="width:100%;border-collapse:collapse;">
    <tr>
        <td style="padding:32px 16px;">
            <table role="presentation" style="max-width:680px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;">
                <tr>
                    <td style="background:#171717;padding:28px 32px;color:#fff;">
                        <p style="margin:0;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;opacity:.6;">Workshop-Anfrage · Plattform-Discovery</p>
                        <h1 style="margin:6px 0 0;font-size:22px;font-weight:600;">{{ $request->name }}@if($request->company) · {{ $request->company }}@endif</h1>
                        <p style="margin:8px 0 0;font-size:14px;opacity:.75;">
                            <a href="mailto:{{ $request->email }}" style="color:inherit;text-decoration:underline;">{{ $request->email }}</a>
                            @if($request->phone) · <a href="tel:{{ $request->phone }}" style="color:inherit;text-decoration:underline;">{{ $request->phone }}</a>@endif
                            @if($request->role) · {{ $request->role }}@endif
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:28px 32px;">
                        @if($request->trigger_question)
                        <p style="margin:0 0 6px;font-size:11px;letter-spacing:1.2px;text-transform:uppercase;color:#64748b;">Anlass / Ausgangsfrage</p>
                        <p style="margin:0 0 24px;font-size:15px;line-height:1.6;background:#f8fafc;padding:14px 16px;border-left:3px solid #171717;">{{ $request->trigger_question }}</p>
                        @endif

                        <table role="presentation" style="width:100%;border-collapse:collapse;font-size:14px;">
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;width:200px;color:#64748b;">Branche</td>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;">{{ $request->industry ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;color:#64748b;">Workflow-Bereiche</td>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;">
                                    @if(! empty($request->workflow_areas))
                                        {{ implode(', ', $request->workflow_areas) }}
                                    @else — @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;color:#64748b;">Bestandssysteme</td>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;">
                                    @if(! empty($request->existing_systems))
                                        {{ implode(', ', $request->existing_systems) }}
                                    @else — @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;color:#64748b;">Stand der Recherche</td>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;"><strong>{{ $request->procurement_stage ?: '—' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;color:#64748b;">Budget-Indikation</td>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;"><strong>{{ $request->budget_indication ?: '—' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;color:#64748b;">Wann produktiv?</td>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;">{{ $request->go_live_timeline ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;color:#64748b;">Unternehmensgröße</td>
                                <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;">{{ $request->company_size ?: '—' }}</td>
                            </tr>
                        </table>

                        <p style="margin:24px 0 8px;font-size:11px;letter-spacing:1.2px;text-transform:uppercase;color:#64748b;">Workshop-Präferenz</p>
                        <table role="presentation" style="width:100%;border-collapse:collapse;font-size:14px;background:#f8fafc;">
                            <tr>
                                <td style="padding:10px 14px;width:200px;color:#64748b;">Format</td>
                                <td style="padding:10px 14px;"><strong>{{ $request->workshop_format ?: '—' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:10px 14px;color:#64748b;">Termin-Wunsch</td>
                                <td style="padding:10px 14px;"><strong>{{ $request->preferred_timing ?: '—' }}</strong></td>
                            </tr>
                            <tr>
                                <td style="padding:10px 14px;color:#64748b;">Bevorzugte Tageszeit</td>
                                <td style="padding:10px 14px;">{{ $request->preferred_daytime ?: '—' }}</td>
                            </tr>
                        </table>

                        @if($request->briefing_notes)
                        <p style="margin:24px 0 6px;font-size:11px;letter-spacing:1.2px;text-transform:uppercase;color:#64748b;">Vorab-Briefing / Notizen</p>
                        <p style="margin:0;font-size:14px;line-height:1.6;background:#f8fafc;padding:14px 16px;white-space:pre-wrap;">{{ $request->briefing_notes }}</p>
                        @endif

                        <p style="margin:28px 0 0;font-size:12px;color:#a3a3a3;border-top:1px solid #e5e7eb;padding-top:14px;">
                            Eingegangen am {{ $request->created_at->format('d.m.Y · H:i') }} ·
                            IP {{ $request->ip ?? '—' }} ·
                            Lead-ID #{{ $request->id }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

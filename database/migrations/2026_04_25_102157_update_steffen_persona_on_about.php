<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Phase A.2 — Update About page to reflect the new positioning:
 * Steffen Fasselt visibly positioned as Senior Product Owner & Plattform-
 * Architekt (not "Gründer · Webentwickler"), with a personal-voice paragraph
 * in the Haltung section that explains the 20-year Unternehmer + 10+ year PO
 * background.
 *
 * Deliberate: only touches Steffen's team entry and adds one paragraph to
 * Haltung. Other team members, sections and texts stay untouched. Editorial
 * Filament edits to those will not be overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        $about = Page::where('type', Page::TYPE_ABOUT)->first();
        if (! $about) {
            return;
        }

        $content = $about->getTranslation('content', 'de') ?? [];

        // 1. Update Steffen's team-entry — role + description match new positioning
        if (isset($content['team']['members']) && is_array($content['team']['members'])) {
            foreach ($content['team']['members'] as $i => $member) {
                if (($member['name'] ?? '') === 'Steffen Fasselt') {
                    $content['team']['members'][$i]['role'] = 'Senior Product Owner & Plattform-Architekt';
                    $content['team']['members'][$i]['description'] = 'Seit 20 Jahren Unternehmer, seit über 10 Jahren als Product Owner für Mittelständler. Schwerpunkt: maßgeschneiderte B2B-Plattformen, die mit dem Geschäft mitwachsen.';
                    break;
                }
            }
        }

        // 2. Append the longer Variante B bio to Haltung paragraphs as a personal voice
        $haltungBio = 'Persönlich von Steffen Fasselt: Seit 20 Jahren bin ich Unternehmer. Seit über 10 Jahren begleite ich Unternehmen als Product Owner durch ihre Wachstumsphasen — über Monate und Jahre, nicht über einzelne Projekte. Daraus ist eine klare Spezialisierung gewachsen: Ich entwickle für etablierte Mittelständler die maßgeschneiderten Plattformen, die ihre operativen Workflows tragen. Bestellplattformen, Vermittlungsplattformen, Kundenportale, interne Service-Tools — immer dann, wenn Standard-Software an Grenzen stößt und eine eigene Lösung mit dem Geschäft mitwachsen muss.';

        $existing = $content['haltung']['paragraphs'] ?? [];
        // Make idempotent: don't append twice
        $alreadyAppended = false;
        foreach ($existing as $p) {
            if (str_contains($p, 'Persönlich von Steffen Fasselt')) {
                $alreadyAppended = true;
                break;
            }
        }
        if (! $alreadyAppended) {
            $existing[] = $haltungBio;
        } else {
            // Idempotent overwrite: replace the existing bio paragraph in case it changed
            foreach ($existing as $idx => $p) {
                if (str_contains($p, 'Persönlich von Steffen Fasselt')) {
                    $existing[$idx] = $haltungBio;
                    break;
                }
            }
        }
        $content['haltung']['paragraphs'] = $existing;

        $about->setTranslation('content', 'de', $content);
        $about->save();
    }

    public function down(): void
    {
        $about = Page::where('type', Page::TYPE_ABOUT)->first();
        if (! $about) {
            return;
        }

        $content = $about->getTranslation('content', 'de') ?? [];

        // Restore Steffen's previous role/description
        if (isset($content['team']['members']) && is_array($content['team']['members'])) {
            foreach ($content['team']['members'] as $i => $member) {
                if (($member['name'] ?? '') === 'Steffen Fasselt') {
                    $content['team']['members'][$i]['role'] = 'Gründer · Webentwickler';
                    $content['team']['members'][$i]['description'] = 'Planung, Architektur und Entwicklung digitaler Systeme sind seit vielen Jahren mein Schwerpunkt.';
                    break;
                }
            }
        }

        // Remove the appended Steffen-bio paragraph from Haltung
        $paragraphs = $content['haltung']['paragraphs'] ?? [];
        $content['haltung']['paragraphs'] = array_values(array_filter(
            $paragraphs,
            fn ($p) => ! str_contains($p, 'Persönlich von Steffen Fasselt')
        ));

        $about->setTranslation('content', 'de', $content);
        $about->save();
    }
};

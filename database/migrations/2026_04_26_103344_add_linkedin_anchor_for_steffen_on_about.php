<?php

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

/**
 * Phase D — Personal-brand foundation: anchor Steffen's LinkedIn profile
 * on the About page so visitors and AI search engines can cross-verify
 * the named principal of the agency.
 *
 * Two parallel signals:
 *   1. Visible "Auf LinkedIn folgen" anchor on Steffen's team card
 *      (rendered by about.blade.php when team.members[i].linkedin is set).
 *   2. Org-wide settings.linkedin_url, which feeds the existing
 *      Organization sameAs ladder (homepage + contact JSON-LD) and the
 *      new Person schema (about JSON-LD).
 *
 * Default URL is the most likely handle (linkedin.com/in/steffenfasselt).
 * If wrong, an editor can correct it via Filament Settings or by editing
 * the About page member directly — both signals stay in sync because the
 * Person schema prefers the per-member field but falls back to the global
 * setting.
 */
return new class extends Migration
{
    private const DEFAULT_LINKEDIN_URL = 'https://www.linkedin.com/in/steffenfasselt/';

    public function up(): void
    {
        $about = Page::where('type', Page::TYPE_ABOUT)->first();
        if ($about) {
            $content = $about->getTranslation('content', 'de') ?? [];

            if (isset($content['team']['members']) && is_array($content['team']['members'])) {
                foreach ($content['team']['members'] as $i => $member) {
                    if (($member['name'] ?? '') === 'Steffen Fasselt' && empty($member['linkedin'])) {
                        $content['team']['members'][$i]['linkedin'] = self::DEFAULT_LINKEDIN_URL;
                        break;
                    }
                }
            }

            $about->setTranslation('content', 'de', $content);
            $about->save();
        }

        // Only seed the global setting if it is currently empty — never
        // overwrite an editor-set value.
        $settings = Setting::first();
        if ($settings && empty($settings->linkedin_url)) {
            $settings->linkedin_url = self::DEFAULT_LINKEDIN_URL;
            $settings->save();
        }
    }

    public function down(): void
    {
        $about = Page::where('type', Page::TYPE_ABOUT)->first();
        if (! $about) {
            return;
        }

        $content = $about->getTranslation('content', 'de') ?? [];
        if (isset($content['team']['members']) && is_array($content['team']['members'])) {
            foreach ($content['team']['members'] as $i => $member) {
                if (($member['name'] ?? '') === 'Steffen Fasselt') {
                    unset($content['team']['members'][$i]['linkedin']);
                    break;
                }
            }
        }
        $about->setTranslation('content', 'de', $content);
        $about->save();
    }
};

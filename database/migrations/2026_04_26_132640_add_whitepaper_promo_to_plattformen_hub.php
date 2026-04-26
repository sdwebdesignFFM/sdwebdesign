<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Surface the "Eigene Plattform oder Standard-Software?" whitepaper
 * on the Plattformen hub page. Until now the lead magnet was only
 * reachable via direct URL or the sitemap — no visitor would find it.
 *
 * The hub is the highest-intent page in the platform funnel: anyone
 * landing there is already evaluating a platform decision, which is
 * exactly the audience the whitepaper is written for. The promo
 * block sits between the last content section and the bottom CTA.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = Page::where('type', Page::TYPE_SOLUTION_HUB)
            ->where('slug->de', 'plattformen')
            ->whereNull('parent_id')
            ->first();
        if (! $page) {
            return;
        }

        $content = $page->getTranslation('content', 'de') ?? [];

        $content['whitepaper_promo'] = [
            'label' => 'Vorab-Lektüre · Whitepaper',
            'title' => 'Eigene Plattform oder Standard-Software? Ein Entscheidungsleitfaden für Mittelständler.',
            'text' => 'Bevor Sie das erste Angebot einholen oder einen Workshop buchen — lesen Sie den 12-seitigen Entscheidungsleitfaden. Drei Software-Schichten, vier Schlüsselfragen, eine Roadmap-Schablone. Strukturiert, neutral, ohne Sales-Pitch.',
            'button_text' => 'Whitepaper kostenlos anfordern',
            'link' => '/whitepaper/eigene-plattform-vs-standard-software',
        ];

        $page->setTranslation('content', 'de', $content);
        $page->save();
    }

    public function down(): void
    {
        $page = Page::where('type', Page::TYPE_SOLUTION_HUB)
            ->where('slug->de', 'plattformen')
            ->whereNull('parent_id')
            ->first();
        if (! $page) {
            return;
        }

        $content = $page->getTranslation('content', 'de') ?? [];
        unset($content['whitepaper_promo']);
        $page->setTranslation('content', 'de', $content);
        $page->save();
    }
};

<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Three improvements to the Plattform-Discovery page:
 *
 *   1. Hero gets its own visible CTA button. Until now visitors had
 *      to scroll past 7 sections to reach the only "Workshop anfragen"
 *      button — too far for a paid lead-magnet that's supposed to
 *      filter early.
 *
 *   2. The generic "Betrieb, Hosting & Wartung" block is hidden on
 *      this page. It is fitting on most solution-detail pages but
 *      semantically off on a workshop-sales page where the deliverable
 *      is a one-time PDF, not a long-running service.
 *
 *   3. (Defensive) Re-affirms the modal_event/payload set in the
 *      previous migration so re-running this stack stays consistent.
 */
return new class extends Migration
{
    public function up(): void
    {
        $page = Page::where('slug->de', 'plattform-discovery')->first();
        if (! $page) {
            return;
        }

        $content = $page->getTranslation('content', 'de') ?? [];

        $content['hide_maintenance_block'] = true;

        $hero = $content['hero'] ?? [];
        $hero['cta_text'] = 'Workshop anfragen';
        $hero['cta_subtext'] = '2 Stunden · 990 € Festpreis · dokumentiertes Ergebnis';
        $content['hero'] = $hero;

        $cta = $content['cta'] ?? [];
        $cta['modal_event'] = 'openWorkshopRequestModal';
        $cta['modal_payload'] = ['slug' => 'plattform-discovery'];
        $content['cta'] = $cta;

        $page->setTranslation('content', 'de', $content);
        $page->save();
    }

    public function down(): void
    {
        $page = Page::where('slug->de', 'plattform-discovery')->first();
        if (! $page) {
            return;
        }

        $content = $page->getTranslation('content', 'de') ?? [];
        unset($content['hide_maintenance_block']);
        if (isset($content['hero'])) {
            unset($content['hero']['cta_text'], $content['hero']['cta_subtext']);
        }
        $page->setTranslation('content', 'de', $content);
        $page->save();
    }
};

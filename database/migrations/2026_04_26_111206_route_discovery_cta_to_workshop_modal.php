<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * The Plattform-Discovery page's CTA button used to dispatch the
 * generic ContactModal — same modal as every other contact entry.
 * That modal asks generic project-discovery questions ("which project
 * type / which budget bucket / which timeline") that are not what
 * Steffen actually needs to prepare a paid 2-hour Discovery workshop.
 *
 * Solution-detail.blade.php now reads `cta.modal_event` from the page
 * content and dispatches that event instead, falling back to the
 * generic openContactModal when not set. This migration sets the
 * Discovery page's CTA to dispatch openWorkshopRequestModal, which
 * opens the new 4-step Workshop-Anfrage form (vorhaben → stand →
 * format → kontakt) — collecting exactly the briefing inputs Steffen
 * needs before the call.
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
        if (isset($content['cta'])) {
            unset($content['cta']['modal_event'], $content['cta']['modal_payload']);
            $page->setTranslation('content', 'de', $content);
            $page->save();
        }
    }
};

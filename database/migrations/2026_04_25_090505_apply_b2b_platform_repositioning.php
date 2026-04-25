<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Apply the strategic repositioning to "B2B Platform Partner for established
 * Mittelstand" decided in the strategy session.
 *
 * Concrete changes:
 * 1. Homepage hero — new title + subline matching the new positioning
 * 2. Solution Hubs reordered — Plattformen first, Websites moved back,
 *    Gründerpaket stays at the end
 *
 * Idempotent: re-running this migration applies the same end state. It WILL
 * overwrite manual Filament edits to the homepage hero and to hub sort_order
 * — that's intentional, this is a deliberate "set the new strategic state"
 * action. Editorial copy on other content blocks stays untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->updateHomepageHero();
        $this->reorderSolutionHubs();
    }

    public function down(): void
    {
        // Restore the previous homepage hero values
        $home = Page::where('type', Page::TYPE_HOME)->first();
        if ($home) {
            $content = $home->getTranslation('content', 'de') ?? [];
            $content['hero']['title'] = 'Software, die Unternehmen entlastet';
            $content['hero']['subtitle'] = 'Wir entwickeln maßgeschneiderte Softwarelösungen, die Geschäftsprozesse automatisieren und das Wachstum nachhaltig unterstützen.';
            $home->setTranslation('content', 'de', $content);
            $home->save();
        }

        // Restore previous hub sort_order
        $previousOrder = [
            'websites' => 1,
            'plattformen' => 2,
            'e-commerce' => 3,
            'mobile-anwendungen' => 4,
        ];
        foreach ($previousOrder as $slug => $order) {
            $hub = Page::where('type', Page::TYPE_SOLUTION_HUB)
                ->where('slug->de', $slug)
                ->whereNull('parent_id')
                ->first();
            if ($hub) {
                $hub->sort_order = $order;
                $hub->save();
            }
        }
    }

    private function updateHomepageHero(): void
    {
        $home = Page::where('type', Page::TYPE_HOME)->first();
        if (! $home) {
            return;
        }

        $content = $home->getTranslation('content', 'de') ?? [];

        $content['hero'] = array_merge($content['hero'] ?? [], [
            'title' => 'Maßgeschneiderte B2B-Plattformen für etablierte Mittelständler',
            'subtitle' => 'Wenn Standard-Software an Grenzen stößt: Wir entwickeln Bestell-, Vermittlungs- und Service-Plattformen, mit denen Sie Ihr Geschäft führen — als langfristiger Partner aus Frankfurt.',
        ]);

        $home->setTranslation('content', 'de', $content);

        // Meta-Title also gets the new positioning so the SERP snippet matches
        $home->setTranslation('meta_title', 'de', 'sdWebdesign — B2B-Plattformen für den Mittelstand aus Frankfurt');
        $home->setTranslation('meta_description', 'de', 'Maßgeschneiderte B2B-Plattformen für etablierte Mittelständler aus Frankfurt: Bestell-, Vermittlungs- und Service-Plattformen, die mit Ihrem Geschäft wachsen. Senior PO + Plattform-Team.');

        $home->save();
    }

    private function reorderSolutionHubs(): void
    {
        // New ordering: Plattformen / Workflow-Lösungen prominent first,
        // Websites moved back so the page doesn't open with website services
        // (which dilutes the platform positioning).
        $newOrder = [
            'plattformen' => 1,
            'e-commerce' => 2,
            'mobile-anwendungen' => 3,
            'websites' => 4,
            // gruenderpaket-frankfurt stays at sort_order 50 (already there)
        ];

        foreach ($newOrder as $slug => $order) {
            $hub = Page::where('type', Page::TYPE_SOLUTION_HUB)
                ->where('slug->de', $slug)
                ->whereNull('parent_id')
                ->first();
            if ($hub) {
                $hub->sort_order = $order;
                $hub->save();
            }
        }
    }
};

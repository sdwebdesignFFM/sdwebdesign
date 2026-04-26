<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

/**
 * Repurpose the existing "unsicher?" microcopy slot under the
 * homepage solutions section to point at the Plattform-Discovery
 * workshop (Phase C lead magnet) instead of the generic contact
 * modal.
 *
 * For undecided visitors who scrolled past four solution accordions
 * without picking one, the natural next step is no longer "let's
 * talk" — it is the paid 2-hour Discovery workshop that produces a
 * documented assessment. This is the highest-leverage placement for
 * the lead magnet.
 *
 * Adds the new `microcopy_link` key (rendered as anchor instead of
 * Livewire button when set) and overwrites microcopy + button.
 */
return new class extends Migration
{
    public function up(): void
    {
        $home = Page::where('type', Page::TYPE_HOME)->first();
        if (! $home) {
            return;
        }

        $content = $home->getTranslation('content', 'de') ?? [];
        $solutions = $content['solutions'] ?? [];

        $solutions['microcopy'] = 'Unsicher, welcher Einstieg sinnvoll ist? Im 2-Stunden-Discovery-Workshop klären wir das gemeinsam — Festpreis 990 €, dokumentiertes Ergebnis. Bei Folgeprojekt verrechnen wir den Workshop auf das Budget.';
        $solutions['microcopy_button'] = 'Discovery-Workshop ansehen';
        $solutions['microcopy_link'] = '/loesungen/plattformen/plattform-discovery';

        $content['solutions'] = $solutions;
        $home->setTranslation('content', 'de', $content);
        $home->save();
    }

    public function down(): void
    {
        $home = Page::where('type', Page::TYPE_HOME)->first();
        if (! $home) {
            return;
        }

        $content = $home->getTranslation('content', 'de') ?? [];
        if (isset($content['solutions'])) {
            unset(
                $content['solutions']['microcopy'],
                $content['solutions']['microcopy_button'],
                $content['solutions']['microcopy_link'],
            );
            $home->setTranslation('content', 'de', $content);
            $home->save();
        }
    }
};

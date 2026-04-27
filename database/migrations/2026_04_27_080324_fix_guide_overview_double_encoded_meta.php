<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The /ratgeber (guide-overview) page rendered its meta_title as a
 * literal JSON string in the browser tab —
 * {"de":"Ratgeber | …","en":"Guides | …"} | sdWebdesign.
 *
 * Root cause: someone (likely a Filament editor field that wasn't
 * the translatable variant) wrote a JSON-encoded string into the
 * meta_title column, double-encoding it. Spatie Translatable then
 * read the column back as a single string per locale, which is why
 * $page->meta_title returned the raw JSON.
 *
 * This migration:
 *   1. Reads the raw column value via DB facade (bypasses Translatable
 *      so we see what is actually stored).
 *   2. If it looks like a literal JSON map, parses it and re-saves
 *      via setTranslations() so the values land in the proper shape.
 *   3. Same fix applied to meta_description and title for safety.
 *
 * Idempotent: a second run finds clean data and is a no-op.
 */
return new class extends Migration
{
    public function up(): void
    {
        $row = DB::table('pages')
            ->where('type', Page::TYPE_GUIDE_OVERVIEW)
            ->first();
        if (! $row) {
            return;
        }

        $page = Page::find($row->id);
        if (! $page) {
            return;
        }

        foreach (['title', 'meta_title', 'meta_description'] as $field) {
            $raw = $row->{$field} ?? null;
            if (! is_string($raw) || $raw === '') {
                continue;
            }

            $decoded = json_decode($raw, true);
            // The bad shape is a JSON object whose keys are locale codes.
            if (is_array($decoded) && isset($decoded['de'])) {
                $cleaned = [];
                foreach ($decoded as $locale => $value) {
                    if (! is_string($value)) {
                        continue;
                    }
                    // Strip the manual brand suffix if present — the SEO
                    // package appends it again at render time.
                    $cleaned[$locale] = preg_replace(
                        '/\s*\|\s*sd\s?webdesign\s*$/iu',
                        '',
                        $value
                    );
                }
                if ($cleaned !== []) {
                    $page->setTranslations($field, $cleaned);
                }

                continue;
            }

            // Sometimes the column holds a JSON-quoted string of the
            // object — i.e. the value is itself a JSON-encoded string
            // ("{\"de\":\"…\"}"). One more decode strips that layer.
            if (is_string($decoded)) {
                $inner = json_decode($decoded, true);
                if (is_array($inner) && isset($inner['de'])) {
                    $cleaned = [];
                    foreach ($inner as $locale => $value) {
                        if (! is_string($value)) {
                            continue;
                        }
                        $cleaned[$locale] = preg_replace(
                            '/\s*\|\s*sd\s?webdesign\s*$/iu',
                            '',
                            $value
                        );
                    }
                    if ($cleaned !== []) {
                        $page->setTranslations($field, $cleaned);
                    }
                }
            }
        }

        $page->save();
    }

    public function down(): void
    {
        // Restoring the broken shape would re-introduce the bug — no-op.
    }
};

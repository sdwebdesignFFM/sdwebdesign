<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert blog_articles translatable fields
        $blogArticles = DB::table('blog_articles')->get();
        foreach ($blogArticles as $article) {
            $updates = [];

            // Text/varchar fields - wrap in locale JSON
            foreach (['title', 'slug', 'category', 'excerpt', 'intro', 'conclusion', 'meta_title', 'meta_description'] as $field) {
                if ($article->$field !== null && ! $this->isJson($article->$field)) {
                    $updates[$field] = json_encode(['de' => $article->$field]);
                }
            }

            // JSON field (sections) - wrap existing JSON in locale
            if ($article->sections !== null) {
                $existingValue = json_decode($article->sections, true);
                if ($existingValue !== null && ! isset($existingValue['de'])) {
                    $updates['sections'] = json_encode(['de' => $existingValue]);
                }
            }

            if (! empty($updates)) {
                DB::table('blog_articles')->where('id', $article->id)->update($updates);
            }
        }

        // Convert pages translatable fields
        $pages = DB::table('pages')->get();
        foreach ($pages as $page) {
            $updates = [];

            // Text/varchar fields
            foreach (['slug', 'title', 'meta_title', 'meta_description'] as $field) {
                if ($page->$field !== null && ! $this->isJson($page->$field)) {
                    $updates[$field] = json_encode(['de' => $page->$field]);
                }
            }

            // JSON field (content) - wrap existing JSON in locale
            if ($page->content !== null) {
                $existingValue = json_decode($page->content, true);
                if ($existingValue !== null && ! isset($existingValue['de'])) {
                    $updates['content'] = json_encode(['de' => $existingValue]);
                }
            }

            if (! empty($updates)) {
                DB::table('pages')->where('id', $page->id)->update($updates);
            }
        }

        // Convert settings translatable fields
        $settings = DB::table('settings')->get();
        foreach ($settings as $setting) {
            $updates = [];

            foreach (['tagline', 'cta_title', 'cta_subtitle', 'cta_button_text', 'cta_secondary_button_text', 'default_meta_title', 'default_meta_description'] as $field) {
                if ($setting->$field !== null && ! $this->isJson($setting->$field)) {
                    $updates[$field] = json_encode(['de' => $setting->$field]);
                }
            }

            if (! empty($updates)) {
                DB::table('settings')->where('id', $setting->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert blog_articles
        $blogArticles = DB::table('blog_articles')->get();
        foreach ($blogArticles as $article) {
            $updates = [];

            foreach (['title', 'slug', 'category', 'excerpt', 'intro', 'conclusion', 'meta_title', 'meta_description'] as $field) {
                if ($article->$field !== null && $this->isJson($article->$field)) {
                    $decoded = json_decode($article->$field, true);
                    if (isset($decoded['de'])) {
                        $updates[$field] = is_string($decoded['de']) ? $decoded['de'] : json_encode($decoded['de']);
                    }
                }
            }

            if ($article->sections !== null) {
                $decoded = json_decode($article->sections, true);
                if (isset($decoded['de'])) {
                    $updates['sections'] = json_encode($decoded['de']);
                }
            }

            if (! empty($updates)) {
                DB::table('blog_articles')->where('id', $article->id)->update($updates);
            }
        }

        // Revert pages
        $pages = DB::table('pages')->get();
        foreach ($pages as $page) {
            $updates = [];

            foreach (['slug', 'title', 'meta_title', 'meta_description'] as $field) {
                if ($page->$field !== null && $this->isJson($page->$field)) {
                    $decoded = json_decode($page->$field, true);
                    if (isset($decoded['de'])) {
                        $updates[$field] = is_string($decoded['de']) ? $decoded['de'] : json_encode($decoded['de']);
                    }
                }
            }

            if ($page->content !== null) {
                $decoded = json_decode($page->content, true);
                if (isset($decoded['de'])) {
                    $updates['content'] = json_encode($decoded['de']);
                }
            }

            if (! empty($updates)) {
                DB::table('pages')->where('id', $page->id)->update($updates);
            }
        }

        // Revert settings
        $settings = DB::table('settings')->get();
        foreach ($settings as $setting) {
            $updates = [];

            foreach (['tagline', 'cta_title', 'cta_subtitle', 'cta_button_text', 'cta_secondary_button_text', 'default_meta_title', 'default_meta_description'] as $field) {
                if ($setting->$field !== null && $this->isJson($setting->$field)) {
                    $decoded = json_decode($setting->$field, true);
                    if (isset($decoded['de'])) {
                        $updates[$field] = is_string($decoded['de']) ? $decoded['de'] : json_encode($decoded['de']);
                    }
                }
            }

            if (! empty($updates)) {
                DB::table('settings')->where('id', $setting->id)->update($updates);
            }
        }
    }

    private function isJson(string $string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
};

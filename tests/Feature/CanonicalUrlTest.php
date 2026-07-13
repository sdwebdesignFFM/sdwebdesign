<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanonicalUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Guide overview (needed for routes / breadcrumbs)
        Page::factory()->create([
            'slug' => ['de' => 'ratgeber', 'en' => 'guides'],
            'title' => ['de' => 'Ratgeber', 'en' => 'Guides'],
            'type' => Page::TYPE_GUIDE_OVERVIEW,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        // Guide article with distinct German and English slugs
        Page::factory()->create([
            'slug' => ['de' => 'website-vs-webanwendung', 'en' => 'website-vs-web-application'],
            'title' => ['de' => 'Website oder Webanwendung?', 'en' => 'Website or Web Application?'],
            'meta_title' => [
                'de' => 'Website oder Webanwendung? | Ratgeber | sdWebdesign',
                'en' => 'Website or Web Application? | Guide | sdWebdesign',
            ],
            'meta_description' => ['de' => 'Wann reicht eine Website und wann braucht es eine Webanwendung?'],
            'type' => Page::TYPE_GUIDE,
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['badge' => 'Websites', 'subtitle' => 'Wann reicht eine Website?'],
                    'intro' => ['text' => 'Die Grenze ist fliessend.'],
                ],
            ],
        ]);

        // Reference detail with distinct German and English slugs
        Page::factory()->create([
            'slug' => ['de' => 'projekt-alpha', 'en' => 'project-alpha'],
            'title' => ['de' => 'Projekt Alpha', 'en' => 'Project Alpha'],
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['category' => 'Web-Applikation', 'tagline' => 'Eine Test-Referenz'],
                    'meta' => [['label' => 'Kunde', 'value' => 'Test Kunde']],
                ],
            ],
        ]);

        // Contact page for footer links / routes
        Page::factory()->create([
            'slug' => ['de' => 'kontakt', 'en' => 'contact'],
            'title' => ['de' => 'Kontakt', 'en' => 'Contact'],
            'type' => Page::TYPE_CONTACT,
            'is_active' => true,
            'content' => ['de' => []],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Part A: Canonical 301 redirects for cross-locale guide slugs
    |--------------------------------------------------------------------------
    */

    public function test_en_guide_requested_with_german_slug_redirects_to_canonical_en(): void
    {
        $response = $this->get('/en/guides/website-vs-webanwendung');

        $response->assertStatus(301);
        $response->assertRedirect('/en/guides/website-vs-web-application');
    }

    public function test_de_guide_requested_with_english_slug_redirects_to_canonical_de(): void
    {
        $response = $this->get('/ratgeber/website-vs-web-application');

        $response->assertStatus(301);
        $response->assertRedirect('/ratgeber/website-vs-webanwendung');
    }

    public function test_canonical_german_guide_returns_200(): void
    {
        $this->get('/ratgeber/website-vs-webanwendung')->assertStatus(200);
    }

    public function test_canonical_english_guide_returns_200(): void
    {
        $this->get('/en/guides/website-vs-web-application')->assertStatus(200);
    }

    public function test_canonical_german_guide_has_self_referencing_canonical(): void
    {
        $content = $this->get('/ratgeber/website-vs-webanwendung')->assertStatus(200)->getContent();

        $this->assertStringContainsString(
            'rel="canonical" href="'.url('/ratgeber/website-vs-webanwendung').'"',
            $content
        );
    }

    public function test_canonical_english_guide_has_self_referencing_canonical(): void
    {
        $content = $this->get('/en/guides/website-vs-web-application')->assertStatus(200)->getContent();

        $this->assertStringContainsString(
            'rel="canonical" href="'.url('/en/guides/website-vs-web-application').'"',
            $content
        );
    }

    public function test_nonexistent_guide_slug_still_returns_404(): void
    {
        $this->get('/ratgeber/gibt-es-nicht')->assertStatus(404);
        $this->get('/en/guides/does-not-exist')->assertStatus(404);
    }

    /*
    |--------------------------------------------------------------------------
    | Part A: Reference detail pages share the same resolution logic
    |--------------------------------------------------------------------------
    */

    public function test_en_reference_requested_with_german_slug_redirects_to_canonical_en(): void
    {
        $response = $this->get('/en/references/projekt-alpha');

        $response->assertStatus(301);
        $response->assertRedirect('/en/references/project-alpha');
    }

    public function test_de_reference_requested_with_english_slug_redirects_to_canonical_de(): void
    {
        $response = $this->get('/referenzen/project-alpha');

        $response->assertStatus(301);
        $response->assertRedirect('/referenzen/projekt-alpha');
    }

    public function test_canonical_reference_pages_return_200(): void
    {
        $this->get('/referenzen/projekt-alpha')->assertStatus(200);
        $this->get('/en/references/project-alpha')->assertStatus(200);
    }

    /*
    |--------------------------------------------------------------------------
    | Part B: BlogPosting schema on guide articles
    |--------------------------------------------------------------------------
    */

    public function test_guide_emits_blogposting_with_headline_person_author_and_breadcrumb(): void
    {
        $content = $this->get('/ratgeber/website-vs-webanwendung')->assertStatus(200)->getContent();

        $blogPosting = $this->findJsonLdBlock($content, 'BlogPosting');
        $this->assertNotNull($blogPosting, 'Guide page must emit a BlogPosting schema block');

        // Headline is the pure article title, without the meta-title suffix.
        $this->assertSame('Website oder Webanwendung?', $blogPosting['headline']);

        // Author as a Person entity.
        $this->assertSame('Person', $blogPosting['author']['@type']);
        $this->assertSame('Steffen Fasselt', $blogPosting['author']['name']);
        $this->assertSame('https://www.linkedin.com/in/steffenfasselt/', $blogPosting['author']['url']);

        // Publisher linked to the organization entity via shared @id.
        $this->assertStringEndsWith('/#organization', $blogPosting['publisher']['@id']);

        // Dates, language and mainEntityOfPage.
        $this->assertArrayHasKey('datePublished', $blogPosting);
        $this->assertArrayHasKey('dateModified', $blogPosting);
        $this->assertSame('de', $blogPosting['inLanguage']);
        $this->assertArrayHasKey('mainEntityOfPage', $blogPosting);

        // Breadcrumb list Home > Ratgeber > Title.
        $this->assertNotNull($this->findJsonLdBlock($content, 'BreadcrumbList'));
    }

    public function test_english_guide_emits_blogposting_in_english(): void
    {
        $content = $this->get('/en/guides/website-vs-web-application')->assertStatus(200)->getContent();

        $blogPosting = $this->findJsonLdBlock($content, 'BlogPosting');
        $this->assertNotNull($blogPosting);
        $this->assertSame('Website or Web Application?', $blogPosting['headline']);
        $this->assertSame('en', $blogPosting['inLanguage']);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findJsonLdBlock(string $html, string $type): ?array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

        foreach ($matches[1] as $jsonText) {
            $data = json_decode(trim($jsonText), true);
            if (isset($data['@type']) && $data['@type'] === $type) {
                return $data;
            }
        }

        return null;
    }
}

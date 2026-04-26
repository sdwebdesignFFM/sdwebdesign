<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPersonSchemaTest extends TestCase
{
    use RefreshDatabase;

    private function seedAboutWithSteffen(?string $memberLinkedin = null): Page
    {
        return Page::factory()->create([
            'type' => Page::TYPE_ABOUT,
            'slug' => ['de' => 'ueber-uns', 'en' => 'about'],
            'title' => ['de' => 'Über uns', 'en' => 'About'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Über uns'],
                    'team' => [
                        'title' => 'Team',
                        'members' => [
                            array_filter([
                                'name' => 'Steffen Fasselt',
                                'role' => 'Senior Product Owner & Plattform-Architekt',
                                'description' => 'Bio.',
                                'icon' => 'user',
                                'linkedin' => $memberLinkedin,
                            ]),
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function test_about_emits_person_schema_with_steffen_as_named_principal(): void
    {
        $this->seedAboutWithSteffen();

        $body = $this->get('/ueber-uns')->assertStatus(200)->getContent();

        preg_match_all('#<script type="application/ld\+json">\s*(\{.*?\})\s*</script>#s', $body, $matches);
        $schemas = array_map(fn ($s) => json_decode($s, true), $matches[1]);
        $person = collect($schemas)->first(fn ($s) => ($s['@type'] ?? null) === 'Person');

        $this->assertNotNull($person, 'No Person schema emitted on /ueber-uns');
        $this->assertSame('Steffen Fasselt', $person['name']);
        $this->assertSame('Senior Product Owner & Plattform-Architekt', $person['jobTitle']);
        $this->assertStringEndsWith('/ueber-uns#steffen-fasselt', $person['@id']);
        $this->assertStringEndsWith('/ueber-uns', $person['url']);
        $this->assertStringEndsWith('/#organization', $person['worksFor']['@id']);
    }

    public function test_person_schema_same_as_uses_member_linkedin_when_set(): void
    {
        $this->seedAboutWithSteffen('https://www.linkedin.com/in/custom-handle/');

        $body = $this->get('/ueber-uns')->getContent();

        preg_match_all('#<script type="application/ld\+json">\s*(\{.*?\})\s*</script>#s', $body, $matches);
        $person = collect(array_map(fn ($s) => json_decode($s, true), $matches[1]))
            ->first(fn ($s) => ($s['@type'] ?? null) === 'Person');

        $this->assertContains('https://www.linkedin.com/in/custom-handle/', $person['sameAs']);
    }

    public function test_person_schema_falls_back_to_settings_linkedin_when_member_has_none(): void
    {
        $this->seedAboutWithSteffen(null);
        Setting::create(['linkedin_url' => 'https://www.linkedin.com/in/from-settings/']);

        $body = $this->get('/ueber-uns')->getContent();

        preg_match_all('#<script type="application/ld\+json">\s*(\{.*?\})\s*</script>#s', $body, $matches);
        $person = collect(array_map(fn ($s) => json_decode($s, true), $matches[1]))
            ->first(fn ($s) => ($s['@type'] ?? null) === 'Person');

        $this->assertContains('https://www.linkedin.com/in/from-settings/', $person['sameAs']);
    }

    public function test_about_renders_visible_linkedin_anchor_for_steffen_when_set(): void
    {
        $this->seedAboutWithSteffen('https://www.linkedin.com/in/steffenfasselt/');

        $response = $this->get('/ueber-uns')->assertStatus(200);

        $response->assertSee('href="https://www.linkedin.com/in/steffenfasselt/"', false);
        $response->assertSee('Auf LinkedIn folgen');
    }

    public function test_about_renders_no_linkedin_anchor_when_member_has_no_linkedin(): void
    {
        $this->seedAboutWithSteffen(null);

        $response = $this->get('/ueber-uns')->assertStatus(200);

        $response->assertDontSee('Auf LinkedIn folgen');
    }
}

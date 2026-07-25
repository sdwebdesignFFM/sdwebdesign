<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PageSeeder::class);
    }

    public function test_homepage_returns_success(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('sdWebdesign');
    }

    public function test_solutions_page_returns_success(): void
    {
        $response = $this->get('/loesungen');

        $response->assertStatus(200);
    }

    public function test_solution_detail_page_returns_success(): void
    {
        $response = $this->get('/loesungen/digitale-plattformen');

        $response->assertStatus(200);
        $response->assertSee('Digitale Plattformen');
    }

    public function test_solution_detail_page_returns_404_for_invalid_slug(): void
    {
        $response = $this->get('/loesungen/nicht-existierend');

        $response->assertStatus(404);
    }

    public function test_references_page_returns_success(): void
    {
        $response = $this->get('/referenzen');

        $response->assertStatus(200);
        $response->assertSee('Referenzen');
    }

    public function test_reference_detail_page_returns_success(): void
    {
        // Create a reference detail page for testing
        Page::create([
            'slug' => 'test-reference',
            'title' => 'Test Referenz',
            'type' => Page::TYPE_REFERENCE_DETAIL,
            'is_active' => true,
            'content' => [
                'hero' => [
                    'category' => 'Web-Applikation',
                    'tagline' => 'Eine Test-Referenz',
                ],
                'meta' => [
                    ['label' => 'Kunde', 'value' => 'Test Kunde'],
                ],
            ],
        ]);

        $response = $this->get('/referenzen/test-reference');

        $response->assertStatus(200);
        $response->assertSee('Test Referenz');
    }

    public function test_reference_detail_page_returns_404_for_invalid_slug(): void
    {
        $response = $this->get('/referenzen/nicht-existierend');

        $response->assertStatus(404);
    }

    public function test_about_page_returns_success(): void
    {
        $response = $this->get('/ueber-uns');

        $response->assertStatus(200);
    }

    public function test_contact_page_returns_success(): void
    {
        $response = $this->get('/kontakt');

        $response->assertStatus(200);
        $response->assertSee('Kontakt');
    }

    public function test_imprint_page_returns_success(): void
    {
        $response = $this->get('/impressum');

        $response->assertStatus(200);
        $response->assertSee('Impressum');
    }

    public function test_privacy_page_returns_success(): void
    {
        $response = $this->get('/datenschutz');

        $response->assertStatus(200);
    }

    public function test_contact_form_validation_requires_name(): void
    {
        $response = $this->post('/kontakt', [
            'email' => 'test@example.com',
            'company' => 'Test Company',
            'message' => 'Test message',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_contact_form_validation_requires_email(): void
    {
        $response = $this->post('/kontakt', [
            'name' => 'Test Name',
            'company' => 'Test Company',
            'message' => 'Test message',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_contact_form_validation_requires_valid_email(): void
    {
        $response = $this->post('/kontakt', [
            'name' => 'Test Name',
            'email' => 'invalid-email',
            'company' => 'Test Company',
            'message' => 'Test message',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_contact_form_validation_requires_company(): void
    {
        $response = $this->post('/kontakt', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
            'message' => 'Test message',
        ]);

        $response->assertSessionHasErrors('company');
    }

    public function test_contact_form_validation_requires_message(): void
    {
        $response = $this->post('/kontakt', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
            'company' => 'Test Company',
        ]);

        $response->assertSessionHasErrors('message');
    }

    public function test_contact_thank_you_redirects_without_session(): void
    {
        $response = $this->get('/anfrage-gesendet');

        $response->assertRedirect('/');
    }

    public function test_contact_thank_you_shows_with_session(): void
    {
        $response = $this->withSession([
            'contact_submitted' => true,
            'contact_data' => [
                'name' => 'Max Mustermann',
                'email' => 'max@test.de',
                'projectTypes' => ['Webdesign & UI/UX'],
                'budget' => '5.000 - 15.000 €',
                'timeline' => '1-3 Monate',
            ],
        ])->get('/anfrage-gesendet');

        $response->assertStatus(200);
        $response->assertSee('Vielen Dank');
        $response->assertSee('Max');
    }
}

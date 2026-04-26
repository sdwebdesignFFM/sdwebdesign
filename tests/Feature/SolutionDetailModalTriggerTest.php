<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolutionDetailModalTriggerTest extends TestCase
{
    use RefreshDatabase;

    private function seedHubAndDetail(array $cta = [], array $heroExtra = [], array $extraContent = []): Page
    {
        $hub = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'parent_id' => null,
            'slug' => ['de' => 'plattformen', 'en' => 'plattformen'],
            'title' => ['de' => 'Plattformen', 'en' => 'Platforms'],
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        return Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'parent_id' => $hub->id,
            'slug' => ['de' => 'demo-detail', 'en' => 'demo-detail'],
            'title' => ['de' => 'Demo', 'en' => 'Demo'],
            'is_active' => true,
            'content' => [
                'de' => array_merge([
                    'hero' => array_merge(['tagline' => 't', 'description' => 'd'], $heroExtra),
                    'cta' => array_merge(['title' => 'CTA', 'button_text' => 'Anfragen'], $cta),
                ], $extraContent),
            ],
        ]);
    }

    public function test_cta_button_uses_data_attributes_no_inline_javascript(): void
    {
        $this->seedHubAndDetail();

        $body = $this->get('/loesungen/plattformen/demo-detail')->assertStatus(200)->getContent();

        // No inline onclick handlers with Livewire.dispatch — that escape
        // pattern broke when payload was JSON.
        $this->assertStringNotContainsString('onclick="Livewire.dispatch', $body);
        // Instead the trigger uses data-modal-event picked up by the
        // global delegated handler in the layout.
        $this->assertStringContainsString('data-modal-event="openContactModal"', $body);
    }

    public function test_cta_button_uses_custom_modal_event_when_set(): void
    {
        $this->seedHubAndDetail([
            'modal_event' => 'openWorkshopRequestModal',
            'modal_payload' => ['slug' => 'plattform-discovery'],
        ]);

        $body = $this->get('/loesungen/plattformen/demo-detail')->getContent();

        $this->assertStringContainsString('data-modal-event="openWorkshopRequestModal"', $body);
        $this->assertStringContainsString('&quot;slug&quot;:&quot;plattform-discovery&quot;', $body);
        $this->assertStringNotContainsString('data-modal-event="openContactModal"', $body);
    }

    public function test_modal_event_name_is_whitelisted_against_injection(): void
    {
        $this->seedHubAndDetail([
            'modal_event' => 'openContactModal\');alert(1);Livewire.dispatch(\'',
        ]);

        $body = $this->get('/loesungen/plattformen/demo-detail')->getContent();

        $this->assertStringContainsString('data-modal-event="openContactModal"', $body);
        $this->assertStringNotContainsString('alert(1)', $body);
    }

    public function test_hero_cta_button_renders_when_hero_cta_text_set(): void
    {
        $this->seedHubAndDetail(
            cta: ['modal_event' => 'openWorkshopRequestModal', 'modal_payload' => ['slug' => 'x']],
            heroExtra: ['cta_text' => 'Workshop anfragen', 'cta_subtext' => '2 Stunden · 990 €'],
        );

        $body = $this->get('/loesungen/plattformen/demo-detail')->getContent();

        // Hero CTA uses the same modal config — both buttons are present
        $matches = substr_count($body, 'data-modal-event="openWorkshopRequestModal"');
        $this->assertSame(2, $matches, 'Hero and bottom CTA should both render the trigger');
        $this->assertStringContainsString('Workshop anfragen', $body);
        $this->assertStringContainsString('2 Stunden · 990 €', $body);
    }

    public function test_hero_cta_button_absent_when_hero_cta_text_not_set(): void
    {
        $this->seedHubAndDetail();

        $body = $this->get('/loesungen/plattformen/demo-detail')->getContent();

        $matches = substr_count($body, 'data-modal-event="openContactModal"');
        $this->assertSame(1, $matches, 'Only the bottom CTA should render when no hero CTA');
    }

    public function test_maintenance_block_is_hidden_when_flag_set(): void
    {
        $this->seedHubAndDetail(extraContent: ['hide_maintenance_block' => true]);

        $body = $this->get('/loesungen/plattformen/demo-detail')->getContent();

        $this->assertStringNotContainsString('Betrieb, Hosting &amp; Wartung', $body);
    }

    public function test_maintenance_block_is_shown_by_default(): void
    {
        $this->seedHubAndDetail();

        $body = $this->get('/loesungen/plattformen/demo-detail')->getContent();

        $this->assertStringContainsString('Betrieb', $body);
    }

    public function test_global_layout_provides_delegated_modal_click_handler(): void
    {
        $this->seedHubAndDetail();

        $body = $this->get('/loesungen/plattformen/demo-detail')->getContent();

        $this->assertStringContainsString('[data-modal-event]', $body);
        $this->assertStringContainsString('Livewire.dispatch(eventName, payload)', $body);
    }
}

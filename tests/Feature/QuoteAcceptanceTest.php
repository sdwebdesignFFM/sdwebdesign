<?php

namespace Tests\Feature;

use App\Enums\QuoteStatus;
use App\Livewire\QuoteAcceptance;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuoteAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_quote_show_page_displays_correctly(): void
    {
        $quote = Quote::factory()->sent()->create();
        QuoteItem::factory()->for($quote)->create([
            'name' => 'Webdesign',
            'unit_price' => 1000,
            'total_price' => 1000,
        ]);

        $response = $this->get(route('quotes.show', ['token' => $quote->token]));

        $response->assertStatus(200);
        $response->assertSee($quote->title);
        $response->assertSee('Webdesign');
    }

    public function test_quote_viewing_updates_status_to_viewed(): void
    {
        $quote = Quote::factory()->sent()->create();

        $this->get(route('quotes.show', ['token' => $quote->token]));

        $quote->refresh();
        $this->assertEquals(QuoteStatus::Viewed, $quote->status);
        $this->assertNotNull($quote->first_viewed_at);
    }

    public function test_expired_quote_shows_expired_page(): void
    {
        $quote = Quote::factory()->sent()->create([
            'valid_until' => now()->subDay(),
        ]);

        $response = $this->get(route('quotes.show', ['token' => $quote->token]));

        $response->assertStatus(200);
        $response->assertSee('abgelaufen');
    }

    public function test_accepted_quote_shows_confirmation_page(): void
    {
        $quote = Quote::factory()->accepted()->create();

        $response = $this->get(route('quotes.accepted', ['token' => $quote->token]));

        $response->assertStatus(200);
        $response->assertSee('erfolgreich angenommen');
    }

    public function test_livewire_component_can_toggle_optional_items(): void
    {
        $quote = Quote::factory()->viewed()->create();
        $optionalItem = QuoteItem::factory()->for($quote)->optional()->create([
            'name' => 'SEO Optimierung',
            'unit_price' => 500,
            'total_price' => 500,
        ]);

        Livewire::test(QuoteAcceptance::class, ['quote' => $quote])
            ->assertSee('SEO Optimierung')
            ->call('toggleOption', $optionalItem->id);

        $optionalItem->refresh();
        $this->assertTrue($optionalItem->is_selected);
    }

    public function test_livewire_component_shows_acceptance_form(): void
    {
        $quote = Quote::factory()->viewed()->create();
        QuoteItem::factory()->for($quote)->create();

        Livewire::test(QuoteAcceptance::class, ['quote' => $quote])
            ->call('showAcceptForm')
            ->assertSet('showAcceptanceForm', true)
            ->assertSet('currentStep', 1)
            ->assertSee('Rechnungsadresse');
    }

    public function test_quote_can_be_accepted_through_livewire(): void
    {
        $quote = Quote::factory()->viewed()->create();
        QuoteItem::factory()->for($quote)->create();

        // Base64 encoded tiny PNG (1x1 pixel) for signature
        $signatureData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        Livewire::test(QuoteAcceptance::class, ['quote' => $quote])
            // Step 1: Fill billing details
            ->set('billingName', 'Max Mustermann')
            ->set('billingStreet', 'Teststraße 1')
            ->set('billingZip', '12345')
            ->set('billingCity', 'Berlin')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            // Step 2: Accept with signature
            ->set('acceptedName', 'Max Mustermann')
            ->set('termsAccepted', true)
            ->set('signatureData', $signatureData)
            ->call('accept')
            ->assertRedirect(route('quotes.accepted', ['token' => $quote->token]));

        $quote->refresh();
        $this->assertEquals(QuoteStatus::Accepted, $quote->status);
        $this->assertEquals('Max Mustermann', $quote->accepted_name);
        $this->assertEquals('Max Mustermann', $quote->billing_name);
        $this->assertEquals('Teststraße 1', $quote->billing_street);
        $this->assertEquals('12345', $quote->billing_zip);
        $this->assertEquals('Berlin', $quote->billing_city);
        $this->assertNotNull($quote->signature_data);
        $this->assertNotNull($quote->contract);
    }

    public function test_quote_acceptance_requires_name(): void
    {
        $quote = Quote::factory()->viewed()->create();
        QuoteItem::factory()->for($quote)->create();

        $signatureData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        Livewire::test(QuoteAcceptance::class, ['quote' => $quote])
            ->set('currentStep', 2)
            ->set('acceptedName', '')
            ->set('termsAccepted', true)
            ->set('signatureData', $signatureData)
            ->call('accept')
            ->assertHasErrors(['acceptedName' => 'required']);
    }

    public function test_quote_acceptance_requires_terms_accepted(): void
    {
        $quote = Quote::factory()->viewed()->create();
        QuoteItem::factory()->for($quote)->create();

        $signatureData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        Livewire::test(QuoteAcceptance::class, ['quote' => $quote])
            ->set('currentStep', 2)
            ->set('acceptedName', 'Max Mustermann')
            ->set('termsAccepted', false)
            ->set('signatureData', $signatureData)
            ->call('accept')
            ->assertHasErrors(['termsAccepted' => 'accepted']);
    }

    public function test_quote_acceptance_requires_signature(): void
    {
        $quote = Quote::factory()->viewed()->create();
        QuoteItem::factory()->for($quote)->create();

        Livewire::test(QuoteAcceptance::class, ['quote' => $quote])
            ->set('currentStep', 2)
            ->set('acceptedName', 'Max Mustermann')
            ->set('termsAccepted', true)
            ->set('signatureData', '')
            ->call('accept')
            ->assertHasErrors(['signatureData' => 'required']);
    }

    public function test_expired_quote_cannot_be_accepted(): void
    {
        $quote = Quote::factory()->viewed()->create([
            'valid_until' => now()->subDay(),
        ]);
        QuoteItem::factory()->for($quote)->create();

        $signatureData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        Livewire::test(QuoteAcceptance::class, ['quote' => $quote])
            ->set('currentStep', 2)
            ->set('acceptedName', 'Max Mustermann')
            ->set('termsAccepted', true)
            ->set('signatureData', $signatureData)
            ->call('accept')
            ->assertSet('errorMessage', 'Dieses Angebot kann nicht mehr angenommen werden.');
    }

    public function test_already_accepted_quote_cannot_be_accepted_again(): void
    {
        $quote = Quote::factory()->accepted()->create();
        QuoteItem::factory()->for($quote)->create();

        $signatureData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        Livewire::test(QuoteAcceptance::class, ['quote' => $quote])
            ->set('currentStep', 2)
            ->set('acceptedName', 'Max Mustermann')
            ->set('termsAccepted', true)
            ->set('signatureData', $signatureData)
            ->call('accept')
            ->assertSet('errorMessage', 'Dieses Angebot kann nicht mehr angenommen werden.');
    }

    public function test_step_1_billing_validation(): void
    {
        $quote = Quote::factory()->viewed()->create();
        QuoteItem::factory()->for($quote)->create();

        Livewire::test(QuoteAcceptance::class, ['quote' => $quote])
            ->call('showAcceptForm')
            ->set('billingName', '')
            ->set('billingStreet', 'Teststraße 1')
            ->set('billingZip', '12345')
            ->set('billingCity', 'Berlin')
            ->call('nextStep')
            ->assertHasErrors(['billingName' => 'required'])
            ->assertSet('currentStep', 1);
    }

    public function test_quote_pdf_can_be_downloaded(): void
    {
        $quote = Quote::factory()->viewed()->create();

        $response = $this->get(route('quotes.pdf', ['token' => $quote->token]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_invalid_token_returns_404(): void
    {
        $response = $this->get(route('quotes.show', ['token' => 'invalid-token']));

        $response->assertStatus(404);
    }
}

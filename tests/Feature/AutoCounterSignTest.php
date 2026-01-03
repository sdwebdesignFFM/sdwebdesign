<?php

namespace Tests\Feature;

use App\Enums\QuoteStatus;
use App\Mail\QuoteAcceptedClient;
use App\Mail\QuoteFullySignedClient;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Setting;
use App\Services\Quote\QuoteService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AutoCounterSignTest extends TestCase
{
    use RefreshDatabase;

    private string $signatureData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_auto_counter_sign_when_settings_has_signature(): void
    {
        // Arrange: Configure admin signature in settings
        $settings = Setting::instance();
        $settings->update([
            'admin_signer_name' => 'Max Mustermann',
            'admin_signer_position' => 'Geschäftsführer',
            'admin_signature_data' => $this->signatureData,
            'email' => 'admin@example.com',
        ]);
        Cache::forget('settings');

        $quote = Quote::factory()->viewed()->create([
            'signature_data' => $this->signatureData,
            'signature_at' => now(),
        ]);
        QuoteItem::factory()->for($quote)->create();

        // Act: Accept the quote
        $service = app(QuoteService::class);
        $contract = $service->accept($quote, 'Kunde Testname');

        // Assert: Quote should be auto counter-signed
        $quote->refresh();
        $this->assertNotNull($contract);
        $this->assertEquals(QuoteStatus::Accepted, $quote->status);
        $this->assertNotNull($quote->admin_signature_data);
        $this->assertEquals('Max Mustermann', $quote->admin_signature_name);
        $this->assertEquals('Geschäftsführer', $quote->admin_signature_position);
        $this->assertNotNull($quote->admin_signed_at);
        $this->assertTrue($quote->isFullySigned());

        // Assert: QuoteFullySignedClient email should be sent
        Mail::assertSent(QuoteFullySignedClient::class, function ($mail) use ($quote) {
            return $mail->quote->id === $quote->id;
        });
        Mail::assertNotSent(QuoteAcceptedClient::class);
    }

    public function test_no_auto_counter_sign_when_settings_missing_signature(): void
    {
        // Arrange: No admin signature in settings
        $settings = Setting::instance();
        $settings->update([
            'admin_signer_name' => null,
            'admin_signer_position' => null,
            'admin_signature_data' => null,
            'email' => 'admin@example.com',
        ]);
        Cache::forget('settings');

        $quote = Quote::factory()->viewed()->create([
            'signature_data' => $this->signatureData,
            'signature_at' => now(),
        ]);
        QuoteItem::factory()->for($quote)->create();

        // Act: Accept the quote
        $service = app(QuoteService::class);
        $contract = $service->accept($quote, 'Kunde Testname');

        // Assert: Quote should NOT be auto counter-signed
        $quote->refresh();
        $this->assertNotNull($contract);
        $this->assertEquals(QuoteStatus::Accepted, $quote->status);
        $this->assertNull($quote->admin_signature_data);
        $this->assertNull($quote->admin_signature_name);
        $this->assertNull($quote->admin_signed_at);
        $this->assertFalse($quote->isFullySigned());

        // Assert: Standard QuoteAcceptedClient email should be sent
        Mail::assertSent(QuoteAcceptedClient::class, function ($mail) use ($quote) {
            return $mail->quote->id === $quote->id;
        });
        Mail::assertNotSent(QuoteFullySignedClient::class);
    }

    public function test_no_auto_counter_sign_when_requires_manual_review(): void
    {
        // Arrange: Configure admin signature in settings
        $settings = Setting::instance();
        $settings->update([
            'admin_signer_name' => 'Max Mustermann',
            'admin_signer_position' => 'Geschäftsführer',
            'admin_signature_data' => $this->signatureData,
            'email' => 'admin@example.com',
        ]);
        Cache::forget('settings');

        // Create quote with manual review flag
        $quote = Quote::factory()->viewed()->create([
            'signature_data' => $this->signatureData,
            'signature_at' => now(),
            'requires_manual_review' => true,
        ]);
        QuoteItem::factory()->for($quote)->create();

        // Act: Accept the quote
        $service = app(QuoteService::class);
        $contract = $service->accept($quote, 'Kunde Testname');

        // Assert: Quote should NOT be auto counter-signed due to manual review flag
        $quote->refresh();
        $this->assertNotNull($contract);
        $this->assertEquals(QuoteStatus::Accepted, $quote->status);
        $this->assertNull($quote->admin_signature_data);
        $this->assertNull($quote->admin_signed_at);
        $this->assertFalse($quote->isFullySigned());

        // Assert: Standard QuoteAcceptedClient email should be sent (not fully-signed)
        Mail::assertSent(QuoteAcceptedClient::class, function ($mail) use ($quote) {
            return $mail->quote->id === $quote->id;
        });
        Mail::assertNotSent(QuoteFullySignedClient::class);
    }

    public function test_no_auto_counter_sign_when_signature_name_missing(): void
    {
        // Arrange: Incomplete admin signature (missing name)
        $settings = Setting::instance();
        $settings->update([
            'admin_signer_name' => null,
            'admin_signer_position' => 'Geschäftsführer',
            'admin_signature_data' => $this->signatureData,
            'email' => 'admin@example.com',
        ]);
        Cache::forget('settings');

        $quote = Quote::factory()->viewed()->create([
            'signature_data' => $this->signatureData,
            'signature_at' => now(),
        ]);
        QuoteItem::factory()->for($quote)->create();

        // Act: Accept the quote
        $service = app(QuoteService::class);
        $contract = $service->accept($quote, 'Kunde Testname');

        // Assert: Quote should NOT be auto counter-signed
        $quote->refresh();
        $this->assertFalse($quote->isFullySigned());
        $this->assertNull($quote->admin_signature_data);

        Mail::assertSent(QuoteAcceptedClient::class);
        Mail::assertNotSent(QuoteFullySignedClient::class);
    }

    public function test_no_auto_counter_sign_when_position_missing(): void
    {
        // Arrange: Incomplete admin signature (missing position)
        $settings = Setting::instance();
        $settings->update([
            'admin_signer_name' => 'Max Mustermann',
            'admin_signer_position' => null,
            'admin_signature_data' => $this->signatureData,
            'email' => 'admin@example.com',
        ]);
        Cache::forget('settings');

        $quote = Quote::factory()->viewed()->create([
            'signature_data' => $this->signatureData,
            'signature_at' => now(),
        ]);
        QuoteItem::factory()->for($quote)->create();

        // Act: Accept the quote
        $service = app(QuoteService::class);
        $contract = $service->accept($quote, 'Kunde Testname');

        // Assert: Quote should NOT be auto counter-signed
        $quote->refresh();
        $this->assertFalse($quote->isFullySigned());
        $this->assertNull($quote->admin_signature_data);

        Mail::assertSent(QuoteAcceptedClient::class);
        Mail::assertNotSent(QuoteFullySignedClient::class);
    }

    public function test_quote_model_auto_counter_sign_method(): void
    {
        // Arrange
        $settings = Setting::instance();
        $settings->update([
            'admin_signer_name' => 'Admin Name',
            'admin_signer_position' => 'CEO',
            'admin_signature_data' => $this->signatureData,
        ]);
        Cache::forget('settings');

        $quote = Quote::factory()->viewed()->create();

        // Act
        $result = $quote->autoCounterSign();

        // Assert
        $this->assertTrue($result);
        $this->assertEquals('Admin Name', $quote->admin_signature_name);
        $this->assertEquals('CEO', $quote->admin_signature_position);
        $this->assertEquals($this->signatureData, $quote->admin_signature_data);
        $this->assertNotNull($quote->admin_signed_at);
    }

    public function test_quote_model_auto_counter_sign_returns_false_without_settings(): void
    {
        // Arrange: No signature configured
        $settings = Setting::instance();
        $settings->update([
            'admin_signer_name' => null,
            'admin_signer_position' => null,
            'admin_signature_data' => null,
        ]);
        Cache::forget('settings');

        $quote = Quote::factory()->viewed()->create();

        // Act
        $result = $quote->autoCounterSign();

        // Assert
        $this->assertFalse($result);
        $this->assertNull($quote->admin_signature_data);
    }

    public function test_quote_model_auto_counter_sign_respects_manual_review_flag(): void
    {
        // Arrange
        $settings = Setting::instance();
        $settings->update([
            'admin_signer_name' => 'Admin Name',
            'admin_signer_position' => 'CEO',
            'admin_signature_data' => $this->signatureData,
        ]);
        Cache::forget('settings');

        $quote = Quote::factory()->viewed()->create([
            'requires_manual_review' => true,
        ]);

        // Act
        $result = $quote->autoCounterSign();

        // Assert
        $this->assertFalse($result);
        $this->assertNull($quote->admin_signature_data);
    }

    public function test_setting_has_admin_signature_returns_true_when_complete(): void
    {
        $settings = Setting::instance();
        $settings->update([
            'admin_signer_name' => 'Test Name',
            'admin_signer_position' => 'Test Position',
            'admin_signature_data' => $this->signatureData,
        ]);

        $this->assertTrue($settings->hasAdminSignature());
    }

    public function test_setting_has_admin_signature_returns_false_when_incomplete(): void
    {
        $settings = Setting::instance();

        // All empty
        $settings->update([
            'admin_signer_name' => null,
            'admin_signer_position' => null,
            'admin_signature_data' => null,
        ]);
        $this->assertFalse($settings->hasAdminSignature());

        // Missing signature data
        $settings->update([
            'admin_signer_name' => 'Test Name',
            'admin_signer_position' => 'Test Position',
            'admin_signature_data' => null,
        ]);
        $this->assertFalse($settings->hasAdminSignature());

        // Missing name
        $settings->update([
            'admin_signer_name' => null,
            'admin_signer_position' => 'Test Position',
            'admin_signature_data' => $this->signatureData,
        ]);
        $this->assertFalse($settings->hasAdminSignature());

        // Missing position
        $settings->update([
            'admin_signer_name' => 'Test Name',
            'admin_signer_position' => null,
            'admin_signature_data' => $this->signatureData,
        ]);
        $this->assertFalse($settings->hasAdminSignature());
    }
}

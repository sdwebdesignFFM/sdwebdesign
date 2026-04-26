<?php

namespace Tests\Feature;

use App\Livewire\WhitepaperRequestForm;
use App\Mail\WhitepaperDelivery;
use App\Models\WhitepaperLead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class WhitepaperLeadMagnetTest extends TestCase
{
    use RefreshDatabase;

    public function test_whitepaper_landing_page_renders_with_all_sections(): void
    {
        $response = $this->get('/whitepaper/eigene-plattform-vs-standard-software');

        $response->assertStatus(200);
        $response->assertSee('Eigene Plattform oder Standard-Software');
        $response->assertSee('Was im Whitepaper steht');
        $response->assertSee('Drei Schichten der Software-Entscheidung');
        $response->assertSee('Whitepaper kostenlos anfordern');
    }

    public function test_form_validates_required_email_and_consent(): void
    {
        Livewire::test(WhitepaperRequestForm::class, [
            'whitepaperSlug' => 'platform-vs-standard',
            'whitepaperTitle' => 'Test Title',
            'pdfView' => 'pdfs.whitepaper.platform-vs-standard',
            'pdfFilename' => 'test.pdf',
        ])
            ->set('email', '')
            ->set('consent', false)
            ->call('submit')
            ->assertHasErrors(['email' => 'required', 'consent' => 'accepted']);
    }

    public function test_form_validates_email_format(): void
    {
        Livewire::test(WhitepaperRequestForm::class, [
            'whitepaperSlug' => 'platform-vs-standard',
            'whitepaperTitle' => 'Test Title',
            'pdfView' => 'pdfs.whitepaper.platform-vs-standard',
            'pdfFilename' => 'test.pdf',
        ])
            ->set('email', 'not-an-email')
            ->set('consent', true)
            ->call('submit')
            ->assertHasErrors(['email' => 'email']);
    }

    public function test_valid_submission_creates_lead_and_sends_mail(): void
    {
        Mail::fake();

        Livewire::test(WhitepaperRequestForm::class, [
            'whitepaperSlug' => 'platform-vs-standard',
            'whitepaperTitle' => 'Eigene Plattform oder Standard-Software?',
            'pdfView' => 'pdfs.whitepaper.platform-vs-standard',
            'pdfFilename' => 'sdwebdesign-whitepaper.pdf',
        ])
            ->set('email', 'maria@beispielfirma.de')
            ->set('name', 'Maria Beispiel')
            ->set('company', 'Beispielfirma GmbH')
            ->set('role', 'Geschäftsführung')
            ->set('consent', true)
            ->call('submit')
            ->assertSet('submitted', true);

        $lead = WhitepaperLead::firstWhere('email', 'maria@beispielfirma.de');
        $this->assertNotNull($lead);
        $this->assertSame('Maria Beispiel', $lead->name);
        $this->assertSame('Beispielfirma GmbH', $lead->company);
        $this->assertSame('Geschäftsführung', $lead->role);
        $this->assertSame('platform-vs-standard', $lead->whitepaper_slug);
        $this->assertNotNull($lead->sent_at);

        Mail::assertSent(WhitepaperDelivery::class, function (WhitepaperDelivery $m) {
            return $m->hasTo('maria@beispielfirma.de');
        });
    }

    public function test_resubmitting_with_same_email_updates_existing_lead_no_duplicate(): void
    {
        Mail::fake();

        $component = Livewire::test(WhitepaperRequestForm::class, [
            'whitepaperSlug' => 'platform-vs-standard',
            'whitepaperTitle' => 'X',
            'pdfView' => 'pdfs.whitepaper.platform-vs-standard',
            'pdfFilename' => 'x.pdf',
        ]);

        $component->set('email', 'duplicate@firma.de')->set('consent', true)->call('submit');
        $component->set('email', 'duplicate@firma.de')->set('name', 'Updated Name')->set('consent', true)->call('submit');

        $this->assertSame(1, WhitepaperLead::where('email', 'duplicate@firma.de')->count());
        $this->assertSame('Updated Name', WhitepaperLead::firstWhere('email', 'duplicate@firma.de')->name);
    }

    public function test_pdf_template_renders_without_error(): void
    {
        $lead = WhitepaperLead::create([
            'whitepaper_slug' => 'platform-vs-standard',
            'email' => 'pdf-test@firma.de',
        ]);

        $html = view('pdfs.whitepaper.platform-vs-standard', ['lead' => $lead])->render();

        $this->assertStringContainsString('Eigene Plattform oder Standard-Software', $html);
        $this->assertStringContainsString('Discovery-Workshop', $html);
        $this->assertStringContainsString('Roadmap-Schablone', $html);
    }
}

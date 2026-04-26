<?php

namespace Tests\Feature;

use App\Livewire\WorkshopRequestModal;
use App\Mail\WorkshopRequestAdmin;
use App\Mail\WorkshopRequestConfirmation;
use App\Models\Page;
use App\Models\Setting;
use App\Models\WorkshopRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class WorkshopRequestModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_modal_opens_when_event_is_dispatched(): void
    {
        Livewire::test(WorkshopRequestModal::class)
            ->assertSet('isOpen', false)
            ->dispatch('openWorkshopRequestModal', slug: 'plattform-discovery')
            ->assertSet('isOpen', true)
            ->assertSet('workshopSlug', 'plattform-discovery')
            ->assertSet('currentStep', 1);
    }

    public function test_step_navigation_does_not_lose_state(): void
    {
        Livewire::test(WorkshopRequestModal::class)
            ->dispatch('openWorkshopRequestModal')
            ->set('triggerQuestion', 'Standard-Software passt nicht mehr')
            ->call('toggleArrayValue', 'workflowAreas', 'disposition')
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->call('nextStep')
            ->assertSet('currentStep', 3)
            ->call('previousStep')
            ->assertSet('currentStep', 2)
            ->assertSet('triggerQuestion', 'Standard-Software passt nicht mehr')
            ->assertSet('workflowAreas', ['disposition']);
    }

    public function test_multi_select_toggle_adds_and_removes_values(): void
    {
        $component = Livewire::test(WorkshopRequestModal::class)
            ->dispatch('openWorkshopRequestModal');

        $component->call('toggleArrayValue', 'existingSystems', 'personio')
            ->assertSet('existingSystems', ['personio'])
            ->call('toggleArrayValue', 'existingSystems', 'sap')
            ->assertSet('existingSystems', ['personio', 'sap'])
            ->call('toggleArrayValue', 'existingSystems', 'personio')
            ->assertSet('existingSystems', ['sap']);
    }

    public function test_single_value_setter_toggles_off_when_same_value_clicked(): void
    {
        Livewire::test(WorkshopRequestModal::class)
            ->dispatch('openWorkshopRequestModal')
            ->call('setSingleValue', 'workshopFormat', 'remote')
            ->assertSet('workshopFormat', 'remote')
            ->call('setSingleValue', 'workshopFormat', 'remote')
            ->assertSet('workshopFormat', '');
    }

    public function test_submit_validates_required_contact_fields(): void
    {
        Livewire::test(WorkshopRequestModal::class)
            ->dispatch('openWorkshopRequestModal')
            ->set('currentStep', 4)
            ->call('submit')
            ->assertHasErrors(['name' => 'required', 'email' => 'required', 'phone' => 'required', 'consent' => 'accepted']);
    }

    public function test_submit_creates_request_and_sends_two_mails(): void
    {
        Mail::fake();
        Setting::create(['email' => 'admin@sdwebdesign.de']);

        Livewire::test(WorkshopRequestModal::class)
            ->dispatch('openWorkshopRequestModal')
            ->set('triggerQuestion', 'Wir suchen die richtige Plattform für Disposition.')
            ->set('industry', 'personal')
            ->call('toggleArrayValue', 'workflowAreas', 'disposition')
            ->call('toggleArrayValue', 'existingSystems', 'personio')
            ->call('toggleArrayValue', 'existingSystems', 'excel')
            ->call('setSingleValue', 'procurementStage', 'angebote')
            ->call('setSingleValue', 'budgetIndication', '6_stellig_klein')
            ->call('setSingleValue', 'goLiveTimeline', '6_monate')
            ->call('setSingleValue', 'workshopFormat', 'remote')
            ->call('setSingleValue', 'preferredTiming', 'in_2_wochen')
            ->call('setSingleValue', 'preferredDaytime', 'vormittag')
            ->set('name', 'Maria Beispiel')
            ->set('email', 'maria@beispielfirma.de')
            ->set('phone', '+49 69 1234567')
            ->set('company', 'Beispielfirma GmbH')
            ->set('role', 'Geschäftsführung')
            ->set('companySize', '50_bis_250')
            ->set('briefingNotes', 'Zwei vorherige Tools sind gescheitert.')
            ->set('consent', true)
            ->call('submit')
            ->assertSet('isSubmitted', true);

        $request = WorkshopRequest::firstWhere('email', 'maria@beispielfirma.de');
        $this->assertNotNull($request);
        $this->assertSame('plattform-discovery', $request->workshop_slug);
        $this->assertSame('Personalvermittlung / -dienstleistung', $request->industry);
        $this->assertSame(['Disposition / Workforce-Management'], $request->workflow_areas);
        $this->assertContains('Personio', $request->existing_systems);
        $this->assertContains('Excel- / Office-Listen', $request->existing_systems);
        $this->assertSame('Angebote eingeholt, suche zweite Meinung', $request->procurement_stage);
        $this->assertSame('6-stellig (100–250 k €)', $request->budget_indication);
        $this->assertSame('Remote per Video', $request->workshop_format);
        $this->assertSame('Geschäftsführung', $request->role);
        $this->assertSame('50–250 Mitarbeiter', $request->company_size);
        $this->assertSame('Zwei vorherige Tools sind gescheitert.', $request->briefing_notes);
        $this->assertNotNull($request->admin_notified_at);
        $this->assertNotNull($request->confirmation_sent_at);

        Mail::assertSent(WorkshopRequestAdmin::class, fn ($m) => $m->hasTo('admin@sdwebdesign.de'));
        Mail::assertSent(WorkshopRequestConfirmation::class, fn ($m) => $m->hasTo('maria@beispielfirma.de'));
    }

    public function test_submit_succeeds_and_persists_lead_when_mail_dispatch_throws(): void
    {
        Setting::create(['email' => 'admin@sdwebdesign.de']);

        // Force the mail driver to throw on send. The lead must still land
        // in the DB and the user must see the success state — we never
        // 500 on a transient SMTP issue.
        Mail::shouldReceive('to')->andReturnSelf();
        Mail::shouldReceive('send')->andThrow(new \RuntimeException('SMTP exploded'));

        Livewire::test(WorkshopRequestModal::class)
            ->dispatch('openWorkshopRequestModal')
            ->set('name', 'Anna Test')
            ->set('email', 'anna@test.de')
            ->set('phone', '0123456789')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('isSubmitted', true);

        $lead = WorkshopRequest::firstWhere('email', 'anna@test.de');
        $this->assertNotNull($lead, 'lead row must persist even when mail throws');
        $this->assertNull($lead->admin_notified_at);
        $this->assertNull($lead->confirmation_sent_at);
    }

    public function test_admin_mail_envelope_handles_blank_name_gracefully(): void
    {
        $request = WorkshopRequest::create([
            'workshop_slug' => 'plattform-discovery',
            'name' => '',
            'email' => 'leer@test.de',
        ]);

        $envelope = (new WorkshopRequestAdmin($request))->envelope();

        $this->assertNotEmpty($envelope->replyTo);
    }

    /**
     * Reproduces the live 500: a name with characters that fail RFC-2822
     * "phrase" validation (commas, parens, apostrophes). Sending the full
     * Mailable through the real (faked) mailer exercises the same code
     * path that crashed live — Mail::fake() short-circuits delivery but
     * still constructs the envelope and message exactly like real send.
     *
     * @dataProvider tricky_name_provider
     */
    public function test_admin_mail_survives_tricky_names_in_reply_to(string $trickyName): void
    {
        $request = WorkshopRequest::create([
            'workshop_slug' => 'plattform-discovery',
            'name' => $trickyName,
            'email' => 'tricky@test.de',
        ]);

        Mail::fake();
        // Must not throw — the previous code crashed on these names.
        Mail::to('admin@sdwebdesign.de')->send(new WorkshopRequestAdmin($request));

        Mail::assertSent(WorkshopRequestAdmin::class);
    }

    public static function tricky_name_provider(): array
    {
        return [
            'name with comma' => ['Müller, Anna'],
            'name with parens' => ['Anna (CEO)'],
            'name with apostrophe' => ["O'Brien"],
            'name with semicolon' => ['Test; Inc'],
            'name with angle brackets' => ['<Anna>'],
            'name with at sign' => ['anna@test'],
            'empty name' => [''],
            'whitespace name' => ['   '],
        ];
    }

    public function test_admin_email_falls_back_when_setting_is_empty_string(): void
    {
        // Empty string in Setting must not crash Mail::to('').
        Setting::create(['email' => '']);
        config(['mail.from.address' => 'fallback@example.com']);

        Livewire::test(WorkshopRequestModal::class)
            ->dispatch('openWorkshopRequestModal')
            ->set('name', 'OK')
            ->set('email', 'visitor@test.de')
            ->set('phone', '0123456789')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('isSubmitted', true);

        $this->assertNotNull(WorkshopRequest::firstWhere('email', 'visitor@test.de'));
    }

    public function test_admin_mail_uses_request_email_as_reply_to(): void
    {
        Mail::fake();
        Setting::create(['email' => 'admin@sdwebdesign.de']);

        Livewire::test(WorkshopRequestModal::class)
            ->dispatch('openWorkshopRequestModal')
            ->set('name', 'Otto Test')
            ->set('email', 'otto@test.de')
            ->set('phone', '0123456789')
            ->set('consent', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('isSubmitted', true);

        $request = WorkshopRequest::firstWhere('email', 'otto@test.de');
        $envelope = (new WorkshopRequestAdmin($request))->envelope();

        $this->assertNotEmpty($envelope->replyTo);
        $addresses = collect($envelope->replyTo);
        $this->assertTrue(
            $addresses->contains(fn ($a) => (is_object($a) ? $a->address : $a) === 'otto@test.de')
            || $addresses->keys()->contains('otto@test.de'),
            'reply-to must contain the request email'
        );
    }

    public function test_discovery_page_cta_dispatches_workshop_modal_after_migration(): void
    {
        $hub = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'plattformen', 'en' => 'plattformen'],
            'title' => ['de' => 'Plattformen', 'en' => 'Platforms'],
            'parent_id' => null,
            'is_active' => true,
            'content' => ['de' => []],
        ]);

        $createDiscovery = require database_path('migrations/2026_04_25_174042_create_plattform_discovery_lead_magnet.php');
        $createDiscovery->up();

        $body = $this->get('/loesungen/plattformen/plattform-discovery')
            ->assertStatus(200)
            ->getContent();
        $this->assertStringContainsString('data-modal-event="openContactModal"', $body);
        $this->assertStringNotContainsString('data-modal-event="openWorkshopRequestModal"', $body);

        $route = require database_path('migrations/2026_04_26_111206_route_discovery_cta_to_workshop_modal.php');
        $route->up();

        $body = $this->get('/loesungen/plattformen/plattform-discovery')->getContent();
        $this->assertStringContainsString('data-modal-event="openWorkshopRequestModal"', $body);
        // Payload is HTML-escaped inside the data-* attribute (browser parses
        // it back to JSON on read), so look for the escaped form.
        $this->assertStringContainsString('&quot;slug&quot;:&quot;plattform-discovery&quot;', $body);
    }

    public function test_other_solution_detail_pages_still_use_generic_contact_modal(): void
    {
        $hub = Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_HUB,
            'slug' => ['de' => 'plattformen', 'en' => 'plattformen'],
            'title' => ['de' => 'Plattformen', 'en' => 'Platforms'],
            'parent_id' => null,
            'is_active' => true,
            'content' => ['de' => []],
        ]);
        Page::factory()->create([
            'type' => Page::TYPE_SOLUTION_DETAIL,
            'parent_id' => $hub->id,
            'slug' => ['de' => 'kundenportal', 'en' => 'customer-portal'],
            'title' => ['de' => 'Kundenportal', 'en' => 'Customer Portal'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['tagline' => 'x'],
                    'cta' => ['title' => 'CTA', 'button_text' => 'Anfragen'],
                ],
            ],
        ]);

        $body = $this->get('/loesungen/plattformen/kundenportal')->assertStatus(200)->getContent();

        $this->assertStringContainsString('data-modal-event="openContactModal"', $body);
        $this->assertStringNotContainsString('data-modal-event="openWorkshopRequestModal"', $body);
    }
}

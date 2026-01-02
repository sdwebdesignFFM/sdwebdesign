<?php

namespace Tests\Feature;

use App\Livewire\ContactModal;
use App\Mail\ContactRequestAdmin;
use App\Mail\ContactRequestConfirmation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ContactModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_modal_can_be_opened(): void
    {
        Livewire::test(ContactModal::class)
            ->assertSet('isOpen', false)
            ->dispatch('openContactModal')
            ->assertSet('isOpen', true);
    }

    public function test_project_types_can_be_toggled(): void
    {
        Livewire::test(ContactModal::class)
            ->assertSet('selectedProjectTypes', [])
            ->call('toggleProjectType', 'webdesign')
            ->assertSet('selectedProjectTypes', ['webdesign'])
            ->call('toggleProjectType', 'ecommerce')
            ->assertCount('selectedProjectTypes', 2)
            ->call('toggleProjectType', 'webdesign')
            ->assertSet('selectedProjectTypes', ['ecommerce']);
    }

    public function test_budget_can_be_selected(): void
    {
        Livewire::test(ContactModal::class)
            ->assertSet('budget', '')
            ->call('selectBudget', '5k_15k')
            ->assertSet('budget', '5k_15k');
    }

    public function test_timeline_can_be_selected(): void
    {
        Livewire::test(ContactModal::class)
            ->assertSet('timeline', '')
            ->call('selectTimeline', '1_3_months')
            ->assertSet('timeline', '1_3_months');
    }

    public function test_callback_days_can_be_toggled(): void
    {
        Livewire::test(ContactModal::class)
            ->assertSet('selectedCallbackDays', [])
            ->call('toggleCallbackDay', 'mo')
            ->assertSet('selectedCallbackDays', ['mo'])
            ->call('toggleCallbackDay', 'mi')
            ->assertCount('selectedCallbackDays', 2)
            ->call('toggleCallbackDay', 'mo')
            ->assertSet('selectedCallbackDays', ['mi']);
    }

    public function test_callback_time_can_be_selected(): void
    {
        Livewire::test(ContactModal::class)
            ->assertSet('callbackTime', '')
            ->call('selectCallbackTime', 'morning')
            ->assertSet('callbackTime', 'morning');
    }

    public function test_cannot_advance_from_step_1_without_project_type(): void
    {
        Livewire::test(ContactModal::class)
            ->assertSet('currentStep', 1)
            ->call('nextStep')
            ->assertSet('currentStep', 1);
    }

    public function test_can_advance_from_step_1_with_project_type(): void
    {
        Livewire::test(ContactModal::class)
            ->set('selectedProjectTypes', ['webdesign'])
            ->call('nextStep')
            ->assertSet('currentStep', 2);
    }

    public function test_can_navigate_steps(): void
    {
        Livewire::test(ContactModal::class)
            ->set('selectedProjectTypes', ['webdesign'])
            ->call('nextStep')
            ->assertSet('currentStep', 2)
            ->call('nextStep')
            ->assertSet('currentStep', 3)
            ->call('previousStep')
            ->assertSet('currentStep', 2);
    }

    public function test_form_validation(): void
    {
        Livewire::test(ContactModal::class)
            ->set('selectedProjectTypes', ['webdesign'])
            ->set('currentStep', 3)
            ->call('submit')
            ->assertHasErrors(['name', 'email']);
    }

    public function test_submit_sends_emails_and_redirects(): void
    {
        Mail::fake();

        Livewire::test(ContactModal::class)
            ->set('selectedProjectTypes', ['webdesign'])
            ->set('budget', '5k_15k')
            ->set('timeline', '1_3_months')
            ->set('currentStep', 3)
            ->set('name', 'Max Mustermann')
            ->set('email', 'max@test.de')
            ->set('company', 'Test GmbH')
            ->set('phone', '+49 123 456789')
            ->set('projectDescription', 'Ein Testprojekt')
            ->call('submit')
            ->assertRedirect(localized_route('contact.thank-you'));

        Mail::assertSent(ContactRequestAdmin::class);
        Mail::assertSent(ContactRequestConfirmation::class, function ($mail) {
            return $mail->hasTo('max@test.de');
        });
    }

    public function test_session_data_is_set_after_submit(): void
    {
        Mail::fake();

        Livewire::test(ContactModal::class)
            ->set('selectedProjectTypes', ['webdesign'])
            ->set('budget', '5k_15k')
            ->set('timeline', '1_3_months')
            ->set('currentStep', 3)
            ->set('name', 'Max Mustermann')
            ->set('email', 'max@test.de')
            ->call('submit');

        $this->assertTrue(session()->has('contact_submitted'));
        $this->assertEquals('Max Mustermann', session('contact_data.name'));
    }

    public function test_rate_limiting_blocks_excessive_submissions(): void
    {
        Mail::fake();

        // Submit 5 times (allowed limit)
        for ($i = 1; $i <= 5; $i++) {
            Livewire::test(ContactModal::class)
                ->set('selectedProjectTypes', ['webdesign'])
                ->set('currentStep', 3)
                ->set('name', "Test User {$i}")
                ->set('email', "test{$i}@example.de")
                ->call('submit');
        }

        // 6th submission should be rate limited
        Livewire::test(ContactModal::class)
            ->set('selectedProjectTypes', ['webdesign'])
            ->set('currentStep', 3)
            ->set('name', 'Blocked User')
            ->set('email', 'blocked@example.de')
            ->call('submit')
            ->assertSet('rateLimitError', fn ($error) => str_contains($error, 'Zu viele Anfragen'));
    }
}

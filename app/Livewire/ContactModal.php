<?php

namespace App\Livewire;

use App\Mail\ContactRequestAdmin;
use App\Mail\ContactRequestConfirmation;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactModal extends Component
{
    public bool $isOpen = false;

    public int $currentStep = 1;

    public int $totalSteps = 3;

    // Step 1: Project Types (Multiple Selection)
    public array $selectedProjectTypes = [];

    // Step 2: Budget & Timeline
    public string $budget = '';

    public string $timeline = '';

    // Step 3: Contact Information
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:255')]
    public string $company = '';

    #[Validate('nullable|string|max:50')]
    public string $phone = '';

    #[Validate('nullable|string|max:2000')]
    public string $projectDescription = '';

    // Callback preferences
    public array $selectedCallbackDays = [];

    public string $callbackTime = '';

    public bool $isSubmitting = false;

    public bool $isSubmitted = false;

    public string $rateLimitError = '';

    public function getProjectTypesProperty(): array
    {
        return [
            'websites' => [
                'label' => __('contact.project_type_websites'),
                'description' => __('contact.project_type_websites_desc'),
                'icon' => 'globe',
            ],
            'platforms' => [
                'label' => __('contact.project_type_platforms'),
                'description' => __('contact.project_type_platforms_desc'),
                'icon' => 'layout-dashboard',
            ],
            'ecommerce' => [
                'label' => __('contact.project_type_ecommerce'),
                'description' => __('contact.project_type_ecommerce_desc'),
                'icon' => 'shopping-cart',
            ],
            'mobile' => [
                'label' => __('contact.project_type_mobile'),
                'description' => __('contact.project_type_mobile_desc'),
                'icon' => 'device-phone-mobile',
            ],
            'seo' => [
                'label' => __('contact.project_type_seo'),
                'description' => __('contact.project_type_seo_desc'),
                'icon' => 'magnifying-glass',
            ],
            'sea' => [
                'label' => __('contact.project_type_sea'),
                'description' => __('contact.project_type_sea_desc'),
                'icon' => 'currency-euro',
            ],
        ];
    }

    public function getBudgetsProperty(): array
    {
        return [
            'under_5k' => __('contact.budget_under_5k'),
            '5k_15k' => __('contact.budget_5k_15k'),
            '15k_50k' => __('contact.budget_15k_50k'),
            'over_50k' => __('contact.budget_over_50k'),
        ];
    }

    public function getTimelinesProperty(): array
    {
        return [
            'asap' => __('contact.timeline_asap'),
            '1_3_months' => __('contact.timeline_1_3_months'),
            '3_6_months' => __('contact.timeline_3_6_months'),
            'flexible' => __('contact.timeline_flexible'),
        ];
    }

    public function getCallbackDaysProperty(): array
    {
        return [
            'mo' => __('contact.day_mo'),
            'di' => __('contact.day_di'),
            'mi' => __('contact.day_mi'),
            'do' => __('contact.day_do'),
            'fr' => __('contact.day_fr'),
            'sa' => __('contact.day_sa'),
        ];
    }

    public function getCallbackTimesProperty(): array
    {
        return [
            'morning' => __('contact.time_morning'),
            'noon' => __('contact.time_noon'),
            'afternoon' => __('contact.time_afternoon'),
            'evening' => __('contact.time_evening'),
        ];
    }

    #[On('openContactModal')]
    public function open(): void
    {
        $this->isOpen = true;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->currentStep = 1;
        $this->selectedProjectTypes = [];
        $this->budget = '';
        $this->timeline = '';
        $this->name = '';
        $this->email = '';
        $this->company = '';
        $this->phone = '';
        $this->projectDescription = '';
        $this->selectedCallbackDays = [];
        $this->callbackTime = '';
        $this->isSubmitted = false;
        $this->rateLimitError = '';
    }

    public function toggleCallbackDay(string $day): void
    {
        if (in_array($day, $this->selectedCallbackDays)) {
            $this->selectedCallbackDays = array_values(array_diff($this->selectedCallbackDays, [$day]));
        } else {
            $this->selectedCallbackDays[] = $day;
        }
    }

    public function selectCallbackTime(string $time): void
    {
        $this->callbackTime = $this->callbackTime === $time ? '' : $time;
    }

    public function getSelectedCallbackDaysLabels(): array
    {
        return array_map(
            fn ($day) => $this->callbackDays[$day] ?? $day,
            $this->selectedCallbackDays
        );
    }

    public function getSelectedProjectTypesLabels(): array
    {
        return array_map(
            fn ($type) => $this->projectTypes[$type]['label'] ?? $type,
            $this->selectedProjectTypes
        );
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function toggleProjectType(string $type): void
    {
        if (in_array($type, $this->selectedProjectTypes)) {
            $this->selectedProjectTypes = array_values(array_diff($this->selectedProjectTypes, [$type]));
        } else {
            $this->selectedProjectTypes[] = $type;
        }
    }

    public function selectBudget(string $budget): void
    {
        $this->budget = $this->budget === $budget ? '' : $budget;
    }

    public function selectTimeline(string $timeline): void
    {
        $this->timeline = $this->timeline === $timeline ? '' : $timeline;
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1 && empty($this->selectedProjectTypes)) {
            return;
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function submit(): mixed
    {
        $this->rateLimitError = '';

        // Rate limiting: 5 submissions per hour per IP
        $rateLimitKey = 'contact-form:'.request()->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $minutes = ceil($seconds / 60);
            $this->rateLimitError = __('contact.rate_limit_error', ['minutes' => $minutes]);

            return null;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'projectDescription' => 'nullable|string|max:2000',
        ]);

        // Increment rate limiter on successful validation
        RateLimiter::hit($rateLimitKey, 3600); // 1 hour decay

        $this->isSubmitting = true;

        $settings = Setting::instance();

        $emailData = [
            'name' => $this->name,
            'email' => $this->email,
            'company' => $this->company,
            'phone' => $this->phone,
            'projectTypes' => $this->getSelectedProjectTypesLabels(),
            'budget' => $this->budgets[$this->budget] ?? '',
            'timeline' => $this->timelines[$this->timeline] ?? '',
            'projectDescription' => $this->projectDescription,
            'callbackDays' => $this->getSelectedCallbackDaysLabels(),
            'callbackTime' => $this->callbackTimes[$this->callbackTime] ?? '',
        ];

        // Send notification to admin
        Mail::to($settings->email ?? 'info@sdwebdesign.de')
            ->send(new ContactRequestAdmin($emailData));

        // Send confirmation to customer
        Mail::to($this->email)
            ->send(new ContactRequestConfirmation($emailData));

        // Store data in session for thank-you page
        session()->put('contact_submitted', true);
        session()->put('contact_data', $emailData);

        $this->isSubmitting = false;
        $this->isOpen = false;

        return $this->redirect(localized_route('contact.thank-you'), navigate: true);
    }

    public function render()
    {
        return view('livewire.contact-modal');
    }
}

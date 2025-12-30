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

    public array $projectTypes = [
        'webdesign' => [
            'label' => 'Webdesign & UI/UX',
            'description' => 'Firmenwebseite, Relaunch',
            'icon' => 'layout',
        ],
        'web_application' => [
            'label' => 'Webanwendung',
            'description' => 'Firmenprozesse digitalisieren, Portale',
            'icon' => 'code',
        ],
        'ecommerce' => [
            'label' => 'E-Commerce / Shop',
            'description' => 'Online-Shop, Marktplatz, B2B-Portal',
            'icon' => 'shopping-cart',
        ],
        'mobile_app' => [
            'label' => 'Mobile App',
            'description' => 'iOS & Android Apps, PWA',
            'icon' => 'smartphone',
        ],
        'api_integration' => [
            'label' => 'API & Integration',
            'description' => 'Systeme verbinden, Automatisierung',
            'icon' => 'git-merge',
        ],
        'ai_integration' => [
            'label' => 'KI-Integration',
            'description' => 'Chatbots, Automatisierung, AI-Tools',
            'icon' => 'cpu',
        ],
    ];

    // Step 2: Budget & Timeline
    public string $budget = '';

    public array $budgets = [
        'under_5k' => '< 5.000 €',
        '5k_15k' => '5.000 - 15.000 €',
        '15k_50k' => '15.000 - 50.000 €',
        'over_50k' => '> 50.000 €',
    ];

    public string $timeline = '';

    public array $timelines = [
        'asap' => 'So schnell wie möglich',
        '1_3_months' => '1-3 Monate',
        '3_6_months' => '3-6 Monate',
        'flexible' => 'Flexibel',
    ];

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

    public array $callbackDays = [
        'mo' => 'Mo',
        'di' => 'Di',
        'mi' => 'Mi',
        'do' => 'Do',
        'fr' => 'Fr',
        'sa' => 'Sa',
    ];

    public string $callbackTime = '';

    public array $callbackTimes = [
        'morning' => '9 - 12 Uhr',
        'noon' => '12 - 14 Uhr',
        'afternoon' => '14 - 18 Uhr',
        'evening' => '18 - 20 Uhr',
    ];

    public bool $isSubmitting = false;

    public bool $isSubmitted = false;

    public string $rateLimitError = '';

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

    public function getSelectedProjectTypesLabels(): array
    {
        return array_map(
            fn ($type) => $this->projectTypes[$type]['label'] ?? $type,
            $this->selectedProjectTypes
        );
    }

    public function submit(): mixed
    {
        $this->rateLimitError = '';

        // Rate limiting: 5 submissions per hour per IP
        $rateLimitKey = 'contact-form:' . request()->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $minutes = ceil($seconds / 60);
            $this->rateLimitError = "Zu viele Anfragen. Bitte versuchen Sie es in {$minutes} Minuten erneut.";

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

        return $this->redirect(route('contact.thank-you'), navigate: true);
    }

    public function render()
    {
        return view('livewire.contact-modal');
    }
}

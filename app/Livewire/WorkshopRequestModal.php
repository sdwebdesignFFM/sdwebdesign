<?php

namespace App\Livewire;

use App\Mail\WorkshopRequestAdmin;
use App\Mail\WorkshopRequestConfirmation;
use App\Models\Setting;
use App\Models\WorkshopRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class WorkshopRequestModal extends Component
{
    public bool $isOpen = false;

    public int $currentStep = 1;

    public int $totalSteps = 4;

    public string $workshopSlug = 'plattform-discovery';

    // Step 1 — Vorhaben
    public string $triggerQuestion = '';

    public string $industry = '';

    public array $workflowAreas = [];

    // Step 2 — Stand & Bestand
    public array $existingSystems = [];

    public string $procurementStage = '';

    public string $budgetIndication = '';

    public string $goLiveTimeline = '';

    // Step 3 — Workshop-Format
    public string $workshopFormat = '';

    public string $preferredTiming = '';

    public string $preferredDaytime = '';

    // Step 4 — Kontakt
    #[Validate('required|string|max:191')]
    public string $name = '';

    #[Validate('required|email|max:191')]
    public string $email = '';

    #[Validate('required|string|max:50')]
    public string $phone = '';

    #[Validate('nullable|string|max:191')]
    public string $company = '';

    #[Validate('nullable|string|max:120')]
    public string $role = '';

    public string $companySize = '';

    #[Validate('nullable|string|max:2000')]
    public string $briefingNotes = '';

    #[Validate('accepted')]
    public bool $consent = false;

    public bool $isSubmitting = false;

    public bool $isSubmitted = false;

    public string $rateLimitError = '';

    public function getIndustryOptionsProperty(): array
    {
        return [
            'industrie' => 'Industrie / Fertigung',
            'handel' => 'Handel / E-Commerce',
            'dienstleistung' => 'Dienstleistung / Beratung',
            'logistik' => 'Logistik / Transport',
            'personal' => 'Personalvermittlung / -dienstleistung',
            'gesundheit' => 'Gesundheit / Pharma',
            'bildung' => 'Bildung / Training',
            'finanzen' => 'Finanzen / Versicherung',
            'sonstige' => 'Andere Branche',
        ];
    }

    public function getWorkflowAreaOptionsProperty(): array
    {
        return [
            'disposition' => 'Disposition / Workforce-Management',
            'kundenportal' => 'Kunden- / Partnerportal',
            'internes_tool' => 'Internes Tool / Verwaltungsplattform',
            'b2b_shop' => 'B2B-Bestellplattform',
            'datenintegration' => 'Datenintegration / Schnittstellen',
            'reporting' => 'Reporting / Auswertungen',
            'sonstiges' => 'Etwas anderes',
        ];
    }

    public function getExistingSystemsOptionsProperty(): array
    {
        return [
            'personio' => 'Personio',
            'sap' => 'SAP',
            'dynamics' => 'Microsoft Dynamics',
            'datev' => 'DATEV',
            'lexware' => 'Lexware / Sage',
            'shopify' => 'Shopify',
            'salesforce' => 'Salesforce',
            'excel' => 'Excel- / Office-Listen',
            'eigene_tools' => 'Eigene Tools / Access',
            'andere' => 'Andere',
            'keine' => 'Noch keine relevanten Systeme',
        ];
    }

    public function getProcurementStageOptionsProperty(): array
    {
        return [
            'recherche' => 'Noch in der Recherche',
            'erste_gespraeche' => 'Erste Gespräche mit Anbietern',
            'angebote' => 'Angebote eingeholt, suche zweite Meinung',
            'ausgewaehlt' => 'Anbieter im Kopf, will Validierung',
        ];
    }

    public function getBudgetOptionsProperty(): array
    {
        return [
            'unklar' => 'Noch unklar',
            '5_stellig' => '5-stellig (10–99 k €)',
            '6_stellig_klein' => '6-stellig (100–250 k €)',
            '6_stellig_gross' => '6-stellig (250 k €+)',
        ];
    }

    public function getGoLiveOptionsProperty(): array
    {
        return [
            '3_monate' => 'Innerhalb 3 Monaten',
            '6_monate' => 'In 3–6 Monaten',
            '12_monate' => 'In 6–12 Monaten',
            'spaeter' => 'Später / unklar',
        ];
    }

    public function getWorkshopFormatOptionsProperty(): array
    {
        return [
            'vor_ort' => 'Vor Ort in Frankfurt',
            'remote' => 'Remote per Video',
            'egal' => 'Egal',
        ];
    }

    public function getTimingOptionsProperty(): array
    {
        return [
            'naechste_woche' => 'Nächste Woche',
            'in_2_wochen' => 'Innerhalb 2 Wochen',
            'in_4_wochen' => 'Innerhalb 4 Wochen',
            'flexibel' => 'Flexibel',
        ];
    }

    public function getDaytimeOptionsProperty(): array
    {
        return [
            'vormittag' => 'Vormittag (9–12 Uhr)',
            'nachmittag' => 'Nachmittag (13–17 Uhr)',
            'egal' => 'Egal',
        ];
    }

    public function getCompanySizeOptionsProperty(): array
    {
        return [
            'unter_10' => 'Unter 10 Mitarbeiter',
            '10_bis_50' => '10–50 Mitarbeiter',
            '50_bis_250' => '50–250 Mitarbeiter',
            'ueber_250' => 'Über 250 Mitarbeiter',
        ];
    }

    #[On('openWorkshopRequestModal')]
    public function open(string $slug = 'plattform-discovery'): void
    {
        $this->resetForm();
        $this->workshopSlug = $slug;
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    public function resetForm(): void
    {
        $this->currentStep = 1;
        $this->triggerQuestion = '';
        $this->industry = '';
        $this->workflowAreas = [];
        $this->existingSystems = [];
        $this->procurementStage = '';
        $this->budgetIndication = '';
        $this->goLiveTimeline = '';
        $this->workshopFormat = '';
        $this->preferredTiming = '';
        $this->preferredDaytime = '';
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->company = '';
        $this->role = '';
        $this->companySize = '';
        $this->briefingNotes = '';
        $this->consent = false;
        $this->isSubmitted = false;
        $this->rateLimitError = '';
    }

    public function toggleArrayValue(string $field, string $value): void
    {
        $current = $this->{$field} ?? [];
        if (in_array($value, $current, true)) {
            $this->{$field} = array_values(array_diff($current, [$value]));
        } else {
            $current[] = $value;
            $this->{$field} = $current;
        }
    }

    public function setSingleValue(string $field, string $value): void
    {
        $this->{$field} = $this->{$field} === $value ? '' : $value;
    }

    public function nextStep(): void
    {
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

    public function submit(): void
    {
        $this->rateLimitError = '';

        $rateLimitKey = 'workshop-request:'.request()->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $minutes = ceil(RateLimiter::availableIn($rateLimitKey) / 60);
            $this->rateLimitError = "Zu viele Anfragen. Bitte versuchen Sie es in {$minutes} Minuten erneut.";

            return;
        }

        $this->validate();

        RateLimiter::hit($rateLimitKey, 3600);
        $this->isSubmitting = true;

        $request = WorkshopRequest::create([
            'workshop_slug' => $this->workshopSlug,
            'trigger_question' => $this->triggerQuestion ?: null,
            'industry' => $this->industryOptions[$this->industry] ?? null,
            'workflow_areas' => array_values(array_filter(array_map(
                fn ($v) => $this->workflowAreaOptions[$v] ?? null,
                $this->workflowAreas
            ))),
            'existing_systems' => array_values(array_filter(array_map(
                fn ($v) => $this->existingSystemsOptions[$v] ?? null,
                $this->existingSystems
            ))),
            'procurement_stage' => $this->procurementStageOptions[$this->procurementStage] ?? null,
            'budget_indication' => $this->budgetOptions[$this->budgetIndication] ?? null,
            'go_live_timeline' => $this->goLiveOptions[$this->goLiveTimeline] ?? null,
            'workshop_format' => $this->workshopFormatOptions[$this->workshopFormat] ?? null,
            'preferred_timing' => $this->timingOptions[$this->preferredTiming] ?? null,
            'preferred_daytime' => $this->daytimeOptions[$this->preferredDaytime] ?? null,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company ?: null,
            'role' => $this->role ?: null,
            'company_size' => $this->companySizeOptions[$this->companySize] ?? null,
            'briefing_notes' => $this->briefingNotes ?: null,
            'locale' => app()->getLocale(),
            'ip' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 191),
        ]);

        // Use first non-empty value — Setting::email may be persisted as ''
        // which the null-coalesce operator wouldn't replace.
        $adminEmail = collect([
            Setting::first()?->email,
            config('mail.from.address'),
            'info@sdwebdesign.de',
        ])->first(fn ($v) => is_string($v) && trim($v) !== '');

        // Mail dispatch is wrapped in try/catch so a transient SMTP outage,
        // an RFC-compliance error or a misconfigured from-address never
        // takes the lead capture down with it. The lead row is already in
        // the DB by the time we get here — Steffen can always see it via
        // tinker or a future Filament resource even if no mail went out.
        try {
            Mail::to($adminEmail)->send(new WorkshopRequestAdmin($request));
            $request->update(['admin_notified_at' => now()]);
        } catch (Throwable $e) {
            Log::error('WorkshopRequestAdmin mail failed', [
                'lead_id' => $request->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
            Mail::to($request->email)->send(new WorkshopRequestConfirmation($request));
            $request->update(['confirmation_sent_at' => now()]);
        } catch (Throwable $e) {
            Log::error('WorkshopRequestConfirmation mail failed', [
                'lead_id' => $request->id,
                'error' => $e->getMessage(),
            ]);
        }

        $this->isSubmitting = false;
        $this->isSubmitted = true;
    }

    public function render()
    {
        return view('livewire.workshop-request-modal');
    }
}

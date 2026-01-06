<?php

namespace App\Livewire;

use App\Models\Quote;
use App\Models\Setting;
use App\Services\Quote\QuoteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class QuoteAcceptance extends Component
{
    public Quote $quote;

    /** @var array<int, bool> */
    public array $selectedOptions = [];

    /** @var array<string, int> */
    public array $optionGroupSelections = [];

    // Modal state
    public bool $showAcceptanceForm = false;

    public bool $showTermsModal = false;

    public bool $showAgbModal = false;

    public ?int $termsItemId = null;

    public int $currentStep = 1;

    public ?string $errorMessage = null;

    // Step 1: Billing details
    public string $billingCompany = '';

    public string $billingFirstName = '';

    public string $billingLastName = '';

    public string $billingStreet = '';

    public string $billingZip = '';

    public string $billingCity = '';

    public string $billingCountry = 'Deutschland';

    public string $billingVatId = '';

    // Step 2: Terms & Signature
    public string $acceptedName = '';

    public bool $termsAccepted = false;

    public string $signatureData = '';

    public function mount(Quote $quote): void
    {
        $this->quote = $quote->load(['items' => fn ($q) => $q->orderBy('sort_order'), 'client']);

        // Pre-fill billing details from linked client or quote client data
        if ($quote->client) {
            $this->billingCompany = $quote->client->company ?? '';
            $this->billingFirstName = $quote->client->first_name ?? '';
            $this->billingLastName = $quote->client->last_name ?? '';
            $this->billingStreet = $quote->client->street ?? '';
            $this->billingZip = $quote->client->zip ?? '';
            $this->billingCity = $quote->client->city ?? '';
            $this->billingCountry = $quote->client->country ?? 'Deutschland';
        } else {
            $this->billingCompany = $quote->client_company ?? '';
        }

        // Initialize selected options
        foreach ($this->quote->items as $item) {
            if ($item->is_optional) {
                $this->selectedOptions[$item->id] = $item->is_selected;
            }

            // Initialize option group selections
            if ($item->option_group && $item->is_selected) {
                $this->optionGroupSelections[$item->option_group] = $item->id;
            }
        }
    }

    public function toggleOption(int $itemId): void
    {
        if (! $this->quote->canBeAccepted()) {
            return;
        }

        $item = $this->quote->items->firstWhere('id', $itemId);
        if (! $item || ! $item->is_optional) {
            return;
        }

        // If it's part of an option group, handle as radio button
        if ($item->option_group) {
            $this->selectOptionGroup($item->option_group, $itemId);

            return;
        }

        $this->selectedOptions[$itemId] = ! ($this->selectedOptions[$itemId] ?? false);
        $this->updateOptionOnServer($itemId, $this->selectedOptions[$itemId]);
    }

    public function selectOptionGroup(string $group, int $itemId): void
    {
        if (! $this->quote->canBeAccepted()) {
            return;
        }

        // Deselect all items in this group
        foreach ($this->quote->items as $item) {
            if ($item->option_group === $group) {
                $this->selectedOptions[$item->id] = false;
                $this->updateOptionOnServer($item->id, false);
            }
        }

        // Select the chosen item
        $this->selectedOptions[$itemId] = true;
        $this->optionGroupSelections[$group] = $itemId;
        $this->updateOptionOnServer($itemId, true);
    }

    private function updateOptionOnServer(int $itemId, bool $isSelected): void
    {
        $quoteService = app(QuoteService::class);
        $quoteService->updateOptionSelection($this->quote, $itemId, $isSelected);
        $this->quote->refresh();
        $this->quote->load(['items' => fn ($q) => $q->orderBy('sort_order')]);
    }

    public function showAcceptForm(): void
    {
        $this->showAcceptanceForm = true;
        $this->currentStep = 1;
        $this->errorMessage = null;
    }

    public function hideAcceptForm(): void
    {
        $this->showAcceptanceForm = false;
        $this->currentStep = 1;
        $this->errorMessage = null;
    }

    public function showTerms(int $itemId): void
    {
        $this->termsItemId = $itemId;
        $this->showTermsModal = true;
    }

    public function hideTerms(): void
    {
        $this->showTermsModal = false;
        $this->termsItemId = null;
    }

    public function getTermsItemProperty(): ?\App\Models\QuoteItem
    {
        if (! $this->termsItemId) {
            return null;
        }

        return $this->quote->items->firstWhere('id', $this->termsItemId);
    }

    public function nextStep(): void
    {
        $this->errorMessage = null;

        if ($this->currentStep === 1) {
            $this->validate([
                'billingFirstName' => 'required|string|min:2|max:255',
                'billingLastName' => 'required|string|min:2|max:255',
                'billingStreet' => 'required|string|max:255',
                'billingZip' => 'required|string|max:10',
                'billingCity' => 'required|string|max:255',
            ], [
                'billingFirstName.required' => 'Bitte geben Sie einen Vornamen ein.',
                'billingFirstName.min' => 'Der Vorname muss mindestens 2 Zeichen lang sein.',
                'billingLastName.required' => 'Bitte geben Sie einen Nachnamen ein.',
                'billingLastName.min' => 'Der Nachname muss mindestens 2 Zeichen lang sein.',
                'billingStreet.required' => 'Bitte geben Sie eine Straße ein.',
                'billingZip.required' => 'Bitte geben Sie eine Postleitzahl ein.',
                'billingCity.required' => 'Bitte geben Sie eine Stadt ein.',
            ]);

            $this->currentStep = 2;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function clearSignature(): void
    {
        $this->signatureData = '';
        $this->dispatch('signature-cleared');
    }

    public function accept(): void
    {
        $this->errorMessage = null;

        if (! $this->quote->canBeAccepted()) {
            $this->errorMessage = 'Dieses Angebot kann nicht mehr angenommen werden.';

            return;
        }

        $this->validate([
            'acceptedName' => 'required|string|min:3|max:255',
            'termsAccepted' => 'accepted',
            'signatureData' => 'required|string',
        ], [
            'acceptedName.required' => 'Bitte geben Sie Ihren vollständigen Namen ein.',
            'acceptedName.min' => 'Der Name muss mindestens 3 Zeichen lang sein.',
            'termsAccepted.accepted' => 'Bitte akzeptieren Sie die AGB und Leistungsvereinbarungen.',
            'signatureData.required' => 'Bitte unterschreiben Sie das Angebot.',
        ]);

        // Save billing details and acceptance metadata
        $this->quote->update([
            'billing_company' => $this->billingCompany,
            'billing_name' => trim($this->billingFirstName.' '.$this->billingLastName),
            'billing_street' => $this->billingStreet,
            'billing_zip' => $this->billingZip,
            'billing_city' => $this->billingCity,
            'billing_country' => $this->billingCountry,
            'billing_vat_id' => $this->billingVatId,
            'signature_data' => $this->signatureData,
            'signature_at' => now(),
            // Legal proof metadata (internal only, not shown in PDF)
            'accepted_ip' => request()->ip(),
            'accepted_user_agent' => request()->userAgent(),
            'accepted_documents' => $this->quote->buildAcceptedDocuments(),
            'document_hash' => $this->quote->generateDocumentHash(),
        ]);

        $quoteService = app(QuoteService::class);
        $contract = $quoteService->accept($this->quote, $this->acceptedName);

        if ($contract) {
            $this->redirect(route('quotes.accepted', ['token' => $this->quote->token]));

            return;
        }

        $this->errorMessage = 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.';
    }

    /**
     * Download PDF for manual signing.
     */
    public function downloadForSigning(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->errorMessage = null;

        // Validate required fields
        $this->validate([
            'acceptedName' => 'required|string|min:3|max:255',
            'termsAccepted' => 'accepted',
        ], [
            'acceptedName.required' => 'Bitte geben Sie Ihren vollständigen Namen ein.',
            'acceptedName.min' => 'Der Name muss mindestens 3 Zeichen lang sein.',
            'termsAccepted.accepted' => 'Bitte bestätigen Sie die Vertragsbedingungen.',
        ]);

        // Save billing details (but don't mark as accepted yet)
        $this->quote->update([
            'billing_company' => $this->billingCompany,
            'billing_name' => trim($this->billingFirstName.' '.$this->billingLastName),
            'billing_street' => $this->billingStreet,
            'billing_zip' => $this->billingZip,
            'billing_city' => $this->billingCity,
            'billing_country' => $this->billingCountry,
            'billing_vat_id' => $this->billingVatId,
        ]);

        // Generate PDF with signature area
        $pdf = Pdf::loadView('pdfs.quote-for-signing', [
            'quote' => $this->quote->load('items'),
            'settings' => Setting::instance(),
            'acceptedName' => $this->acceptedName,
        ]);

        $filename = 'Angebot-'.$this->quote->quote_number.'-zur-Unterschrift.pdf';

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Get items grouped by type (required, optional, option groups).
     *
     * @return array{required: \Illuminate\Support\Collection, optional: \Illuminate\Support\Collection, option_groups: array<string, \Illuminate\Support\Collection>}
     */
    public function getGroupedItemsProperty(): array
    {
        $items = $this->quote->items;

        $required = $items->filter(fn ($item) => ! $item->is_optional && ! $item->option_group);
        $optional = $items->filter(fn ($item) => $item->is_optional && ! $item->option_group);

        // Group option items by their option_group
        $optionGroups = [];
        foreach ($items->filter(fn ($item) => $item->option_group) as $item) {
            $optionGroups[$item->option_group][] = $item;
        }

        return [
            'required' => $required,
            'optional' => $optional,
            'option_groups' => $optionGroups,
        ];
    }

    /**
     * Calculate current totals based on selected options.
     *
     * @return array{subtotal: float, tax_amount: float, total: float}
     */
    public function getCurrentTotalsProperty(): array
    {
        return [
            'subtotal' => (float) $this->quote->subtotal,
            'tax_amount' => (float) $this->quote->tax_amount,
            'total' => (float) $this->quote->total,
        ];
    }

    public function render()
    {
        return view('livewire.quote-acceptance', [
            'settings' => Setting::instance(),
        ]);
    }
}

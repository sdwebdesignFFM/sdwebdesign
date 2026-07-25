<?php

namespace App\Livewire;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Setting;
use App\Services\Quote\QuoteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public ?string $errorMessage = null;

    // Billing details
    public string $billingCompany = '';

    public string $billingFirstName = '';

    public string $billingLastName = '';

    public string $billingStreet = '';

    public string $billingZip = '';

    public string $billingCity = '';

    public string $billingCountry = 'Deutschland';

    public bool $termsAccepted = false;

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
        $this->errorMessage = null;
    }

    public function hideAcceptForm(): void
    {
        $this->showAcceptanceForm = false;
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

    public function getTermsItemProperty(): ?QuoteItem
    {
        if (! $this->termsItemId) {
            return null;
        }

        return $this->quote->items->firstWhere('id', $this->termsItemId);
    }

    public function accept(): void
    {
        $this->errorMessage = null;

        if (! $this->quote->canBeAccepted()) {
            $this->errorMessage = 'Dieses Angebot kann nicht mehr angenommen werden.';

            return;
        }

        $this->validate([
            'billingFirstName' => 'required|string|min:2|max:255',
            'billingLastName' => 'required|string|min:2|max:255',
            'billingStreet' => 'required|string|max:255',
            'billingZip' => 'required|string|max:10',
            'billingCity' => 'required|string|max:255',
            'termsAccepted' => 'accepted',
        ], [
            'billingFirstName.required' => 'Bitte geben Sie einen Vornamen ein.',
            'billingFirstName.min' => 'Der Vorname muss mindestens 2 Zeichen lang sein.',
            'billingLastName.required' => 'Bitte geben Sie einen Nachnamen ein.',
            'billingLastName.min' => 'Der Nachname muss mindestens 2 Zeichen lang sein.',
            'billingStreet.required' => 'Bitte geben Sie eine Straße ein.',
            'billingZip.required' => 'Bitte geben Sie eine Postleitzahl ein.',
            'billingCity.required' => 'Bitte geben Sie eine Stadt ein.',
            'termsAccepted.accepted' => 'Bitte akzeptieren Sie die AGB und Leistungsvereinbarungen.',
        ]);

        $acceptedName = trim($this->billingFirstName.' '.$this->billingLastName);

        // Save billing details and acceptance metadata
        $this->quote->update([
            'billing_company' => $this->billingCompany,
            'billing_name' => $acceptedName,
            'billing_street' => $this->billingStreet,
            'billing_zip' => $this->billingZip,
            'billing_city' => $this->billingCity,
            'billing_country' => $this->billingCountry,
            'accepted_at' => now(),
            // Legal proof metadata (internal only, not shown in PDF)
            'accepted_ip' => request()->ip(),
            'accepted_user_agent' => request()->userAgent(),
            'accepted_documents' => $this->quote->buildAcceptedDocuments(),
            'document_hash' => $this->quote->generateDocumentHash(),
        ]);

        $quoteService = app(QuoteService::class);
        $contract = $quoteService->accept($this->quote, $acceptedName);

        if ($contract) {
            $this->redirect(route('quotes.accepted', ['token' => $this->quote->token]));

            return;
        }

        $this->errorMessage = 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.';
    }

    /**
     * Download PDF for manual signing.
     */
    public function downloadForSigning(): StreamedResponse
    {
        $this->errorMessage = null;

        // Validate required fields
        $this->validate([
            'billingFirstName' => 'required|string|min:2|max:255',
            'billingLastName' => 'required|string|min:2|max:255',
            'termsAccepted' => 'accepted',
        ], [
            'billingFirstName.required' => 'Bitte geben Sie einen Vornamen ein.',
            'billingLastName.required' => 'Bitte geben Sie einen Nachnamen ein.',
            'termsAccepted.accepted' => 'Bitte bestätigen Sie die Vertragsbedingungen.',
        ]);

        $acceptedName = trim($this->billingFirstName.' '.$this->billingLastName);

        // Save billing details (but don't mark as accepted yet)
        $this->quote->update([
            'billing_company' => $this->billingCompany,
            'billing_name' => $acceptedName,
            'billing_street' => $this->billingStreet,
            'billing_zip' => $this->billingZip,
            'billing_city' => $this->billingCity,
            'billing_country' => $this->billingCountry,
        ]);

        // Generate PDF with signature area
        $pdf = Pdf::loadView('pdfs.quote-for-signing', [
            'quote' => $this->quote->load('items'),
            'settings' => Setting::instance(),
            'acceptedName' => $acceptedName,
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
     * @return array{required: Collection, optional: Collection, option_groups: array<string, Collection>}
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

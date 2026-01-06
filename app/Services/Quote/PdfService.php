<?php

namespace App\Services\Quote;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Quote;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PdfService
{
    public function __construct(
        private QuoteNumberService $numberService
    ) {}

    /**
     * Generate quote PDF.
     */
    public function createQuotePdf(Quote $quote): string
    {
        $pdf = Pdf::loadView('pdfs.quote', [
            'quote' => $quote->load('items'),
            'company' => $this->getCompanyData(),
            'settings' => Setting::instance(),
        ]);

        $pdf->setPaper('a4');

        $filename = "quotes/{$quote->quote_number}.pdf";
        Storage::disk('local')->put($filename, $pdf->output());

        return $filename;
    }

    /**
     * Generate contract PDF.
     */
    public function createContractPdf(Contract $contract): string
    {
        $pdf = Pdf::loadView('pdfs.contract', [
            'contract' => $contract->load(['items', 'quote']),
            'company' => $this->getCompanyData(),
            'settings' => Setting::instance(),
        ]);

        $pdf->setPaper('a4');

        $filename = "contracts/{$contract->contract_number}.pdf";
        Storage::disk('local')->put($filename, $pdf->output());

        $contract->update(['pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Generate invoice PDF.
     */
    public function createInvoicePdf(Invoice $invoice): string
    {
        $pdf = Pdf::loadView('pdfs.invoice', [
            'invoice' => $invoice->load('items'),
            'company' => $this->getCompanyData(),
        ]);

        $pdf->setPaper('a4');

        $filename = "invoices/{$invoice->invoice_number}.pdf";
        Storage::disk('local')->put($filename, $pdf->output());

        $invoice->update(['pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Generate cancellation PDF.
     */
    public function createCancellationPdf(Invoice $invoice): string
    {
        if (! $invoice->cancellation_number) {
            throw new \RuntimeException('Keine Stornonummer vorhanden.');
        }

        $pdf = Pdf::loadView('pdfs.cancellation', [
            'invoice' => $invoice->load('items'),
            'company' => $this->getCompanyData(),
        ]);

        $pdf->setPaper('a4');

        $filename = "cancellations/{$invoice->cancellation_number}.pdf";
        Storage::disk('local')->put($filename, $pdf->output());

        $invoice->update(['cancellation_pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Download quote PDF.
     */
    public function downloadQuote(Quote $quote): Response
    {
        $this->createQuotePdf($quote);

        $pdf = Pdf::loadView('pdfs.quote', [
            'quote' => $quote->load('items'),
            'company' => $this->getCompanyData(),
            'settings' => Setting::instance(),
        ]);

        return $pdf->download("Angebot-{$quote->quote_number}.pdf");
    }

    /**
     * Download contract PDF.
     */
    public function downloadContract(Contract $contract): Response
    {
        $pdf = Pdf::loadView('pdfs.contract', [
            'contract' => $contract->load(['items', 'quote']),
            'company' => $this->getCompanyData(),
            'settings' => Setting::instance(),
        ]);

        return $pdf->download("Vertrag-{$contract->contract_number}.pdf");
    }

    /**
     * Download invoice PDF.
     */
    public function downloadInvoice(Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('pdfs.invoice', [
            'invoice' => $invoice->load('items'),
            'company' => $this->getCompanyData(),
        ]);

        return $pdf->download("Rechnung-{$invoice->invoice_number}.pdf");
    }

    /**
     * Download cancellation PDF.
     */
    public function downloadCancellation(Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('pdfs.cancellation', [
            'invoice' => $invoice->load('items'),
            'company' => $this->getCompanyData(),
        ]);

        return $pdf->download("Storno-{$invoice->cancellation_number}.pdf");
    }

    /**
     * Get company data for PDFs.
     */
    private function getCompanyData(): array
    {
        $settings = Setting::instance();

        return [
            'name' => $settings->company_name ?? 'SD Webdesign',
            'owner' => $settings->owner_name ?? '',
            'street' => $settings->street ?? '',
            'postal_code' => $settings->postal_code ?? '',
            'city' => $settings->city ?? '',
            'country' => $settings->country ?? 'Deutschland',
            'email' => $settings->email ?? '',
            'phone' => $settings->phone ?? '',
            'website' => $settings->website_url ?? '',
            'vat_id' => $settings->vat_id ?? '',
            'tax_number' => $settings->tax_number ?? '',
            'bank_name' => $settings->bank_name ?? '',
            'bank_iban' => $settings->bank_iban ?? '',
            'bank_bic' => $settings->bank_bic ?? '',
        ];
    }

    /**
     * Stream quote PDF (inline view).
     */
    public function streamQuote(Quote $quote): Response
    {
        $pdf = Pdf::loadView('pdfs.quote', [
            'quote' => $quote->load('items'),
            'company' => $this->getCompanyData(),
            'settings' => Setting::instance(),
        ]);

        return $pdf->stream("Angebot-{$quote->quote_number}.pdf");
    }

    /**
     * Stream invoice PDF (inline view).
     */
    public function streamInvoice(Invoice $invoice): Response
    {
        $pdf = Pdf::loadView('pdfs.invoice', [
            'invoice' => $invoice->load('items'),
            'company' => $this->getCompanyData(),
        ]);

        return $pdf->stream("Rechnung-{$invoice->invoice_number}.pdf");
    }
}

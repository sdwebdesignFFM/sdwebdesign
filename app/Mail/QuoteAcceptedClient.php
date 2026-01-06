<?php

namespace App\Mail;

use App\Models\Contract;
use App\Models\Quote;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteAcceptedClient extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Quote $quote,
        public Contract $contract,
        public ?string $serviceTermsPdf = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vertragsbestätigung: '.$this->quote->title.' ('.$this->contract->contract_number.')',
        );
    }

    public function content(): Content
    {
        $settings = Setting::instance();

        return new Content(
            view: 'emails.quotes.accepted-client',
            with: [
                'quote' => $this->quote,
                'contract' => $this->contract,
                'settings' => $settings,
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $settings = Setting::instance();

        $pdf = Pdf::loadView('pdfs.quote', [
            'quote' => $this->quote->load('items'),
            'settings' => $settings,
            'company' => $this->getCompanyData($settings),
        ]);

        $attachments = [
            Attachment::fromData(
                fn () => $pdf->output(),
                'Angebot-'.$this->quote->quote_number.'.pdf'
            )->withMime('application/pdf'),
        ];

        // Attach service terms PDF if available
        if ($this->serviceTermsPdf) {
            $attachments[] = Attachment::fromData(
                fn () => $this->serviceTermsPdf,
                'Leistungsvereinbarungen-'.$this->quote->quote_number.'.pdf'
            )->withMime('application/pdf');
        }

        return $attachments;
    }

    /**
     * Get company data for PDFs.
     *
     * @return array<string, string>
     */
    private function getCompanyData(Setting $settings): array
    {
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
}

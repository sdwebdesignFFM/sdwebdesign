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

class QuoteFullySignedClient extends Mailable
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
            subject: 'Ihre Auftragsbestätigung: '.$this->quote->quote_number.' - '.$this->quote->title,
        );
    }

    public function content(): Content
    {
        $settings = Setting::instance();

        return new Content(
            view: 'emails.quotes.fully-signed-client',
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
        // Generate the fully-signed PDF
        $pdf = Pdf::loadView('pdfs.quote', [
            'quote' => $this->quote->load('items'),
            'settings' => Setting::instance(),
        ]);

        $attachments = [
            Attachment::fromData(
                fn () => $pdf->output(),
                'Auftragsbestätigung-'.$this->quote->quote_number.'.pdf'
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
}

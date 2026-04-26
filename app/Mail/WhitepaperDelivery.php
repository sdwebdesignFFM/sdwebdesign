<?php

namespace App\Mail;

use App\Models\WhitepaperLead;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WhitepaperDelivery extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public WhitepaperLead $lead,
        public string $whitepaperTitle,
        public string $pdfView,
        public string $pdfFilename,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ihr Whitepaper: '.$this->whitepaperTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.whitepaper-delivery',
            with: [
                'lead' => $this->lead,
                'whitepaperTitle' => $this->whitepaperTitle,
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView($this->pdfView, [
            'lead' => $this->lead,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), $this->pdfFilename)
                ->withMime('application/pdf'),
        ];
    }
}

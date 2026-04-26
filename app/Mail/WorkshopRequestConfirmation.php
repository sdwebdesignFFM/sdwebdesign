<?php

namespace App\Mail;

use App\Models\WorkshopRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkshopRequestConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public WorkshopRequest $request,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ihre Workshop-Anfrage bei sdwebdesign — Plattform-Discovery',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.workshop-request-confirmation',
            with: ['request' => $this->request],
        );
    }
}

<?php

namespace App\Mail;

use App\Models\WorkshopRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkshopRequestAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public WorkshopRequest $request,
    ) {}

    public function envelope(): Envelope
    {
        $companySuffix = $this->request->company ? ' · '.$this->request->company : '';

        // Reply-To is a plain email string — no display-name attached.
        // Symfony's Address parser rejects names containing characters
        // like commas, parentheses or apostrophes (RFC 2822 "phrase"
        // restrictions), which crashed live submits with non-trivial
        // names. The visitor email is already validated as a valid
        // address, so this branch is always safe.
        return new Envelope(
            subject: 'Workshop-Anfrage Plattform-Discovery: '.$this->request->name.$companySuffix,
            replyTo: [$this->request->email],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.workshop-request-admin',
            with: ['request' => $this->request],
        );
    }
}

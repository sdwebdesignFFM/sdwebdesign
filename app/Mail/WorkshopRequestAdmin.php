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

        return new Envelope(
            subject: 'Workshop-Anfrage Plattform-Discovery: '.$this->request->name.$companySuffix,
            replyTo: [$this->request->email => $this->request->name],
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

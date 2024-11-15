<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class JournalPINRequest extends Mailable
{
    use Queueable, SerializesModels;
    public $client;
    public $requestorFN;
    public $requestorLN;
    public $journal;
    /**
     * Create a new message instance.
     */
    public function __construct($client, $requestorFN, $requestorLN, $journal)
    {
        $this->client = $client;
    $this->requestorFN = $requestorFN;
    $this->requestorLN = $requestorLN;
    $this->journal = $journal;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('bms.systeminfo@gmail.com', 'BMS'),
            subject: 'Journal PIN Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.RequestJournalPIN',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

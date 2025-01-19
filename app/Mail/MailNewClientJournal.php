<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class MailNewClientJournal extends Mailable
{
    use Queueable, SerializesModels;
    public $client;
    public $journalID;
    public $dataUserEntry;
    /**
     * Create a new message instance.
     */
    public function __construct($client, $journalID, $dataUserEntry)
    {
        $this->client = $client;
        $this->journalID = $journalID;
        $this->dataUserEntry = $dataUserEntry;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('bms.systeminfo@gmail.com', 'BMS'),
            subject: 'New Client Journal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.MailNewClientJournal',
            with:[
                'client' => $this->client,
                'journalID' => $this->journalID
            ]
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

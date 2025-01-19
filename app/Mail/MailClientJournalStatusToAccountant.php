<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailClientJournalStatusToAccountant extends Mailable
{
    use Queueable, SerializesModels;
    protected $status;
    protected $client;
    protected $accountant;
    protected $journalID;
    /**
     * Create a new message instance.
     */
    public function __construct($status, $client, $accountant, $journalID)
    {
        $this->status = $status;
        $this->client = $client;
        $this->accountant = $accountant;
        $this->journalID = $journalID;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Journal Billing',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.AccountantJournalStatusMail',
            with: [
                'status' => $this->status, // Pass the $status variable to the Blade template
                'client' => $this->client,
                'journalID' => $this->journalID
            ],
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

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class MailClientBillingStatement extends Mailable
{
    use Queueable, SerializesModels;
    public $clientId;
    public $result;
    public $systemProfile;
    public $client;
    public $currentDate;
    public $ads;
    public $clientEmail;
    /**
     * Create a new message instance.
     */
    public function __construct($clientId, $result, $systemProfile, $client, $currentDate, $ads, $clientEmail)
    {
        $this->clientId = $clientId;
        $this->result = $result;
        $this->systemProfile = $systemProfile;
        $this->client = $client;
        $this->currentDate = $currentDate;
        $this->ads = $ads;
        $this->clientEmail = $clientEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('lagrosaedrian06@gmail.com', 'BMS'),
            subject: 'Mail Client Billing Statement',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.MailClientBillingStatement',
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

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CampaignAssignedFieldAgentMail extends Mailable
{
    use Queueable, SerializesModels;
    public $fieldagents;
    public $campaign;

    /**
     * Create a new message instance.
     */
        
    public function __construct($fieldagents, $campaign)
    {
        $this->fieldagents = $fieldagents;
        $this->campaign = $campaign;
    }
    public function build()
    {
        return $this->subject('New Campaign Assigned')
                    ->view('email.campaign_assigned_mail_field');
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Campaign Assigned Field Agent Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.campaign_assigned_mail_field',
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

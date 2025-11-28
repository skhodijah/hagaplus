<?php

namespace App\Mail;

use App\Models\Reimbursement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReimbursementStatusChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reimbursement;

    /**
     * Create a new message instance.
     */
    public function __construct(Reimbursement $reimbursement)
    {
        $this->reimbursement = $reimbursement;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reimbursement Status Update - #' . $this->reimbursement->reference_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reimbursement_status_changed',
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

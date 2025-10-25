<?php

namespace App\Mail;

use App\Models\Disposition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DispositionCreatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $disposition;
    public $recipientName;

    /**
     * Create a new message instance.
     */
    public function __construct(Disposition $disposition)
    {
        $this->disposition = $disposition;
        $this->recipientName = $disposition->toUser->name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Disposisi Baru: ' . $this->disposition->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.disposition-created',
            with: [
                'disposition' => $this->disposition,
                'recipientName' => $this->recipientName,
                'fromUser' => $this->disposition->fromUser->name,
                'priority' => $this->disposition->priority,
                'deadline' => $this->disposition->deadline ? $this->disposition->deadline->format('d M Y') : '-',
                'url' => route('staff.disposisi.show', $this->disposition->id),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
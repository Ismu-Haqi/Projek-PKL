<?php

namespace App\Mail;

use App\Models\Disposition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DispositionCompletedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $disposition;

    /**
     * Create a new message instance.
     */
    public function __construct(Disposition $disposition)
    {
        $this->disposition = $disposition;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Disposisi Diselesaikan: ' . $this->disposition->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.disposition-completed',
            with: [
                'disposition' => $this->disposition,
                'completedBy' => $this->disposition->toUser->name,
                'completedAt' => $this->disposition->completed_at 
                    ? $this->disposition->completed_at->format('d M Y H:i') 
                    : now()->format('d M Y H:i'),
                'notes' => $this->disposition->notes ?? '-',
                'url' => route('admin.disposisi.show', $this->disposition->id),
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
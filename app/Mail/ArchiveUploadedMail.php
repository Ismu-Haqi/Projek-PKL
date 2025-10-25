<?php

namespace App\Mail;

use App\Models\Archive;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ArchiveUploadedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $archive;
    public $recipientName;

    /**
     * Create a new message instance.
     */
    public function __construct(Archive $archive, $recipientName)
    {
        $this->archive = $archive;
        $this->recipientName = $recipientName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📄 Arsip Baru Diunggah: ' . $this->archive->judul,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.archive-uploaded',
            with: [
                'archive' => $this->archive,
                'recipientName' => $this->recipientName,
                'uploader' => $this->archive->uploader->name ?? 'Unknown',
                'category' => $this->archive->category->name ?? 'Tanpa Kategori',
                'tanggalSurat' => $this->archive->tanggal_surat->format('d M Y'),
                'url' => route('admin.arsip.show', $this->archive->id),
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
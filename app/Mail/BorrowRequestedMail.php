<?php

namespace App\Mail;

use App\Models\AssetBorrow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BorrowRequestedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $borrow;
    public $recipientName;

    /**
     * Create a new message instance.
     */
    public function __construct(AssetBorrow $borrow, $recipientName)
    {
        $this->borrow = $borrow;
        $this->recipientName = $recipientName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📦 Pengajuan Peminjaman Aset Baru: ' . $this->borrow->asset->nama,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.borrow-requested',
            with: [
                'borrow' => $this->borrow,
                'recipientName' => $this->recipientName,
                'borrower' => $this->borrow->borrower->name,
                'borrowerUnit' => $this->borrow->borrower_unit,
                'assetName' => $this->borrow->asset->nama,
                'assetCode' => $this->borrow->asset->kode_asset,
                'keperluan' => $this->borrow->keperluan,
                'tanggalKembali' => $this->borrow->tanggal_kembali_rencana->format('d M Y'),
                'url' => route('admin.peminjaman.show', $this->borrow->id),
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
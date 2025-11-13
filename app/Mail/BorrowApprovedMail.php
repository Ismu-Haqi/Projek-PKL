<?php

namespace App\Mail;

use App\Models\AssetBorrow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BorrowApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $borrow;

    /**
     * Create a new message instance.
     */
    public function __construct(AssetBorrow $borrow)
    {
        $this->borrow = $borrow;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Peminjaman Aset Disetujui: ' . $this->borrow->asset->nama,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.borrow-approved',
            with: [
                'borrow' => $this->borrow,
                'borrowerName' => $this->borrow->borrower->name,
                'assetName' => $this->borrow->asset->nama,
                'assetCode' => $this->borrow->asset->kode_asset,
                'kodePeminjaman' => $this->borrow->kode_peminjaman,
                'tanggalPinjam' => $this->borrow->tanggal_pinjam ? $this->borrow->tanggal_pinjam->format('d M Y') : '-',
                'tanggalKembali' => $this->borrow->tanggal_kembali_rencana->format('d M Y'),
                'url' => route('staff.peminjaman.show', $this->borrow->id),
            ]
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
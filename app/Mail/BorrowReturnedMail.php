<?php

namespace App\Mail;

use App\Models\AssetBorrow;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BorrowReturnedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $borrow;
    public $ownerName;

    /**
     * Create a new message instance.
     */
    public function __construct(AssetBorrow $borrow, $ownerName = 'Pemilik Aset')
    {
        $this->borrow = $borrow;
        $this->ownerName = $ownerName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔄 Aset Telah Dikembalikan: ' . $this->borrow->asset->nama,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.borrow-returned',
            with: [
                'borrow' => $this->borrow,
                'ownerName' => $this->ownerName,
                'borrowerName' => $this->borrow->borrower->name,
                'borrowerUnit' => $this->borrow->borrower_unit,
                'assetName' => $this->borrow->asset->nama,
                'assetCode' => $this->borrow->asset->kode_asset,
                'kodePeminjaman' => $this->borrow->kode_peminjaman,
                'tanggalKembali' => $this->borrow->tanggal_kembali_aktual ? $this->borrow->tanggal_kembali_aktual->format('d M Y, H:i') : '-',
                'kondisiKembali' => ucfirst($this->borrow->kondisi_kembali ?? 'baik'),
                'url' => route('staff.aset.show', $this->borrow->asset_id),
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
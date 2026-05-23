<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentSignature extends Model
{
    protected $table = 'document_signatures';

    protected $fillable = [
        'token',
        'document_type',
        'document_title',
        'signed_by',
        'signed_by_title',
        'signed_at',
        'instansi',
        'metadata',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'metadata'  => 'array',
    ];

    /**
     * Generate token unik dan simpan record signature baru
     */
    public static function generateFor(
        string $documentType,
        string $documentTitle,
        string $signedBy = 'Azwar Arsyadi, S.Kom',
        string $signedByTitle = 'Kepala Dinas',
        array $metadata = []
    ): self {
        return self::create([
            'token'          => Str::random(32),
            'document_type'  => $documentType,
            'document_title' => $documentTitle,
            'signed_by'      => $signedBy,
            'signed_by_title'=> $signedByTitle,
            'signed_at'      => now(),
            'instansi'       => 'Dinas Komunikasi dan Informatika Kab. Barito Kuala',
            'metadata'       => $metadata,
        ]);
    }

    /**
     * URL validasi publik untuk QR Code
     */
    public function getValidasiUrlAttribute(): string
    {
        return url('/validasi/' . $this->token);
    }
}

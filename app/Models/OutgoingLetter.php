<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutgoingLetter extends Model
{
    use HasFactory;

    protected $table = 'outgoing_letters';

    protected $fillable = [
        'nomor_agenda',
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'sifat',
        'file_path',
        'file_name',
        'file_size',
        'keterangan',
        'status',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public static function generateNomorAgenda(): string
    {
        $month = date('m');
        $year  = date('Y');

        $last = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? (intval(substr($last->nomor_agenda, -4)) + 1) : 1;

        return sprintf('SK/%s/%s/%04d', $month, $year, $number);
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'draft'          => ['text' => 'Draft',              'color' => 'gray'],
            'menunggu_tte'   => ['text' => 'Menunggu TTE',       'color' => 'yellow'],
            'ditandatangani' => ['text' => 'Sudah Ditandatangani', 'color' => 'green'],
            'terkirim'       => ['text' => 'Terkirim',           'color' => 'blue'],
            default          => ['text' => '-',                  'color' => 'gray'],
        };
    }
}

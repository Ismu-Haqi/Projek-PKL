<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCheck extends Model
{
    protected $table = 'asset_checks';

    protected $fillable = [
        'asset_id',
        'checked_by',
        'kondisi_saat_cek',
        'lokasi_saat_cek',
        'catatan',
        'kondisi_berubah',
        'checked_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'kondisi_berubah' => 'boolean',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    // Nama tabel default akan otomatis 'units' (sesuai nama model)
    protected $fillable = [
        'nama_unit',
    ];
}

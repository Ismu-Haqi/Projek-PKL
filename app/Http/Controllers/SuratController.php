<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class SuratController extends Controller
{
    public function arsipkan($id)
    {
        // Cari data surat berdasarkan ID
        $surat = \App\Models\Archive::findOrFail($id); 
        
        // Ubah status surat menjadi diarsipkan
        $surat->status = 'diarsipkan'; 
        $surat->save();

        return back()->with('success', 'Dokumen berhasil disimpan ke dalam Laci Arsip!');
    }

} 
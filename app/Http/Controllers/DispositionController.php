<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispositionController extends Controller
{
    public function index()
    {
        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';
        
        // Data Placeholder minimum
        $dispositions = [(object)['id' => 1, 'surat' => 'SK Kepala Dinas', 'status' => 'Baru']];

        // Memuat view di resources/views/{admin/staff}/disposisi/index.blade.php
        return view("{$viewPrefix}.disposisi.index", compact('dispositions')); 
    }
}
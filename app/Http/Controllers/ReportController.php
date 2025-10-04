<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan import ini

class ReportController extends Controller
{
    public function index()
    {
        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';
        return view("{$viewPrefix}.laporan.index");
    }
}
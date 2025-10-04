<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan import ini

class UserController extends Controller
{
    public function index()
    {
        // View prefix disesuaikan berdasarkan role yang login
        $viewPrefix = Auth::user()->role === 'admin' ? 'admin' : 'staff';
        return view("{$viewPrefix}.user.index");
    }
}
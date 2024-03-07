<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Middleware auth akan dijalankan sebelum metode controller dijalankan
    }
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->get();
        return view('pages.laporan.index', compact('transactions'));
    }
}

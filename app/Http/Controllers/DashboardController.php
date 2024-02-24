<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $totalProduk = Product::count();
        $totalKategori = Category::count();
        return view('dashboard', compact('totalProduk', 'totalKategori'));
    }
}

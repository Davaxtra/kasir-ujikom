<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DetailTransaction;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        $pendapatan = Transaction::select(DB::raw('MONTH(created_at) as bulan'), DB::raw('SUM(total_harga) as total_pendapatan'))
            ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 11 MONTH)')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_pendapatan', 'bulan');

        // Menginisialisasi array untuk menyimpan data
        $labels = [];
        $data = [];

        // Loop untuk menyiapkan data untuk 12 bulan
        for ($i = 1; $i <= 12; $i++) {
            $bulan = Carbon::create()->month($i);
            $labels[] = $bulan->format('F');

            // Jika ada data pendapatan untuk bulan tersebut, gunakan nilainya, jika tidak, gunakan 0
            if (isset($pendapatan[$i])) {
                $data[] = $pendapatan[$i];
            } else {
                $data[] = 0;
            }
        }

        // Membuat array untuk dataChart
        $dataChart = [
            'labels' => $labels,
            'data' => $data
        ];

        $mostPurchasedProducts = DetailTransaction::select('produk_id', DB::raw('SUM(qty) as total_quantity'))
    ->with('product') // Memuat relasi Product
    ->leftJoin('products', 'produk_id', '=', 'products.id')
    ->groupBy('produk_id')
    ->orderByDesc('total_quantity')
    ->limit(5) // Ambil 5 produk teratas
    ->get();

    
    // Inisialisasi array untuk menyimpan data
    $donutLabels = [];
    $donutData = [];
    
    // Loop melalui data produk yang paling banyak dibeli dan menyimpannya ke dalam array
    foreach ($mostPurchasedProducts as $product) {
        $donutLabels[] = $product->product->name; // Mengambil nama produk dari relasi
        $donutData[] = $product->total_quantity;
    }
    
    // Membuat array untuk dataDonut
    $dataDonut = [
        'labels' => $donutLabels,
        'data' => $donutData
    ];
    

        $totalProduk = Product::count();
        $totalKategori = Category::count();
        $totalTransaksi = Transaction::count();
        // dd($mostPurchasedProducts);
        
        return view('dashboard', compact('totalProduk', 'totalKategori', 'totalTransaksi','dataChart', 'dataDonut'));
    }

    public function pendapatanChart()
    {
        // Replace this with your actual data retrieval logic
        $data = [
            'labels' => ['January', 'February', 'March', 'April', 'May'],
            'data' => [65, 59, 80, 81, 56],
        ];
        return view('dashboard', compact('data'));
    }
}

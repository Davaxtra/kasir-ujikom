<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan semua produk
        $produk = DB::table('products')->where('stock', '>', 0)->latest()->get();

        return view('pages.transaksi.index', compact('produk'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Ambil data produk dari permintaan
        $productId = $request->input('product_id');
        $productName = $request->input('product_name');
        $productPrice = $request->input('product_price');
        $kasir_id = Auth()->id();

        // Simpan transaksi ke database
        $transaction = new Transaction();
        $transaction->product_id = $productId;
        $transaction->product_name = $productName;
        $transaction->product_price = $productPrice;
        $transaction->kasir_id = $kasir_id;
        // Tambahkan kolom lain sesuai kebutuhan, seperti id kasir dan tanggal

        $transaction->save();

        return response()->json(['message' => 'Transaksi berhasil dibuat!']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'produk_id' => 'required|array',
            'jumlah' => 'required|array',
            'subtotal' => 'required|array',
            'total' => 'required|numeric|min:0',
            'bayar' => 'required|numeric|min:' . $request->total,
            'kembalian' => 'required|numeric|min:0',
        ], [
            'bayar.min' => 'Jumlah bayar harus sama atau lebih besar dari total harga.',
            'kembalian.min' => 'Kembalian tidak boleh negatif.',
        ]);

        // Mendapatkan data dari request
        $id_produk = $request->produk_id;
        $jumlah_produk = $request->jumlah;
        $sub_total = $request->subtotal;
        $total = $request->total;
        $bayar = $request->bayar;
        $kembalian = $request->kembalian;

        // Membuat transaksi baru
        $transaksi = Transaction::create([
            'kasir_id' => auth()->id(),
            'total_harga' => $total,
            'bayar' => $bayar,
            'kembalian' => $kembalian,
        ]);

        // Mendapatkan ID transaksi yang baru saja dibuat
        $id_transaksi = $transaksi->id;

        // Menyimpan detail transaksi
        // Menyimpan detail transaksi jika ada produk yang dibeli
        if ($id_produk) {
            for ($i = 0; $i < count($id_produk); $i++) {
                DetailTransaction::create([
                    'transaksi_id' => $id_transaksi,
                    'produk_id' => $id_produk[$i],
                    'qty' => $jumlah_produk[$i],
                    'subtotal' => $sub_total[$i],
                ]);
            }
        }

        // Redirect ke halaman indeks transaksi dengan pesan sukses
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

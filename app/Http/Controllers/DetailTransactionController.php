<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaction;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DetailTransactionController extends Controller
{
    public function store(Request $request)
    {
        // Mendapatkan transaksi yang sedang diproses
        $transaction = Transaction::findOrFail($request->transaction_id);

        // Mengambil detail transaksi yang terkait dengan transaksi tersebut
        $detailTransactions = $transaction->detailTransactions;

        // Pastikan $detailTransactions memiliki nilai sebelum melakukan iterasi
        if ($detailTransactions) {
            // Mengumpulkan informasi produk yang terkait dengan detail transaksi
            $productsInCart = [];
            $totalPrice = 0;
            foreach ($detailTransactions as $detailTransaction) {
                $product = $detailTransaction->product;
                $price = $product->price * $detailTransaction->qty; // Menghitung total harga untuk produk ini
                $totalPrice += $price; // Menambahkan harga produk ini ke total harga
                $productsInCart[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $detailTransaction->qty,
                    // Anda bisa menambahkan informasi lain yang Anda butuhkan
                ];
            }

            // Simpan data keranjang ke dalam sesi
            $request->session()->put('productsInCart', $productsInCart);
        } else {
            // Jika $detailTransactions kosong, atur $productsInCart dan $totalPrice menjadi kosong atau null
            $productsInCart = [];
            $totalPrice = 0;
        }

        // Redirect kembali ke halaman transaksi
        return redirect()->route('transaksi');
    }



    public function addToCart(Request $request)
    {
        // Validasi data yang diterima dari request
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Cari produk berdasarkan ID yang diberikan
        $product = Product::find($request->product_id);

        // Pastikan produk ditemukan
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        // Lanjutkan dengan logika menambahkan produk ke dalam keranjang
        // Misalnya, Anda dapat membuat entri detail transaksi di sini

        // Contoh logika sederhana untuk menambahkan produk ke dalam keranjang
        $detailTransaction = new DetailTransaction();
        $detailTransaction->transaksi_id = $request->transaction_id;
        $detailTransaction->produk_id = $request->product_id;
        $detailTransaction->qty = $request->quantity;

        // Harga produk diambil dari data produk yang ditemukan
        $detailTransaction->harga = $product->price;

        $detailTransaction->save();

         // Hitung total harga dari semua produk dalam keranjang
        $totalPrice = DetailTransaction::where('transaksi_id', $request->transaction_id)->sum('harga');

        // Ambil view untuk menampilkan isi keranjang
        $view = view('pages.transaksi.index', compact('productsInCart', 'totalPrice'))->render();

        // Respon dengan HTML yang berisi tampilan isi keranjang
        return response()->json([
            'success' => 'Produk berhasil ditambahkan ke dalam keranjang',
            'cartContent' => $view
        ], 200);
    }

    

    // Metode untuk menghapus produk dari keranjang
    public function removeFromCart(Request $request)
    {
        // Implementasikan logika untuk menghapus produk dari keranjang
    }
}

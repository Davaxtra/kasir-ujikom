<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::view('/add', 'pages.kategori.add');
Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Rute-rute yang memerlukan autentikasi
    Route::get('/', [DashboardController::class, 'index']);
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('pegawai', UserController::class);
    Route::resource('kategori', CategoriesController::class);
    Route::resource('produk', ProductController::class);
    Route::resource('transaksi', TransactionController::class);
    // Route::post('/buat-transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
});
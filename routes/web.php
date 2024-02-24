<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
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



// Route::controller(CategoriesController::class)->group(function () {
//     Route::get('/category', 'index')->name('categories.index');
//     Route::get('/category/{id}', 'show');
//     Route::post('/category', 'store')->name('store');
// });
Route::resource('category', CategoriesController::class);
Route::resource('product', ProductController::class);

// Route::controller(ProductController::class)->group(function () {
//     Route::get('/product', 'index')->name('product.index');
//     Route::post('/product/store', 'store')->name('product.store');
//     Route::get('/product/fetchall', 'fetchAll')->name('product.fetchAll');
//     Route::delete('/product/delete', 'delete')->name('product.delete');
//     Route::get('/product/edit' . 'edit')->name('product.edit');
//     Route::post('/product/update', 'update')->name('product.update');
// });

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [DashboardController::class, 'index']);
Route::resource('pegawai', UserController::class);
// Route::controller(UserController::class)->group(function(){
//     Route::get('/pegawai', 'index')->name('pegawai');
//     Route::post('/pegawai/store', 'store')->name('pegawai.store');
//     Route::get('/pegawai/{id}/edit', 'edit')->name('pegawai.edit');
//     // Route::delete()
// });
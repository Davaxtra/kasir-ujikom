<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
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

Route::get('/', function () {
    return view('welcome');
});

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

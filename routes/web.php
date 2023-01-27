<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::post('/', [App\Http\Controllers\HomeController::class, 'store'])->name('addtocart');
Route::patch('/', [App\Http\Controllers\HomeController::class, 'kupon'])->name('cek.kupon');
Route::delete('/', [App\Http\Controllers\HomeController::class, 'destroy'])->name('clearcart');
Route::delete('/cart/{id}', [App\Http\Controllers\HomeController::class, 'hapus_item'])->name('hapus_item');



Auth::routes();

// prefixs url, seluruh url mengandung awalan v1
// contoh http://127.0.0.1:8000/v1/product dan seterusnya
Route::prefix('admin')->group(function () {

    // dashboard 
    Route::get('', [App\Http\Controllers\Admin\IndexController::class, 'index'])->name('dashboard');

    // route product
    Route::resource('product', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('voucher', App\Http\Controllers\Admin\VoucherController::class);

});

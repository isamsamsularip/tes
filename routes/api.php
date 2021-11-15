<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransaksiController;

Route::post('tes_a', [ApiController::class, 'Logic_test']);
Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('create', [ProductController::class, 'store']);
    Route::put('update/{product}',  [ProductController::class, 'update']);
    Route::delete('delete/{product}',  [ProductController::class, 'destroy']);
    //category
    Route::get('category', [CategoryController::class, 'index']);
    Route::get('category/{id}', [CategoryController::class, 'show']);
    Route::post('category/create', [CategoryController::class, 'store']);
    Route::put('category/update/{category}',  [CategoryController::class, 'update']);
    Route::delete('category/delete/{category}',  [CategoryController::class, 'destroy']);

    //transaksi
    Route::get('transaksi', [TransaksiController::class, 'index']);
    Route::get('transaksi/{id}', [TransaksiController::class, 'show']);
    Route::post('transaksi/create', [TransaksiController::class, 'store']);
    Route::put('transaksi/update/{transaksi}',  [TransaksiController::class, 'update']);
    Route::put('transaksi/delete/{transaksi}',  [TransaksiController::class, 'destroy']);
    Route::post('transaksi/laporan', [TransaksiController::class, 'GetLaporan']);
});

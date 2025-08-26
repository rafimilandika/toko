<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Route::get('/', function () {
//     return view('home');
// });
Route::resource('/', HomeController::class);
Route::get('/tambah', [HomeController::class, 'tambah']);
Route::post('/inputData', [HomeController::class, 'inputData']);
Route::post('/editBarang', [HomeController::class, 'editBarang']);
Route::post('/updatetData', [HomeController::class, 'updatetData']);
Route::post('/hapusBarang', [HomeController::class, 'hapusBarang']);
Route::post('/search', [HomeController::class, 'search']);
Route::post('/tambah-ke-keranjang', [HomeController::class, 'tambahKeKeranjang']);
Route::post('/hapus-dari-keranjang', [HomeController::class, 'hapusDariKeranjang']);
Route::post('/hapus-keranjang', [HomeController::class, 'hapusKeranjang']);
Route::get('/lihat-keranjang', [HomeController::class, 'lihatKeranjang']);

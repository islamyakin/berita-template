<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NewsController::class, 'index'])->name('berita.index');
Route::get('/kategori/{slug}', [NewsController::class, 'category'])->name('berita.kategori');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('berita.show');

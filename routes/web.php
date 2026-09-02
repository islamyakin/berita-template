<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\WafTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NewsController::class, 'index'])->name('berita.index');
Route::get('/kategori/{slug}', [NewsController::class, 'category'])->name('berita.kategori');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('berita.show');

// A form for watching the edge WAF work: a payload it judges hostile is
// answered 403 before these routes are reached at all.
Route::get('/uji-waf', [WafTestController::class, 'index'])->name('uji-waf');
Route::post('/uji-waf', [WafTestController::class, 'store'])->name('uji-waf.store');

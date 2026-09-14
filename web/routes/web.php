<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/partneri', [PartnerController::class, 'index'])->name('partneri');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/turnaje', [TournamentController::class, 'index'])->name('turnaje');
Route::get('/novinky', [ArticleController::class, 'index'])->name('novinky');
Route::get('/novinky/{article:slug}', [ArticleController::class, 'show'])->name('novinky.show');

Route::get('/zpravodajstvi/vykonny-vybor', [NoticeController::class, 'index'])->name('zpravodajstvi.vykonny-vybor');
Route::get('/zpravodajstvi/vykonny-vybor/{notice:slug}', [NoticeController::class, 'show'])->name('zpravodajstvi.vykonny-vybor.show');

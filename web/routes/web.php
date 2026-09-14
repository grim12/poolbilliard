<?php

use App\Http\Controllers\FaqController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/partneri', [PartnerController::class, 'index'])->name('partneri');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/turnaje', [TournamentController::class, 'index'])->name('turnaje');

<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HernaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JakZacitController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PravidlaController;
use App\Http\Controllers\RecurringTournamentController;
use App\Http\Controllers\SoutezeController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/partneri', [PartnerController::class, 'index'])->name('partneri');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/pravidla', [PravidlaController::class, 'index'])->name('pravidla');
Route::get('/kalendar', [CalendarController::class, 'index'])->name('kalendar');
Route::get('/souteze', [SoutezeController::class, 'index'])->name('souteze');
Route::get('/jak-zacit', [JakZacitController::class, 'index'])->name('jak-zacit');
Route::get('/turnaj/{tournament:slug_cs}', [TournamentController::class, 'show'])->name('turnaj.show');
Route::get('/pravidelny-turnaj/{recurringTournament:slug_cs}', [RecurringTournamentController::class, 'show'])->name('pravidelny-turnaj.show');
Route::get('/novinky', [ArticleController::class, 'index'])->name('novinky');
Route::get('/novinky/{article:slug_cs}', [ArticleController::class, 'show'])->name('novinky.show');

Route::get('/zpravodajstvi/vykonny-vybor', [NoticeController::class, 'index'])->name('zpravodajstvi.vykonny-vybor');
Route::get('/zpravodajstvi/vykonny-vybor/{notice:slug_cs}', [NoticeController::class, 'show'])->name('zpravodajstvi.vykonny-vybor.show');

Route::get('/kluby', [ClubController::class, 'index'])->name('kluby');
Route::get('/klub/{club:slug_cs}', [ClubController::class, 'show'])->name('klub.show');
Route::get('/herny', [HernaController::class, 'index'])->name('herny');
Route::get('/herna/{herna:slug_cs}', [HernaController::class, 'show'])->name('herna.show');

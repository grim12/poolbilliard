<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\DeployRunnerController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HernaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JakZacitController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PravidlaController;
use App\Http\Controllers\RecurringTournamentController;
use App\Http\Controllers\RegistraceHernyController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SiteLockController;
use App\Http\Controllers\SoutezeController;
use App\Http\Controllers\SvazController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');

Route::get('/system/deploy-runner', [DeployRunnerController::class, 'run'])->name('deploy-runner.run');

Route::get('/pristup', [SiteLockController::class, 'show'])->name('site-lock.show');
Route::post('/pristup', [SiteLockController::class, 'attempt'])
    ->middleware('throttle:5,1')
    ->name('site-lock.attempt');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/partneri', [PartnerController::class, 'index'])->name('partneri');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/pravidla', [PravidlaController::class, 'index'])->name('pravidla');
Route::get('/kalendar', [CalendarController::class, 'index'])->name('kalendar');
Route::get('/souteze', [SoutezeController::class, 'index'])->name('souteze');
Route::get('/jak-zacit', [JakZacitController::class, 'index'])->name('jak-zacit');
Route::get('/sportovni-svaz', [SvazController::class, 'index'])->name('sportovni-svaz');
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
Route::get('/registrace-herny', [RegistraceHernyController::class, 'create'])->name('registrace-herny');
Route::post('/registrace-herny', [RegistraceHernyController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('registrace-herny.store');

/**
 * English mirror of every route above — same controllers/views (translatable model attributes
 * and __() calls read app()->getLocale() themselves once SetLocale has run, see that
 * middleware's docblock), translated path segments (chosen over a plain /en/ prefix on the
 * Czech paths — see README's SEO section), and `{model:slug_en}` bindings instead of slug_cs.
 * Route names get an 'en.' prefix (Route::name('en.') below), so e.g. 'klub.show' pairs with
 * 'en.klub.show' — <x-layouts.app> relies on that exact pairing to build hreflang tags and the
 * header's language switcher links generically, without every page having to say so itself.
 */
Route::name('en.')->prefix('en')->middleware('locale:en')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/partners', [PartnerController::class, 'index'])->name('partneri');
    Route::get('/faq', [FaqController::class, 'index'])->name('faq');
    Route::get('/rules', [PravidlaController::class, 'index'])->name('pravidla');
    Route::get('/calendar', [CalendarController::class, 'index'])->name('kalendar');
    Route::get('/competitions', [SoutezeController::class, 'index'])->name('souteze');
    Route::get('/getting-started', [JakZacitController::class, 'index'])->name('jak-zacit');
    Route::get('/association', [SvazController::class, 'index'])->name('sportovni-svaz');
    Route::get('/tournament/{tournament:slug_en}', [TournamentController::class, 'show'])->name('turnaj.show');
    Route::get('/recurring-tournament/{recurringTournament:slug_en}', [RecurringTournamentController::class, 'show'])->name('pravidelny-turnaj.show');
    Route::get('/news', [ArticleController::class, 'index'])->name('novinky');
    Route::get('/news/{article:slug_en}', [ArticleController::class, 'show'])->name('novinky.show');

    Route::get('/committee-news', [NoticeController::class, 'index'])->name('zpravodajstvi.vykonny-vybor');
    Route::get('/committee-news/{notice:slug_en}', [NoticeController::class, 'show'])->name('zpravodajstvi.vykonny-vybor.show');

    Route::get('/clubs', [ClubController::class, 'index'])->name('kluby');
    Route::get('/club/{club:slug_en}', [ClubController::class, 'show'])->name('klub.show');
    Route::get('/venues', [HernaController::class, 'index'])->name('herny');
    Route::get('/venue/{herna:slug_en}', [HernaController::class, 'show'])->name('herna.show');
    Route::get('/venue-registration', [RegistraceHernyController::class, 'create'])->name('registrace-herny');
    Route::post('/venue-registration', [RegistraceHernyController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('registrace-herny.store');
});

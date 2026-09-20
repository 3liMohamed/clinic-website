<?php

use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;
use App\Services\PageService;

Route::get('locale/{locale}', LocaleController::class)->name('locale');

Route::get('/', function (PageService $pageService) {

    $page = $pageService->findByNameOrFail('home');
    // return $page;
    return view('pages.home', compact('page'));

})->name('home');

<?php

use App\Http\Controllers\DetailsController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('locale/{locale}', LocaleController::class)->name('locale');

Route::get('/', [PageController::class, 'show'])->defaults('name', 'home')->name('home');

Route::get('/doctors', [PageController::class, 'show'])->defaults('name', 'doctors')->name('doctors');

Route::get('/blogs', [PageController::class, 'show'])->defaults('name', 'blogs')->name('blogs');


Route::get('/portfolio/{slug}', [DetailsController::class, 'show'])
    ->defaults('type', 'portfolio')
    ->name('portfolio.show');

Route::get('/service/{slug}', [DetailsController::class, 'show'])
    ->defaults('type', 'service')
    ->name('service.show');

Route::get('/doctor/{slug}', [DetailsController::class, 'show'])
    ->defaults('type', 'doctor')
    ->name('doctor.show');

Route::get('/blog/{slug}', [DetailsController::class, 'show'])
    ->defaults('type', 'blog')
    ->name('blog.show');

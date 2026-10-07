<?php

use App\Http\Controllers\DetailsController;
use App\Http\Controllers\Forms\AppointmentController;
use App\Http\Controllers\Forms\SubscribeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('locale/{locale}', LocaleController::class)->name('locale');

Route::get('/', [PageController::class, 'show'])->defaults('name', 'home')->name('home');


Route::get('/about', [PageController::class, 'show'])->defaults('name', 'about-us')->name('about');


Route::get('/portfolio/{slug}', [DetailsController::class, 'show'])->defaults('type', 'portfolio')->name('portfolio.show');



Route::get('/services/', [PageController::class, 'show'])->defaults('name', 'services')->name('services');
Route::get('/service/{slug}', [DetailsController::class, 'show'])->defaults('type', 'service')->name('service.show');


Route::get('/doctors', [PageController::class, 'show'])->defaults('name', 'doctors')->name('doctors');
Route::get('/doctor/{slug}', [DetailsController::class, 'show'])->defaults('type', 'doctor')->name('doctor.show');


Route::get('/blogs', [PageController::class, 'show'])->defaults('name', 'blogs')->name('blogs');
Route::get('/blog/{slug}', [DetailsController::class, 'show'])->defaults('type', 'blog')->name('blog.show');

Route::get('/faqs', [PageController::class, 'show'])->defaults('name', 'faqs')->name('faqs');

Route::post('subscribe', SubscribeController::class)->middleware('throttle:subscribe')->name('subscribe');

Route::post('/appointments', AppointmentController::class)->middleware('throttle:appointments')->name('appointments.store');

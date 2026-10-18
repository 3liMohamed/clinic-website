<?php

use App\Http\Controllers\Forms\ContactController;
use App\Http\Controllers\DetailsController;
use App\Http\Controllers\Forms\AppointmentController;
use App\Http\Controllers\Forms\SubscribeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('locale/{locale}', LocaleController::class)->name('locale');

Route::get('/', PageController::class)->defaults('name', 'home')->name('home');

Route::get('/about', PageController::class)->defaults('name', 'about-us')->name('about');

Route::get('/portfolio/{slug}', DetailsController::class)->defaults('type', 'portfolio')->name('portfolio.show');

Route::get('/services/', PageController::class)->defaults('name', 'services')->name('services');
Route::get('/service/{slug}', DetailsController::class)->defaults('type', 'service')->name('service.show');

Route::get('/doctors', PageController::class)->defaults('name', 'doctors')->name('doctors');
Route::get('/doctor/{slug}', DetailsController::class)->defaults('type', 'doctor')->name('doctor.show');

Route::get('/blogs', PageController::class)->defaults('name', 'blogs')->name('blogs');
Route::get('/blog/{slug}', DetailsController::class)->defaults('type', 'blog')->name('blog.show');

Route::get('/faqs', PageController::class)->defaults('name', 'faqs')->name('faqs');

Route::post('subscribe/store', SubscribeController::class)->middleware('throttle:subscribe')->name('subscribe');

Route::get('appointment', PageController::class)->defaults('name', 'appointment')->name('appointment');

Route::post('/appointment/store', AppointmentController::class)->middleware('throttle:appointment')->name('appointment.store');

Route::get('/contact', PageController::class)->defaults('name', 'contact')->name('contact');

Route::post('/contact/store', ContactController::class)->middleware('throttle:contact')->name('contact.store');

Route::get('test', function () {
    return \App\Models\Setting::all();
});

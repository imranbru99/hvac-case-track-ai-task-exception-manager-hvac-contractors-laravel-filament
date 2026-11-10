<?php

use App\Http\Controllers\MarketingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketingController::class, 'home'])->name('home');
Route::get('/features', [MarketingController::class, 'features'])->name('features');
Route::get('/modules', [MarketingController::class, 'modules'])->name('modules');
Route::get('/workflow', [MarketingController::class, 'workflow'])->name('workflow');
Route::get('/pricing', [MarketingController::class, 'pricing'])->name('pricing');
Route::get('/demo', [MarketingController::class, 'demo'])->name('demo');
Route::post('/demo/intake', [MarketingController::class, 'submitMessage'])->name('demo.intake');
Route::get('/contact', [MarketingController::class, 'contact'])->name('contact');

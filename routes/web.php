<?php

use App\Http\Controllers\Api\ShortUrlController;
use Illuminate\Support\Facades\Route;

// 短網址重定向
Route::get('/s/{code}', [ShortUrlController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9\-\_]+')
    ->name('short-url.redirect');

Route::get('/', function () {
    return view('welcome');
});

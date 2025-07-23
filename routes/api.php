<?php

use App\Http\Controllers\Api\ShortUrlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 短網址 API 路由
Route::prefix('v1')->group(function () {
    // 創建短網址
    Route::post('/shorten', [ShortUrlController::class, 'store']);
    
    // 獲取短網址統計信息
    Route::get('/stats/{code}', [ShortUrlController::class, 'stats']);
    
    // 刪除短網址（需要認證）
    Route::middleware('auth:sanctum')->delete('/url/{code}', [ShortUrlController::class, 'destroy']);
});

// 用戶認證路由
Route::middleware('auth:sanctum')->get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
});

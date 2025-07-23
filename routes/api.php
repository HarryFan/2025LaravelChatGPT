<?php

use App\Http\Controllers\Api\ShortUrlController;
use Illuminate\Support\Facades\Route;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="短網址服務 API 文檔",
 *     description="提供短網址生成與管理功能"
 * )
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="本地開發環境"
 * )
 * @OA\PathItem(
 *     path="/api/v1"
 * )
 * @OA\Tag(
 *     name="Short URLs",
 *     description="短網址相關操作"
 * )
 * @OA\Tag(
 *     name="Authentication",
 *     description="用戶認證相關操作"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     securityScheme="bearerAuth",
 *     name="Authorization"
 * )
 */

// 短網址 API 路由
Route::prefix('v1')->group(function () {
    /**
     * @OA\Post(
     *     path="/api/v1/shorten",
     *     summary="創建短網址",
     *     tags={"Short URLs"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"original_url"},
     *             @OA\Property(property="original_url", type="string", format="url", example="https://example.com"),
     *             @OA\Property(property="expires_at", type="string", format="date-time", example="2025-12-31 23:59:59"),
     *             @OA\Property(property="max_clicks", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="短網址創建成功"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="驗證錯誤"
     *     )
     * )
     */
    Route::post('/shorten', [ShortUrlController::class, 'store']);
    
    /**
     * @OA\Get(
     *     path="/api/v1/stats/{code}",
     *     summary="獲取短網址統計信息",
     *     tags={"Short URLs"},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="短網址代碼",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功獲取統計信息"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="短網址不存在"
     *     )
     * )
     */
    Route::get('/stats/{code}', [ShortUrlController::class, 'stats']);
    
    /**
     * @OA\Delete(
     *     path="/api/v1/url/{code}",
     *     summary="刪除短網址",
     *     tags={"Short URLs"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="短網址代碼",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="短網址已刪除"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="未授權"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="短網址不存在"
     *     )
     * )
     */
    Route::middleware('auth:sanctum')->delete('/url/{code}', [ShortUrlController::class, 'destroy']);
});

// 用戶認證路由
/**
 * @OA\Get(
 *     path="/api/user",
 *     summary="獲取當前認證用戶信息",
 *     tags={"Authentication"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="成功獲取用戶信息"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="未授權"
 *     )
 * )
 */
Route::middleware('auth:sanctum')->get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
});

<?php

namespace App\Http\Controllers\Api\Swagger;

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
class ShortUrlApi
{
    // 這個類僅用於存放 Swagger 註解
}

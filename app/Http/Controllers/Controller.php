<?php

namespace App\Http\Controllers;

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
 */
abstract class Controller
{
    //
}

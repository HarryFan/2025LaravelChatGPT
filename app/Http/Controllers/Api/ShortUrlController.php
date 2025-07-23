<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateShortUrlRequest;
use App\Models\ClickLog;
use App\Models\ShortUrl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="短網址服務 API 文件",
 *     description="提供短網址生成與管理功能"
 * )
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="本地開發環境"
 * )
 */

/**
 * @OA\Tag(
 *     name="Short URLs",
 *     description="短網址相關操作"
 * )
 */

/**
 * @OA\Tag(
 *     name="Short URLs",
 *     description="短網址相關操作"
 * )
 */
class ShortUrlController extends Controller
{
    /**
     * @OA\PathItem(
     *     path="/api/v1"
     * )
     */
    /**
     * @OA\Post(
     *     path="/api/v1/shorten",
     *     summary="建立短網址",
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
     *         description="短網址建立成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="original_url", type="string"),
     *                 @OA\Property(property="short_url", type="string"),
     *                 @OA\Property(property="short_code", type="string"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time"),
     *                 @OA\Property(property="max_clicks", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="驗證錯誤"
     *     )
     * )
     */
    public function store(CreateShortUrlRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        $shortUrl = ShortUrl::create([
            'original_url' => $data['original_url'],
            'short_code' => $data['short_code'] ?? null,
            'max_clicks' => $data['max_clicks'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'original_url' => $shortUrl->original_url,
                'short_url' => url('/s/' . $shortUrl->short_code),
                'short_code' => $shortUrl->short_code,
                'expires_at' => $shortUrl->expires_at?->toDateTimeString(),
                'max_clicks' => $shortUrl->max_clicks,
                'created_at' => $shortUrl->created_at->toDateTimeString(),
            ],
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/s/{code}",
     *     summary="重定向到原始網址",
     *     description="根據短網址代碼重定向到對應的原始網址",
     *     tags={"Short URLs"},
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         required=true,
     *         description="短網址代碼",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=301,
     *         description="重定向到原始網址"
     *     ),
     *     @OA\Response(
     *         response=410,
     *         description="短網址已過期或已達到點擊上限"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="短網址不存在"
     *     )
     * )
     */
    public function redirect(string $code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();

        // 檢查短網址是否過期
        if ($shortUrl->isExpired()) {
            abort(410, '此短網址已過期');
        }

        // 檢查是否達到點擊上限
        if ($shortUrl->hasReachedClickLimit()) {
            abort(410, '此短網址已達到點擊上限');
        }

        // 檢查是否停用
        if (!$shortUrl->is_active) {
            abort(410, '此短網址已停用');
        }

        // 記錄點擊
        ClickLog::createForRequest($shortUrl);

        // 增加點擊計數
        $shortUrl->incrementClicks();

        // 執行重定向
        return redirect()->away($shortUrl->original_url, 301);
    }

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
     *         description="成功獲取統計信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="original_url", type="string"),
     *                 @OA\Property(property="short_url", type="string"),
     *                 @OA\Property(property="short_code", type="string"),
     *                 @OA\Property(property="total_clicks", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time", nullable=true),
     *                 @OA\Property(
     *                     property="stats",
     *                     type="object",
     *                     @OA\Property(property="total_clicks", type="integer"),
     *                     @OA\Property(property="unique_visitors", type="integer"),
     *                     @OA\Property(
     *                         property="browsers",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="browser", type="string"),
     *                             @OA\Property(property="count", type="integer")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="platforms",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="platform", type="string"),
     *                             @OA\Property(property="count", type="integer")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="devices",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="device_type", type="string"),
     *                             @OA\Property(property="count", type="integer")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="referrers",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="referer", type="string"),
     *                             @OA\Property(property="count", type="integer")
     *                         )
     *                     ),
     *                     @OA\Property(
     *                         property="clicks_by_date",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(property="date", type="string", format="date"),
     *                             @OA\Property(property="count", type="integer")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="短網址不存在"
     *     )
     * )
     */
    public function stats(string $code): JsonResponse
    {
        $shortUrl = ShortUrl::withCount('clickLogs')
            ->where('short_code', $code)
            ->firstOrFail();

        $stats = [
            'total_clicks' => $shortUrl->clicks,
            'unique_visitors' => $shortUrl->clickLogs()->distinct('ip_address')->count('ip_address'),
            'browsers' => $shortUrl->clickLogs()
                ->select('browser')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('browser')
                ->orderByDesc('count')
                ->get(),
            'platforms' => $shortUrl->clickLogs()
                ->select('platform')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('platform')
                ->orderByDesc('count')
                ->get(),
            'devices' => $shortUrl->clickLogs()
                ->select('device_type')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('device_type')
                ->orderByDesc('count')
                ->get(),
            'referrers' => $shortUrl->clickLogs()
                ->select('referer')
                ->selectRaw('COUNT(*) as count')
                ->whereNotNull('referer')
                ->groupBy('referer')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
            'clicks_by_date' => $shortUrl->clickLogs()
                ->selectRaw('DATE(created_at) as date')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'original_url' => $shortUrl->original_url,
                'short_url' => url('/s/' . $shortUrl->short_code),
                'short_code' => $shortUrl->short_code,
                'total_clicks' => $shortUrl->clicks,
                'created_at' => $shortUrl->created_at->toDateTimeString(),
                'expires_at' => $shortUrl->expires_at?->toDateTimeString(),
                'stats' => $stats,
            ],
        ]);
    }

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
     *         response=200,
     *         description="短網址刪除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="短網址已刪除")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="未授權"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="沒有權限"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="短網址不存在"
     *     )
     * )
     */
    public function destroy(string $code): JsonResponse
    {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();

        // 檢查是否有權限刪除（只有管理員或擁有者可以刪除）
        if (Auth::id() !== $shortUrl->user_id && !Auth::user()?->isAdmin()) {
            abort(403, '沒有權限刪除此短網址');
        }

        $shortUrl->delete();

        return response()->json([
            'success' => true,
            'message' => '短網址已刪除',
        ]);
    }
}

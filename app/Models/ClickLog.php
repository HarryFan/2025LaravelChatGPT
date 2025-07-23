<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class ClickLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_url_id',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'device_type',
        'browser',
        'platform',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function shortUrl(): BelongsTo
    {
        return $this->belongsTo(ShortUrl::class);
    }

    public static function createForRequest(ShortUrl $shortUrl): self
    {
        $userAgent = Request::userAgent();
        
        return $shortUrl->clickLogs()->create([
            'ip_address' => Request::ip(),
            'user_agent' => $userAgent,
            'referer' => Request::header('referer'),
            'country' => null, // 可以整合 GeoIP 服務來獲取國家
            'device_type' => self::parseDeviceType($userAgent),
            'browser' => self::parseBrowser($userAgent),
            'platform' => self::parsePlatform($userAgent),
        ]);
    }

    protected static function parseBrowser(?string $userAgent): ?string
    {
        if (empty($userAgent)) {
            return null;
        }

        if (preg_match('/MSIE|Trident/i', $userAgent)) {
            return 'Internet Explorer';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            return 'Microsoft Edge';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            return 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            return 'Google Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Opera|OPR/i', $userAgent)) {
            return 'Opera';
        }

        return 'Unknown';
    }

    protected static function parsePlatform(?string $userAgent): ?string
    {
        if (empty($userAgent)) {
            return null;
        }

        if (preg_match('/windows|win32|win64/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            return 'iOS';
        }

        return 'Unknown';
    }

    protected static function parseDeviceType(?string $userAgent): ?string
    {
        if (empty($userAgent)) {
            return null;
        }

        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i', $userAgent)) {
            return 'Tablet';
        } elseif (preg_match('/mobile|iphone|android|ipod|blackberry|opera mini|iemobile/i', $userAgent)) {
            return 'Mobile';
        }

        return 'Desktop';
    }
}

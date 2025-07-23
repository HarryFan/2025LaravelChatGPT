<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_url',
        'short_code',
        'clicks',
        'max_clicks',
        'expires_at',
        'is_active',
        'user_id'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'clicks' => 'integer',
        'max_clicks' => 'integer',
        'user_id' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shortUrl) {
            if (empty($shortUrl->short_code)) {
                $shortUrl->short_code = static::generateUniqueShortCode();
            }
        });
    }

    public function clickLogs(): HasMany
    {
        return $this->hasMany(ClickLog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        if ($this->expires_at) {
            return now()->gt($this->expires_at);
        }
        return false;
    }

    public function hasReachedClickLimit(): bool
    {
        return $this->max_clicks && $this->clicks >= $this->max_clicks;
    }

    public function incrementClicks(): void
    {
        $this->increment('clicks');
    }

    public static function generateUniqueShortCode(int $length = 6): string
    {
        do {
            $code = Str::random($length);
        } while (static::where('short_code', $code)->exists());

        return $code;
    }
}

<?php

namespace Tests\Feature;

use App\Models\ShortUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_short_url()
    {
        $response = $this->postJson('/api/v1/shorten', [
            'original_url' => 'https://example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'original_url',
                    'short_url',
                    'short_code',
                    'expires_at',
                    'max_clicks',
                    'created_at',
                ],
            ]);

        $this->assertDatabaseHas('short_urls', [
            'original_url' => 'https://example.com',
        ]);
    }

    /** @test */
    public function it_can_redirect_to_original_url()
    {
        $shortUrl = ShortUrl::create([
            'original_url' => 'https://example.com',
            'short_code' => 'abc123',
            'is_active' => true,
        ]);

        $response = $this->get('/s/abc123');

        $response->assertStatus(301)
            ->assertRedirect('https://example.com');

        $this->assertEquals(1, $shortUrl->fresh()->clicks);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_short_code()
    {
        $response = $this->get('/s/nonexistent');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_410_for_expired_short_url()
    {
        $shortUrl = ShortUrl::create([
            'original_url' => 'https://example.com',
            'short_code' => 'expired',
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);

        $response = $this->get('/s/expired');

        $response->assertStatus(410);
    }

    /** @test */
    public function it_returns_410_for_deactivated_short_url()
    {
        $shortUrl = ShortUrl::create([
            'original_url' => 'https://example.com',
            'short_code' => 'inactive',
            'is_active' => false,
        ]);

        $response = $this->get('/s/inactive');

        $response->assertStatus(410);
    }

    /** @test */
    public function it_can_get_short_url_stats()
    {
        $shortUrl = ShortUrl::create([
            'original_url' => 'https://example.com',
            'short_code' => 'stats123',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/stats/stats123');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'original_url',
                    'short_url',
                    'short_code',
                    'total_clicks',
                    'created_at',
                    'expires_at',
                    'stats' => [
                        'total_clicks',
                        'unique_visitors',
                        'browsers',
                        'platforms',
                        'devices',
                        'referrers',
                        'clicks_by_date',
                    ],
                ],
            ]);
    }
}

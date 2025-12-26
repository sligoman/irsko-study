<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * InstagramService
 *
 * Responsible for talking to the Meta Graph API for an Instagram Business account,
 * mapping results to a safe shape, and caching them for fast frontend consumption.
 */
class InstagramService
{
    // Cache key used for storing the feed
    public const CACHE_KEY = 'instagram_feed';

    // Fetch latest media from Graph API and cache result
    // Returns an array with keys: items (array), fetched_at (ISO8601)
    public function refreshFeed(int $limit = 12): array
    {
        $config = config('instagram');
        $igId = $config['business_account_id'] ?? env('INSTAGRAM_BUSINESS_ACCOUNT_ID');
        $token = $config['page_access_token'] ?? env('FACEBOOK_PAGE_ACCESS_TOKEN');
        $version = $config['graph_version'] ?? 'v17.0';
        $cacheTtl = $config['cache_ttl'] ?? 3600;

        // If Graph credentials are missing, optionally fall back to a local JSON
        $useLocal = env('INSTAGRAM_USE_LOCAL_JSON', false) || ($config['use_local_json'] ?? false);
        if ((empty($igId) || empty($token)) && $useLocal) {
            Log::info('InstagramService: Graph credentials missing, using local instagram.json fallback');
            return $this->loadLocalFeed($cacheTtl);
        }

        if (empty($igId) || empty($token)) {
            Log::warning('InstagramService: missing configuration (IG id or token)');
            throw new \RuntimeException('Instagram configuration missing');
        }

        $fields = 'id,media_type,media_url,thumbnail_url,caption,permalink,timestamp';
        $url = "https://graph.facebook.com/{$version}/{$igId}/media";

        try {
            $resp = Http::timeout(10)
                ->retry(2, 100)
                ->get($url, [
                    'fields' => $fields,
                    'limit' => $limit,
                    'access_token' => $token,
                ]);

            if ($resp->clientError() || $resp->serverError()) {
                // log details and throw
                Log::error('InstagramService: Graph API error', [
                    'status' => $resp->status(),
                    'body' => $resp->body(),
                ]);
                throw new \RuntimeException('Graph API error: ' . $resp->status());
            }

            $data = $resp->json();
            $items = $this->mapItems($data['data'] ?? []);

            $payload = [
                'items' => $items,
                'fetched_at' => now()->toIso8601String(),
            ];

            Cache::put(self::CACHE_KEY, $payload, $cacheTtl);

            return $payload;
        } catch (\Exception $e) {
            Log::warning('InstagramService: failed to refresh feed', ['error' => $e->getMessage()]);
            // If we have a cached value, return it as a fallback
            $cached = Cache::get(self::CACHE_KEY);
            if ($cached) {
                return $cached;
            }
            // If allowed, use local JSON before re-throwing
            if ($useLocal) {
                return $this->loadLocalFeed($cacheTtl);
            }
            throw $e;
        }
    }

    // Return cached feed if available, otherwise call refresh
    public function getFeed(int $limit = 12): array
    {
        $cached = Cache::get(self::CACHE_KEY);
        if (is_array($cached) && !empty($cached['items'])) {
            return $cached;
        }
        // If no cached value, try to refresh. If Graph is unavailable but local JSON exists, refreshFeed will handle fallback.
        return $this->refreshFeed($limit);
    }

    // Load local storage/app/instagram.json and return payload in the same shape as Graph API mapping.
    protected function loadLocalFeed(int $cacheTtl = 3600): array
    {
        $path = storage_path('app/instagram.json');
        if (!file_exists($path)) {
            Log::warning('InstagramService: local instagram.json not found at ' . $path);
            return ['items' => [], 'fetched_at' => null];
        }

        try {
            $contents = file_get_contents($path);
            $raw = json_decode($contents, true);
            if (!is_array($raw)) {
                return ['items' => [], 'fetched_at' => null];
            }

            // Support two formats: already-mapped (id, media_url, etc.) or simple (id,image,caption,link)
            $items = [];
            foreach ($raw as $item) {
                if (isset($item['media_url']) || isset($item['thumbnail_url'])) {
                    $items[] = [
                        'id' => $item['id'] ?? null,
                        'media_type' => $item['media_type'] ?? null,
                        'media_url' => $item['media_url'] ?? ($item['image'] ?? null),
                        'thumbnail_url' => $item['thumbnail_url'] ?? ($item['image'] ?? null),
                        'caption' => $item['caption'] ?? null,
                        'permalink' => $item['permalink'] ?? ($item['link'] ?? null),
                        'timestamp' => $item['timestamp'] ?? null,
                    ];
                } else {
                    // fallback shape used in storage/app/instagram.json sample
                    $items[] = [
                        'id' => $item['id'] ?? null,
                        'media_type' => 'IMAGE',
                        'media_url' => $item['image'] ?? null,
                        'thumbnail_url' => $item['image'] ?? null,
                        'caption' => $item['caption'] ?? null,
                        'permalink' => $item['link'] ?? null,
                        'timestamp' => $item['timestamp'] ?? null,
                    ];
                }
            }

            $payload = [
                'items' => $items,
                'fetched_at' => now()->toIso8601String(),
            ];

            Cache::put(self::CACHE_KEY, $payload, $cacheTtl);
            return $payload;
        } catch (\Exception $e) {
            Log::warning('InstagramService: failed loading local instagram.json', ['error' => $e->getMessage()]);
            return ['items' => [], 'fetched_at' => null];
        }
    }

    // Map raw Graph API media items to our public shape.
    protected function mapItems(array $rawItems): array
    {
        $items = [];
        foreach ($rawItems as $item) {
            $items[] = [
                'id' => $item['id'] ?? null,
                'media_type' => $item['media_type'] ?? null,
                'media_url' => $item['media_url'] ?? ($item['thumbnail_url'] ?? null),
                'thumbnail_url' => $item['thumbnail_url'] ?? ($item['media_url'] ?? null),
                'caption' => $item['caption'] ?? null,
                'permalink' => $item['permalink'] ?? null,
                'timestamp' => $item['timestamp'] ?? null,
            ];
        }
        return $items;
    }
}

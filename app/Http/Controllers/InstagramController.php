<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\InstagramService;

class InstagramController extends Controller
{
    protected InstagramService $service;

    public function __construct(InstagramService $service)
    {
        $this->service = $service;
    }

    /**
     * GET /api/instagram/feed
     * Return cached feed if present; if not, fetch from Graph API and cache.
     * Response structure:
     * {
     *   items: [ { id, media_type, media_url, thumbnail_url, caption, permalink, timestamp } ],
     *   fetched_at: ISO8601 string
     * }
     */
    public function feed(Request $request)
    {
        try {
            $limit = (int) $request->query('limit', 12);
            $data = $this->service->getFeed($limit);
            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('InstagramController: failed to return feed', ['error' => $e->getMessage()]);
            return response()->json([
                'items' => [],
                'fetched_at' => null,
                'error' => 'Unable to fetch instagram feed',
            ], 503);
        }
    }

    /**
     * Legacy/local fallback endpoint: reads storage/app/instagram.json
     * Useful for development or when Graph API credentials are not configured.
     */
    public function index(Request $request)
    {
        $path = storage_path('app/instagram.json');
        if (!file_exists($path)) {
            return response()->json(['items' => [], 'fetched_at' => null]);
        }
        $contents = file_get_contents($path);
        $data = json_decode($contents, true) ?: [];
        return response()->json(['items' => $data, 'fetched_at' => now()->toIso8601String()]);
    }
}

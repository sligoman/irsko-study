<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InstagramController extends Controller
{
    /**
     * Return cached Instagram posts as JSON.
     * This controller reads a local JSON file at storage/app/instagram.json
     * and caches it for 60 minutes. For production, replace with a proxy
     * to the official Instagram API and proper authentication.
     */
    public function index(Request $request)
    {
        return Cache::remember('instagram_posts', 60 * 60, function () {
            $path = storage_path('app/instagram.json');
            if (!file_exists($path)) {
                // return a small set of sample posts when file missing
                return [
                    [
                        'id' => '1',
                        'image' => 'https://picsum.photos/800/800?random=1',
                        'caption' => 'Sample post 1',
                        'link' => 'https://instagram.com/',
                    ],
                    [
                        'id' => '2',
                        'image' => 'https://picsum.photos/800/800?random=2',
                        'caption' => 'Sample post 2',
                        'link' => 'https://instagram.com/',
                    ],
                    [
                        'id' => '3',
                        'image' => 'https://picsum.photos/800/800?random=3',
                        'caption' => 'Sample post 3',
                        'link' => 'https://instagram.com/',
                    ],
                ];
            }

            $contents = file_get_contents($path);
            $data = json_decode($contents, true);
            if (!is_array($data)) {
                return [];
            }
            return $data;
        });
    }
}

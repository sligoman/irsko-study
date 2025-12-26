<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InstagramService;
use Illuminate\Support\Facades\Log;

class RefreshInstagramFeed extends Command
{
    protected $signature = 'instagram:refresh {--limit=12}';
    protected $description = 'Fetch latest Instagram media from Meta Graph API and cache the result';

    public function handle(InstagramService $service)
    {
        $limit = (int) $this->option('limit');
        $this->info('Refreshing Instagram feed...');
        try {
            $payload = $service->refreshFeed($limit);
            $this->info('Instagram feed refreshed: ' . count($payload['items']) . ' items');
            return 0;
        } catch (\Exception $e) {
            Log::error('RefreshInstagramFeed: failed', ['error' => $e->getMessage()]);
            $this->error('Failed to refresh instagram feed: ' . $e->getMessage());
            return 1;
        }
    }
}

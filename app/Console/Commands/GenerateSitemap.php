<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SitemapGenerator;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--no-gzip : Do not create gzipped copies}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap XML files (chunked) and write them to public/sitemaps';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gzip = !$this->option('no-gzip');
        $this->info('Starting sitemap generation (gzip=' . ($gzip ? 'yes' : 'no') . ')');

        try {
            $generator = new SitemapGenerator();
            $files = $generator->generate($gzip);
            $this->info('Sitemap generation completed. Files:');
            foreach ($files as $f) {
                $this->line(' - ' . $f['file']);
            }
            $this->info('Index: public/sitemaps/sitemap-index.xml');
            return 0;
        } catch (\Throwable $e) {
            $this->error('Sitemap generation failed: ' . $e->getMessage());
            return 1;
        }
    }
}

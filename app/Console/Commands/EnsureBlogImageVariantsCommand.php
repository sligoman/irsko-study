<?php

namespace App\Console\Commands;

use App\Support\ResponsiveImageResolver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EnsureBlogImageVariantsCommand extends Command
{
    protected $signature = 'images:ensure-blog-variants
                            {--dry-run : Simulate actions without writing files}
                            {--force : Overwrite existing variants in public/img/blog}';

    protected $description = 'Scan public/img/blog and generate any missing -1x..-4x image variants without migrating files';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');
        $blogDir = public_path('img/blog');

        if (!File::isDirectory($blogDir)) {
            $this->error("Blog image directory does not exist: {$blogDir}");
            return self::FAILURE;
        }

        $result = ResponsiveImageResolver::ensureFlatStaticDirectoryVariants(
            staticDir: $blogDir,
            dryRun: $dryRun,
            force: $force,
        );

        $this->newLine();
        $this->table(
            ['metric', 'count'],
            [
                ['groups', (string) $result['groups']],
                ['created', (string) $result['created']],
                ['skipped', (string) $result['skipped']],
                ['errors', (string) $result['errors']],
            ]
        );

        if ($dryRun) {
            $this->comment('Dry run only: no files were written.');
        }

        return $result['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}

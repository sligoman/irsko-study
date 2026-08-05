<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Throwable;

class MigrateBlogVariantsCommand extends Command
{
    protected $signature = 'images:migrate-blog-variants
                            {--dry-run : Simulate actions without copying files}
                            {--force : Overwrite existing target files}
                            {--only= : Comma-separated list: thumbnail,medium,large,original}
                            {--verbose-report : Print per-file action lines}';

    protected $description = 'Copy blog image variants from legacy subfolders into flat -1x..-4x naming in public/img/blog';

    /**
     * @var array<string,int>
     */
    private array $scaleMap = [
        'thumbnail' => 1,
        'medium' => 2,
        'large' => 3,
        'original' => 4,
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');
        $verbose = (bool) $this->option('verbose-report');
        $selectedFolders = $this->resolveFolders((string) $this->option('only'));

        if ($selectedFolders === null) {
            return self::FAILURE;
        }

        $blogDir = public_path('img/blog');

        if (!File::isDirectory($blogDir)) {
            $this->warn("Destination root does not exist, creating: {$blogDir}");
            if (!$dryRun) {
                File::makeDirectory($blogDir, 0755, true);
            }
        }

        $global = [
            'copied' => 0,
            'skipped_existing' => 0,
            'missing_source_folder' => 0,
            'errors' => 0,
        ];

        $perFolder = [];

        foreach ($selectedFolders as $folder) {
            $sourceDir = $blogDir . DIRECTORY_SEPARATOR . $folder;
            $scale = $this->scaleMap[$folder];

            $stats = [
                'copied' => 0,
                'skipped_existing' => 0,
                'missing_source_folder' => 0,
                'errors' => 0,
            ];

            if (!File::isDirectory($sourceDir)) {
                $stats['missing_source_folder'] = 1;
                $global['missing_source_folder']++;
                $perFolder[$folder] = $stats;
                $this->warn("Missing source folder: {$sourceDir}");
                continue;
            }

            $entries = File::files($sourceDir);

            foreach ($entries as $entry) {
                $filename = $entry->getFilename();

                if ($filename === '' || str_starts_with($filename, '.')) {
                    continue;
                }

                $base = pathinfo($filename, PATHINFO_FILENAME);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                if ($base === '' || $ext === '') {
                    continue;
                }

                $targetFilename = "{$base}-{$scale}x.{$ext}";
                $targetPath = $blogDir . DIRECTORY_SEPARATOR . $targetFilename;
                $sourcePath = $entry->getPathname();

                if (File::exists($targetPath) && !$force) {
                    $stats['skipped_existing']++;
                    $global['skipped_existing']++;
                    if ($verbose) {
                        $this->line("[SKIP] {$sourcePath} -> {$targetPath}");
                    }
                    continue;
                }

                if ($verbose) {
                    $prefix = $dryRun ? '[DRY-RUN COPY]' : '[COPY]';
                    $this->line("{$prefix} {$sourcePath} -> {$targetPath}");
                }

                if ($dryRun) {
                    $stats['copied']++;
                    $global['copied']++;
                    continue;
                }

                try {
                    File::copy($sourcePath, $targetPath);
                    @chmod($targetPath, 0644);
                    $stats['copied']++;
                    $global['copied']++;
                } catch (Throwable $e) {
                    $stats['errors']++;
                    $global['errors']++;
                    $this->error("Failed to copy {$sourcePath} -> {$targetPath}: {$e->getMessage()}");
                }
            }

            $perFolder[$folder] = $stats;
        }

        $this->newLine();
        $this->info('Per-folder summary');
        $rows = [];
        foreach ($perFolder as $folder => $stats) {
            $rows[] = [
                $folder,
                (string) $stats['copied'],
                (string) $stats['skipped_existing'],
                (string) $stats['missing_source_folder'],
                (string) $stats['errors'],
            ];
        }
        $this->table(
            ['folder', 'copied', 'skipped_existing', 'missing_source_folder', 'errors'],
            $rows
        );

        $this->info('Global summary');
        $this->table(
            ['metric', 'count'],
            [
                ['copied', (string) $global['copied']],
                ['skipped_existing', (string) $global['skipped_existing']],
                ['missing_source_folder', (string) $global['missing_source_folder']],
                ['errors', (string) $global['errors']],
            ]
        );

        if ($dryRun) {
            $this->comment('Dry run only: no files were written.');
        }

        return $global['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @return array<int,string>|null
     */
    private function resolveFolders(string $only): ?array
    {
        if (trim($only) === '') {
            return array_keys($this->scaleMap);
        }

        $requested = collect(explode(',', $only))
            ->map(fn ($value) => trim(strtolower($value)))
            ->filter()
            ->values()
            ->all();

        $allowed = array_keys($this->scaleMap);
        $invalid = array_values(array_diff($requested, $allowed));

        if (!empty($invalid)) {
            $this->error('Invalid --only value(s): ' . implode(', ', $invalid));
            $this->line('Allowed values: ' . implode(',', $allowed));
            return null;
        }

        return $requested;
    }
}


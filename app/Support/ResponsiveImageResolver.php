<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use SplFileInfo;
use Throwable;

class ResponsiveImageResolver
{
    /**
     * @param  object|array|string|null  $subject
     */
    public static function imageFilename(object|array|string|null $subject): ?string
    {
        if (is_string($subject)) {
            return self::sanitizeFilename($subject);
        }

        if (is_array($subject)) {
            $value = $subject['image'] ?? $subject['featured_image'] ?? null;
            return self::sanitizeFilename(is_string($value) ? $value : null);
        }

        if (is_object($subject)) {
            $image = data_get($subject, 'image');
            $featuredImage = data_get($subject, 'featured_image');
            $value = $image ?: $featuredImage;
            return self::sanitizeFilename(is_string($value) ? $value : null);
        }

        return null;
    }

    public static function variantFilename(string $filename, int $scale): ?string
    {
        $filename = self::sanitizeFilename($filename);
        if (empty($filename)) {
            return null;
        }

        $base = pathinfo($filename, PATHINFO_FILENAME);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if ($base === '' || $ext === '') {
            return null;
        }

        return "{$base}-{$scale}x.{$ext}";
    }

    /**
     * @return array<int,string>
     */
    public static function variantFilenameCandidates(string $filename, int $scale): array
    {
        $filename = self::sanitizeFilename($filename);
        if (empty($filename)) {
            return [];
        }

        $base = pathinfo($filename, PATHINFO_FILENAME);
        $ext = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));
        if ($base === '' || $ext === '') {
            return [];
        }

        $candidates = [];

        if ($ext === 'png') {
            $candidates[] = "{$base}-{$scale}x.webp";
        }

        $candidates[] = "{$base}-{$scale}x.{$ext}";

        return array_values(array_unique($candidates));
    }

    /**
     * @param  object|array|string|null  $subject
     */
    public static function urlForScale(object|array|string|null $subject, string $folder, int $scale): ?string
    {
        $filename = self::imageFilename($subject);
        if (empty($filename)) {
            return null;
        }

        foreach (self::variantFilenameCandidates($filename, $scale) as $variant) {
            $relativePath = "img/{$folder}/{$variant}";
            if (file_exists(public_path($relativePath))) {
                return asset($relativePath);
            }
        }

        return null;
    }

    /**
     * @param  object|array|string|null  $subject
     * @param  array<int,int>  $scales
     */
    public static function firstAvailableUrl(object|array|string|null $subject, string $folder, array $scales = [3, 2, 4, 1]): ?string
    {
        foreach ($scales as $scale) {
            $url = self::urlForScale($subject, $folder, (int) $scale);
            if (!empty($url)) {
                return $url;
            }
        }

        return null;
    }

    /**
     * @param  object|array|string|null  $subject
     */
    public static function hasAnyVariant(object|array|string|null $subject, string $folder): bool
    {
        return !empty(self::firstAvailableUrl($subject, $folder, [1, 2, 3, 4]));
    }

    /**
     * Ensure missing responsive variants are generated in destination directory.
     * Intended for migration commands, not runtime request rendering.
     *
     * @param  array<int,int>  $widthByScale
     * @return array{created: int, skipped: int, errors: int}
     */
    public static function ensureMissingStaticVariants(
        string $originalPath,
        string $destinationDir,
        string $baseName,
        string $extension,
        bool $dryRun = false,
        bool $force = false,
        array $widthByScale = [1 => 640, 2 => 960, 3 => 1200, 4 => 1600]
    ): array {
        $result = ['created' => 0, 'skipped' => 0, 'errors' => 0];

        if (!File::exists($originalPath) || !File::isFile($originalPath)) {
            $result['errors']++;
            return $result;
        }

        if (!$dryRun && !File::isDirectory($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }

        $ext = strtolower(trim($extension));
        $rasterExtensions = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
        $isRaster = in_array($ext, $rasterExtensions, true);

        foreach ($widthByScale as $scale => $width) {
            $target = $destinationDir . DIRECTORY_SEPARATOR . "{$baseName}-{$scale}x.{$ext}";
            if (File::exists($target) && !$force) {
                $result['skipped']++;
                continue;
            }

            if ($dryRun) {
                $result['created']++;
                continue;
            }

            try {
                if (!$isRaster) {
                    File::copy($originalPath, $target);
                    @chmod($target, 0644);
                    $result['created']++;
                    continue;
                }

                if (!self::canResizeSafely($originalPath, (int) $width)) {
                    File::copy($originalPath, $target);
                    @chmod($target, 0644);
                    $result['created']++;
                    continue;
                }

                $img = Image::read($originalPath);
                $currentWidth = (int) $img->width();

                if ($currentWidth > (int) $width) {
                    $newHeight = (int) round($img->height() * ($width / max(1, $currentWidth)));
                    $img->resize((int) $width, max(1, $newHeight), function ($constraint) {
                        $constraint->upsize();
                    });
                }

                $quality = $scale >= 4 ? 100 : 90;
                $encoded = $img->encodeByExtension($ext, $quality);
                File::put($target, $encoded);
                @chmod($target, 0644);
                unset($img, $encoded);
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
                $result['created']++;
            } catch (Throwable $e) {
                $result['errors']++;
            }
        }

        return $result;
    }

    /**
     * Ensure each image group inside a flat static directory has -1x..-4x variants.
     *
     * @param  array<int,int>  $widthByScale
     * @return array{groups:int,created:int,skipped:int,errors:int}
     */
    public static function ensureFlatStaticDirectoryVariants(
        string $staticDir,
        bool $dryRun = false,
        bool $force = false,
        array $widthByScale = [1 => 640, 2 => 960, 3 => 1200, 4 => 1600]
    ): array {
        $result = ['groups' => 0, 'created' => 0, 'skipped' => 0, 'errors' => 0];

        if (!File::isDirectory($staticDir)) {
            $result['errors']++;
            return $result;
        }

        $groups = [];

        /** @var SplFileInfo $file */
        foreach (File::files($staticDir) as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $filename = $file->getFilename();
            if ($filename === '' || str_starts_with($filename, '.')) {
                continue;
            }

            $base = pathinfo($filename, PATHINFO_FILENAME);
            $ext = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));
            if ($base === '' || $ext === '') {
                continue;
            }

            [$normalizedBase, $scale] = self::extractBaseAndScale($base);
            $groupKey = strtolower("{$normalizedBase}|{$ext}");

            $groups[$groupKey][] = [
                'path' => $file->getPathname(),
                'base' => $normalizedBase,
                'ext' => $ext,
                'scale' => $scale,
            ];
        }

        foreach ($groups as $items) {
            $result['groups']++;
            $sample = $items[0];
            $originalCandidate = collect($items)
                ->sortByDesc('scale')
                ->first();

            if (!$originalCandidate || !File::exists($originalCandidate['path'])) {
                $result['errors']++;
                continue;
            }

            $generated = self::ensureMissingStaticVariants(
                originalPath: $originalCandidate['path'],
                destinationDir: $staticDir,
                baseName: $sample['base'],
                extension: $sample['ext'],
                dryRun: $dryRun,
                force: $force,
                widthByScale: $widthByScale
            );

            $result['created'] += $generated['created'];
            $result['skipped'] += $generated['skipped'];
            $result['errors'] += $generated['errors'];
        }

        return $result;
    }

    private static function sanitizeFilename(?string $filename): ?string
    {
        if ($filename === null) {
            return null;
        }

        $filename = trim($filename);
        if ($filename === '') {
            return null;
        }

        $path = parse_url($filename, PHP_URL_PATH) ?: $filename;

        return rawurldecode(basename($path));
    }

    /**
     * @return array{0:string,1:int}
     */
    private static function extractBaseAndScale(string $base): array
    {
        if (preg_match('/^(.*?)[\-_]?([1-4])x$/i', $base, $matches)) {
            $normalized = trim((string) $matches[1]);
            $scale = (int) $matches[2];
            if ($normalized !== '') {
                return [$normalized, $scale];
            }
        }

        return [$base, 4];
    }

    private static function canResizeSafely(string $originalPath, int $targetWidth): bool
    {
        $size = @getimagesize($originalPath);
        if (!$size || empty($size[0]) || empty($size[1])) {
            return false;
        }

        $originalWidth = (int) $size[0];
        $originalHeight = (int) $size[1];
        $targetWidth = max(1, min($originalWidth, $targetWidth));
        $targetHeight = max(1, (int) round($originalHeight * ($targetWidth / max(1, $originalWidth))));

        $memoryLimit = self::memoryLimitBytes();
        if ($memoryLimit <= 0) {
            return true;
        }

        $available = max(0, $memoryLimit - memory_get_usage(true));
        $required = (int) (($originalWidth * $originalHeight * 4) + ($targetWidth * $targetHeight * 4) + (24 * 1024 * 1024));

        return $available > $required;
    }

    private static function memoryLimitBytes(): int
    {
        $value = trim((string) ini_get('memory_limit'));
        if ($value === '' || $value === '-1') {
            return -1;
        }

        $unit = strtolower(substr($value, -1));
        $number = (int) $value;

        return match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => (int) $value,
        };
    }
}

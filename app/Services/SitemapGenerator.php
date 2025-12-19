<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Sligoman\AiblogApiWeb\Models\AiblogPost;
use Sligoman\Caofinder\Models\CaoSchool;
use Sligoman\Caofinder\Models\CaoCourse;

class SitemapGenerator
{
    /**
     * Generate sitemap files under public/sitemaps.
     * @param bool $gzip whether to write gzipped copies alongside XML files
     * @param int $chunkSize max URLs per sitemap file (Google limit 50,000)
     * @return array list of generated files
     */
    public function generate(bool $gzip = true, int $chunkSize = 50000): array
    {
        $sitemapDir = public_path('sitemaps');
        if (!is_dir($sitemapDir)) {
            @mkdir($sitemapDir, 0755, true);
        }

        $files = [];

        // Static pages + posts
        $staticUrls = [
            ['loc' => URL::to('/'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => URL::to('/o-nas'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => URL::to('/proc-irsko'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => URL::to('/vysoke-skoly'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => URL::to('/sluzby'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => URL::to('/faq'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => URL::to('/kontakt'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => URL::to('/ochrana-soukromi'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => URL::to('/blog'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '0.9'],
        ];

        try {
            $posts = AiblogPost::with('type')->whereHas('type', function ($q) {
                $q->whereIn('name', ['blog', 'news']);
            })->orderBy('updated_at', 'desc')->get();
            foreach ($posts as $post) {
                $staticUrls[] = [
                    'loc' => URL::to('/blog/' . $post->slug),
                    'lastmod' => optional($post->updated_at)->toAtomString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('SitemapGenerator: could not fetch posts: '.$e->getMessage());
        }

        // helper to render urlset
        $renderUrlset = function (array $items) {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>\n';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';
            foreach ($items as $i) {
                $xml .= "  <url>\n";
                $xml .= "    <loc>" . htmlspecialchars($i['loc'], ENT_QUOTES, 'UTF-8') . "</loc>\n";
                if (!empty($i['lastmod'])) {
                    $xml .= "    <lastmod>" . $i['lastmod'] . "</lastmod>\n";
                }
                if (!empty($i['changefreq'])) {
                    $xml .= "    <changefreq>" . $i['changefreq'] . "</changefreq>\n";
                }
                if (!empty($i['priority'])) {
                    $xml .= "    <priority>" . $i['priority'] . "</priority>\n";
                }
                $xml .= "  </url>\n";
            }
            $xml .= '</urlset>';
            return $xml;
        };

        $staticFile = 'sitemap-static.xml';
        $staticXml = $renderUrlset($staticUrls);
        file_put_contents($sitemapDir . DIRECTORY_SEPARATOR . $staticFile, $staticXml);
        $files[] = ['file' => $staticFile, 'lastmod' => now()->toAtomString()];
        if ($gzip) {
            file_put_contents($sitemapDir . DIRECTORY_SEPARATOR . $staticFile . '.gz', gzencode($staticXml, 9));
        }

        // Schools
        $schools = [];
        try {
            $schoolModels = CaoSchool::orderBy('updated_at', 'desc')->get();
            foreach ($schoolModels as $s) {
                if (!empty($s->url)) {
                    $loc = URL::to('/vysoke-skoly/' . $s->url);
                } elseif (!empty($s->school_id)) {
                    $loc = URL::to('/vysoke-skoly/' . $s->school_id);
                } else {
                    $loc = URL::to('/vysoke-skoly/' . $s->id);
                }
                $schools[] = ['loc' => $loc, 'lastmod' => optional($s->updated_at)->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.7'];
            }
        } catch (\Throwable $e) {
            Log::warning('SitemapGenerator: could not fetch schools: '.$e->getMessage());
        }

        if (!empty($schools)) {
            $schoolsFile = 'sitemap-schools.xml';
            $schoolsXml = $renderUrlset($schools);
            file_put_contents($sitemapDir . DIRECTORY_SEPARATOR . $schoolsFile, $schoolsXml);
            $files[] = ['file' => $schoolsFile, 'lastmod' => now()->toAtomString()];
            if ($gzip) {
                file_put_contents($sitemapDir . DIRECTORY_SEPARATOR . $schoolsFile . '.gz', gzencode($schoolsXml, 9));
            }
        }

        // Courses (all) -> chunked
        $courses = [];
        try {
            $courseModels = CaoCourse::with('school')->orderBy('updated_at', 'desc')->get();
            foreach ($courseModels as $c) {
                if (!empty($c->url)) {
                    $slug = $c->url;
                } elseif (!empty($c->school) && !empty($c->school->school_id) && !empty($c->code)) {
                    $slug = strtolower($c->school->school_id . '-' . $c->code);
                } elseif (!empty($c->code)) {
                    $slug = $c->code;
                } else {
                    $slug = $c->id;
                }
                $courses[] = ['loc' => URL::to('/kurzy/' . $slug), 'lastmod' => optional($c->updated_at)->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.6'];
            }
        } catch (\Throwable $e) {
            Log::warning('SitemapGenerator: could not fetch courses: '.$e->getMessage());
        }

        if (!empty($courses)) {
            $chunks = array_chunk($courses, $chunkSize);
            foreach ($chunks as $i => $chunk) {
                $file = 'sitemap-courses-' . ($i + 1) . '.xml';
                $xml = $renderUrlset($chunk);
                file_put_contents($sitemapDir . DIRECTORY_SEPARATOR . $file, $xml);
                $files[] = ['file' => $file, 'lastmod' => now()->toAtomString()];
                if ($gzip) {
                    file_put_contents($sitemapDir . DIRECTORY_SEPARATOR . $file . '.gz', gzencode($xml, 9));
                }
            }
        }

        // Build sitemap index
        $indexXml = '<?xml version="1.0" encoding="UTF-8"?>\n';
        $indexXml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';
        foreach ($files as $f) {
            $indexXml .= "  <sitemap>\n";
            $indexXml .= "    <loc>" . htmlspecialchars(URL::to('/sitemaps/' . $f['file']), ENT_QUOTES, 'UTF-8') . "</loc>\n";
            if (!empty($f['lastmod'])) {
                $indexXml .= "    <lastmod>" . $f['lastmod'] . "</lastmod>\n";
            }
            $indexXml .= "  </sitemap>\n";
        }
        $indexXml .= '</sitemapindex>';

        $indexFile = $sitemapDir . DIRECTORY_SEPARATOR . 'sitemap-index.xml';
        file_put_contents($indexFile, $indexXml);
        if ($gzip) {
            file_put_contents($sitemapDir . DIRECTORY_SEPARATOR . 'sitemap-index.xml.gz', gzencode($indexXml, 9));
        }

        return $files;
    }
}

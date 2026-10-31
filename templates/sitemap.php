<?php
/**
 * SEO Universalis — Sitemap Index Profesional (v3, file-cached)
 *  /sitemap.xml          → sitemap index (menunjuk 2 child)
 *  /sitemap-pages.xml    → halaman inti + programatik + artikel (prioritas berjenjang)
 *  /sitemap-tags.xml     → 1.386 tag hub
 * Dipicu dari index.php dengan ?type=index|pages|tags.
 * Cache file 1 jam (data/cache/) — dibuang otomatis saat admin menyimpan
 * artikel/pengaturan (cache_clear) agar lastmod & daftar URL tetap akurat.
 */
declare(strict_types=1);

$type = $_GET['type'] ?? 'index';
$base = rtrim(setting('site_url', SITE_URL), '/');
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=3600');

$cacheKey = 'sitemap-' . $type;
$hit = cache_get($cacheKey, 3600);
if ($hit !== null) {
    header('X-Cache: HIT');
    echo $hit;
    exit;
}

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

if ($type === 'index') {
    $xml .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n"
        . "  <sitemap><loc>" . e($base) . "/sitemap-pages.xml</loc><lastmod>" . date('Y-m-d') . "</lastmod></sitemap>\n"
        . "  <sitemap><loc>" . e($base) . "/sitemap-tags.xml</loc><lastmod>" . date('Y-m-d') . "</lastmod></sitemap>\n"
        . "</sitemapindex>\n";
    cache_set($cacheKey, $xml);
    echo $xml;
    exit;
}

/* ---------- pages | tags ---------- */
$urls = []; // [loc, priority, changefreq, lastmod]
$today = date('Y-m-d');

if ($type === 'pages') {
    $urls[] = ['/', '1.0', 'daily', $today];
    foreach (array_keys(core_pages()) as $slug) {
        $urls[] = ['/' . $slug, '0.9', 'weekly', $today];
    }
    foreach (json_load('articles') as $a) {
        $lm = date('Y-m-d', strtotime((string) ($a['updated'] ?? 'now')) ?: time());
        $urls[] = ['/artikel/' . ($a['slug'] ?? ''), '0.8', 'monthly', $lm];
    }
    foreach (json_load('conditions')['all'] as $c) {
        $urls[] = ['/air-bambu-untuk/' . ($c['slug'] ?? ''), '0.8', 'monthly', $today];
    }
    foreach (json_load('services')['all'] as $s) {
        $urls[] = ['/layanan/' . url_slug($s), '0.8', 'monthly', $today];
    }
    foreach (json_load('bamboo') as $b) {
        $urls[] = ['/bambu/' . url_slug($b), '0.8', 'monthly', $today];
    }
    foreach (json_load('cities')['all'] as $c) {
        $urls[] = ['/kota/' . ($c['slug'] ?? ''), '0.7', 'monthly', $today];
    }
} else { /* tags */
    foreach (seo_tags() as $t) {
        $prio = ($t['group'] ?? '') === 'produk' ? '0.6' : '0.4';
        $urls[] = ['/tag/' . ($t['slug'] ?? ''), $prio, 'weekly', $today];
    }
}

$xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach ($urls as [$loc, $prio, $freq, $lm]) {
    $xml .= "  <url><loc>" . e($base . $loc) . "</loc><lastmod>" . e($lm) . "</lastmod><changefreq>" . e($freq) . "</changefreq><priority>" . e($prio) . "</priority></url>\n";
}
$xml .= "</urlset>\n";

cache_set($cacheKey, $xml);
echo $xml;

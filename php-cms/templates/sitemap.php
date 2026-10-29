<?php
/** Sitemap dinamis — seluruh 2.644+ URL (paritas dengan Next.js). */
declare(strict_types=1);

header('Content-Type: application/xml; charset=utf-8');
$base = rtrim(setting('site_url', SITE_URL), '/');
$urls = ['/'];
foreach (array_keys(core_pages()) as $slug) {
    $urls[] = '/' . $slug;
}
foreach (json_load('articles') as $a) {
    $urls[] = '/artikel/' . ($a['slug'] ?? '');
}
foreach (json_load('cities')['all'] as $c) {
    $urls[] = '/kota/' . ($c['slug'] ?? '');
}
foreach (json_load('conditions')['all'] as $c) {
    $urls[] = '/air-bambu-untuk/' . ($c['slug'] ?? '');
}
foreach (json_load('services')['all'] as $s) {
    $urls[] = '/layanan/' . url_slug($s);
}
foreach (json_load('bamboo') as $b) {
    $urls[] = '/bambu/' . url_slug($b);
}
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach (array_unique($urls) as $u): ?>
  <url><loc><?= e($base . $u) ?></loc><lastmod><?= date('Y-m-d') ?></lastmod></url>
<?php endforeach; ?>
</urlset>

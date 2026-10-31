<?php
/**
 * SEO Universalis (v0.5.0) — RSS 2.0 Feed (/feed.xml)
 * Kanal langganan untuk pembaca + sinyal kesegaran untuk Bing/DuckDuckGo.
 */
declare(strict_types=1);

if (!defined('ROOT_DIR')) { exit; }

$base = rtrim(setting('site_url', SITE_URL), '/');
$name = setting('site_name', APP_NAME);
$articles = json_load('articles');

header('Content-Type: application/rss+xml; charset=utf-8');
header('Cache-Control: public, max-age=1800');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?= e($name) ?> — Artikel & Panduan Air Bambu</title>
    <link><?= e($base) ?>/</link>
    <description>Artikel edukasi, sains, dan panduan penggunaan air bambu — terapi pendamping alami warisan Nusantara.</description>
    <language>id-ID</language>
    <lastBuildDate><?= date('r') ?></lastBuildDate>
    <atom:link href="<?= e($base) ?>/feed.xml" rel="self" type="application/rss+xml"/>
<?php foreach ($articles as $a): $u = $base . '/artikel/' . ($a['slug'] ?? ''); ?>
    <item>
      <title><?= e((string) ($a['title'] ?? '')) ?></title>
      <link><?= e($u) ?></link>
      <guid isPermaLink="true"><?= e($u) ?></guid>
      <pubDate><?= date('r', strtotime((string) ($a['updated'] ?? $a['date'] ?? 'now')) ?: time()) ?></pubDate>
      <description><?= e((string) ($a['metaDescription'] ?? $a['hook'] ?? '')) ?></description>
    </item>
<?php endforeach; ?>
  </channel>
</rss>

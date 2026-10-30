<?php
/**
 * Layout utama — shell navbar/footer/WA float/palette.
 * Variabel: $meta = ['title','desc','canonical'], $content = HTML body.
 */
$meta = array_merge(['title' => APP_NAME, 'desc' => '', 'canonical' => '/', 'type' => 'website', 'keywords' => '', 'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1', 'published' => '', 'modified' => '', 'breadcrumbs' => []], $meta ?? []);
// S1-DESIGN: brand ditambahkan TEPAT SEKALI di layout — template memberi title polos.
$_siteName = (string) setting('site_name', APP_NAME);
$_fullTitle = trim((string) $meta['title']);
if (stripos($_fullTitle, $_siteName) === false) {
    $_fullTitle .= ' | ' . $_siteName;
}
$company = company('COMPANY');
$navItems = [
    '/' => t('Beranda', 'Home'),
    '/apa-itu-air-bambu' => t('Tentang Air Bambu', 'About Bamboo Water'),
    '/harga-paket' => t('Harga & Paket', 'Pricing'),
    '/layanan' => t('Layanan', 'Services'),
    '/kesehatan' => t('Kesehatan', 'Health'),
    '/bambu' => t('Jenis Bambu', 'Bamboo Types'),
    '/artikel' => t('Artikel', 'Articles'),
    '/faq' => 'FAQ',
    '/kontak' => t('Kontak', 'Contact'),
];
$_siteUrl = rtrim(setting('site_url', SITE_URL), '/');
$_canonUrl = $_siteUrl . $meta['canonical'];
?><!DOCTYPE html>
<html lang="<?= lang() === 'en' ? 'en' : 'id' ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($_fullTitle) ?></title>
<meta name="description" content="<?= e($meta['desc']) ?>">
<?php if (($meta['keywords'] ?? '') !== ''): ?><meta name="keywords" content="<?= e($meta['keywords']) ?>">
<?php endif; ?>
<meta name="robots" content="<?= e($meta['robots']) ?>">
<meta name="googlebot" content="<?= e($meta['robots']) ?>">
<meta name="bingbot" content="<?= e($meta['robots']) ?>">
<?php if (SEO_GOOGLE_SITE_VERIFICATION !== ''): ?><meta name="google-site-verification" content="<?= e(SEO_GOOGLE_SITE_VERIFICATION) ?>">
<?php endif; if (SEO_BING_SITE_VERIFICATION !== ''): ?><meta name="msvalidate.01" content="<?= e(SEO_BING_SITE_VERIFICATION) ?>">
<?php endif; if (SEO_YANDEX_VERIFICATION !== ''): ?><meta name="yandex-verification" content="<?= e(SEO_YANDEX_VERIFICATION) ?>">
<?php endif; ?>
<link rel="canonical" href="<?= e($_canonUrl) ?>">
<link rel="alternate" hreflang="id" href="<?= e($_canonUrl) ?>">
<link rel="alternate" hreflang="en" href="<?= e($_canonUrl . (str_contains($meta['canonical'], '?') ? '&' : '?') . 'lang=en') ?>">
<link rel="alternate" hreflang="x-default" href="<?= e($_canonUrl) ?>">
<link rel="alternate" type="application/rss+xml" title="<?= e(setting('site_name', APP_NAME)) ?> — RSS" href="<?= e($_siteUrl) ?>/feed.xml">
<link rel="sitemap" type="application/xml" href="<?= e($_siteUrl) ?>/sitemap.xml">
<meta property="og:type" content="<?= e($meta['type']) ?>">
<meta property="og:site_name" content="<?= e(setting('site_name', APP_NAME)) ?>">
<meta property="og:url" content="<?= e($_canonUrl) ?>">
<meta property="og:locale" content="<?= lang() === 'en' ? 'en_US' : 'id_ID' ?>">
<meta property="og:title" content="<?= e($meta['title']) ?>">
<meta property="og:description" content="<?= e($meta['desc']) ?>">
<meta property="og:image" content="<?= e($_siteUrl . site_url_path('/assets/images/hero-bamboo.jpg')) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<?php if (($meta['published'] ?? '') !== ''): ?><meta property="article:published_time" content="<?= e($meta['published']) ?>">
<?php endif; if (($meta['modified'] ?? '') !== ''): ?><meta property="article:modified_time" content="<?= e($meta['modified']) ?>">
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="<?= e(SEO_TWITTER_HANDLE) ?>">
<meta name="twitter:title" content="<?= e($meta['title']) ?>">
<meta name="twitter:description" content="<?= e($meta['desc']) ?>">
<meta name="twitter:image" content="<?= e($_siteUrl . site_url_path('/assets/images/hero-bamboo.jpg')) ?>">
<meta name="geo.region" content="ID">
<meta name="geo.placename" content="Indonesia">
<meta name="theme-color" content="#022c22">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Ccircle cx='16' cy='16' r='16' fill='%23022c22'/%3E%3Cpath d='M16 6c3 4 5 7 5 10a5 5 0 1 1-10 0c0-3 2-6 5-10z' fill='%23d4a843'/%3E%3C/svg%3E" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= site_url_path('/assets/style.css') ?>?v=<?= APP_VERSION ?>" id="mainStyle">
<script type="application/ld+json"><?= json_encode([
    '@context' => 'https://schema.org', '@type' => 'Organization',
    'name' => setting('site_name', APP_NAME), 'url' => setting('site_url', SITE_URL),
    'telephone' => setting('phone', DEFAULT_PHONE),
    'email' => setting('email', DEFAULT_EMAIL),
    'softwareVersion' => APP_VERSION,
]) ?></script>
<?php if (($meta['breadcrumbs'] ?? []) !== []): ?><script type="application/ld+json"><?= seo_breadcrumb_ld($meta['breadcrumbs']) ?></script>
<?php endif; ?>
</head>
<body>
<a class="skip-link" href="#main"><?= t('Langsung ke konten', 'Skip to content') ?></a>

<header class="nav">
  <div class="container nav-inner">
    <a class="brand" href="<?= site_url_path('/') ?>">
      <span class="logo-dot" aria-hidden="true">🎋</span>
      <span>Bambu Water<br><small style="letter-spacing:.28em;font-size:.6rem;color:var(--gold-600)">NUSANTARA</small></span>
    </a>
    <button class="nav-toggle" aria-label="Menu" aria-expanded="false" onclick="const n=document.getElementById('navLinks');n.classList.toggle('open');this.setAttribute('aria-expanded',n.classList.contains('open'))">☰</button>
    <nav class="nav-links" id="navLinks" aria-label="<?= t('Navigasi utama', 'Main navigation') ?>">
      <?php foreach ($navItems as $href => $label): ?>
        <a href="<?= site_url_path($href) ?>" <?= ($meta['canonical'] === $href) ? 'aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
      <button class="tab-btn" style="margin-left:6px" onclick="openPalette()" aria-label="Cari situs (Ctrl+K)">🔍 Cari</button>
      <a href="<?= site_url_path('/kontak') ?>" class="btn btn-gold" style="padding:10px 18px"><?= t('Pesan Sekarang', 'Order Now') ?></a>
      <a href="<?= lang_switch_url() ?>" class="nav-links-lang" style="padding:9px 10px;font-size:.85rem"><?= lang() === 'en' ? '🇮🇩 ID' : '🇬🇧 EN' ?></a>
    </nav>
  </div>
</header>

<main id="main">
<?= $content ?? '' ?>
</main>

<footer class="site">
  <div class="container">
    <div class="footer-grid">
      <div>
        <h4><?= e(setting('site_name', APP_NAME)) ?></h4>
        <p style="font-size:.92rem"><?= e(company('COMPANY')['description'] ?? 'Produsen air bambu terapi pengobatan & distribusi bambu berkualitas massal se-Indonesia.') ?></p>
        <p style="font-size:.92rem">📞 <?= e(setting('phone', DEFAULT_PHONE)) ?><br>✉️ <?= e(setting('email', DEFAULT_EMAIL)) ?></p>
      </div>
      <div>
        <h4><?= t('Jelajahi', 'Explore') ?></h4>
        <a href="<?= site_url_path('/tentang-kami') ?>"><?= t('Tentang Kami', 'About Us') ?></a>
        <a href="<?= site_url_path('/kesehatan') ?>"><?= t('Pusat Kesehatan', 'Health Center') ?></a>
        <a href="<?= site_url_path('/kota') ?>"><?= t('Jangkauan Kota', 'City Coverage') ?></a>
        <a href="<?= site_url_path('/testimoni') ?>"><?= t('Testimoni', 'Testimonials') ?></a>
        <a href="<?= site_url_path('/karier') ?>"><?= t('Karier', 'Careers') ?></a>
      </div>
      <div>
        <h4><?= t('Produk & Layanan', 'Products & Services') ?></h4>
        <a href="<?= site_url_path('/harga-paket') ?>"><?= t('Harga & Paket', 'Pricing') ?></a>
        <a href="<?= site_url_path('/layanan') ?>"><?= t('Katalog Layanan', 'Services') ?></a>
        <a href="<?= site_url_path('/bambu') ?>"><?= t('Jenis Bambu', 'Bamboo Types') ?></a>
        <a href="<?= site_url_path('/pengiriman-massal') ?>"><?= t('Pengiriman Massal', 'Bulk Delivery') ?></a>
        <a href="<?= site_url_path('/lacak-pesanan') ?>"><?= t('Lacak Pesanan', 'Track Order') ?></a>
      </div>
      <div>
        <h4><?= t('Bantuan', 'Help') ?></h4>
        <a href="<?= site_url_path('/faq') ?>">FAQ</a>
        <a href="<?= site_url_path('/kebijakan-privasi') ?>"><?= t('Kebijakan Privasi', 'Privacy Policy') ?></a>
        <a href="<?= site_url_path('/syarat-ketentuan') ?>"><?= t('Syarat & Ketentuan', 'Terms') ?></a>
        <a href="<?= site_url_path('/peta-situs') ?>"><?= t('Peta Situs', 'Sitemap') ?></a>
        <a href="<?= site_url_path('/admin') ?>"><?= t('Masuk Admin', 'Admin Login') ?></a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?= date('Y') ?> PT <?= e(setting('site_name', APP_NAME)) ?> • bambuwater.web.id</span>
      <span><?= t('Terapi alami pendamping — bukan pengganti nasihat medis', 'Natural complementary therapy — not medical advice') ?>
        • <a class="version-badge" href="<?= site_url_path('/api/version') ?>">v<?= APP_VERSION ?></a></span>
    </div>
  </div>
</footer>

<a class="wa-float" href="<?= wa_link(t('Halo, saya ingin bertanya tentang air bambu', 'Hello, I want to ask about bamboo water')) ?>" target="_blank" rel="noopener" aria-label="Chat WhatsApp">
  <svg width="30" height="30" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5.4 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.3-.7-2.8-1.1-4.6-4-4.7-4.2-.1-.2-1.1-1.5-1.1-2.9s.7-2 1-2.3c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5s.7 1.8.8 1.9c0 .1.1.3 0 .4-.4.8-.9 1-.7 1.4.8 1.3 1.7 2 3 2.7.4.2.6.2.8-.1l.8-1c.2-.3.4-.2.7-.1l2 1c.3.1.5.2.6.4 0 .1 0 .7-.2 1.2z"/></svg>
</a>

<div class="pal-backdrop" id="palBackdrop" role="dialog" aria-modal="true" aria-label="Pencarian situs">
  <div class="pal">
    <input id="palInput" type="search" placeholder="<?= t('Cari halaman, kota, kondisi kesehatan…', 'Search pages, cities, health conditions…') ?>" aria-label="Kata kunci pencarian">
    <div class="pal-list" id="palList"></div>
  </div>
</div>

<script src="<?= site_url_path('/assets/app.js') ?>?v=<?= APP_VERSION ?>" defer></script>

<?php /* ===== Analitik (v0.5.1) — aktif hanya bila ID diisi di config.php =====
   Tanpa ID = nol script (privasi-utama). GA4 dianonimkan IP (UU PDP/GDPR). */
if (ANALYTICS_GA4_ID !== ''): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(ANALYTICS_GA4_ID) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= e(ANALYTICS_GA4_ID) ?>', { anonymize_ip: true });
</script>
<?php endif; if (ANALYTICS_CLARITY_ID !== ''): ?>
<script>
  (function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
  t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
  y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);})
  (window,document,"clarity","script","<?= e(ANALYTICS_CLARITY_ID) ?>");
</script>
<?php endif; ?>
</body>
</html>

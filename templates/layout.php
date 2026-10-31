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
$_lang = lang();
?><!DOCTYPE html>
<html lang="<?= e($_lang) ?>" dir="<?= lang_is_rtl($_lang) ? 'rtl' : 'ltr' ?>">
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
<?php foreach (i18n_langs() as $_lc): if ($_lc === 'id'): ?>
<link rel="alternate" hreflang="id" href="<?= e($_canonUrl) ?>">
<?php else: ?>
<link rel="alternate" hreflang="<?= e($_lc) ?>" href="<?= e($_canonUrl . (str_contains($meta['canonical'], '?') ? '&' : '?') . 'lang=' . $_lc) ?>">
<?php endif; endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= e($_canonUrl) ?>">
<link rel="alternate" type="application/rss+xml" title="<?= e(setting('site_name', APP_NAME)) ?> — RSS" href="<?= e($_siteUrl) ?>/feed.xml">
<link rel="sitemap" type="application/xml" href="<?= e($_siteUrl) ?>/sitemap.xml">
<meta property="og:type" content="<?= e($meta['type']) ?>">
<meta property="og:site_name" content="<?= e(setting('site_name', APP_NAME)) ?>">
<meta property="og:url" content="<?= e($_canonUrl) ?>">
<meta property="og:locale" content="<?= e(lang_og_locale($_lang)) ?>">
<meta property="og:title" content="<?= e($meta['title']) ?>">
<meta property="og:description" content="<?= e($meta['desc']) ?>">
<meta property="og:image" content="<?= e($_siteUrl . site_url_path('/assets/images/og-image.jpg')) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<?php if (($meta['published'] ?? '') !== ''): ?><meta property="article:published_time" content="<?= e($meta['published']) ?>">
<?php endif; if (($meta['modified'] ?? '') !== ''): ?><meta property="article:modified_time" content="<?= e($meta['modified']) ?>">
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="<?= e(SEO_TWITTER_HANDLE) ?>">
<meta name="twitter:title" content="<?= e($meta['title']) ?>">
<meta name="twitter:description" content="<?= e($meta['desc']) ?>">
<meta name="twitter:image" content="<?= e($_siteUrl . site_url_path('/assets/images/og-image.jpg')) ?>">
<meta name="geo.region" content="ID">
<meta name="geo.placename" content="Indonesia">
<meta name="theme-color" content="#022c22">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Ccircle cx='16' cy='16' r='16' fill='%23022c22'/%3E%3Cpath d='M16 6c3 4 5 7 5 10a5 5 0 1 1-10 0c0-3 2-6 5-10z' fill='%23d4a843'/%3E%3C/svg%3E" type="image/svg+xml">
<?php /* Font self-hosted (lihat assets/fonts/) — nol request pihak ketiga,
   font-display: swap, preconnect/Google Fonts dihapus (render-blocking). */ ?>
<link rel="preload" as="font" type="font/woff2" crossorigin href="<?= site_url_path('/assets/fonts/plus-jakarta-sans-var.woff2') ?>">
<link rel="preload" as="font" type="font/woff2" crossorigin href="<?= site_url_path('/assets/fonts/playfair-display-700.woff2') ?>">
<?php if (!empty($meta['lcp'])): /* LCP hero — prioritas tertinggi */ ?><link rel="preload" as="image" href="<?= e($_siteUrl . $meta['lcp']) ?>" fetchpriority="high">
<?php endif; ?>
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
    <button class="nav-toggle" id="navToggle" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="navLinks">☰</button>
    <nav class="nav-links" id="navLinks" aria-label="<?= t('Navigasi utama', 'Main navigation') ?>">
      <?php foreach ($navItems as $href => $label): ?>
        <a href="<?= site_url_path($href) ?>" <?= ($meta['canonical'] === $href) ? 'aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
      <button class="tab-btn" style="margin-left:6px" data-pal-open aria-label="Cari situs (Ctrl+K)">🔍 <?= t('Cari', 'Search') ?></button>
      <a href="<?= site_url_path('/kontak') ?>" class="btn btn-gold btn-sm"><?= t('Pesan Sekarang', 'Order Now') ?></a>
      <details class="lang">
        <summary aria-label="<?= t('Ganti bahasa', 'Change language') ?>">🌐 <?= e(strtoupper($_lang)) ?></summary>
        <div class="lang-panel">
          <?php foreach (i18n_langs() as $_lc): ?>
          <a href="<?= lang_url($_lc) ?>" <?= $_lc === $_lang ? 'aria-current="true"' : '' ?>><?= e(lang_native_name($_lc)) ?><small><?= e(strtoupper($_lc)) ?></small></a>
          <?php endforeach; ?>
        </div>
      </details>
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
<button class="to-top" id="toTop" aria-label="<?= t('Kembali ke atas', 'Back to top') ?>">↑</button>

<div class="pal-backdrop" id="palBackdrop" role="dialog" aria-modal="true" aria-label="Pencarian situs">
  <div class="pal">
    <input id="palInput" type="search" placeholder="<?= t('Cari halaman, kota, kondisi kesehatan…', 'Search pages, cities, health conditions…') ?>" aria-label="Kata kunci pencarian">
    <div class="pal-list" id="palList"></div>
  </div>
</div>

<script src="<?= site_url_path('/assets/app.js') ?>?v=<?= APP_VERSION ?>" defer></script>

<?php /* ===== Analitik (v3) — privasi-utama (UU PDP/GDPR) =====
   Script analitik HANYA dimuat bila: (1) ID diisi di config.php, DAN
   (2) pengunjung menekan "Setuju" pada banner cookie (cookie bwn_consent=1).
   Tanpa ID atau tanpa persetujuan = nol script pihak ketiga. GA4 dianonimkan IP. */
$_analyticsOn = (ANALYTICS_GA4_ID !== '' || ANALYTICS_CLARITY_ID !== '');
$_consent = ($_COOKIE['bwn_consent'] ?? '') === '1';
if ($_analyticsOn && !$_consent): ?>
<div class="consent" id="consentBanner" role="region" aria-label="Persetujuan cookie">
  <p>Kami memakai cookie fungsional; analitik (Google Analytics / Clarity) hanya aktif dengan persetujuan Anda. <a href="<?= site_url_path('/kebijakan-privasi') ?>">Kebijakan Privasi</a>.</p>
  <div>
    <button class="btn btn-primary btn-sm" data-consent="1"><?= t('Setuju', 'Accept') ?></button>
    <button class="btn btn-outline btn-sm" data-consent="0"><?= t('Tolak', 'Decline') ?></button>
  </div>
</div>
<?php endif; if ($_analyticsOn && $_consent): if (ANALYTICS_GA4_ID !== ''): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e(ANALYTICS_GA4_ID) ?>"></script>
<script nonce="<?= csp_nonce() ?>">
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= e(ANALYTICS_GA4_ID) ?>', { anonymize_ip: true });
</script>
<?php endif; if (ANALYTICS_CLARITY_ID !== ''): ?>
<script nonce="<?= csp_nonce() ?>">
  (function(c,l,a,r,i,t,y){c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
  t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
  y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);})
  (window,document,"clarity","script","<?= e(ANALYTICS_CLARITY_ID) ?>");
</script>
<?php endif; endif; ?>
</body>
</html>

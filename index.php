<?php
/**
 * Front Controller — BWN PHP CMS v0.5.0 "SEO Universalis"
 * Semua request lewat sini (via .htaccess / router php -S).
 * URL bersih: /kota/{slug}, /air-bambu-untuk/{slug}, /tag/{slug}, /llms.txt, dll.
 */
declare(strict_types=1);

/* php -S router: serahkan file statis nyata ke server bawaan,
   TAPI kunci file kode/data agar tak bisa diunduh mentah */
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($file)) {
        $blocked = preg_match('#\.(json|sqlite|sqlite-wal|sqlite-shm|md|log|gz)$#i', $file)
            || str_contains($file, '/data/') || str_contains($file, '/lib/')
            || str_contains($file, '/templates/') || str_ends_with($file, '/config.php');
        if ($blocked) {
            http_response_code(403);
            exit;
        }
        if (!str_ends_with($file, '.php')) {
            return false;
        }
    }
}

require __DIR__ . '/config.php';
require ROOT_DIR . '/lib/seo.php';               // SEO Universalis: tag hub, IndexNow, JSON-LD
require ROOT_DIR . '/templates/pages.php';      // core_pages() dipakai sitemap & router
require ROOT_DIR . '/templates/programmatic.php';
require ROOT_DIR . '/templates/tags.php';       // /tag + /tag/{slug}
require ROOT_DIR . '/templates/llms.php';       // /llms.txt + /llms-full.txt (AI SEO)

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
lang();                       // panggil awal: cookie preferensi bahasa terpasang sebelum output
send_security_headers();      // CSP + proteksi header untuk SEMUA respons via front controller

/* ---------- Path dasar (dukung subfolder + query lang) ---------- */
$rawPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if (BASE_PATH !== '' && str_starts_with($rawPath, BASE_PATH)) {
    $rawPath = substr($rawPath, strlen(BASE_PATH)) ?: '/';
}

/* Kanonikalisasi URL (SEO): trailing-slash & huruf besar → 301 permanen.
   File fisik nyata dilayani Apache/cli-server sebelum sampai sini. */
if ($rawPath !== '/' && (str_ends_with($rawPath, '/') || preg_match('/[A-Z]/', $rawPath))) {
    $target = '/' . trim(mb_strtolower($rawPath), '/');
    $qs = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY);
    header('Location: ' . site_url_path($target === '' ? '/' : $target) . ($qs ? '?' . $qs : ''), true, 301);
    exit;
}
$path = '/' . trim($rawPath, '/');
if ($path === '//') {
    $path = '/';
}

/* ---------- API & teknis ---------- */
if ($path === '/api/version') {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: public, max-age=300');
    echo json_encode([
        'name' => APP_NAME, 'domain' => 'bambuwater.web.id', 'version' => APP_VERSION,
        'codename' => APP_CODENAME, 'engine' => 'PHP ' . PHP_VERSION,
        'status' => 'ok', 'timestamp' => date('c'),
    ]);
    exit;
}

if ($path === '/api/health') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        db()->query('SELECT 1');
        echo json_encode(['status' => 'healthy', 'db' => 'up', 'version' => APP_VERSION]);
    } catch (Throwable $ex) {
        echo json_encode(['status' => 'degraded', 'db' => 'down', 'version' => APP_VERSION]);
    }
    exit;
}

if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: public, max-age=3600');
    $base = rtrim(setting('site_url', SITE_URL), '/');
    echo "# Bambu Water Nusantara — robots.txt (SEO Universalis v" . APP_VERSION . ")\n";
    echo "# Mesin pencari utama: seluruh situs terbuka kecuali panel admin\n\n";
    foreach (['Googlebot','Googlebot-Image','Googlebot-News','Googlebot-Video','Bingbot','DuckDuckBot','YandexBot','Baiduspider','Applebot','Slurp'] as $ua) {
        echo "User-agent: {$ua}\nAllow: /\nDisallow: /admin\n\n";
    }
    echo "# Mesin pencari & asisten AI — dipersilakan penuh (dokumen publik)\n";
    foreach (['GPTBot','OAI-SearchBot','ChatGPT-User','ClaudeBot','Claude-User','Claude-SearchBot','anthropic-ai','PerplexityBot','Perplexity-User','Google-Extended','Applebot-Extended','Bytespider','YouBot','cohere-ai','Meta-ExternalAgent','Amazonbot'] as $ua) {
        echo "User-agent: {$ua}\nAllow: /\nDisallow: /admin\n\n";
    }
    echo "# Semua agen lain\nUser-agent: *\nAllow: /\nDisallow: /admin\n\n";
    echo "Sitemap: {$base}/sitemap.xml\n";
    echo "# Dokumen AI: {$base}/llms.txt (ringkas) · {$base}/llms-full.txt (lengkap)\n";
    exit;
}

if ($path === '/sitemap.xml') {
    $_GET['type'] = 'index';
    require ROOT_DIR . '/templates/sitemap.php';
    exit;
}
if ($path === '/sitemap-pages.xml') {
    $_GET['type'] = 'pages';
    require ROOT_DIR . '/templates/sitemap.php';
    exit;
}
if ($path === '/sitemap-tags.xml') {
    $_GET['type'] = 'tags';
    require ROOT_DIR . '/templates/sitemap.php';
    exit;
}
if ($path === '/feed.xml') {
    require ROOT_DIR . '/templates/feed.php';
    exit;
}
if ($path === '/llms.txt') {
    llms_render(false);
    exit;
}
if ($path === '/llms-full.txt') {
    llms_render(true);
    exit;
}
/* IndexNow key verification file: /{KEY}.txt */
if ($path === '/' . indexnow_key() . '.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo indexnow_key();
    exit;
}

/* ---------- Verifikasi metode FILE (v0.5.1) — GSC / Yandex / Bing ----------
 * Hanya melayani nama file yang TEPAT terdaftar di config.php (aman:
 * kode asing otomatis 404). Konten wajib persis ketentuan masing-masing. */
if (preg_match('#^/(google[a-z0-9_\-]+\.html)$#', $path, $m)) {
    if (SEO_GSC_VERIFY_FILE !== '' && $m[1] === SEO_GSC_VERIFY_FILE) {
        header('Content-Type: text/plain; charset=utf-8');
        echo 'google-site-verification: ' . SEO_GSC_VERIFY_FILE;
        exit;
    }
}
if (preg_match('#^/(yandex[a-z0-9_\-]+\.html)$#', $path, $m)) {
    if (SEO_YANDEX_VERIFY_FILE !== '' && $m[1] === SEO_YANDEX_VERIFY_FILE) {
        header('Content-Type: text/html; charset=utf-8');
        echo '<html><head></head><body>Verification: ' . SEO_YANDEX_VERIFY_FILE . '</body></html>';
        exit;
    }
}
if ($path === '/BingSiteAuth.xml' && SEO_BING_AUTH_XML !== '') {
    header('Content-Type: application/xml; charset=utf-8');
    echo SEO_BING_AUTH_XML;
    exit;
}

/* ---------- Tag Hub (SEO Universalis) ---------- */
if ($path === '/tag') {
    render_tags_index();
    exit;
}
if (preg_match('#^/tag/([a-z0-9\-]+)$#', $path, $m)) {
    render_tag($m[1]);
    exit;
}

if ($path === '/search-index.json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: public, max-age=600');
    $hit = cache_get('search-index', 600);
    if ($hit !== null) {
        header('X-Cache: HIT');
        echo $hit;
        exit;
    }
    $b = BASE_PATH;
    $idx = [['t' => 'Beranda', 'g' => 'Halaman', 'u' => $b . '/']];
    foreach (json_load('company')['PRODUCTS'] ?? [] as $p) {
        $idx[] = ['t' => $p['name'] ?? '', 'g' => 'Produk', 'u' => $b . '/' . ($p['slug'] ?? '')];
    }
    foreach (json_load('cities')['all'] as $c) {
        $idx[] = ['t' => 'Air Bambu ' . ($c['name'] ?? ''), 'g' => 'Kota — ' . ($c['province'] ?? ''), 'u' => $b . '/kota/' . ($c['slug'] ?? '')];
    }
    foreach (json_load('conditions')['all'] as $c) {
        $idx[] = ['t' => 'Air Bambu untuk ' . ($c['name'] ?? ''), 'g' => 'Kondisi Kesehatan', 'u' => $b . '/air-bambu-untuk/' . ($c['slug'] ?? '')];
    }
    foreach (json_load('services')['all'] as $s) {
        $idx[] = ['t' => $s['name'] ?? '', 'g' => 'Layanan', 'u' => $b . '/layanan/' . url_slug($s)];
    }
    foreach (json_load('bamboo') as $bt) {
        $idx[] = ['t' => 'Bambu ' . ($bt['name'] ?? ''), 'g' => 'Jenis Bambu', 'u' => $b . '/bambu/' . url_slug($bt)];
    }
    foreach (json_load('articles') as $a) {
        $idx[] = ['t' => $a['title'] ?? '', 'g' => 'Artikel', 'u' => $b . '/artikel/' . ($a['slug'] ?? '')];
    }
    $json = json_encode($idx, JSON_UNESCAPED_SLASHES);
    cache_set('search-index', (string) $json);
    echo $json;
    exit;
}

/* ---------- Admin CMS ---------- */
if ($path === '/admin' || str_starts_with($path, '/admin/')) {
    $_GET['route'] = ltrim(substr($path, 6), '/') ?: '/';
    require ROOT_DIR . '/admin-cms.php';
    exit;
}

/* ---------- Kontak (form POST) ---------- */
if ($path === '/kontak' && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    require ROOT_DIR . '/templates/contact-submit.php';
    exit;
}

/* ---------- Router konten ---------- */
/* Halaman programatik */
if (preg_match('#^/kota/([a-z0-9\-]+)$#', $path, $m)) {
    render_city($m[1]);
    exit;
}
if ($path === '/kota') {
    render_cities_index();
    exit;
}
if (preg_match('#^/air-bambu-untuk/([a-z0-9\-]+)$#', $path, $m)) {
    render_condition($m[1]);
    exit;
}
if ($path === '/kesehatan') {
    render_conditions_index();
    exit;
}
if (preg_match('#^/layanan/([a-z0-9\-]+)$#', $path, $m)) {
    render_service($m[1]);
    exit;
}
if ($path === '/layanan') {
    render_services_index();
    exit;
}
if (preg_match('#^/bambu/([a-z0-9\-]+)$#', $path, $m)) {
    render_bamboo($m[1]);
    exit;
}
if ($path === '/bambu') {
    render_bamboo_index();
    exit;
}
if (preg_match('#^/artikel/([a-z0-9\-]+)$#', $path, $m)) {
    render_article($m[1]);
    exit;
}
if ($path === '/artikel') {
    render_articles_index();
    exit;
}

/* Halaman inti terdaftar */
if (render_core_page($path)) {
    exit;
}

/* Beranda */
if ($path === '/') {
    render_home();
    exit;
}

/* 404 ramah */
http_response_code(404);
$meta = ['title' => 'Halaman Tidak Ditemukan', 'desc' => 'Halaman yang Anda cari tidak ada. Jelajahi peta situs.', 'canonical' => '/404'];
ob_start();
$pagePath = site_url_path('/peta-situs'); ?>
<section class="page-hero"><div class="container">
  <p class="crumbs"><a href="<?= site_url_path('/') ?>">Beranda</a> › 404</p>
  <h1>404 — Halaman Tidak Ditemukan</h1>
  <p>Alamat ini tidak ada atau sudah dipindah. Tenang — semua pintu tetap terbuka.</p>
</div></section>
<section class="section"><div class="container" style="text-align:center">
  <a class="btn btn-primary" href="<?= site_url_path('/') ?>">Ke Beranda</a>
  <a class="btn btn-outline" href="<?= $pagePath ?>">Peta Situs</a>
</div></section>
<?php
render_layout($meta, ob_get_clean());

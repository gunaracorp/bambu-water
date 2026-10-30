<?php
/**
 * SEO Universalis (v0.5.0) — Mesin SEO terpadu PHP CMS
 * Tag hub, pencocokan konten, IndexNow, fragment JSON-LD.
 * Prinsip Dewan-46: tag adalah HUB agregasi konten nyata — bukan doorway tipis.
 */
declare(strict_types=1);

/* ---------- Bank kata kunci ---------- */
function seo_tags(): array
{
    static $tags = null;
    if ($tags === null) {
        $raw = json_decode(@file_get_contents(DATA_DIR . '/keywords.json'), true);
        $tags = is_array($raw['tags'] ?? null) ? $raw['tags'] : [];
    }
    return $tags;
}

function seo_tag_by_slug(string $slug): ?array
{
    foreach (seo_tags() as $t) {
        if (($t['slug'] ?? '') === $slug) {
            return $t;
        }
    }
    return null;
}

/* ---------- Pencocokan token (hub ↔ konten nyata) ---------- */
function seo_tokens(string $text): array
{
    $t = preg_replace('/[^a-z0-9]+/', ' ', mb_strtolower($text));
    $toks = array_filter(explode(' ', (string) $t), fn($w) => mb_strlen($w) >= 4);
    return array_values(array_unique($toks));
}

/**
 * Kandidat halaman nyata untuk satu tag (kota, kondisi, layanan, bambu, artikel).
 * Skor = irisan token. Maksimal 12 agar hub tetap fokus.
 * @return array<int, array{title:string,url:string,group:string}>
 */
function seo_tag_matches(array $tag, int $max = 12): array
{
    $kw = (string) ($tag['kw'] ?? '');
    $toks = seo_tokens($kw);
    if ($toks === []) {
        return [];
    }
    $b = BASE_PATH;
    $cands = [];

    foreach (json_load('cities')['all'] ?? [] as $c) {
        $name = (string) ($c['name'] ?? '');
        if (stripos($kw, mb_strtolower($name)) !== false || seo_overlap($toks, $name)) {
            $cands[] = ['title' => 'Air Bambu ' . $name, 'url' => $b . '/kota/' . ($c['slug'] ?? ''), 'group' => 'Kota', 'score' => 3 + (int) (stripos($kw, mb_strtolower($name)) !== false)];
        }
    }
    foreach (json_load('conditions')['all'] ?? [] as $c) {
        $name = (string) ($c['name'] ?? '');
        if (seo_overlap($toks, $name)) {
            $cands[] = ['title' => 'Air Bambu untuk ' . $name, 'url' => $b . '/air-bambu-untuk/' . ($c['slug'] ?? ''), 'group' => 'Kondisi Kesehatan', 'score' => 2];
        }
    }
    foreach (json_load('services')['all'] ?? [] as $s) {
        $name = (string) ($s['name'] ?? '');
        if (seo_overlap($toks, $name)) {
            $cands[] = ['title' => $name, 'url' => $b . '/layanan/' . url_slug($s), 'group' => 'Layanan', 'score' => 2];
        }
    }
    foreach (json_load('bamboo') as $bt) {
        $name = (string) ($bt['name'] ?? '');
        if (seo_overlap($toks, $name)) {
            $cands[] = ['title' => 'Bambu ' . str_ireplace('bambu ', '', $name), 'url' => $b . '/bambu/' . ($bt['slug'] ?? ''), 'group' => 'Jenis Bambu', 'score' => 2];
        }
    }
    foreach (json_load('articles') as $a) {
        $title = (string) ($a['title'] ?? '');
        if (seo_overlap($toks, $title)) {
            $cands[] = ['title' => $title, 'url' => $b . '/artikel/' . ($a['slug'] ?? ''), 'group' => 'Artikel', 'score' => 1];
        }
    }
    usort($cands, fn($x, $y) => $y['score'] <=> $x['score']);
    return array_slice($cands, 0, $max);
}

function seo_overlap(array $toks, string $text): bool
{
    $lt = mb_strtolower($text);
    foreach ($toks as $w) {
        if (mb_strpos($lt, $w) !== false) {
            return true;
        }
    }
    return false;
}

/** Tag sekelompok + irisan token (untuk "pencarian terkait"). */
function seo_related_tags(array $tag, int $max = 10): array
{
    $group = (string) ($tag['group'] ?? '');
    $toks = seo_tokens((string) ($tag['kw'] ?? ''));
    $rel = [];
    foreach (seo_tags() as $t) {
        if (($t['slug'] ?? '') === ($tag['slug'] ?? '')) { continue; }
        if (($t['group'] ?? '') !== $group) { continue; }
        if ($toks !== [] && !seo_overlap($toks, (string) ($t['kw'] ?? ''))) { continue; }
        $rel[] = $t;
        if (count($rel) >= $max) { break; }
    }
    return $rel;
}

/* ---------- Meta templating (variasi rotasi anti-duplikat) ---------- */
function seo_tag_title(array $tag): string
{
    $v = [
        ucfirst((string) $tag['kw']) . ' — Info, Harga & Panduan',
        'Panduan ' . ucfirst((string) $tag['kw']),
        ucfirst((string) $tag['kw']) . ' | Info Lengkap & Pesan',
    ];
    return $v[((int) (hexdec(substr(md5((string) $tag['slug']), 0, 4)) % 3))];
}
function seo_tag_desc(array $tag): string
{
    $wa = setting('phone', DEFAULT_PHONE);
    $v = [
        'Kumpulan informasi ' . $tag['kw'] . ' di Bambu Water Nusantara: halaman kota, panduan kesehatan, layanan, dan artikel terkait. Pesan cepat via WA ' . $wa . '.',
        'Pusat panduan ' . $tag['kw'] . ' — air bambu suling & bambu berkualitas, pengiriman se-Indonesia. Jelajahi tautan relevan terkurasi Dewan kami.',
        'Info lengkap seputar ' . $tag['kw'] . ': cara pesan, harga, dan panduan terapi pendamping alami. Hubungi kami via WhatsApp ' . $wa . '.',
    ];
    return $v[((int) (hexdec(substr(md5((string) $tag['slug']), 0, 4)) % 3))];
}

/* ---------- Breadcrumb JSON-LD ---------- */
function seo_breadcrumb_ld(array $items): string
{
    $base = rtrim(setting('site_url', SITE_URL), '/');
    $li = [];
    $pos = 1;
    foreach ($items as $name => $url) {
        $li[] = ['@type' => 'ListItem', 'position' => $pos++, 'name' => $name, 'item' => $base . $url];
    }
    return json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $li,
    ], JSON_UNESCAPED_SLASHES);
}

/**
 * Konversi tanggal teks Indonesia ("Juni 2025", "12 Desember 2025") → ISO 8601.
 * Jika tak terbaca, kembalikan string kosong (bukan angka salah).
 */
function seo_iso_date(string $text): string
{
    $map = [
        'januari' => 'January', 'februari' => 'February', 'maret' => 'March', 'april' => 'April',
        'mei' => 'May', 'juni' => 'June', 'juli' => 'July', 'agustus' => 'August',
        'september' => 'September', 'oktober' => 'October', 'november' => 'November', 'desember' => 'December',
    ];
    $en = str_ireplace(array_keys($map), array_values($map), trim($text));
    $ts = strtotime($en);
    return $ts ? date('c', $ts) : '';
}

/** FAQPage JSON-LD — dikutip mesin pencari & AI sebagai jawaban langsung. */
function seo_faq_ld(array $faqs): string
{
    $qa = [];
    foreach ($faqs as $f) {
        $q = trim((string) ($f['q'] ?? ''));
        $a = trim((string) ($f['a'] ?? ''));
        if ($q === '' || $a === '') { continue; }
        $qa[] = ['@type' => 'Question', 'name' => $q, 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a]];
    }
    if ($qa === []) { return ''; }
    return json_encode(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $qa], JSON_UNESCAPED_SLASHES);
}

/** Product + AggregateOffer JSON-LD dari priceRange bebas-teks ("Rp 85.000 – 150.000 / batang"). */
function seo_product_ld(array $b): string
{
    $range = (string) ($b['priceRange'] ?? '');
    preg_match_all('/[\d][\d\.]*/', str_replace('.', '', $range), $m);
    $nums = array_map('intval', $m[0] ?? []);
    $node = [
        '@context' => 'https://schema.org', '@type' => 'Product',
        'name' => (string) ($b['name'] ?? ''),
        'description' => (string) ($b['hook'] ?? ''),
        'category' => (string) ($b['category'] ?? ''),
        'brand' => ['@type' => 'Brand', 'name' => setting('site_name', APP_NAME)],
    ];
    if ($nums !== []) {
        $node['offers'] = [
            '@type' => 'AggregateOffer',
            'priceCurrency' => 'IDR',
            'lowPrice' => min($nums),
            'highPrice' => max($nums),
            'offerCount' => 1,
            'availability' => 'https://schema.org/InStock',
        ];
    }
    return json_encode($node, JSON_UNESCAPED_SLASHES);
}

/* ---------- IndexNow (Bing instant indexing) ---------- */
function indexnow_key(): string
{
    return (string) (setting('indexnow_key', INDEXNOW_KEY));
}

/**
 * Kirim URL ke jaringan IndexNow (Bing, Yandex, Seznam, Naver).
 * Best-effort: gagal jaringan = silent false (tak pernah menghambat request user).
 * @param string[] $urls
 */
function indexnow_ping(array $urls): bool
{
    $key = indexnow_key();
    if ($key === '' || $urls === []) { return false; }
    $host = parse_url(setting('site_url', SITE_URL), PHP_URL_HOST) ?: 'bambuwater.web.id';
    $body = json_encode([
        'host' => $host,
        'key' => $key,
        'keyLocation' => rtrim(setting('site_url', SITE_URL), '/') . '/' . $key . '.txt',
        'urlList' => array_values(array_slice($urls, 0, 10000)),
    ], JSON_UNESCAPED_SLASHES);
    $ctx = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json; charset=utf-8\r\n",
            'content' => $body,
            'timeout' => 4,
            'ignore_errors' => true,
        ],
    ]);
    $res = @file_get_contents('https://api.indexnow.org/indexnow', false, $ctx);
    return $res !== false;
}

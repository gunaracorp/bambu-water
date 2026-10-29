<?php
/**
 * Halaman programatik — 2.524 kota, 60 kondisi, 25 layanan, 8 bambu, 7 artikel.
 * Semua di-serve dinamis dari data JSON (tanpa generate ribuan file).
 */
declare(strict_types=1);

/* ---------- KOTA ---------- */
function render_city(string $slug): void
{
    $city = find_by_slug(json_load('cities')['all'], $slug);
    if (!$city) {
        not_found_redirect();
        return;
    }
    $name = (string) $city['name'];
    $meta = [
        'title' => "Jual Air Bambu {$name} — Pengiriman {$city['eta']}",
        'desc' => "Air bambu suling & bambu berkualitas dikirim ke {$name}, {$city['province']}. Estimasi tiba {$city['eta']}. Harga grosir & retail transparan — WA 0813-1651-6524.",
        'canonical' => '/kota/' . $slug,
    ];
    ob_start(); ?>
<?= page_hero('Kota › ' . $name, "Air Bambu & Bambu Berkualitas untuk {$name}", "Pengiriman ke {$name}, {$city['province']} ({$city['region']}) dengan estimasi {$city['eta']}. Retail botol, langganan galon, hingga kargo truk.") ?>
<section class="section"><div class="container">
  <div class="grid grid-3">
    <div class="card reveal"><div class="icon">⏱️</div><h3>Estimasi Tiba</h3><p><b><?= e($city['eta']) ?></b> dari pabrik Tasikmalaya.</p></div>
    <div class="card reveal"><div class="icon">📦</div><h3>Volume Fleksibel</h3><p>Botol 600 ml hingga kargo truk full loads untuk distributor.</p></div>
    <div class="card reveal"><div class="icon">💬</div><h3>Konsultasi Gratis</h3><p>Tim kami hitungkan biaya kargo terbaik ke <?= e($name) ?>.</p></div>
  </div>
  <h2 class="reveal" style="margin-top:40px">Produk yang Bisa Anda Terima di <?= e($name) ?></h2>
  <div class="grid grid-3" style="margin-top:16px">
    <?php foreach (company('PRODUCTS', []) as $p): ?>
      <div class="card reveal"><div class="icon">💧</div><h3 style="font-size:1.02rem"><?= e($p['name']) ?></h3><p><?= e(implode(' • ', $p['sizes'] ?? [])) ?></p></div>
    <?php endforeach; ?>
    <div class="card reveal"><div class="icon">🎋</div><h3 style="font-size:1.02rem">Bambu Batang Pilihan</h3><p>Petung, Wulung, Apus, Ori — konstruksi & industri.</p></div>
  </div>
</div></section>
<?= cta_band("Pesan untuk {$name} Sekarang", "Sebut jumlah & alamat — penawaran lengkap dengan ongkos kargo kami balas cepat.") ?>
<?php render_layout($meta, (string) ob_get_clean());
}

function render_cities_index(): void
{
    $cities = json_load('cities')['all'];
    $meta = ['title' => 'Jangkauan Pengiriman 2.524 Kota & Kabupaten Se-Indonesia', 'desc' => 'Daftar lengkap kota & kabupaten tujuan pengiriman air bambu dan bambu berkualitas — 38 provinsi, estimasi per wilayah.', 'canonical' => '/kota'];
    ob_start(); ?>
<?= page_hero('Jangkauan Kota', '2.524 Kota & Kabupaten Terjangkau', 'Dari Banda Aceh sampai Papua — cari kota Anda, lihat estimasi tiba, pesan dalam hitungan menit.', '38 Provinsi') ?>
<section class="section"><div class="container">
  <div class="field" style="max-width:480px"><label for="listFilter">Cari kota</label><input id="listFilter" type="search" placeholder="mis. Yogyakarta…"></div>
  <p id="listCount" style="color:var(--muted);font-size:.88rem"><?= count($cities) ?> hasil</p>
  <div class="grid grid-4" style="margin-top:14px">
    <?php foreach ($cities as $c): ?>
      <a data-filter-item class="card reveal" style="padding:13px;text-decoration:none" href="<?= site_url_path('/kota/' . $c['slug']) ?>"><b style="font-size:.92rem"><?= e($c['name']) ?></b><br><small style="color:var(--muted)"><?= e($c['province']) ?> • <?= e($c['eta']) ?></small></a>
    <?php endforeach; ?>
  </div>
</div></section>
<?php render_layout($meta, (string) ob_get_clean());
}

/* ---------- KONDISI KESEHATAN ---------- */
function render_condition(string $slug): void
{
    $c = find_by_slug(json_load('conditions')['all'], $slug);
    if (!$c) {
        not_found_redirect();
        return;
    }
    $name = (string) $c['name'];
    $meta = [
        'title' => "Air Bambu untuk {$name} — Panduan Terapi Alami",
        'desc' => "Panduan air bambu untuk {$name}: cara pakai, kandungan pendukung, tradisi tabib Nusantara, dan FAQ. Terapi pendamping alami.",
        'canonical' => '/air-bambu-untuk/' . $slug,
    ];
    ob_start(); ?>
<?= page_hero('Kesehatan › ' . $name, 'Air Bambu untuk ' . $name, (string) $c['hook'], 'Terapi Pendamping Alami') ?>
<section class="section"><div class="container split">
  <div class="reveal">
    <h2>Cara Pemakaian Tradisional & Modern</h2>
    <p style="color:var(--muted)"><?= e($c['usage']) ?></p>
    <h3 style="margin-top:18px">Kandungan Pendukung</h3>
    <ul style="color:var(--muted)"><?php foreach ($c['nutrients'] ?? [] as $n): ?><li><?= e($n) ?></li><?php endforeach; ?></ul>
    <div class="alert alert-err" style="margin-top:16px">⚠️ Terapi pendamping — bukan pengganti pengobatan medis. Konsultasikan dengan tenaga kesehatan.</div>
    <a class="btn btn-primary" href="<?= wa_link('Halo, saya ingin konsultasi air bambu untuk ' . $name) ?>" target="_blank" rel="noopener">Konsultasi Gratis via WA</a>
  </div>
  <img class="reveal" src="<?= site_url_path('/assets/images/wellness.jpg') ?>" alt="Terapi air bambu untuk <?= e($name) ?>" loading="lazy">
</div></section>
<?php if (!empty($c['faqs'])): ?>
<section class="section section-tint"><div class="container" style="max-width:820px">
  <h2 class="reveal">Pertanyaan Seputar <?= e($name) ?></h2>
  <?php foreach ($c['faqs'] as $f): ?>
    <details class="faq reveal"><summary><?= e($f['q']) ?></summary><div class="faq-body"><?= e($f['a']) ?></div></details>
  <?php endforeach; ?>
</div></section>
<?php endif; ?>
<?= cta_band("Mulai Terapi untuk {$name}", "Air bambu suling & tabib tersedia untuk kirim ke seluruh Indonesia.") ?>
<?php render_layout($meta, (string) ob_get_clean());
}

function render_conditions_index(): void
{
    $all = json_load('conditions')['all'];
    $cats = [];
    foreach ($all as $c) {
        $cats[$c['category']][] = $c;
    }
    $meta = ['title' => 'Pusat Kesehatan — 60+ Kondisi & Air Bambu', 'desc' => 'Jelajahi 60+ kondisi kesehatan yang didokumentasikan tradisi tabib Nusantara & literatur modern beserta panduan pemakaian air bambu.', 'canonical' => '/kesehatan'];
    ob_start(); ?>
<?= page_hero('Pusat Kesehatan', '60+ Kondisi, Satu Sari Alam', 'Dokumentasi jujur penggunaan air bambu per kondisi kesehatan — lengkap dengan cara pakai dan FAQ.', 'Literatur + Tradisi') ?>
<section class="section"><div class="container">
  <div class="field" style="max-width:480px"><label for="listFilter">Cari kondisi</label><input id="listFilter" type="search" placeholder="mis. ginjal, demam, kolesterol…"></div>
  <?php foreach ($cats as $cat => $list): ?>
    <h2 class="reveal" style="font-size:1.25rem;margin-top:34px"><?= e($cat) ?> <small style="color:var(--muted);font-weight:400">(<?= count($list) ?>)</small></h2>
    <div class="grid grid-4" style="margin-top:14px">
      <?php foreach ($list as $c): ?>
        <a data-filter-item class="card reveal" style="padding:15px;text-decoration:none" href="<?= site_url_path('/air-bambu-untuk/' . $c['slug']) ?>"><b style="font-size:.92rem"><?= e($c['name']) ?></b></a>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div></section>
<?php render_layout($meta, (string) ob_get_clean());
}

/* ---------- LAYANAN ---------- */
function render_service(string $slug): void
{
    $s = find_by_slug(json_load('services')['all'], $slug);
    if (!$s) {
        not_found_redirect();
        return;
    }
    $meta = ['title' => $s['name'] . ' — ' . APP_NAME, 'desc' => $s['hook'], 'canonical' => '/layanan/' . $slug];
    ob_start(); ?>
<?= page_hero('Layanan › ' . $s['name'], $s['name'], (string) $s['hook'], $s['category']) ?>
<section class="section"><div class="container split">
  <div class="reveal">
    <h2>Detail Layanan</h2>
    <ul style="color:var(--muted)"><?php foreach ($s['features'] ?? [] as $f): ?><li><?= e($f) ?></li><?php endforeach; ?></ul>
    <div class="grid grid-2" style="margin-top:16px">
      <div class="card" style="padding:16px"><b>Ideal untuk</b><p style="margin:4px 0 0;font-size:.9rem"><?= e($s['idealFor'] ?? '—') ?></p></div>
      <div class="card" style="padding:16px"><b>Volume minimum</b><p style="margin:4px 0 0;font-size:.9rem"><?= e($s['moq'] ?? '—') ?></p></div>
    </div>
  </div>
  <div class="reveal">
    <?php if (!empty($s['faqs'])): foreach ($s['faqs'] as $f): ?>
      <details class="faq"><summary><?= e($f['q']) ?></summary><div class="faq-body"><?= e($f['a']) ?></div></details>
    <?php endforeach; endif; ?>
    <a class="btn btn-primary" style="margin-top:16px" href="<?= wa_link('Halo, saya tertarik layanan: ' . $s['name']) ?>" target="_blank" rel="noopener">Tanya Langsung via WA</a>
  </div>
</div></section>
<?= cta_band('Kustomisasi Layanan Anda', 'Semua layanan bisa disesuaikan volume & jadwalnya.') ?>
<?php render_layout($meta, (string) ob_get_clean());
}

function render_services_index(): void
{
    $all = json_load('services')['all'];
    $cats = [];
    foreach ($all as $s) {
        $cats[$s['category'] ?? 'Lainnya'][] = $s;
    }
    $meta = ['title' => 'Katalog 25 Layanan — Langganan, Kargo, Kemitraan', 'desc' => '25 layanan Bambu Water Nusantara: langganan rumah tangga, pasokan korporat, kargo antar pulau, kemitraan distributor, hingga supply konstruksi bambu.', 'canonical' => '/layanan'];
    ob_start(); ?>
<?= page_hero('Katalog Layanan', '25 Layanan untuk Semua Skala', 'Dari galon rumah tangga sampai kontrak pasokan korporat dan kargo full loads.', 'Resmi & Terkontrak') ?>
<section class="section"><div class="container">
  <?php foreach ($cats as $cat => $list): ?>
    <h2 class="reveal" style="font-size:1.25rem;margin-top:32px"><?= e($cat) ?> <small style="color:var(--muted);font-weight:400">(<?= count($list) ?>)</small></h2>
    <div class="grid grid-3" style="margin-top:14px">
      <?php foreach ($list as $s): ?>
        <a class="card reveal" style="text-decoration:none" href="<?= site_url_path('/layanan/' . url_slug($s)) ?>"><div class="icon">🤝</div><h3 style="font-size:1.02rem"><?= e($s['name']) ?></h3><p style="font-size:.88rem"><?= e($s['hook']) ?></p></a>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div></section>
<?php render_layout($meta, (string) ob_get_clean());
}

/* ---------- BAMBU ---------- */
function render_bamboo(string $slug): void
{
    $b = find_by_slug(json_load('bamboo'), $slug);
    if (!$b) {
        not_found_redirect();
        return;
    }
    $name = (string) $b['name'];
    $meta = ['title' => "Bambu {$name} — Harga, Spesifikasi & FAQ", 'desc' => $b['hook'] . ' Harga ' . ($b['priceRange'] ?? '') . '. Pengiriman massal se-Indonesia.', 'canonical' => '/bambu/' . $slug];
    ob_start(); ?>
<?= page_hero('Jenis Bambu › ' . $name, $name, (string) $b['hook'], ($b['category'] ?? '') . ' • ' . ($b['latin'] ?? '')) ?>
<section class="section"><div class="container split">
  <div class="reveal">
    <h2>Spesifikasi & Harga</h2>
    <p style="color:var(--gold-600);font-weight:800;font-size:1.2rem"><?= e($b['priceRange']) ?></p>
    <table class="price-table" style="margin-top:14px"><tbody>
      <?php foreach ($b['specs'] ?? [] as $sp): ?>
        <tr><td><b><?= e($sp['label']) ?></b></td><td><?= e($sp['value']) ?></td></tr>
      <?php endforeach; ?>
    </tbody></table>
    <h3 style="margin-top:22px">Aplikasi Umum</h3>
    <ul style="color:var(--muted)"><?php foreach ($b['applications'] ?? [] as $a): ?><li><?= e($a) ?></li><?php endforeach; ?></ul>
    <h3 style="margin-top:18px">Tips Perawatan</h3>
    <p style="color:var(--muted)"><?= e($b['careTips']) ?></p>
  </div>
  <div class="reveal">
    <img src="<?= site_url_path('/assets/images/product-bamboo-poles.jpg') ?>" alt="Batang bambu <?= e($name) ?>" loading="lazy" style="border-radius:22px;box-shadow:var(--shadow)">
    <?php if (!empty($b['faqs'])): foreach ($b['faqs'] as $f): ?>
      <details class="faq" style="margin-top:12px"><summary><?= e($f['q']) ?></summary><div class="faq-body"><?= e($f['a']) ?></div></details>
    <?php endforeach; endif; ?>
  </div>
</div></section>
<?= cta_band("Pesan Bambu {$name} Massal", 'Sebut panjang, diameter, dan jumlah — estimasi kargo keluar 1×24 jam.') ?>
<?php render_layout($meta, (string) ob_get_clean());
}

function render_bamboo_index(): void
{
    $all = json_load('bamboo');
    $meta = ['title' => '8 Jenis Bambu Berkualitas — Harga & Spesifikasi', 'desc' => 'Petung, Betung, Wulung, Apus, Ori, dan lainnya — harga per batang, spesifikasi teknis, aplikasi, dan tips perawatan.', 'canonical' => '/bambu'];
    ob_start(); ?>
<?= page_hero('Jenis Bambu', '8 Varietas Pilihan Kami', 'Setiap jenis punya karakter — kami bantu pilih yang tepat untuk proyek Anda.', 'Harga Transparan') ?>
<section class="section"><div class="container grid grid-4">
  <?php foreach ($all as $b): ?>
    <a class="card reveal" style="text-decoration:none" href="<?= site_url_path('/bambu/' . url_slug($b)) ?>"><div class="icon">🎋</div><h3 style="font-size:1.05rem"><?= e($b['name']) ?></h3><p style="font-size:.82rem;font-style:italic"><?= e($b['latin'] ?? '') ?></p><p style="font-size:.88rem"><?= e($b['hook']) ?></p><p style="color:var(--gold-600);font-weight:700;font-size:.85rem"><?= e($b['priceRange']) ?></p></a>
  <?php endforeach; ?>
</div></section>
<?php render_layout($meta, (string) ob_get_clean());
}

/* ---------- ARTIKEL ---------- */
function render_articles_index(): void
{
    $all = json_load('articles');
    $meta = ['title' => 'Artikel & Wawasan Air Bambu', 'desc' => 'Kumpulan artikel edukasi: air bambu untuk gula darah, kolesterol, ginjal, hingga panduan memilih jenis bambu konstruksi.', 'canonical' => '/artikel'];
    ob_start(); ?>
<?= page_hero('Artikel', 'Wawasan & Panduan Air Bambu', 'Artikel berbasis riset — ditulis jujur, merujuk literatur publik yang kredibel.', 'Edukasi Berkelanjutan') ?>
<section class="section"><div class="container grid grid-3">
  <?php foreach ($all as $a): ?>
    <a class="card reveal" style="text-decoration:none" href="<?= site_url_path('/artikel/' . $a['slug']) ?>">
      <span class="pill pill-dim"><?= e($a['category']) ?></span>
      <h3 style="font-size:1.08rem;margin-top:10px"><?= e($a['title']) ?></h3>
      <p style="font-size:.88rem"><?= e($a['excerpt']) ?></p>
      <small style="color:var(--muted)">⏱ <?= (int) $a['readMinutes'] ?> menit baca • diperbarui <?= e($a['updated']) ?></small>
    </a>
  <?php endforeach; ?>
</div></section>
<?php render_layout($meta, (string) ob_get_clean());
}

function render_article(string $slug): void
{
    $a = find_by_slug(json_load('articles'), $slug);
    if (!$a) {
        not_found_redirect();
        return;
    }
    $meta = ['title' => $a['seoTitle'] ?? $a['title'], 'desc' => $a['metaDescription'] ?? $a['excerpt'], 'canonical' => '/artikel/' . $slug];
    ob_start(); ?>
<?= page_hero('Artikel › ' . ($a['category'] ?? ''), (string) $a['title'], (string) $a['excerpt'], ($a['readMinutes'] ?? '') . ' menit baca') ?>
<script type="application/ld+json"><?= json_encode(['@context' => 'https://schema.org', '@type' => 'BlogPosting', 'headline' => $a['title'], 'description' => $a['metaDescription'] ?? '', 'dateModified' => $a['updated'] ?? '', 'author' => ['@type' => 'Organization', 'name' => APP_NAME]]) ?></script>
<section class="section"><div class="container" style="max-width:800px">
  <div class="card reveal" style="background:var(--em-50)"><b>Poin kunci:</b><ul style="margin:8px 0 0;color:var(--muted)"><?php foreach ($a['keyPoints'] ?? [] as $kp): ?><li><?= e($kp) ?></li><?php endforeach; ?></ul></div>
  <div class="reveal" style="margin-top:26px">
    <p style="font-size:1.04rem"><?= e($a['intro']) ?></p>
    <?php foreach ($a['sections'] ?? [] as $sec): ?>
      <h2 style="margin-top:34px"><?= e($sec['heading']) ?></h2>
      <?php foreach ($sec['paragraphs'] ?? [] as $par): ?><p style="color:var(--muted)"><?= e($par) ?></p><?php endforeach; ?>
    <?php endforeach; ?>
    <h2 style="margin-top:34px">Kesimpulan</h2>
    <p style="color:var(--muted)"><?= e($a['conclusion'] ?? '') ?></p>
  </div>
  <?php if (!empty($a['faqs'])): ?>
  <h2 style="margin-top:36px">FAQ</h2>
  <?php foreach ($a['faqs'] as $f): ?>
    <details class="faq"><summary><?= e($f['q']) ?></summary><div class="faq-body"><?= e($f['a']) ?></div></details>
  <?php endforeach; endif; ?>
  <?php if (!empty($a['references'])): ?>
  <h2 style="margin-top:36px">Referensi</h2>
  <ol style="color:var(--muted);font-size:.9rem"><?php foreach ($a['references'] as $r): ?><li><?= e(is_array($r) ? ($r['title'] ?? json_encode($r)) : $r) ?></li><?php endforeach; ?></ol>
  <?php endif; ?>
  <?php if (!empty($a['related'])): ?>
  <h2 style="margin-top:36px">Bacaan Terkait</h2>
  <div class="grid grid-3" style="margin-top:14px">
    <?php foreach ($a['related'] as $rel): $ra = find_by_slug(json_load('articles'), is_array($rel) ? ($rel['slug'] ?? '') : $rel); if (!$ra) continue; ?>
      <a class="card" style="text-decoration:none;padding:16px" href="<?= site_url_path('/artikel/' . $ra['slug']) ?>"><b style="font-size:.9rem"><?= e($ra['title']) ?></b></a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div></section>
<?= cta_band('Ambil Bagian dalam Kebaikan Ini', 'Air bambu asli dari pabrik kami — bukan imitasi.') ?>
<?php render_layout($meta, (string) ob_get_clean());
}

/* ---------- Util ---------- */
function not_found_redirect(): void
{
    http_response_code(404);
    $meta = ['title' => 'Tidak Ditemukan', 'desc' => 'Halaman tidak ditemukan', 'canonical' => '/404'];
    render_layout($meta, '<section class="section"><div class="container" style="text-align:center"><h1>404 — Tidak Ditemukan</h1><p>Konten tidak tersedia. <a href="' . site_url_path('/peta-situs') . '">Buka peta situs</a>.</p></div></section>');
}

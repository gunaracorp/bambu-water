<?php
/**
 * Halaman inti + layout renderer — mirror desain situs Next.js bambuwater.web.id.
 * Semua konten dari data/*.json (Z2: Content Outlives Code).
 */
declare(strict_types=1);

function render_layout(array $meta, string $content): void
{
    $meta['canonical'] = $meta['canonical'] ?? '/';
    require ROOT_DIR . '/templates/layout.php';
}

function page_hero(string $crumb, string $title, string $desc, string $badge = ''): string
{
    ob_start(); ?>
    <section class="page-hero">
      <div class="container">
        <p class="crumbs"><a href="<?= site_url_path('/') ?>">Beranda</a> › <?= e($crumb) ?></p>
        <?php if ($badge !== ''): ?><span class="badge-glass"><?= e($badge) ?></span><?php endif; ?>
        <h1><?= e($title) ?></h1>
        <p><?= e($desc) ?></p>
      </div>
    </section>
    <?php return (string) ob_get_clean();
}

function cta_band(string $title, string $sub): string
{
    ob_start(); ?>
    <section class="section" style="background:var(--em-950);color:#fff;text-align:center">
      <div class="container reveal">
        <h2 style="color:#fff"><?= e($title) ?></h2>
        <p style="max-width:60ch;margin:10px auto 26px;color:rgba(255,255,255,.85)"><?= e($sub) ?></p>
        <a class="btn btn-gold" href="<?= wa_link('Halo, saya tertarik dengan air bambu Bambu Water Nusantara') ?>" target="_blank" rel="noopener">💬 WhatsApp <?= e(setting('phone', DEFAULT_PHONE)) ?></a>
        <a class="btn btn-ghost" href="<?= site_url_path('/kontak') ?>">Form Pemesanan</a>
      </div>
    </section>
    <?php return (string) ob_get_clean();
}

/* ============================================================
   BERANDA
   ============================================================ */
function render_home(): void
{
    $co = company('COMPANY');
    $stats = company('STATS', []);
    $benefits = company('BENEFITS', []);
    $nutrition = company('NUTRITION', []);
    $products = company('PRODUCTS', []);
    $process = company('PROCESS', []);
    $tiers = company('DELIVERY_TIERS', []);
    $leaders = company('LEADERSHIP', []);
    $faqs = company('FAQS', []);
    $testi = [
        ['Bu Ratna — Surabaya', 'Air bambu ini jadi pendamping terapi ayah saya. Rasanya bersih, segar, dan pengirimannya selalu tepat waktu.'],
        ['Pak Hendra — Bandung', 'Untuk proyek kami, pengiriman batang bambu Petung ke 3 kota sekaligus lancar. Harga transparan, komunikasi enak.'],
        ['Ibu Sari — Jakarta', 'Sudah setahun berlangganan galon. Keluarga merasakan tubuh lebih segar. Pelayanan ramah, respons cepat.'],
        ['Drg. Maya — Tasikmalaya', 'Sebagai praktisi kesehatan saya apresiasi transparansi proses produksinya. Higienis dan konsisten.'],
        ['Pak Budi — Makassar', 'Kargo antar pulau ternyata tidak ribet. Galon tiba aman, packing rapi sekali.'],
        ['Bu Lilis — Bogor', 'Anak saya yang susah minum air putih malah suka air bambunya. Manis alami tanpa gula.'],
    ];
    $meta = ['title' => 'Air Bambu Terapi Pengobatan Alami & Distribusi Bambu Se-Indonesia', 'desc' => 'PT Bambu Water Nusantara — produsen air bambu suling & tabib untuk terapi kesehatan alami sejak 2007. Distribusi bambu berkualitas massal ke seluruh Indonesia. WA 0813-1651-6524.', 'canonical' => '/'];

    ob_start(); ?>
<section class="hero">
  <div class="hero-bg" style="background-image:url('<?= site_url_path('/assets/images/hero-bamboo.jpg') ?>')" role="img" aria-label="Hutan bambu Nusantara"></div>
  <div class="container hero-inner">
    <span class="badge-glass">🍃 <?= e(company('BRAND_CLAIM')) ?></span>
    <h1>Sari Bambu Murni, <em>Kesehatan dari Alam</em> Nusantara</h1>
    <p class="lead"><?= e($co['description'] ?? 'Air sadapan bambu untuk terapi kesehatan alami — kaya silika organik, flavonoid, dan mineral esensial.') ?></p>
    <div class="hero-cta">
      <a class="btn btn-primary" href="<?= site_url_path('/harga-paket') ?>">Jelajahi Produk →</a>
      <a class="btn btn-ghost" href="<?= wa_link('Halo, saya ingin pesan air bambu') ?>" target="_blank" rel="noopener">🚚 Pesan Grosir / Kargo</a>
    </div>
    <div class="hero-trust">
      <span>Suling bertahap higienis</span><span>100% alami tanpa pengawet</span><span>Kirim ke 38 provinsi</span>
    </div>
  </div>
</section>

<section class="section" style="padding:48px 0">
  <div class="container stats reveal">
    <?php foreach ($stats as $s): ?>
      <div class="stat"><b><?= e(($s['prefix'] ?? '') . number_format((float) $s['value'], 0, ',', '.') . ($s['suffix'] ?? '')) ?></b><span><?= e($s['label']) ?></span></div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section section-tint">
  <div class="container split">
    <div class="reveal">
      <p class="eyebrow">Tentang Kami</p>
      <h2>Pionir Air Bambu Terapi — <?= e(company('YEARS')) ?> Tahun Membina Hutan & Kesehatan</h2>
      <p><?= e($co['aboutText'] ?? 'Kami mengumpulkan sadapan alami dari ruas bambu muda pilihan, memprosesnya dengan suling bertahap higienis, lalu mengantarnya ke seluruh Indonesia — dari botol keluarga hingga kargo truk untuk distributor dan proyek.') ?></p>
      <p style="color:var(--muted)">Didukung hutan bambu rakyat 2,1 juta hektare — terluas di dunia — dan tim produksi yang membina petani secara berkelanjutan.</p>
      <a class="btn btn-primary" href="<?= site_url_path('/tentang-kami') ?>">Kenali Kami Lebih Dekat</a>
    </div>
    <img class="reveal" src="<?= site_url_path('/assets/images/about-plantation.jpg') ?>" alt="Perkebunan bambu Nusantara" loading="lazy">
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <p class="eyebrow">Manfaat Tradisional & Modern</p>
      <h2>8 Khasiat Air Bambu yang Dipercaya Berabad-Abad</h2>
    </div>
    <div class="grid grid-4">
      <?php foreach (array_slice($benefits, 0, 8) as $b): ?>
        <div class="card reveal"><div class="icon" aria-hidden="true">🌿</div><h3><?= e($b['title']) ?></h3><p><?= e($b['description']) ?></p></div>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:26px"><a class="btn btn-outline" href="<?= site_url_path('/manfaat-air-bambu') ?>">Lihat Semua Manfaat →</a></p>
  </div>
</section>

<section class="section section-tint">
  <div class="container split">
    <img class="reveal" src="<?= site_url_path('/assets/images/wellness.jpg') ?>" alt="Gelas air bambu sehat" loading="lazy">
    <div class="reveal">
      <p class="eyebrow">Sains di Baliknya</p>
      <h2>Kandungan Nutrisi Tertinggi di Kelasnya</h2>
      <?php foreach ($nutrition as $n): ?>
        <div style="margin:16px 0">
          <div style="display:flex;justify-content:space-between;font-weight:700;font-size:.92rem"><span><?= e($n['name']) ?></span><span style="color:var(--em-700)"><?= e((string) $n['value']) ?>%</span></div>
          <div style="height:10px;border-radius:6px;background:var(--em-100);overflow:hidden"><div style="height:100%;width:<?= min(100, (float) $n['value']) ?>%;background:linear-gradient(90deg,var(--em-700),var(--em-600))"></div></div>
          <small style="color:var(--muted)"><?= e($n['note']) ?></small>
        </div>
      <?php endforeach; ?>
      <a class="btn btn-outline" href="<?= site_url_path('/kandungan-nutrisi') ?>">Baca Analisis Lengkap</a>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal"><p class="eyebrow">Proses Produksi</p><h2>Dari Hutan ke Gelas Anda — 4 Tahap Terjaga</h2></div>
    <ul class="timeline reveal" style="max-width:760px;margin:auto">
      <?php foreach ($process as $p): ?>
        <li><b><?= e($p['step']) ?> — <?= e($p['title']) ?></b><br><span style="color:var(--muted)"><?= e($p['description']) ?></span></li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="section section-tint" data-tabs>
  <div class="container">
    <div class="section-head reveal"><p class="eyebrow">Produk Kami</p><h2>Pilih Sesuai Kebutuhan Anda</h2></div>
    <div class="tabs" role="tablist">
      <?php foreach ($products as $i => $p): ?>
        <button class="tab-btn <?= $i === 0 ? 'active' : '' ?>" data-tab="p<?= $i ?>" role="tab"><?= e($p['name']) ?></button>
      <?php endforeach; ?>
    </div>
    <?php foreach ($products as $i => $p): ?>
      <div class="tab-panel <?= $i === 0 ? 'active' : '' ?>" data-panel="p<?= $i ?>">
        <div class="split">
          <img src="<?= site_url_path($p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy" style="border-radius:22px;box-shadow:var(--shadow)">
          <div>
            <?php if (!empty($p['badge'])): ?><span class="pill pill-new"><?= e($p['badge']) ?></span><?php endif; ?>
            <h3 style="margin-top:8px"><?= e($p['name']) ?> — <span style="color:var(--gold-600)"><?= e($p['tagline'] ?? '') ?></span></h3>
            <p style="color:var(--muted)"><?= e($p['description']) ?></p>
            <ul style="color:var(--muted);font-size:.93rem"><?php foreach ($p['features'] ?? [] as $f): ?><li><?= e($f) ?></li><?php endforeach; ?></ul>
            <p style="font-weight:700">Tersedia: <?= e(implode(' • ', $p['sizes'] ?? [])) ?></p>
            <a class="btn btn-primary" href="<?= site_url_path('/harga-paket') ?>">Cek Harga</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal"><p class="eyebrow">Pengiriman Massal</p><h2>Tiga Tier Pengiriman ke Seluruh Indonesia</h2></div>
    <div class="grid grid-3">
      <?php foreach ($tiers as $t2): ?>
        <div class="card reveal"><div class="icon" aria-hidden="true">🚚</div><h3><?= e($t2['name']) ?></h3>
          <p><b style="color:var(--em-800)"><?= e($t2['area']) ?></b> — <?= e($t2['eta']) ?></p>
          <p><?= e($t2['description']) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-tint">
  <div class="container">
    <div class="section-head reveal"><p class="eyebrow">Kepercayaan Pelanggan</p><h2>Kata Mereka yang Sudah Merasakan</h2></div>
    <div class="grid grid-3">
      <?php foreach ($testi as $i => $ts): ?>
        <div class="card reveal"><p style="font-style:italic">“<?= e($ts[1]) ?>”</p><b style="color:var(--em-800)"><?= e($ts[0]) ?></b></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container split">
    <div class="reveal">
      <p class="eyebrow">Pimpinan</p>
      <h2>Dibangun atas Amanah — Struktur Badan Hukum Resmi</h2>
      <p style="color:var(--muted)">PT Bambu Water Nusantara berdiri resmi dengan modal dasar Rp700 juta, disetor Rp300 juta — dipimpin dewan komisaris dan direksi yang terdaftar dalam akta pendirian.</p>
      <a class="btn btn-outline" href="<?= site_url_path('/tentang-kami') ?>">Struktur Lengkap</a>
    </div>
    <div class="grid grid-2 reveal">
      <?php foreach (['pendiri' => 'Dewan Pendiri', 'komisaris' => 'Komisaris', 'direktur' => 'Direksi'] as $grp => $label): ?>
        <?php foreach (($leaders[$grp] ?? []) as $l): ?>
          <div class="card" style="padding:16px"><b><?= e($l['name']) ?></b><br><small style="color:var(--muted)"><?= e($l['role']) ?><?= isset($l['shares']) ? ' • ' . e($l['shares']) : '' ?></small></div>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-tint" data-tabs>
  <div class="container">
    <div class="section-head reveal"><p class="eyebrow">FAQ</p><h2>Pertanyaan yang Sering Diajukan</h2></div>
    <div style="max-width:820px;margin:auto">
      <?php foreach (array_slice($faqs, 0, 6) as $f): ?>
        <details class="faq reveal"><summary><?= e($f['q']) ?></summary><div class="faq-body"><?= e($f['a']) ?></div></details>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:22px"><a href="<?= site_url_path('/faq') ?>">Lihat semua FAQ →</a></p>
  </div>
</section>

<?= cta_band('Siap Merasakan Berkah Air Bambu?', 'Tim kami membalas WhatsApp dalam hitungan menit pada jam kerja Senin–Sabtu 08.00–17.00 WIB.') ?>

<?php render_layout($meta, (string) ob_get_clean());
}

/* ============================================================
   REGISTRY HALAMAN INTI
   ============================================================ */
function core_pages(): array
{
    $faqItems = company('FAQS', []);
    return [
        'tentang-kami' => [
            'title' => 'Tentang Kami — PT Bambu Water Nusantara', 'crumb' => 'Tentang Kami',
            'desc' => 'Sejarah, visi misi, dan struktur PT Bambu Water Nusantara — pionir air bambu terapi pengobatan sejak 2007.',
            'badge' => company('BRAND_CLAIM'),
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container split">
  <img class="reveal" src="<?= site_url_path('/assets/images/about-plantation.jpg') ?>" alt="Perkebunan bambu" loading="lazy">
  <div class="reveal">
    <p class="eyebrow">Sejak 2007</p>
    <h2>Visi & Misi</h2>
    <p><b>Visi:</b> menjadikan warisan air bambu Nusantara terpercaya dunia — menyembuhkan dengan jujur, ilmiah, dan berwibawa.</p>
    <p><b>Misi:</b> membina hutan bambu rakyat, memproduksi air bambu higienis bermutu farmasi, melayani rumah tangga hingga korporasi lintas pulau.</p>
    <p style="color:var(--muted)"><?= e(company('PRODUCTION_TEAM_NOTE', 'Tim produksi kami membina petani dan memanen tanpa merusak rumpun — lestari untuk generasi.')) ?></p>
  </div>
</div></section>
<section class="section section-tint"><div class="container">
  <div class="section-head reveal"><h2>Pimpinan & Struktur Badan Hukum</h2><p>Modal dasar Rp700 juta — disetor Rp300 juta. Terdaftar resmi dalam akta pendirian.</p></div>
  <div class="grid grid-3">
    <?php foreach (company('LEADERSHIP') as $grp => $list): foreach ($list as $l): ?>
      <div class="card reveal"><div class="icon">👤</div><h3><?= e($l['name']) ?></h3><p><?= e($l['role']) ?><?= isset($l['shares']) ? ' • ' . e($l['shares']) : '' ?></p></div>
    <?php endforeach; endforeach; ?>
  </div>
</div></section>
<?= cta_band('Mari Berkolaborasi', 'Kemitraan distribusi, pasokan korporat, atau kunjungan pabrik — pintu kami terbuka.') ?>
<?php return (string) ob_get_clean(); },
        ],
        'apa-itu-air-bambu' => [
            'title' => 'Apa Itu Air Bambu? — Sadapan Alami dari Ruas Bambu Muda', 'crumb' => 'Apa Itu Air Bambu',
            'desc' => 'Mengenal air bambu (air tuak bambu): sadapan alami ruas bambu muda, cara panen mata tunas, dan mengapa kaya silika organik.',
            'badge' => 'Edukasi Dasar',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container split">
  <div class="reveal">
    <h2>Air yang Tumbuh di Dalam Bambu</h2>
    <p>Air bambu (air tuak bambu) adalah <b>sadapan alami</b> yang terbentuk di dalam ruas bambu muda — hasil metabolisme tanaman dan kelembapan yang tersaring berlapis-lapis ruas bambu.</p>
    <p>Dipanen dengan teknik <b>mata tunas</b>: lubang kecil dibuat pada ruas terpilih, air menetes semalam suntuk, lalu ditampung higienis. Satu batang hanya memberi 1–2 liter — itulah mengapa air bambu langka dan berharga.</p>
    <ul style="color:var(--muted)"><li>Kaya <b>silika organik</b> hingga 70% — tertinggi di alam</li><li>Mengandung flavonoid & polifenol antioksidan</li><li>Mineral K, Ca, Mg + asam amino alami</li></ul>
  </div>
  <img class="reveal" src="<?= site_url_path('/assets/images/process-harvest.jpg') ?>" alt="Panen sadapan bambu" loading="lazy">
</div></section>
<section class="section section-tint"><div class="container" style="text-align:center">
  <h2 class="reveal">Ingin Tahu Khasiatnya untuk Kondisi Anda?</h2>
  <p class="reveal" style="color:var(--muted)">Jelajahi 60+ kondisi kesehatan yang didokumentasikan tradisi & literatur modern.</p>
  <a class="btn btn-primary reveal" href="<?= site_url_path('/kesehatan') ?>">Pusat Kesehatan →</a>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'manfaat-air-bambu' => [
            'title' => '8+ Manfaat & Khasiat Air Bambu untuk Kesehatan', 'crumb' => 'Manfaat Air Bambu',
            'desc' => 'Manfaat air bambu: meredakan demam, membantu gula darah & kolesterol, kesehatan jantung, pencernaan, kulit, hingga daya tahan tubuh.',
            'badge' => 'Tradisi + Sains',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container">
  <div class="grid grid-3">
    <?php foreach (company('BENEFITS', []) as $b): ?>
      <div class="card reveal"><div class="icon">🌿</div><h3><?= e($b['title']) ?></h3><p><?= e($b['description']) ?></p></div>
    <?php endforeach; ?>
  </div>
  <div class="alert alert-err reveal" style="margin-top:30px">⚠️ <?= t('Air bambu adalah terapi pendamping alami — bukan pengganti obat atau nasihat dokter. Konsultasikan kondisi Anda dengan tenaga kesehatan.', 'Complementary natural therapy — not a substitute for medical advice.') ?></div>
</div></section>
<?= cta_band('Mulai Terapi Alami Anda', 'Konsultasi gratis via WhatsApp — ceritakan kebutuhan Anda.') ?>
<?php return (string) ob_get_clean(); },
        ],
        'kandungan-nutrisi' => [
            'title' => 'Kandungan Nutrisi Air Bambu — Silika Organik Tertinggi di Alam', 'crumb' => 'Kandungan Nutrisi',
            'desc' => 'Analisis kandungan air bambu: silika organik hingga 70%, klorofil, flavonoid, polifenol, mineral kalium kalsium magnesium, dan asam amino.',
            'badge' => 'Data Sains',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container" style="max-width:820px">
  <h2 class="reveal">Profil Nutrisi</h2>
  <?php foreach (company('NUTRITION', []) as $n): ?>
    <div class="card reveal" style="margin-bottom:14px">
      <div style="display:flex;justify-content:space-between;align-items:center"><h3 style="margin:0"><?= e($n['name']) ?></h3><b style="color:var(--em-700);font-size:1.4rem"><?= e((string) $n['value']) ?>%</b></div>
      <p style="margin:6px 0 0;color:var(--muted)"><?= e($n['note']) ?></p>
    </div>
  <?php endforeach; ?>
  <p class="reveal" style="color:var(--muted);font-size:.9rem">Sumber: literatur sains publik (ekstrak daun bambu — PMC4659479), TIME 2015, dan dokumentasi tradisi tabib Nusantara. Angka merupakan rentang indikatif per jenis & metode.</p>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'air-bambu-pengobatan' => [
            'title' => 'Air Bambu untuk Terapi Pengobatan Alami — 60+ Kondisi', 'crumb' => 'Terapi Pengobatan',
            'desc' => 'Dokumentasi penggunaan air bambu untuk terapi pengobatan tradisional Nusantara — 60+ kondisi kesehatan dengan panduan pemakaian.',
            'badge' => '60+ Kondisi Terdokumentasi',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container">
  <p class="reveal" style="max-width:75ch;margin-inline:auto;color:var(--muted)">Berabad-abad tabib Nusantara meresap air bambu pagi hari — saat kandungan mineral tertinggi — untuk beragam kondisi. Kami mendokumentasikannya jujur: <b>terapi pendamping</b>, bukan pengganti pengobatan medis.</p>
  <div class="grid grid-4" style="margin-top:30px">
    <?php foreach (array_slice(json_load('conditions')['all'], 0, 12) as $c): ?>
      <a class="card reveal" href="<?= site_url_path('/air-bambu-untuk/' . $c['slug']) ?>" style="text-decoration:none"><div class="icon">💚</div><h3 style="font-size:1.02rem"><?= e($c['name']) ?></h3><p style="font-size:.85rem"><?= e($c['category']) ?></p></a>
    <?php endforeach; ?>
  </div>
  <p style="text-align:center;margin-top:26px"><a class="btn btn-primary" href="<?= site_url_path('/kesehatan') ?>">Lihat 60+ Kondisi Lainnya →</a></p>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'air-bambu-suling' => [
            'title' => 'Air Bambu Suling Premium — Minuman Terapi Harian', 'crumb' => 'Air Bambu Suling',
            'desc' => 'Air bambu suling premium: disuling bertahap higienis, bebas klorin & logam berat, pH seimbang. Botol 600ml, 1,5L, galon 19L.',
            'badge' => 'Produk Unggulan',
            'body' => function () {
                $p = company('PRODUCTS')[0] ?? [];
                ob_start(); ?>
<section class="section"><div class="container split">
  <img class="reveal" src="<?= site_url_path($p['image'] ?? '/assets/images/product-water-bottle.jpg') ?>" alt="Air bambu suling premium" loading="lazy">
  <div class="reveal">
    <span class="pill pill-new"><?= e($p['badge'] ?? 'Unggulan') ?></span>
    <h2><?= e($p['name']) ?></h2>
    <p style="color:var(--gold-600);font-weight:700"><?= e($p['tagline'] ?? '') ?></p>
    <p style="color:var(--muted)"><?= e($p['description'] ?? '') ?></p>
    <ul style="color:var(--muted)"><?php foreach ($p['features'] ?? [] as $f): ?><li><?= e($f) ?></li><?php endforeach; ?></ul>
    <p style="font-weight:700">Tersedia: <?= e(implode(' • ', $p['sizes'] ?? [])) ?></p>
    <a class="btn btn-primary" href="<?= site_url_path('/harga-paket') ?>">Lihat Harga</a>
  </div>
</div></section>
<?= cta_band('Pesan Air Bambu Suling', 'Retail, karton, hingga langganan galon — semua bisa.') ?>
<?php return (string) ob_get_clean(); },
        ],
        'air-bambu-tabib' => [
            'title' => 'Air Bambu Tabib — Kualitas Farmasi untuk Terapi', 'crumb' => 'Air Bambu Tabib',
            'desc' => 'Air bambu tabib dengan standar pengolahan farmasi — pilihan tabib dan praktisi terapi tradisional di seluruh Indonesia.',
            'badge' => 'Kelas Farmasi',
            'body' => function () {
                $p = company('PRODUCTS')[1] ?? [];
                ob_start(); ?>
<section class="section"><div class="container split">
  <div class="reveal">
    <span class="pill pill-done">Untuk Praktisi</span>
    <h2><?= e($p['name'] ?? 'Air Bambu Tabib') ?></h2>
    <p style="color:var(--muted)"><?= e($p['description'] ?? 'Dirancang bersama tabib Nusantara: resapan pagi hari, suling bertahap, kemasan food-grade besar untuk kebutuhan praktik.') ?></p>
    <ul style="color:var(--muted)"><?php foreach ($p['features'] ?? [] as $f): ?><li><?= e($f) ?></li><?php endforeach; ?></ul>
    <p style="font-weight:700">Tersedia: <?= e(implode(' • ', $p['sizes'] ?? ['Jerigen 5 L', 'Galon 19 L'])) ?></p>
    <a class="btn btn-primary" href="<?= wa_link('Halo, saya tabib/praktisi tertarik Air Bambu Tabib') ?>" target="_blank" rel="noopener">Harga Khusus Praktisi</a>
  </div>
  <img class="reveal" src="<?= site_url_path('/assets/images/process-distillation.jpg') ?>" alt="Distilasi air bambu" loading="lazy">
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'bambu-berkualitas' => [
            'title' => 'Bambu Berkualitas Massal — Petung, Wulung, Apus, Ori', 'crumb' => 'Bambu Berkualitas',
            'desc' => 'Distribusi bambu berkualitas massal se-Indonesia: Petung konstruksi, Wulung elegan, Apus anyaman, Ori hias. Harga transparan per batang.',
            'badge' => 'Kirim Seluruh Indonesia',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container">
  <p class="reveal" style="max-width:75ch;margin-inline:auto;color:var(--muted)">Dari lahan bambu 2,1 juta hektare — terluas di dunia — kami memasok bambu pilihan untuk konstruksi, kerajinan, industri, dan lanskap.</p>
  <div class="grid grid-4" style="margin-top:30px">
    <?php foreach (json_load('bamboo') as $b): ?>
      <a class="card reveal" href="<?= site_url_path('/bambu/' . url_slug($b)) ?>" style="text-decoration:none"><div class="icon">🎋</div><h3 style="font-size:1.05rem"><?= e($b['name']) ?></h3><p style="font-size:.85rem"><?= e($b['hook']) ?></p><p style="color:var(--gold-600);font-weight:700;font-size:.85rem"><?= e($b['priceRange']) ?></p></a>
    <?php endforeach; ?>
  </div>
</div></section>
<?= cta_band('Butuh Ratusan–Ribuan Batang?', 'Kirim spesifikasi Anda — kami hitung estimasi kargo & biaya dalam 1×24 jam.') ?>
<?php return (string) ob_get_clean(); },
        ],
        'proses-produksi' => [
            'title' => 'Proses Produksi Air Bambu — 4 Tahap Terjaga Mutu', 'crumb' => 'Proses Produksi',
            'desc' => 'Dari panen berkelanjutan, resapan mata tunas, suling bertahap higienis, hingga pengemasan food-grade — proses produksi air bambu kami.',
            'badge' => 'Transparan Penuh',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container split">
  <img class="reveal" src="<?= site_url_path('/assets/images/process-harvest.jpg') ?>" alt="Panen bambu" loading="lazy">
  <ul class="timeline reveal">
    <?php foreach (company('PROCESS', []) as $p): ?>
      <li><b><?= e($p['step']) ?> — <?= e($p['title']) ?></b><br><span style="color:var(--muted)"><?= e($p['description']) ?></span></li>
    <?php endforeach; ?>
  </ul>
</div></section>
<section class="section section-tint"><div class="container split">
  <img class="reveal" src="<?= site_url_path('/assets/images/process-distillation.jpg') ?>" alt="Distilasi higienis" loading="lazy">
  <div class="reveal"><h2>Standar Higienis Farmasi</h2><p style="color:var(--muted)">Setiap batch melewati suling bertahap, uji organoleptik, dan pengemasan food-grade. Jejak batch tercatat — bila Anda bertanya, kami jawab.</p></div>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'pengiriman-massal' => [
            'title' => 'Pengiriman Massal Se-Indonesia — Botol hingga Kargo Truk', 'crumb' => 'Pengiriman Massal',
            'desc' => 'Tiga tier pengiriman massal: reguler Jabodetabek 1–3 hari, antar pulau, hingga kargo truk full loads. 2.524 kota & kabupaten terjangkau.',
            'badge' => '2.524 Kota • 38 Provinsi',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container">
  <div class="grid grid-3">
    <?php foreach (company('DELIVERY_TIERS', []) as $t2): ?>
      <div class="card reveal"><div class="icon">🚚</div><h3><?= e($t2['name']) ?></h3><p><b style="color:var(--em-800)"><?= e($t2['area']) ?></b> — <?= e($t2['eta']) ?></p><p><?= e($t2['description']) ?></p></div>
    <?php endforeach; ?>
  </div>
  <h2 class="reveal" style="margin-top:44px">Estimasi Waktu per Wilayah</h2>
  <div class="grid grid-3" style="margin-top:18px">
    <?php foreach (json_load('cities')['eta'] as $region => $eta): ?>
      <div class="card reveal" style="padding:16px"><b><?= e($region) ?></b><br><span style="color:var(--em-700);font-weight:700"><?= e($eta) ?></span></div>
    <?php endforeach; ?>
  </div>
</div></section>
<?= cta_band('Hitung Kargo Anda', 'Sebut kota tujuan & volume — tim logistik kami balas dengan estimasi biaya.') ?>
<?php return (string) ob_get_clean(); },
        ],
        'jangkauan-kota' => [
            'title' => 'Jangkauan 2.524 Kota & Kabupaten Se-Indonesia', 'crumb' => 'Jangkauan Kota',
            'desc' => 'Kami mengirim air bambu & bambu berkualitas ke 2.524 kota/kabupaten di 38 provinsi Indonesia. Cari kota Anda di sini.',
            'badge' => '38 Provinsi',
            'body' => function () {
                $cities = json_load('cities')['all'];
                $provs = [];
                foreach ($cities as $c) {
                    $provs[$c['province']][] = $c;
                }
                ob_start(); ?>
<section class="section"><div class="container">
  <div class="field" style="max-width:480px"><label for="listFilter">Cari kota Anda</label>
    <input id="listFilter" type="search" placeholder="mis. Bandung, Surabaya, Makassar…"></div>
  <p id="listCount" style="color:var(--muted);font-size:.88rem"><?= count($cities) ?> kota & kabupaten</p>
  <div class="grid grid-4" style="margin-top:14px">
    <?php foreach ($cities as $c): ?>
      <a data-filter-item class="card reveal" style="padding:13px;text-decoration:none" href="<?= site_url_path('/kota/' . $c['slug']) ?>"><b style="font-size:.92rem"><?= e($c['name']) ?></b><br><small style="color:var(--muted)"><?= e($c['province']) ?> • <?= e($c['eta']) ?></small></a>
    <?php endforeach; ?>
  </div>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'harga-paket' => [
            'title' => 'Harga & Paket — Air Bambu Retail hingga Grosir', 'crumb' => 'Harga & Paket',
            'desc' => 'Daftar harga transparan: air bambu suling per botol/galon, air bambu tabib jerigen, dan bambu batang per tiang. Tanpa biaya tersembunyi.',
            'badge' => 'Transparan & Terjangkau',
            'body' => function () {
                $pricing = company('PRICING', []);
                $groups = [['airBambu', 'Air Bambu Suling', '💧'], ['airTabib', 'Air Bambu Tabib', '🧑‍⚕️'], ['bambu', 'Bambu Batang', '🎋']];
                ob_start(); ?>
<section class="section"><div class="container">
  <?php foreach ($groups as [$key, $label, $icon]): if (empty($pricing[$key])) continue; ?>
    <h2 class="reveal"><?= $icon ?> <?= e($label) ?></h2>
    <div style="overflow-x:auto;margin:16px 0 34px">
    <table class="price-table reveal"><thead><tr><th>Produk</th><th>Harga</th><th>Satuan</th><th>Catatan</th></tr></thead><tbody>
      <?php foreach ($pricing[$key] as $r): ?>
        <tr><td><b><?= e($r['product']) ?></b></td><td style="color:var(--em-800);font-weight:800"><?= e($r['price']) ?></td><td><?= e($r['unit']) ?></td><td style="color:var(--muted)"><?= e($r['note']) ?></td></tr>
      <?php endforeach; ?>
    </tbody></table></div>
  <?php endforeach; ?>
  <div class="alert alert-ok reveal">💡 Harga besar (truck loads / kontrak tahunan) menunggu penawaran — hubungi tim kami. <b>Harga di sini final, tanpa biaya tersembunyi.</b></div>
</div></section>
<?= cta_band('Kunci Harga Grosir Anda', 'Sebutkan volume & kota tujuan — penawaran resmi keluar 1×24 jam.') ?>
<?php return (string) ob_get_clean(); },
        ],
        'cara-pemesanan' => [
            'title' => 'Cara Pemesanan — 4 Langkah Mudah', 'crumb' => 'Cara Pemesanan',
            'desc' => 'Pesan air bambu atau bambu batang dalam 4 langkah: hubungi kami, konsultasi & penawaran, konfirmasi & bayar, pengiriman dengan resi.',
            'badge' => '4 Langkah',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container" style="max-width:820px">
  <ul class="timeline reveal">
    <li><b>1. Hubungi Kami</b><br><span style="color:var(--muted)">WhatsApp <?= e(setting('phone', DEFAULT_PHONE)) ?> atau isi form kontak — ceritakan kebutuhan & kota tujuan.</span></li>
    <li><b>2. Konsultasi & Penawaran</b><br><span style="color:var(--muted)">Tim kami rekomendasikan produk, volume, dan biaya kargo — penawaran resmi maks. 1×24 jam.</span></li>
    <li><b>3. Konfirmasi & Pembayaran</b><br><span style="color:var(--muted)">Transfer bank / QRIS resmi atas nama PT. Invoice & kontrak untuk order besar.</span></li>
    <li><b>4. Pengiriman & Resi</b><br><span style="color:var(--muted)">Produk dikemas food-grade, dikirim, resi aktif — pantau lewat halaman Lacak Pesanan.</span></li>
  </ul>
  <p style="text-align:center;margin-top:30px"><a class="btn btn-primary" href="<?= site_url_path('/kontak') ?>">Mulai Pesan Sekarang</a></p>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'testimoni' => [
            'title' => 'Testimoni Pelanggan — Kepercayaan dari 38 Provinsi', 'crumb' => 'Testimoni',
            'desc' => 'Cerita pelanggan air bambu & bambu batang Bambu Water Nusantara dari Surabaya hingga Makassar.',
            'badge' => 'Suara Pelanggan',
            'body' => function () {
                $testi = [
                    ['Bu Ratna — Surabaya', 'Air bambu ini jadi pendamping terapi ayah saya. Rasanya bersih, segar, dan pengirimannya selalu tepat waktu.'],
                    ['Pak Hendra — Bandung', 'Untuk proyek kami, pengiriman batang bambu Petung ke 3 kota sekaligus lancar. Harga transparan, komunikasi enak.'],
                    ['Ibu Sari — Jakarta', 'Sudah setahun berlangganan galon. Keluarga merasakan tubuh lebih segar. Pelayanan ramah, respons cepat.'],
                    ['Drg. Maya — Tasikmalaya', 'Sebagai praktisi kesehatan saya apresiasi transparansi proses produksinya. Higienis dan konsisten.'],
                    ['Pak Budi — Makassar', 'Kargo antar pulau ternyata tidak ribet. Galon tiba aman, packing rapi sekali.'],
                    ['Bu Lilis — Bogor', 'Anak saya yang susah minum air putih malah suka air bambunya. Manis alami tanpa gula.'],
                ];
                ob_start(); ?>
<section class="section"><div class="container grid grid-3">
  <?php foreach ($testi as $ts): ?>
    <div class="card reveal"><p style="font-style:italic">“<?= e($ts[1]) ?>”</p><b style="color:var(--em-800)"><?= e($ts[0]) ?></b><p style="color:var(--gold-600)">★★★★★</p></div>
  <?php endforeach; ?>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'faq' => [
            'title' => 'FAQ — Pertanyaan yang Sering Diajukan', 'crumb' => 'FAQ',
            'desc' => 'Jawaban lengkap seputar air bambu: apa itu, cara panen, cara konsumsi, pengiriman, garansi, dan pemesanan.',
            'badge' => 'Semua Terjawab',
            'body' => function () use (&$faqItems) {
                ob_start(); ?>
<section class="section"><div class="container" style="max-width:840px">
  <?php foreach ($faqItems as $f): ?>
    <details class="faq reveal"><summary><?= e($f['q']) ?></summary><div class="faq-body"><?= e($f['a']) ?></div></details>
  <?php endforeach; ?>
</div></section>
<?= cta_band('Masih Ada Pertanyaan?', 'Tanya langsung ke WhatsApp kami — dibalas manusia, bukan bot.') ?>
<?php return (string) ob_get_clean(); },
        ],
        'kontak' => [
            'title' => 'Kontak & Pemesanan — Balasan Cepat di Jam Kerja', 'crumb' => 'Kontak',
            'desc' => 'Hubungi PT Bambu Water Nusantara: WhatsApp 0813-1651-6524, form pemesanan online, kantor Tasikmalaya & cabang Bandung.',
            'badge' => 'Senin–Sabtu 08.00–17.00 WIB',
            'body' => function () {
                $csrf = csrf_token();
                $offices = company('OFFICES', []);
                ob_start(); ?>
<section class="section"><div class="container split">
  <div class="reveal">
    <h2>Kirim Permintaan Anda</h2>
    <p style="color:var(--muted)">Isi form — tim kami menghubungi via WhatsApp maksimal 1×24 jam kerja.</p>
    <?php if (!empty($_GET['ok'])): ?><div class="alert alert-ok" role="status">✅ Permintaan terkirim! Terima kasih — tim kami akan menghubungi Anda dalam 1×24 jam kerja.</div><?php endif; ?>
    <?php if (!empty($_GET['err'])): ?><div class="alert alert-err" role="alert"><?= e((string) $_GET['err']) ?></div><?php endif; ?>
    <form method="post" action="<?= site_url_path('/kontak') ?>">
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
      <div class="field"><label for="f-name">Nama Lengkap *</label><input id="f-name" name="name" required minlength="2" maxlength="120"></div>
      <div class="grid grid-2">
        <div class="field"><label for="f-phone">No. WhatsApp / Telepon *</label><input id="f-phone" name="phone" required minlength="8" maxlength="30"></div>
        <div class="field"><label for="f-email">Email (opsional)</label><input id="f-email" name="email" type="email" maxlength="200"></div>
      </div>
      <div class="grid grid-2">
        <div class="field"><label for="f-city">Kota Pengiriman</label><input id="f-city" name="city" maxlength="80"></div>
        <div class="field"><label for="f-type">Jenis Pesanan</label><select id="f-type" name="order_type">
          <option value="air-bambu">Air Bambu (retail/langganan)</option>
          <option value="bambu-batang">Bambu Batang</option>
          <option value="grosir">Grosir / Kargo</option>
          <option value="korporat">Korporat / Kemitraan</option>
        </select></div>
      </div>
      <div class="field"><label for="f-msg">Pesan / Kebutuhan *</label><textarea id="f-msg" name="message" rows="4" required minlength="5" maxlength="2000"></textarea></div>
      <button class="btn btn-primary" type="submit">Kirim Permintaan →</button>
    </form>
  </div>
  <div class="reveal">
    <h2>Kunjungi Kami</h2>
    <?php foreach ($offices as $o): ?>
      <div class="card" style="margin-bottom:12px"><b><?= e($o['label'] ?? '') ?></b><p style="margin:4px 0 0;color:var(--muted)"><?= e(($o['address'] ?? '') . ' ' . ($o['city'] ?? '')) ?></p></div>
    <?php endforeach; ?>
    <div class="card"><b>Telepon / WhatsApp</b><p style="margin:4px 0 0"><?= e(setting('phone', DEFAULT_PHONE)) ?></p><b style="display:block;margin-top:10px">Email</b><p style="margin:4px 0 0"><?= e(setting('email', DEFAULT_EMAIL)) ?></p></div>
  </div>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'kalkulator-kesehatan' => [
            'title' => 'Kalkulator Kebutuhan Air Harian & Dosis Terapi — Gratis', 'crumb' => 'Kalkulator Kesehatan',
            'desc' => 'Kalkulator gratis: kebutuhan air harian (formula EFSA), BMI, dan dosis terapi air bambu sesuai berat badan Anda.',
            'badge' => 'Interaktif 3-in-1',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container" style="max-width:760px">
  <div class="card reveal" style="padding:34px">
    <h2>Kalkulator Kesehatan</h2>
    <div class="grid grid-2">
      <div class="field"><label for="calcWeight">Berat badan (kg)</label><input id="calcWeight" type="range" min="30" max="150" value="70" style="width:100%"></div>
      <div class="field"><label for="calcHeight">Tinggi badan (cm)</label><input id="calcHeight" type="number" min="100" max="220" value="170"></div>
    </div>
    <p style="font-weight:800;font-size:1.6rem" id="calcWeightVal">70 kg</p>
    <div id="calcOut"></div>
    <p style="color:var(--muted);font-size:.85rem;margin-top:16px">Rumus EFSA ±33 ml/kg untuk kebutuhan harian; dosis terapi indikatif 2 ml/kg berdasar praktik tabib — bukan anjuran medis.</p>
  </div>
</div></section>
<?= cta_band('Terapi Sesuai Takaran', 'Mau dosis personal? Konsultasi gratis via WhatsApp.') ?>
<?php return (string) ob_get_clean(); },
        ],
        'lacak-pesanan' => [
            'title' => 'Lacak Pesanan — Pantau Perjalanan Kiriman Anda', 'crumb' => 'Lacak Pesanan',
            'desc' => 'Masukkan nomor pesanan (contoh: BWN-20250615-014) untuk melihat progres pengiriman dan estimasi tiba.',
            'badge' => 'Real-time Status',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container" style="max-width:760px">
  <div class="card reveal" style="padding:34px">
    <h2>Lacak Pesanan Anda</h2>
    <div class="field"><label for="trackId">Nomor Pesanan</label><input id="trackId" placeholder="BWN-20250615-014"></div>
    <button class="btn btn-primary" id="trackBtn" type="button">Lacak →</button>
    <div id="trackOut" style="margin-top:20px"></div>
  </div>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'karier' => [
            'title' => 'Karier — Bergabung dengan Tim Kami', 'crumb' => 'Karier',
            'desc' => 'Lowongan kerja PT Bambu Water Nusantara: produksi, logistik, pemasaran, dan administrasi. Tumbuh bersama bisnis abadi.',
            'badge' => '4 Posisi Terbuka',
            'body' => function () {
                $jobs = [
                    ['Staff Produksi', 'Tasikmalaya', 'Mengoperasikan suling & pengemasan sesuai SOP higienis.'],
                    ['Admin Logistik', 'Tasikmalaya', 'Koordinasi kargo antar pulau, dokumentasi resi & eta.'],
                    ['Marketing Eksekutif', 'Bandung / Remote', 'Membangun kemitraan distributor & korporat.'],
                    ['Staff Keuangan', 'Tasikmalaya', 'Pembukuan, invoice, rekonsiliasi harian.'],
                ];
                ob_start(); ?>
<section class="section"><div class="container grid grid-2">
  <?php foreach ($jobs as $j): ?>
    <div class="card reveal"><div class="icon">💼</div><h3><?= e($j[0]) ?></h3><p><b><?= e($j[1]) ?></b></p><p><?= e($j[2]) ?></p><a class="btn btn-outline" style="padding:9px 16px;font-size:.85rem" href="<?= wa_link('Halo, saya ingin melamar posisi ' . $j[0]) ?>" target="_blank" rel="noopener">Lamar via WA</a></div>
  <?php endforeach; ?>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'perbandingan' => [
            'title' => 'Air Bambu vs Minuman Lain — Perbandingan Jujur', 'crumb' => 'Perbandingan',
            'desc' => 'Matriks perbandingan air bambu vs air putih, teh, kopi, dan soda: kalori, mineral, antioksidan, dan kecocokan terapi.',
            'badge' => 'Analisis Jujur',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container" style="overflow-x:auto">
  <table class="price-table reveal"><thead><tr><th>Kriteria</th><th>💧 Air Bambu</th><th>🚰 Air Putih</th><th>🍵 Teh</th><th>☕ Kopi</th><th>🥤 Soda</th></tr></thead><tbody>
    <tr><td><b>Kalori</b></td><td>≈ 0</td><td>0</td><td>0–2</td><td>2–5</td><td>± 140</td></tr>
    <tr><td><b>Silika organik</b></td><td style="background:var(--em-50)"><b>Tinggi (hingga 70%)</b></td><td>Tidak ada</td><td>Rendah</td><td>Minim</td><td>Tidak ada</td></tr>
    <tr><td><b>Antioksidan</b></td><td style="background:var(--em-50)"><b>Flavonoid + polifenol</b></td><td>Tidak ada</td><td>Katekin</td><td>Kafein-asam</td><td>Minim</td></tr>
    <tr><td><b>Kafein</b></td><td>0 ✓</td><td>0 ✓</td><td>Ada</td><td>Ada</td><td>Ada (umumnya)</td></tr>
    <tr><td><b>Gula</b></td><td>0 ✓</td><td>0 ✓</td><td>0 (tanpa gula)</td><td>0 (hitam)</td><td>Tinggi ✗</td></tr>
    <tr><td><b>Mineral K/Ca/Mg</b></td><td style="background:var(--em-50)"><b>Alami terkandung</b></td><td>Bergantung sumber</td><td>Minim</td><td>Minim</td><td>Minim</td></tr>
    <tr><td><b>Cocok terapi harian</b></td><td style="background:var(--em-50)"><b>Ya</b></td><td>Netral</td><td>Sekunder</td><td>Batasi</td><td>Hindari</td></tr>
  </tbody></table>
  <p class="reveal" style="color:var(--muted);font-size:.9rem;margin-top:16px">Air bambu bukan pengganti air putih — ia <b>pendamping terapi</b> yang menggantikan minuman manis dengan sari mineral alami.</p>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'glossarium' => [
            'title' => 'Glossarium Air Bambu — 20+ Istilah Penting', 'crumb' => 'Glossarium',
            'desc' => 'Kamus istilah dunia air bambu: mata tunas, resapan, silika organik, klorofil, suling bertahap, hingga food-grade.',
            'badge' => 'Kamus Istilah',
            'body' => function () {
                $terms = [
                    ['Air Tuak Bambu', 'Nama tradisional sadapan yang terbentuk alami di dalam ruas bambu muda.'],
                    ['Mata Tunas', 'Teknik panen: lubang kecil pada ruas terpilih untuk menampung tetesan sadapan semalam.'],
                    ['Resapan', 'Proses menampung sadapan pagi hari saat kandungan mineral tertinggi.'],
                    ['Silika Organik', 'Mineral penopang kolagen & tulang — pada ekstrak bambu mencapai 70%, tertinggi di alam.'],
                    ['Klorofil', 'Pigmen hijau tanaman pada air bambu — memberi rasa manis lembut alami.'],
                    ['Suling Bertahap', 'Proses pemurnian bertingkat yang menjaga mineral alami sekaligus keamanan mikro.'],
                    ['Food-Grade', 'Standar kemasan aman kontak makanan/minuman.'],
                    ['Flavonoid', 'Golongan antioksidan tanaman yang melindungi sel dari radikal bebas.'],
                    ['Polifenol', 'Senyawa bioaktif tanaman — pada bambu berperan dalam proteksi oksidatif.'],
                    ['Batch', 'Sekelompok produksi satu kali olah — tercatat untuk ketertelusuran.'],
                    ['Petung', 'Dendrocalamus asper — raja bambu konstruksi Indonesia.'],
                    ['Wulung', 'Bambu hitam elegan untuk dekorasi & furnitur premium.'],
                    ['Apus', 'Bambu lentur — bintang utama anyaman & tali tradisional.'],
                    ['Ori', 'Bambu kuning hias populer untuk lanskap.'],
                    ['MOQ', 'Minimum Order Quantity — batas minimal pemesanan grosir.'],
                    ['ETA', 'Estimated Time of Arrival — estimasi waktu tiba kiriman.'],
                    ['Kargo Full Loads', 'Pengiriman truk penuh untuk volume besar — tarif paling hemat per liter.'],
                    ['Galon 19 L', 'Kemasan standar refil untuk langganan rumah tangga & kantor.'],
                    ['Jerigen 5 L', 'Kemasan jerrycan praktis untuk praktik tabib & keluarga besar.'],
                    ['Terapi Pendamping', 'Posisi jujur air bambu: menemani pengobatan medis, bukan menggantikan.'],
                ];
                ob_start(); ?>
<section class="section"><div class="container">
  <div class="field" style="max-width:460px"><label for="listFilter">Cari istilah</label><input id="listFilter" type="search" placeholder="mis. silika, mata tunas…"></div>
  <div class="grid grid-3" style="margin-top:16px">
    <?php foreach ($terms as $i => $tm): ?>
      <div data-filter-item class="card reveal" style="padding:18px"><b style="color:var(--em-800)"><?= e($tm[0]) ?></b><p style="margin:6px 0 0;font-size:.9rem"><?= e($tm[1]) ?></p></div>
    <?php endforeach; ?>
  </div>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'peta-situs' => [
            'title' => 'Peta Situs — Semua Halaman dalam Satu Pintu', 'crumb' => 'Peta Situs',
            'desc' => 'Navigasi lengkap 2.600+ halaman bambuwater.web.id: halaman inti, 2.524 kota, 60 kondisi kesehatan, 25 layanan, 8 jenis bambu, 7 artikel.',
            'badge' => '2.600+ URL',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container">
  <?php
  $groups = [
      ['Halaman Inti', array_map(fn ($s) => ['/' . $s, ucfirst(str_replace('-', ' ', $s))], array_keys(core_pages()))],
      ['Kota (2.524)', array_map(fn ($c) => ['/kota/' . $c['slug'], 'Air Bambu ' . $c['name']], array_slice(json_load('cities')['all'], 0, 400))],
      ['Kondisi Kesehatan (60)', array_map(fn ($c) => ['/air-bambu-untuk/' . $c['slug'], 'Untuk ' . $c['name']], json_load('conditions')['all'])],
      ['Layanan (25)', array_map(fn ($s) => ['/layanan/' . url_slug($s), $s['name']], json_load('services')['all'])],
      ['Jenis Bambu (8)', array_map(fn ($b) => ['/bambu/' . url_slug($b), 'Bambu ' . $b['name']], json_load('bamboo'))],
      ['Artikel (7)', array_map(fn ($a) => ['/artikel/' . $a['slug'], $a['title']], json_load('articles'))],
  ];
  foreach ($groups as [$label, $links]): ?>
    <h2 class="reveal" style="font-size:1.2rem;margin-top:30px"><?= e($label) ?></h2>
    <div class="grid grid-4" style="margin-top:12px">
      <?php foreach ($links as [$href, $name]): ?>
        <a data-filter-item style="padding:8px 0;font-size:.88rem" href="<?= site_url_path($href) ?>"><?= e($name) ?></a>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
  <p style="color:var(--muted);font-size:.85rem;margin-top:24px">Daftar kota ditampilkan 400 pertama — seluruh 2.524 URL tersedia di <a href="<?= site_url_path('/sitemap.xml') ?>">sitemap.xml</a>.</p>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'kebijakan-privasi' => [
            'title' => 'Kebijakan Privasi — UU PDP & GDPR-Ready', 'crumb' => 'Kebijakan Privasi',
            'desc' => 'Bagaimana PT Bambu Water Nusantara mengumpulkan, memakai, dan melindungi data pribadi Anda sesuai UU PDP Indonesia & prinsip GDPR.',
            'badge' => 'Terakhir diperbarui: Desember 2025',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container" style="max-width:800px">
  <div class="alert alert-ok"><b>Ringkasan 10 detik:</b> Kami hanya menyimpan data yang Anda kirim lewat form (nama, kontak, kota, pesan) untuk memproses pesanan. Tidak dijual, tidak dibagikan ke pihak ketiga, dan Anda dapat meminta penghapusan kapan saja.</div>
  <h2>1. Data yang Kami Kumpulkan</h2><p style="color:var(--muted)">Data identitas kontak (nama, telepon, email opsional, kota) dan isi pesan Anda melalui form pemesanan. Server otomatis mencatat log teknis baku (IP, waktu) untuk keamanan.</p>
  <h2>2. Dasar & Tujuan Pemrosesan</h2><p style="color:var(--muted)">Pemrosesan dilakukan atas dasar pelaksanaan pesanan/kontrak dan kepentingan sah layanan pelanggan, sesuai UU No. 27/2022 tentang Pelindungan Data Pribadi (UU PDP) serta selaras dengan prinsip GDPR.</p>
  <h2>3. Penyimpanan & Keamanan</h2><p style="color:var(--muted)">Data disimpan pada basis data dengan akses terbatas. Form dilindungi CSRF & pembatasan laju permintaan. Kami menerapkan prinsip minimalisasi data.</p>
  <h2>4. Berbagi Data</h2><p style="color:var(--muted)">Tidak dijual. Hanya dibagikan sebatas kebutuhan pengiriman ke mitra logistik (nama, alamat, telepon).</p>
  <h2>5. Hak Anda</h2><p style="color:var(--muted)">Akses, koreksi, penghapusan, penarikan persetujuan — hubungi <?= e(setting('email', DEFAULT_EMAIL)) ?>. Kami merespons maksimal 3 hari kerja.</p>
  <h2>6. Cookie</h2><p style="color:var(--muted)">Situs memakai cookie teknis (preferensi bahasa, sesi admin). Tanpa pelacak iklan pihak ketiga diaktifkan secara default.</p>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
        'syarat-ketentuan' => [
            'title' => 'Syarat & Ketentuan Penggunaan Layanan', 'crumb' => 'Syarat & Ketentuan',
            'desc' => 'Ketentuan penggunaan situs & pembelian produk PT Bambu Water Nusantara: pemesanan, pembayaran, pengiriman, garansi, dan batasan tanggung jawab.',
            'badge' => 'Terakhir diperbarui: Desember 2025',
            'body' => function () {
                ob_start(); ?>
<section class="section"><div class="container" style="max-width:800px">
  <div class="alert alert-ok"><b>Ringkasan 10 detik:</b> Harga yang tampil final. Pesanan sah setelah konfirmasi & pembayaran. Produk herbal = terapi pendamping, bukan obat. Sengketa diselesaikan musyawarah dulu.</div>
  <h2>1. Layanan & Produk</h2><p style="color:var(--muted)">Situs ini menghadirkan informasi produk air bambu & bambu batang PT Bambu Water Nusantara. Informasi terapi bersifat edukatif dari tradisi & literatur publik.</p>
  <h2>2. Pemesanan & Pembayaran</h2><p style="color:var(--muted)">Pesanan diproses setelah konfirmasi tim & pembayaran ke rekening resmi atas nama PT. Harga dapat berubah tanpa pemberitahuan untuk order berikutnya; harga yang sudah dikonfirmasi mengikat.</p>
  <h2>3. Pengiriman</h2><p style="color:var(--muted)">ETA mengikuti tier wilayah. Risiko kerusakan transportasi ditanggung kami hingga serah terima kurir; komplain wajib maks. 2×24 jam sejak tiba.</p>
  <h2>4. Batasan Kesehatan</h2><p style="color:var(--muted)">Produk kami minuman herbal tradisional. Tidak dimaksudkan mendiagnosis/mengobati penyakit. Konsultasikan dengan dokter bila hamil, menyusui, atau dalam pengobatan rutin.</p>
  <h2>5. Hak Kekayaan Intelektual</h2><p style="color:var(--muted)">Seluruh konten, merek, dan aset visual adalah milik PT Bambu Water Nusantara. Dilarang menyalin untuk komersial tanpa izin tertulis.</p>
  <h2>6. Hukum yang Berlaku</h2><p style="color:var(--muted)">Ketentuan ini tunduk pada hukum Republik Indonesia. Sengketa diselesaikan musyawarah, lalu pengadilan yang berwenang.</p>
</div></section>
<?php return (string) ob_get_clean(); },
        ],
    ];
}

function render_core_page(string $path): bool
{
    $slug = ltrim($path, '/');
    $pages = core_pages();
    if (!isset($pages[$slug])) {
        return false;
    }
    $p = $pages[$slug];
    $meta = ['title' => $p['title'], 'desc' => $p['desc'], 'canonical' => $path];
    $body = page_hero($p['crumb'], str_contains($p['crumb'], ' ') ? $p['crumb'] : ucfirst($p['crumb']), $p['desc'], $p['badge'] ?? '')
        . ($p['body'])();
    render_layout($meta, $body);
    return true;
}

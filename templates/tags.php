<?php
/**
 * SEO Universalis (v0.5.0) — Tag Hub
 * /tag           → direktori seluruh tag (dikelompokkan)
 * /tag/{slug}    → hub satu tag: intro + tautan konten nyata + tag terkait
 * Prinsip: hub yang JUJUR — mengagregasi halaman yang benar-benar ada.
 */
declare(strict_types=1);

if (!defined('ROOT_DIR')) { exit; }

const TAG_GROUP_LABELS = [
    'produk'    => 'Produk & Brand',
    'kota'      => 'Per Kota',
    'kesehatan' => 'Kesehatan & Terapi',
    'layanan'   => 'Layanan & Kemitraan',
    'harga'     => 'Harga & Promo',
    'bambu'     => 'Jenis Bambu',
    'edukasi'   => 'Panduan & Edukasi',
];

function render_tags_index(): void
{
    $meta = [
        'title'       => 'Peta Kata Kunci — 1.000+ Topik Pencarian Populer',
        'desc'        => 'Direktori lengkap kata kunci pencarian seputar air bambu, terapi, harga, layanan, dan pengiriman se-Indonesia. Semua tautan mengarah ke konten nyata.',
        'canonical'   => '/tag',
        'breadcrumbs' => ['Beranda' => '/', 'Peta Kata Kunci' => '/tag'],
    ];
    $groups = [];
    foreach (seo_tags() as $t) {
        $groups[$t['group']][] = $t;
    }
    ksort($groups);
    ob_start(); ?>
<section class="section"><div class="container">
  <?= page_hero('Peta Kata Kunci', '1.386 Topik Pencarian Populer', 'Direktori seluruh topik pencarian tentang air bambu, terapi alami, harga, layanan, dan pengiriman se-Indonesia — setiap tautan mengarah ke halaman konten nyata kami.', count($groups) . ' kelompok topik • disusun otomatis, dikurasi manual') ?>
  <?php foreach ($groups as $g => $list): $label = TAG_GROUP_LABELS[$g] ?? ucfirst($g); ?>
  <div class="reveal" style="margin-top:2.2rem">
    <h2 style="font-family:var(--font-display)"><?= e($label) ?> <span style="color:var(--gold-600);font-size:.85em">(<?= count($list) ?>)</span></h2>
    <div class="tag-cloud" style="margin-top:1rem;display:flex;flex-wrap:wrap;gap:.5rem">
      <?php foreach ($list as $t): ?>
        <a class="tag-pill" href="<?= site_url_path('/tag/' . ($t['slug'] ?? '')) ?>"><?= e($t['kw']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>
  <div class="reveal" style="margin-top:2.5rem;text-align:center">
    <p style="color:var(--muted)">Tidak menemukan topiknya? Tanyakan langsung via WhatsApp — kami jawab pribadi.</p>
    <a class="btn btn-gold" style="margin-top:.8rem" href="<?= site_url_path('/kontak') ?>">Hubungi Kami</a>
  </div>
</div></section>
<?php
    render_layout($meta, (string) ob_get_clean());
}

function render_tag(string $slug): void
{
    $tag = seo_tag_by_slug($slug);
    if ($tag === null) {
        not_found_redirect();
        return;
    }
    $kw = (string) $tag['kw'];
    $matches = seo_tag_matches($tag);
    $related = seo_related_tags($tag);
    $label = TAG_GROUP_LABELS[$tag['group']] ?? ucfirst((string) $tag['group']);
    $meta = [
        'title'       => seo_tag_title($tag),
        'desc'        => seo_tag_desc($tag),
        'canonical'   => '/tag/' . $slug,
        'keywords'    => implode(', ', array_merge([$kw], array_column(array_slice($related, 0, 6), 'kw'))),
        'breadcrumbs' => ['Beranda' => '/', 'Peta Kata Kunci' => '/tag', $kw => '/tag/' . $slug],
    ];
    $wa = wa_link('Halo, saya ingin bertanya tentang: ' . $kw);
    ob_start(); ?>
<section class="section"><div class="container">
  <?= page_hero('Kata Kunci › ' . $label, ucfirst($kw), seo_tag_desc($tag), 'Topik ' . $label) ?>
  <div class="split" style="align-items:start">
    <div class="reveal">
      <p style="font-size:1.05rem;line-height:1.8">Halaman ini adalah <strong>panduan navigasi</strong> untuk topik <strong><?= e(ucfirst($kw)) ?></strong>. Kami rangkum setiap halaman resmi Bambu Water Nusantara yang relevan — halaman kota tujuan pengiriman, panduan kondisi kesehatan, katalog layanan, spesifikasi jenis bambu, hingga artikel edukasi — supaya Anda cukup satu langkah dari jawabannya.</p>
      <p style="font-size:1.05rem;line-height:1.8">Air bambu adalah minuman tradisional hasil sulingan bambu yang dipercaya tabib Nusantara sebagai <em>terapi pendamping</em> — bukan pengganti pengobatan medis. Kami mendistribusikannya secara resmi dengan kemasan higienis dan pengiriman ke seluruh Indonesia.</p>
      <?php if ($matches !== []): ?>
      <h2 style="font-family:var(--font-display);margin-top:1.6rem">Halaman Terkait untuk “<?= e(ucfirst($kw)) ?>”</h2>
      <div class="grid grid-2" style="margin-top:1rem">
        <?php foreach ($matches as $m): ?>
        <a class="card card-link" href="<?= site_url_path($m['url']) ?>">
          <span style="font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-600)"><?= e($m['group']) ?></span>
          <h3 style="margin:.3rem 0 .2rem;font-size:1.02rem"><?= e($m['title']) ?></h3>
          <span style="color:var(--gold-600);font-weight:700;font-size:.9rem">Buka halaman →</span>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <?php if ($related !== []): ?>
      <h2 style="font-family:var(--font-display);margin-top:2rem">Topik Terkait</h2>
      <div class="tag-cloud" style="margin-top:.9rem;display:flex;flex-wrap:wrap;gap:.5rem">
        <?php foreach ($related as $r): ?>
          <a class="tag-pill" href="<?= site_url_path('/tag/' . ($r['slug'] ?? '')) ?>"><?= e($r['kw']) ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <div class="card" style="margin-top:2rem;padding:1.4rem;border-left:4px solid var(--gold-500)">
        <h3 style="font-family:var(--font-display)">Pertanyaan tentang <?= e(ucfirst($kw)) ?>?</h3>
        <p style="color:var(--muted);margin:.5rem 0 1rem">Tim kami menjawab pribadi — jam kerja 08.00–21.00 WIB, tanpa bot.</p>
        <a class="btn btn-green" href="<?= e($wa) ?>" target="_blank" rel="noopener noreferrer external">💬 Tanya via WhatsApp</a>
      </div>
    </div>
  </div>
</div></section>
<script type="application/ld+json"><?= seo_breadcrumb_ld($meta['breadcrumbs']) ?></script>
<?php
    render_layout($meta, (string) ob_get_clean());
}

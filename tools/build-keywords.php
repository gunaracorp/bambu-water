<?php
/**
 * SEO Universalis (v0.5.0) — Generator Bank Kata Kunci
 * Membangun 1.000+ tag pencarian populer (id) → data/keywords.json
 * Filosofi Dewan: tag = HUB agregasi konten nyata (bukan doorway page tipis).
 * Jalankan: php tools/build-keywords.php
 */
declare(strict_types=1);

/* CLI-only: alat ini tidak boleh dieksekusi via web. */
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('CLI only'); }


$ROOT = dirname(__DIR__);
require $ROOT . '/config.php';

/* ---------- Util ---------- */
function slugify(string $s): string {
    $s = strtolower(trim($s));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    return trim($s, '-');
}
function kwadd(array &$out, string $kw, string $group): void {
    $kw = trim(preg_replace('/\s+/', ' ', mb_strtolower($kw)));
    if (mb_strlen($kw) < 3) { return; }
    $slug = slugify($kw);
    if ($slug === '' || isset($out[$slug])) { return; }
    $out[$slug] = ['slug' => $slug, 'kw' => $kw, 'group' => $group];
}

/* ---------- 1. Kota besar Indonesia (~110) ---------- */
$MAJOR_CITIES = ['jakarta','surabaya','bandung','medan','semarang','makassar','palembang','tangerang','depok','bekasi','bogor','batam','pekanbaru','bandar lampung','padang','malang','denpasar','samarinda','tasikmalaya','surakarta','yogyakarta','cimahi','kupang','balikpapan','banjarmasin','pontianak','jambi','bengkulu','manado','ambon','jayapura','serang','cilegon','sukabumi','cirebon','salatiga','magelang','pekalongan','tegal','purwokerto','kediri','probolinggo','pasuruan','mojokerto','madiun','blitar','mataram','bima','tarakan','palu','kendari','gorontalo','manokwari','sorong','timika','ternate','tidore','banda aceh','lhokseumawe','langsa','binjai','tebing tinggi','pematangsiantar','dumai','bukittinggi','payakumbuh','pariaman','solok','lubuklinggau','metro','prabumulih','tanjung pinang','batu','singkawang','bontang','palangka raya','bitung','tomohon','kotamobagu','soe','kefamenanu','waingapu','ruteng','ende','labuan bajo','maumere','nagoya','barabai','amuntai','kandangan','tanjung selor','malinau','nunukan','sintang','sanggau','ketapang','putussibau','puruk cahu','muara teweh','kuala kapuas','pangkalan bun','sampit','tanjung pandan','sukarame','kedoya','tanjung balai','kusamba','ubud','ngaglik','sleman','kalasan','condong catur','maguwo','denpasar barat','kuta','seminyak','canggu','nusa dua','sanur','ubud kaja'];
/* dedup nama kota */
$MAJOR_CITIES = array_values(array_unique($MAJOR_CITIES));

/* ---------- 2. Pola kota × frasa pencarian ---------- */
$CITY_PATTERNS = [
    ['air bambu %s',            'kota'],
    ['jual air bambu %s',       'kota'],
    ['harga air bambu %s',      'harga'],
    ['supplier air bambu %s',   'layanan'],
    ['distributor air bambu %s','layanan'],
    ['pengiriman air bambu %s', 'kota'],
    ['air bambu murah %s',      'harga'],
    ['grosir air bambu %s',     'layanan'],
];

/* ---------- 3. Frasa inti produk & brand ---------- */
$CORE = [
    ['air bambu','produk'],['minuman air bambu','produk'],['air bambu asli','produk'],
    ['air bambu segar','produk'],['air bambu organik','produk'],['air bambu premium','produk'],
    ['air bambu murni','produk'],['air bambu herbal','produk'],['air bambu tradisional','produk'],
    ['air bambu nusantara','produk'],['air tabib bambu','produk'],['air bambu suling','produk'],
    ['air bambu botol','produk'],['air bambu galon','produk'],['air bambu kemasan','produk'],
    ['terapi air bambu','produk'],['air bambu pengobatan alami','produk'],['air bambu kesehatan','produk'],
    ['minuman sehat alami','produk'],['minuman herbal alami','produk'],['minuman tradisional indonesia','produk'],
    ['air herbal bambu','produk'],['air bambu empon empon','produk'],['air bambu jamu','produk'],
    ['jual air bambu','produk'],['beli air bambu','produk'],['air bambu terdekat','produk'],
    ['air bambu resmi','produk'],['agen air bambu','produk'],['reseller air bambu','produk'],
    ['manfaat air bambu','edukasi'],['khasiat air bambu','edukasi'],['manfaat minum air bambu','edukasi'],
    ['khasiat air tabib bambu','edukasi'],['manfaat minuman air bambu','edukasi'],
    ['apa itu air bambu','edukasi'],['cara membuat air bambu','edukasi'],['cara minum air bambu','edukasi'],
    ['aturan minum air bambu','edukasi'],['jadwal minum air bambu','edukasi'],
    ['apakah air bambu aman','edukasi'],['efek samping air bambu','edukasi'],['air bambu untuk ibu hamil','edukasi'],
    ['air bambu untuk lansia','edukasi'],['air bambu untuk anak','edukasi'],['air bambu sehari hari','edukasi'],
    ['sejarah air bambu','edukasi'],['air bambu tabib jawa','edukasi'],['air bambu warisan nusantara','edukasi'],
    ['kandungan air bambu','edukasi'],['klorofil bambu','edukasi'],['silika bambu','edukasi'],
    ['antioksidan air bambu','edukasi'],['mineral air bambu','edukasi'],['elektrolit alami bambu','edukasi'],
    ['air bambu vs air mineral','edukasi'],['air bambu vs air putih','edukasi'],['beda air bambu dan air emas','edukasi'],
    ['cara order air bambu','edukasi'],['cara pesan air bambu','edukasi'],['cara pembayaran air bambu','edukasi'],
    ['garansi air bambu','edukasi'],['retur air bambu','edukasi'],['resi pengiriman air bambu','edukasi'],
    ['masa simpan air bambu','edukasi'],['cara simpan air bambu','edukasi'],['kemasan air bambu','edukasi'],
];

/* ---------- 4. Harga & komersial ---------- */
$PRICE = [
    ['harga air bambu','harga'],['harga air bambu per botol','harga'],['harga air bambu per galon','harga'],
    ['harga air bambu per karton','harga'],['harga air bambu grosir','harga'],['harga air bambu 2025','harga'],
    ['harga air bambu terbaru','harga'],['daftar harga air bambu','harga'],['paket hemat air bambu','harga'],
    ['paket langganan air bambu','harga'],['harga langganan air bambu','harga'],['promo air bambu','harga'],
    ['diskon air bambu','harga'],['air bambu murah','harga'],['air bambu promo bulan ini','harga'],
    ['harga borongan air bambu','harga'],['harga air bambu kantor','harga'],['harga air bambu acara','harga'],
    ['biaya kirim air bambu','harga'],['ongkir air bambu','harga'],['harga air bambu luar pulau','harga'],
    ['harga air bambu kargo','harga'],['minimal order air bambu','harga'],['mo air bambu','harga'],
    ['harga air bambu restoran','harga'],['harga air bambu hotel','harga'],['harga air bambu sekolah','harga'],
    ['harga air bambu masjid','harga'],['harga air bambu katering','harga'],['harga air bambu event','harga'],
];

/* ---------- 5. Pertanyaan long-tail (edukasi) ---------- */
$QUESTIONS = [
    'bagaimana cara membuat air bambu','bagaimana rasa air bambu','apa manfaat minum air bambu setiap hari',
    'apakah air bambu aman diminum setiap hari','apakah air bambu ada efek samping','kapan waktu terbaik minum air bambu',
    'berapa lama air bambu bisa disimpan','air bambu boleh dingin atau hangat','air bambu boleh dicampur madu',
    'air bambu boleh untuk anak berapa tahun','air bambu untuk ibu menyusui aman','air bambu bikin lambung nyeri',
    'air bambu bikin segar atau ngantuk','air bambu cocok untuk apa saja','air bambu cocok untuk penyakit apa',
    'kenapa air bambu disebut air tabib','kenapa air bambu dikemas gelap','mengapa air bambu manis alami',
    'dari mana asal air bambu','pohon bambu apa yang jadi air bambu','bagaimana proses penyulingan air bambu',
    'apakah air bambu pakai pengawet','apakah air bambu ada gula','berapa kalori air bambu',
    'apakah air bambu halal','apakah air bambu vegan','apakah air bambu bisa untuk puasa',
    'air bambu bisa dikirim luar pulau','air bambu bisa cod','air bambu bisa bayar qriscan',
    'cara jadi distributor air bambu','cara jadi reseller air bambu','syarat jadi agen air bambu',
    'keuntungan jadi mitra air bambu','modal jadi distributor air bambu','kerjasama kemitraan air bambu',
    'air bambu untuk acara pernikahan','air bambu untuk souvenir perusahaan','air bambu untuk hampers lebaran',
    'air bambu untuk rapat kantor','air bambu untuk buka puasa','air bambu untuk sahur',
    'air bambu setelah olahraga','air bambu untuk pendaki gunung','air bambu untuk perjalanan jauh',
    'air bambu untuk orang batuk','air bambu untuk orang panas dalam','air bambu untuk nyeri tenggorokan',
    'air bambu untuk sariawan','air bambu untuk bau mulut','air bambu untuk kesehatan mulut',
    'air bambu untuk kencing batu','air bambu untuk pelancar asam urat','air bambu untuk pegal linu',
    'air bambu untuk kolesterol','air bambu untuk darah tinggi',
    'air bambu untuk diabetes boleh','air bambu untuk maag boleh','air bambu untuk wasir',
    'air bambu untuk alergi kulit','air bambu untuk jerawat','air bambu untuk anti penuaan',
    'air bambu untuk daya tahan tubuh','air bambu untuk pemulihan sakit','air bambu untuk orang lelah kerja',
    'testimoni pengguna air bambu','pengalaman minum air bambu','review air bambu nusantara',
    'kisah tabib air bambu','asal usul air tabib bambu',
    'perbedaan air bambu dan air hidrogen','legenda air bambu jawa',
    'filosofi bambu dalam budaya jawa','makna bambu bagi nusantara','bambu simbol ketahanan hidup',
    'pabrik air bambu di mana','siapa pemilik bambu water nusantara','legalitas bambu water nusantara',
    'izin edar air bambu','sertifikat halal air bambu','kantor bambu water nusantara',
    'customer service air bambu','nomor whatsapp pesan air bambu','jam operasional pesan air bambu',
    'cara melacak pesanan air bambu','cara konfirmasi pembayaran air bambu','cara komplain air bambu rusak',
    'pengiriman air bambu jateng','pengiriman air bambu jatim','pengiriman air bambu sumatera',
    'pengiriman air bambu kalimantan','pengiriman air bambu sulawesi','pengiriman air bambu bali ntb ntt',
    'pengiriman air bambu papua','pengiriman air bambu maluku','pengiriman air bambu lampung',
    'pengiriman air bambu riau','pengiriman air bambu kepri','pengiriman air bambu sumut',
    'pengiriman air bambu sumbar','pengiriman air bambu sumsel','pengiriman air bambu bangka belitung',
];

/* ---------- Muat data dinamis ---------- */
$conditions = json_decode(file_get_contents($ROOT . '/data/conditions.json'), true)['all'] ?? [];
$services   = json_decode(file_get_contents($ROOT . '/data/services.json'), true)['all'] ?? [];
$bamboo     = json_decode(file_get_contents($ROOT . '/data/bamboo.json'), true) ?? [];

/* ---------- Rakit ---------- */
$out = [];

/* kota × pola */
foreach ($MAJOR_CITIES as $city) {
    foreach ($CITY_PATTERNS as [$pat, $group]) {
        kwadd($out, sprintf($pat, $city), $group);
    }
}
/* inti, harga, pertanyaan */
foreach ($CORE as [$kw, $g]) { kwadd($out, $kw, $g); }
foreach ($PRICE as [$kw, $g]) { kwadd($out, $kw, $g); }
foreach ($QUESTIONS as $kw) { kwadd($out, $kw, 'edukasi'); }

/* kondisi kesehatan × pola */
foreach ($conditions as $c) {
    $name = mb_strtolower(trim($c['name'] ?? ''));
    if ($name === '') { continue; }
    kwadd($out, 'air bambu untuk ' . $name, 'kesehatan');
    kwadd($out, 'manfaat air bambu untuk ' . $name, 'kesehatan');
}
/* layanan × pola */
foreach ($services as $s) {
    $name = mb_strtolower(trim($s['name'] ?? ''));
    if ($name === '') { continue; }
    kwadd($out, $name, 'layanan');
    kwadd($out, 'harga ' . $name, 'harga');
    kwadd($out, 'cara daftar ' . $name, 'layanan');
}
/* bambu × pola */
foreach ($bamboo as $b) {
    $name = mb_strtolower(trim($b['name'] ?? ''));
    if ($name === '') { continue; }
    kwadd($out, $name, 'bambu');
    kwadd($out, 'harga ' . $name, 'harga');
    kwadd($out, 'manfaat ' . $name, 'edukasi');
    kwadd($out, 'bibit ' . $name, 'bambu');
}

$tags = array_values($out);
usort($tags, fn($a, $b) => [$a['group'], $a['kw']] <=> [$b['group'], $b['kw']]);

$payload = [
    'generated' => date('Y-m-d'),
    'note' => 'Bank kata kunci SEO Universalis — tag = hub agregasi konten nyata, bukan doorway. Regenerasi: php tools/build-keywords.php',
    'count' => count($tags),
    'tags' => $tags,
];
file_put_contents($ROOT . '/data/keywords.json', json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

$byGroup = [];
foreach ($tags as $t) { $byGroup[$t['group']] = ($byGroup[$t['group']] ?? 0) + 1; }
echo "TOTAL TAG: " . count($tags) . "\n";
foreach ($byGroup as $g => $n) { echo "  - {$g}: {$n}\n"; }
echo (count($tags) >= 1000 ? "✅ Target ≥1000 TERCAPAI\n" : "⚠️ Kurang dari 1000 — tambah pola!\n");

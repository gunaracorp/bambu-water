<?php
/**
 * SEO Universalis (v0.5.0) — llms.txt & llms-full.txt
 * Standar buka (llmstxt.org) untuk mesin pencari & asisten AI:
 * GPTBot, OAI-SearchBot, ClaudeBot, PerplexityBot, dkk.
 *  /llms.txt       → ringkasan terkurasi + tautan kunci
 *  /llms-full.txt  → versi lengkap (artikel penuh + katalog ringkas)
 */
declare(strict_types=1);

if (!defined('ROOT_DIR')) { exit; }

function llms_base(): string
{
    return rtrim(setting('site_url', SITE_URL), '/');
}

function llms_render(bool $full = false): void
{
    $base = llms_base();
    $name = setting('site_name', APP_NAME);
    $phone = setting('phone', DEFAULT_PHONE);
    $email = setting('email', DEFAULT_EMAIL);
    $wa = setting('wa', DEFAULT_WA);

    $L = "# {$name}\n\n";
    $L .= "> Distributor resmi air bambu (air tabib bambu) — minuman tradisional sulingan bambu untuk terapi pendamping alami — dan pemasok bambu berkualitas konstruksi, dengan jangkauan pengiriman 34 provinsi Indonesia.\n\n";
    $L .= "Situs ini adalah mirror server-rendered (PHP) dari bambuwater.web.id. Seluruh URL di bawah adalah halaman nyata, bebas-JS untuk pembacaan mesin.\n\n";

    $L .= "## Fakta Kunci\n\n";
    $L .= "- Nama legal: PT {$name}\n- WhatsApp: {$phone} (https://wa.me/{$wa})\n- Email: {$email}\n- Cakupan: 2.524 kota/kabupaten, 34 provinsi\n- Katalog: 25 layanan, 60 panduan kondisi kesehatan, 8 jenis bambu, 7 artikel\n- Posisi jujur: air bambu = terapi PENDAMPING, bukan pengganti pengobatan medis\n\n";

    $L .= "## Halaman Utama\n\n";
    $L .= "- [Beranda]({$base}/): ringkasan perusahaan, produk, dan layanan\n";
    $L .= "- [Harga & Paket]({$base}/harga-paket): daftar harga konsumen & grosir\n";
    $L .= "- [Layanan]({$base}/layanan): 25 layanan (langganan, kargo, kemitraan)\n";
    $L .= "- [Kesehatan]({$base}/kesehatan): 60 panduan penggunaan per kondisi\n";
    $L .= "- [Jenis Bambu]({$base}/bambu): 8 spesies + harga batangan\n";
    $L .= "- [Artikel]({$base}/artikel): edukasi & sains air bambu\n";
    $L .= "- [Kontak]({$base}/kontak): form + WhatsApp + kantor\n";
    $L .= "- [Peta Situs]({$base}/peta-situs): navigasi halaman inti\n";
    $L .= "- [Peta Kata Kunci]({$base}/tag): direktori 1.386 topik pencarian\n\n";

    if ($full) {
        $L .= "## Katalog Layanan (25)\n\n";
        foreach (json_load('services')['all'] ?? [] as $s) {
            $L .= "- [" . ($s['name'] ?? '') . "]({$base}/layanan/" . url_slug($s) . "): " . mb_substr((string) ($s['hook'] ?? ''), 0, 140) . "\n";
        }
        $L .= "\n## Panduan Kondisi Kesehatan (60)\n\n";
        foreach (json_load('conditions')['all'] ?? [] as $c) {
            $L .= "- [Air bambu untuk " . ($c['name'] ?? '') . "]({$base}/air-bambu-untuk/" . ($c['slug'] ?? '') . "): " . mb_substr((string) ($c['hook'] ?? ''), 0, 140) . "\n";
        }
        $L .= "\n## Jenis Bambu (8)\n\n";
        foreach (json_load('bamboo') as $b) {
            $L .= "- [" . ($b['name'] ?? '') . "]({$base}/bambu/" . ($b['slug'] ?? '') . "): " . ($b['latin'] ?? '') . " — " . ($b['priceRange'] ?? '') . "\n";
        }
        $L .= "\n## Artikel (teks penuh)\n\n";
        foreach (json_load('articles') as $a) {
            $L .= "### " . ($a['title'] ?? '') . "\n\n";
            $L .= "URL: {$base}/artikel/" . ($a['slug'] ?? '') . "\n";
            $body = (string) ($a['body'] ?? $a['content'] ?? ($a['metaDescription'] ?? ''));
            $L .= strip_tags($body) . "\n\n";
        }
        $L .= "## Cakupan Kota\n\n";
        $L .= "Halaman pengiriman per kota tersedia untuk 2.524 kota/kabupaten pada pola {$base}/kota/{slug} (contoh: [Bandung]({$base}/kota/bandung)). Daftar lengkap: /search-index.json dan /sitemap-pages.xml.\n";
    } else {
        $L .= "## Format Machine-Readable\n\n";
        $L .= "- [Sitemap pages]({$base}/sitemap-pages.xml)\n- [Sitemap tags]({$base}/sitemap-tags.xml)\n- [RSS feed]({$base}/feed.xml)\n- [Indeks JSON]({$base}/search-index.json)\n";
    }

    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: public, max-age=3600');
    echo $L;
    exit;
}

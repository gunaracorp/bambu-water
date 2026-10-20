# Worklog — PT Bambu Water Nusantara Website

Project: Situs web SEO-friendly full animasi untuk PT Bambu Water Nusantara (air bambu pengobatan + distribusi bambu massal se-Indonesia).

---
Task ID: 1
Agent: orchestrator (Z.ai Code)
Task: Setup, riset, dan perencanaan

Work Log:
- Membaca dokumen upload: AKTA PENDIRIAN (docx = template kosong; data resmi dari PNG akta)
- Data akta: Komisaris Utama Hj. Qonitah Lutfiah, S.E., M.M. (30%), Komisaris Drs. Arif Rachman Hakim (15%), Komisaris Jajang Sutivana (15%), Direktur Utama H. Gugun Gunara, S.E (25%), Direktur Rahmadini (5%), Direktur H. Abdul Rohman (5%), Direktur H. Solihin (5%). Modal dasar Rp700jt, disetor Rp300jt.
- Riset web (11 query + 4 page_reader): sains air bambu (PMC4659479 - proteksi oksidatif/inflamasi/kardiovaskular), TIME 2015 "Bamboo Water Is Now a Thing", silika organik tertinggi ~70%, flavonoid+polifenol, mineral K/Ca/Mg, klorofil, asam amino; manfaat tradisional (demam, batuk, gula darah, kolesterol/stroke, jantung, pencernaan, nyeri haid, daya tahan, kulit); pasar global bambu US$60 miliar; lahan bambu Indonesia 2,1 juta ha (terbesar dunia), 157 spesies; jenis bambu premium (Petung/Betung, Wulung, Apus, Ori) + harga pasar per batang
- Hasil riset tersimpan: research/search-results.json, research/deep-results.json
- Generator gambar branding berjalan background: public/images/ (hero, produk, proses, plantasi, wellness)

Stage Summary:
- Konten & sains terkumpul dari sumber kredibel (PMC, TIME, RRI, Klikdokter, dll.)
- Branding: hijau bambu + emas + krem; font Playfair Display + Plus Jakarta Sans
- Arsitektur: 1 route (/), 14 section animasi, API kontak, JSON-LD SEO lengkap

---
Task ID: 2
Agent: orchestrator (Z.ai Code)
Task: Build situs lengkap PT Bambu Water Nusantara (SEO + animasi penuh + backend)

Work Log:
- Branding: logo SVG custom (bambu + tetesan air, ring emas), palet hijau bambu/emas/krem, font Playfair Display + Plus Jakarta Sans, favicon
- 8 gambar AI generated (hero hutan bambu fajar, botol premium, galon grosir, batang bambu, panen sadapan, distilasi, plantasi aerial, wellness) → public/images/
- SEO: metadata lengkap (title template, 20 keyword ID, OG, twitter, canonical), JSON-LD (Corporation, WebSite, LocalBusiness, 3 Product, FAQPage), sitemap.ts, robots.ts (hapus public/robots.txt konflik), semantic HTML + ARIA + alt text
- 14 komponen section: Navbar (glass sticky + scroll-spy + drawer mobile), Hero (parallax, tetesan jatuh, siluet bambu bergoyang, teks stagger, riak), StatsStrip (count-up), About (visi/misi + kartu legal akta), Benefits (8 kartu + rujukan PMC & TIME), Science (bar nutrisi animasi), Process (timeline zigzag scroll-progress), Products (tab AnimatePresence), Delivery (marquee 32 kota + 3 tier + CTA korporat), Leadership (7 pengurus dari akta), Testimonials (carousel auto), FAQ (accordion), Contact (form 4 tipe pesanan), Footer (sticky mt-auto + CTA band), WhatsAppFloat
- Backend: Prisma ContactMessage + POST/GET /api/contact (zod validation), db:push sukses
- Perbaikan: ukuran gambar 1440x720→1344x768 (API wajib kelipatan 32), duplikat key Jayapura→Sorong, kontras navbar (putih di atas hero, gelap saat scroll)
- Verifikasi Agent Browser: 20 screenshot desktop+mobile, form end-to-end (2 submit → DB terverifikasi via Prisma: orderType=grosir, city=Surabaya), tab produk, accordion FAQ, carousel, drawer menu, robots/sitemap/JSON-LD via curl, konsol bersih, lint 0 error 0 warning

Stage Summary:
- Situs 1 halaman produksi-ready dengan identitas brand kuat, konten berbasis riset ilmiah kredibel
- Data akta (pengurus & modal) terintegrasi di section Pimpinan & kartu legal
- File kunci: src/app/page.tsx, src/lib/company.ts, src/components/site/* (17 file), src/app/api/contact/route.ts, prisma/schema.prisma
- Screenshot verifikasi: research/screenshots/ (20 file)

---
Task ID: 3-a
Agent: general-purpose (penulis konten SEO subagent)
Task: Menulis 4 artikel data (gula darah, batu ginjal, kolesterol, detoksifikasi)

Work Log:
- Membaca worklog.md, src/lib/articles.ts (tipe ArticleData/ArticleSection/ArticleFaq + ARTICLE_SLUGS), dan pages-registry.ts (PAGES_META untuk 4 slug)
- Membuat src/lib/articles-a.ts berisi export const ARTICLES_A: ArticleData[] (TEPAT 4 artikel, urutan sesuai ARTICLE_SLUGS)
- Metadata disalin PERSIS dari PAGES_META (seoTitle, metaDescription, keywords, heroImage, updated=Juni 2025, title=h1); category=Artikel Kesehatan; intro registry diperluas jadi 3 paragraf
- Konten per artikel: excerpt ≤180 karakter, readMinutes 6, heroImageAlt SEO, keyPoints 6 poin, 5 section (H2 unik, paragraf 60-125 kata), minimal 1 list (5-6 item) + 1 callout, conclusion + CTA halus WA 0813-1651-6524 (tepat 1x/artikel), 4 FAQ (jawaban 40-80 kata), 3 related slug valid, 4 referensi (PMC4659479, TIME 2015, Klikdokter, RRI)
- Disclaimer wajib terpasang di callout: air bambu = terapi pendamping BUKAN pengganti obat/dokter; batasan cairan penderita ginjal lanjut disorot di artikel ginjal & detoks
- Branding anti-plagiat: konteks Indonesia + sebut produk "Air Bambu Suling"/"Air Bambu Tabib" natural 1-2x per artikel
- Verifikasi via transpile TypeScript (tanpa build/lint): 0 error sintaks; validasi programatik: jumlah kata 1248-1289/artikel (rentang 900-1300), heading unik, FAQ in-range, related valid, WA 1x, sebutan produk 1-2x, meta 100% identik dengan PAGES_META
- Tidak memodifikasi file lain; tidak menjalankan build/lint

Stage Summary:
- ARTICLES_A siap dipakai: 4 artikel (air-bambu-gula-darah 1289 kata, air-bambu-batu-ginjal 1270, air-bambu-kolesterol 1248, air-bambu-detoksifikasi 1253) — masing-masing 5 section + 1 list + 1 callout + 4 FAQ
- File: src/lib/articles-a.ts (satu-satunya ekspor ARTICLES_A, import type saja, tanpa import tak terpakai)
- articles.ts (Task 2) sudah mengimpor ARTICLES_A — menunggu articles-b.ts (Task 3-b) agar ARTICLES lengkap 7 artikel

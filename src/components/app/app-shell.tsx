'use client';

import { useEffect } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { useHashRoute, setMeta, setCanonical } from '@/hooks/use-hash-route';
import { getPageMeta } from '@/lib/pages-registry';
import { SITE_URL, COMPANY } from '@/lib/company';

import { JsonLd } from '@/components/site/json-ld';
import { Navbar } from '@/components/site/navbar';
import { Footer } from '@/components/site/footer';
import { WhatsAppFloat } from '@/components/site/whatsapp-float';

import { HomePage } from '@/components/pages/home';
import { TentangKamiPage } from '@/components/pages/tentang-kami';
import { ApaItuAirBambuPage } from '@/components/pages/apa-itu-air-bambu';
import { ManfaatAirBambuPage } from '@/components/pages/manfaat-air-bambu';
import { KandunganNutrisiPage } from '@/components/pages/kandungan-nutrisi';
import { PengobatanPage } from '@/components/pages/air-bambu-pengobatan';
import { ArtikelIndexPage } from '@/components/pages/artikel-index';
import { ArticlePage } from '@/components/pages/article-page';
import { AirBambuSulingPage } from '@/components/pages/air-bambu-suling';
import { AirBambuTabibPage } from '@/components/pages/air-bambu-tabib';
import { BambuBerkualitasPage } from '@/components/pages/bambu-berkualitas';
import { ProsesProduksiPage } from '@/components/pages/proses-produksi';
import { PengirimanMassalPage } from '@/components/pages/pengiriman-massal';
import { JangkauanKotaPage } from '@/components/pages/jangkauan-kota';
import { HargaPaketPage } from '@/components/pages/harga-paket';
import { CaraPemesananPage } from '@/components/pages/cara-pemesanan';
import { TestimoniPage } from '@/components/pages/testimoni';
import { FaqPage } from '@/components/pages/faq';
import { KontakPage } from '@/components/pages/kontak';

const ARTICLE_SLUGS = [
  'air-bambu-gula-darah',
  'air-bambu-batu-ginjal',
  'air-bambu-kolesterol',
  'air-bambu-detoksifikasi',
  'air-bambu-tulang-silika',
  'air-bambu-kecantikan',
  'sejarah-air-bambu',
];

const PAGE_MAP: Record<string, () => React.ReactElement> = {
  beranda: () => <HomePage />,
  'tentang-kami': () => <TentangKamiPage />,
  'apa-itu-air-bambu': () => <ApaItuAirBambuPage />,
  'manfaat-air-bambu': () => <ManfaatAirBambuPage />,
  'kandungan-nutrisi': () => <KandunganNutrisiPage />,
  'air-bambu-pengobatan': () => <PengobatanPage />,
  'air-bambu-suling': () => <AirBambuSulingPage />,
  'air-bambu-tabib': () => <AirBambuTabibPage />,
  'bambu-berkualitas': () => <BambuBerkualitasPage />,
  'proses-produksi': () => <ProsesProduksiPage />,
  'pengiriman-massal': () => <PengirimanMassalPage />,
  'jangkauan-kota': () => <JangkauanKotaPage />,
  'harga-paket': () => <HargaPaketPage />,
  'cara-pemesanan': () => <CaraPemesananPage />,
  artikel: () => <ArtikelIndexPage />,
  testimoni: () => <TestimoniPage />,
  faq: () => <FaqPage />,
  kontak: () => <KontakPage />,
};

export function AppShell() {
  const { slug } = useHashRoute();
  const meta = getPageMeta(slug);

  /* ===== Metadata dinamis per halaman (SEO maksimal) ===== */
  useEffect(() => {
    const title = meta.seoTitle;
    const desc = meta.metaDescription;
    const kw = meta.keywords.join(', ');
    const url = slug === 'beranda' ? `${SITE_URL}/` : `${SITE_URL}/#/${slug}`;

    setMeta('name', 'description', desc);
    setMeta('name', 'keywords', kw);
    setMeta('name', 'twitter:title', title);
    setMeta('name', 'twitter:description', desc);
    setMeta('property', 'og:title', title);
    setMeta('property', 'og:description', desc);
    setMeta('property', 'og:url', url);
    setMeta('property', 'og:site_name', `${COMPANY.name} — bambuwater.web.id`);
    if (meta.heroImage) setMeta('property', 'og:image', `${SITE_URL}${meta.heroImage}`);
    setCanonical(url);

    /* Normalisasi <title>: pastikan hanya SATU title dengan teks benar */
    const ours = `${title} | bambuwater.web.id`;
    const titles = Array.from(document.head.querySelectorAll('title'));
    if (titles.length === 0) {
      const t = document.createElement('title');
      t.textContent = ours;
      document.head.prepend(t);
    } else {
      titles[0].textContent = ours;
      titles.slice(1).forEach((t) => t.remove());
    }

    window.scrollTo({ top: 0, behavior: 'instant' as ScrollBehavior });
  }, [slug, meta]);

  const renderPage = () => {
    if (PAGE_MAP[slug]) return PAGE_MAP[slug]();
    if (ARTICLE_SLUGS.includes(slug)) return <ArticlePage slug={slug} />;
    return PAGE_MAP.beranda();
  };

  return (
    <div className="flex min-h-screen flex-col">
      {/* Title dikelola React — tersinkron otomatis per halaman */}
      <title>{`${meta.seoTitle} | bambuwater.web.id`}</title>
      <JsonLd />
      <Navbar activeSlug={slug} />

      <main className="flex-1" id="konten-utama">
        <AnimatePresence mode="wait">
          <motion.div
            key={slug}
            initial={{ opacity: 0, y: 18 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -12 }}
            transition={{ duration: 0.45, ease: [0.21, 0.47, 0.32, 0.98] }}
          >
            {renderPage()}
          </motion.div>
        </AnimatePresence>
      </main>

      <Footer />
      <WhatsAppFloat />
    </div>
  );
}

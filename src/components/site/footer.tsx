'use client';

import { Instagram, Facebook, Linkedin, Mail, Phone, MapPin, ArrowUpRight, MessageCircle } from 'lucide-react';
import { COMPANY, waLink } from '@/lib/company';
import { Logo } from './logo';

/** Sitemap footer — 25 halaman bambuwater.web.id */
const FOOTER_NAV = [
  {
    heading: 'Jelajahi',
    links: [
      { label: 'Beranda', href: '#/' },
      { label: 'Tentang Kami', href: '#/tentang-kami' },
      { label: 'Testimoni', href: '#/testimoni' },
      { label: 'FAQ', href: '#/faq' },
      { label: 'Kontak', href: '#/kontak' },
    ],
  },
  {
    heading: 'Kesehatan',
    links: [
      { label: 'Apa Itu Air Bambu', href: '#/apa-itu-air-bambu' },
      { label: 'Manfaat Air Bambu', href: '#/manfaat-air-bambu' },
      { label: 'Kandungan Nutrisi', href: '#/kandungan-nutrisi' },
      { label: 'Untuk Pengobatan', href: '#/air-bambu-pengobatan' },
      { label: 'Sejarah Air Bambu', href: '#/sejarah-air-bambu' },
    ],
  },
  {
    heading: 'Produk & Layanan',
    links: [
      { label: 'Air Bambu Suling', href: '#/air-bambu-suling' },
      { label: 'Air Bambu Tabib', href: '#/air-bambu-tabib' },
      { label: 'Bambu Berkualitas', href: '#/bambu-berkualitas' },
      { label: 'Harga & Paket', href: '#/harga-paket' },
      { label: 'Pengiriman Massal', href: '#/pengiriman-massal' },
      { label: 'Jangkauan Kota', href: '#/jangkauan-kota' },
      { label: 'Proses Produksi', href: '#/proses-produksi' },
      { label: 'Cara Pemesanan', href: '#/cara-pemesanan' },
    ],
  },
  {
    heading: 'Artikel Kesehatan',
    links: [
      { label: 'Semua Artikel', href: '#/artikel' },
      { label: 'Air Bambu & Gula Darah', href: '#/air-bambu-gula-darah' },
      { label: 'Air Bambu & Batu Ginjal', href: '#/air-bambu-batu-ginjal' },
      { label: 'Air Bambu & Kolesterol', href: '#/air-bambu-kolesterol' },
      { label: 'Detoksifikasi Alami', href: '#/air-bambu-detoksifikasi' },
      { label: 'Tulang & Sendi', href: '#/air-bambu-tulang-silika' },
      { label: 'Kecantikan & Kulit', href: '#/air-bambu-kecantikan' },
    ],
  },
];

export function Footer() {
  const year = new Date().getFullYear();

  return (
    <footer className="relative mt-auto bg-emerald-950 text-emerald-50" aria-label="Footer situs">
      {/* CTA band */}
      <div className="relative overflow-hidden border-b border-white/10">
        <div
          aria-hidden
          className="animate-soft-pulse absolute -left-20 top-0 h-64 w-64 rounded-full bg-teal-500/15 blur-3xl"
        />
        <div
          aria-hidden
          className="animate-soft-pulse absolute -right-20 bottom-0 h-64 w-64 rounded-full bg-amber-400/10 blur-3xl"
          style={{ animationDelay: '2s' }}
        />
        <div className="relative mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-4 py-14 text-center sm:px-6 md:flex-row md:text-left">
          <div>
            <h2 className="font-serif text-2xl font-bold text-white sm:text-3xl">
              Mulai Hidup Sehat dengan <span className="text-shimmer-gold">Sari Bambu</span>
            </h2>
            <p className="mt-2 max-w-xl text-sm leading-relaxed text-emerald-100/75">
              Pesan air bambu untuk keluarga Anda, atau jadikan mitra distribusi kami di kota
              Anda. Konsultasi gratis via WhatsApp {COMPANY.whatsappDisplay}.
            </p>
          </div>
          <div className="flex shrink-0 flex-col gap-3 sm:flex-row">
            <a
              href={waLink('Halo Bambu Water Nusantara! Saya ingin konsultasi gratis tentang air bambu / bambu batang.')}
              target="_blank"
              rel="noopener noreferrer external"
              className="group inline-flex items-center justify-center gap-2.5 rounded-full bg-emerald-500 px-7 py-4 text-base font-bold text-white shadow-xl shadow-emerald-900/40 transition-all duration-300 hover:-translate-y-1 hover:bg-emerald-400 hover:shadow-2xl"
            >
              <MessageCircle className="h-5 w-5" aria-hidden />
              WhatsApp {COMPANY.whatsappDisplay}
            </a>
            <a
              href="#/kontak"
              className="group inline-flex items-center justify-center gap-2.5 rounded-full border border-white/25 px-7 py-4 text-base font-bold text-white transition-all duration-300 hover:-translate-y-1 hover:border-gold hover:text-gold"
            >
              Formulir Pemesanan
              <ArrowUpRight className="h-5 w-5 transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" aria-hidden />
            </a>
          </div>
        </div>
      </div>

      {/* Kolom footer */}
      <div className="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-6">
        <div className="lg:col-span-2">
          <Logo dark />
          <p className="mt-4 text-sm leading-relaxed text-emerald-100/70">
            {COMPANY.description}
          </p>
          <p className="mt-4 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3.5 py-1.5 text-xs text-emerald-100/80">
            <span className="h-1.5 w-1.5 rounded-full bg-emerald-400" aria-hidden />
            <span className="font-semibold text-white">{COMPANY.domain}</span>
            — situs resmi
          </p>
          <div className="mt-5 flex gap-3">
            {[
              { icon: Instagram, href: COMPANY.social.instagram, label: 'Instagram Bambu Water Nusantara' },
              { icon: Facebook, href: COMPANY.social.facebook, label: 'Facebook Bambu Water Nusantara' },
              { icon: Linkedin, href: COMPANY.social.linkedin, label: 'LinkedIn Bambu Water Nusantara' },
            ].map((s) => (
              <a
                key={s.label}
                href={s.href}
                target="_blank"
                rel="noopener noreferrer external"
                aria-label={s.label}
                className="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 bg-white/5 text-emerald-100 transition-all duration-300 hover:-translate-y-1 hover:border-gold/50 hover:bg-gold/20 hover:text-gold"
              >
                <s.icon className="h-4.5 w-4.5" aria-hidden />
              </a>
            ))}
          </div>
        </div>

        {FOOTER_NAV.map((col) => (
          <nav aria-label={`Navigasi footer ${col.heading}`} key={col.heading}>
            <h3 className="text-sm font-bold uppercase tracking-[0.18em] text-gold">{col.heading}</h3>
            <ul className="mt-5 space-y-2.5 text-sm">
              {col.links.map((l) => (
                <li key={l.href}>
                  <a
                    href={l.href}
                    className="text-emerald-100/75 transition-colors hover:text-white hover:underline hover:decoration-gold/60 hover:underline-offset-4"
                  >
                    {l.label}
                  </a>
                </li>
              ))}
            </ul>
          </nav>
        ))}
      </div>

      {/* Kontak baris */}
      <div className="border-t border-white/10">
        <div className="mx-auto grid max-w-7xl gap-4 px-4 py-8 text-sm text-emerald-100/75 sm:px-6 md:grid-cols-3">
          <a href={`tel:${COMPANY.phone.replace(/[^+\d]/g, '')}`} className="flex items-center gap-3 transition-colors hover:text-white">
            <Phone className="h-4.5 w-4.5 shrink-0 text-teal-300" aria-hidden />
            {COMPANY.phone} (telepon / WhatsApp)
          </a>
          <a href={`mailto:${COMPANY.email}`} className="flex items-center gap-3 break-all transition-colors hover:text-white">
            <Mail className="h-4.5 w-4.5 shrink-0 text-teal-300" aria-hidden />
            {COMPANY.email}
          </a>
          <span className="flex items-center gap-3">
            <MapPin className="h-4.5 w-4.5 shrink-0 text-teal-300" aria-hidden />
            {COMPANY.address} — {COMPANY.operationalHours}
          </span>
        </div>
      </div>

      {/* Bar bawah */}
      <div className="border-t border-white/10">
        <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-6 text-center text-xs text-emerald-100/60 sm:px-6 md:flex-row md:text-left">
          <p>
            © {year} {COMPANY.legalName} • {COMPANY.domain}. Seluruh hak cipta dilindungi.
          </p>
          <p className="flex flex-wrap items-center justify-center gap-x-4 gap-y-1">
            <span className="inline-flex items-center gap-1.5">
              <span className="h-1.5 w-1.5 rounded-full bg-emerald-400" aria-hidden />
              Badan Hukum PT Resmi
            </span>
            <span aria-hidden className="hidden md:inline">•</span>
            <span>Terapi alami pendamping — bukan pengganti nasihat medis</span>
          </p>
        </div>
      </div>
    </footer>
  );
}

'use client';

import { Instagram, Facebook, Linkedin, Mail, Phone, MapPin, ArrowUpRight } from 'lucide-react';
import { COMPANY, NAV_LINKS, PRODUCTS } from '@/lib/company';
import { Logo } from './logo';

const PRODUCT_LINKS = PRODUCTS.map((p) => ({ label: p.name, href: '#produk' }));

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
              Anda. Kesempatan bisnis minuman sehat sedang tumbuh pesat.
            </p>
          </div>
          <a
            href="#kontak"
            className="group inline-flex shrink-0 items-center gap-2.5 rounded-full bg-gradient-to-r from-emerald-400 to-teal-400 px-8 py-4 text-base font-bold text-emerald-950 shadow-xl shadow-emerald-900/40 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
          >
            Jadwal Konsultasi Gratis
            <ArrowUpRight className="h-5 w-5 transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" aria-hidden />
          </a>
        </div>
      </div>

      {/* Kolom footer */}
      <div className="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-2 lg:grid-cols-4">
        <div>
          <Logo dark />
          <p className="mt-4 text-sm leading-relaxed text-emerald-100/70">
            {COMPANY.description}
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

        <nav aria-label="Navigasi footer">
          <h3 className="text-sm font-bold uppercase tracking-[0.18em] text-gold">Navigasi</h3>
          <ul className="mt-5 space-y-2.5 text-sm">
            {NAV_LINKS.map((l) => (
              <li key={l.href}>
                <a href={l.href} className="text-emerald-100/75 transition-colors hover:text-white hover:underline hover:decoration-gold/60 hover:underline-offset-4">
                  {l.label}
                </a>
              </li>
            ))}
          </ul>
        </nav>

        <nav aria-label="Produk">
          <h3 className="text-sm font-bold uppercase tracking-[0.18em] text-gold">Produk</h3>
          <ul className="mt-5 space-y-2.5 text-sm">
            {PRODUCT_LINKS.map((l) => (
              <li key={l.label}>
                <a href={l.href} className="text-emerald-100/75 transition-colors hover:text-white hover:underline hover:decoration-gold/60 hover:underline-offset-4">
                  {l.label}
                </a>
              </li>
            ))}
            <li>
              <a href="#pengiriman" className="text-emerald-100/75 transition-colors hover:text-white hover:underline hover:decoration-gold/60 hover:underline-offset-4">
                Pengiriman Grosir & Kargo
              </a>
            </li>
          </ul>
        </nav>

        <div>
          <h3 className="text-sm font-bold uppercase tracking-[0.18em] text-gold">Kontak</h3>
          <ul className="mt-5 space-y-3.5 text-sm text-emerald-100/75">
            <li className="flex items-start gap-3">
              <Phone className="mt-0.5 h-4.5 w-4.5 shrink-0 text-teal-300" aria-hidden />
              <a href={`tel:${COMPANY.phone.replace(/[^+\d]/g, '')}`} className="transition-colors hover:text-white">
                {COMPANY.phone}
              </a>
            </li>
            <li className="flex items-start gap-3">
              <Mail className="mt-0.5 h-4.5 w-4.5 shrink-0 text-teal-300" aria-hidden />
              <a href={`mailto:${COMPANY.email}`} className="break-all transition-colors hover:text-white">
                {COMPANY.email}
              </a>
            </li>
            <li className="flex items-start gap-3">
              <MapPin className="mt-0.5 h-4.5 w-4.5 shrink-0 text-teal-300" aria-hidden />
              <span>{COMPANY.address}</span>
            </li>
          </ul>
        </div>
      </div>

      {/* Bar bawah */}
      <div className="border-t border-white/10">
        <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-6 text-center text-xs text-emerald-100/60 sm:px-6 md:flex-row md:text-left">
          <p>
            © {year} {COMPANY.legalName}. Seluruh hak cipta dilindungi. Modal disetor{' '}
            {COMPANY.modalDisetor}.
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

'use client';

import { MapPin, MessageCircle } from 'lucide-react';
import { motion } from 'framer-motion';
import { Button } from '@/components/ui/button';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal } from '@/components/site/reveal';
import { CtaBand } from '@/components/site/cta-band';
import { waLink } from '@/lib/company';

const ISLANDS: { name: string; icon: string; cities: string[] }[] = [
  {
    name: 'Pulau Jawa',
    icon: '🏙️',
    cities: ['Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Bekasi', 'Bandung', 'Cirebon', 'Semarang', 'Solo', 'Yogyakarta', 'Surabaya', 'Malang', 'Banyuwangi', 'Serang', 'Tasikmalaya'],
  },
  {
    name: 'Sumatera',
    icon: '🌴',
    cities: ['Medan', 'Padang', 'Palembang', 'Pekanbaru', 'Batam', 'Bandar Lampung', 'Banda Aceh', 'Jambi', 'Bengkulu', 'Binjai'],
  },
  {
    name: 'Kalimantan',
    icon: '🌿',
    cities: ['Balikpapan', 'Banjarmasin', 'Pontianak', 'Samarinda', 'Palangkaraya', 'Tarakan'],
  },
  {
    name: 'Sulawesi',
    icon: '🌺',
    cities: ['Makassar', 'Manado', 'Palu', 'Kendari', 'Gorontalo', 'Palopo'],
  },
  {
    name: 'Bali & Nusa Tenggara',
    icon: '🏝️',
    cities: ['Denpasar', 'Mataram', 'Kupang', 'Bima', 'Sumba'],
  },
  {
    name: 'Maluku & Papua',
    icon: '⛵',
    cities: ['Ambon', 'Ternate', 'Jayapura', 'Sorong', 'Timika', 'Manokwari'],
  },
];

/** Halaman 20/25 — Jangkauan Kota */
export function JangkauanKotaPage() {
  return (
    <>
      <PageHero slug="jangkauan-kota" />

      <PageSection bg="light">
        <SectionHeading
          eyebrow="Cakupan Wilayah"
          title="38 Provinsi,"
          highlight="Satu Jaringan Terpercaya"
          description="Sejak 2007 jaringan distribusi kami tumbuh dari Jawa Barat ke seluruh Nusantara — kota Anda pasti terjangkau."
        />

        <div className="mt-14 space-y-8">
          {ISLANDS.map((island, i) => (
            <Reveal key={island.name} delay={i * 0.06}>
              <div className="rounded-3xl border border-primary/10 bg-white p-6 shadow-sm sm:p-8">
                <h3 className="flex items-center gap-3 font-serif text-xl font-bold text-foreground">
                  <span aria-hidden className="text-2xl">{island.icon}</span>
                  {island.name}
                  <span className="rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">
                    {island.cities.length}+ kota
                  </span>
                </h3>
                <div className="mt-5 flex flex-wrap gap-2">
                  {island.cities.map((city) => (
                    <span
                      key={city}
                      className="inline-flex items-center gap-1.5 rounded-full border border-primary/15 bg-secondary/50 px-3.5 py-1.5 text-sm text-foreground/85 transition-colors hover:border-primary/40 hover:bg-primary/10 hover:text-primary"
                    >
                      <MapPin className="h-3 w-3 text-primary" aria-hidden />
                      {city}
                    </span>
                  ))}
                </div>
              </div>
            </Reveal>
          ))}
        </div>

        <Reveal className="mx-auto mt-10 max-w-3xl">
          <div className="flex flex-col items-center gap-4 rounded-3xl border border-primary/15 bg-secondary/50 p-7 text-center sm:p-9">
            <p className="text-base leading-relaxed text-muted-foreground">
              <strong className="text-foreground">Kota Anda tidak tercantum?</strong> Kami tetap
              bisa mengirim ke hampir semua wilayah Indonesia melalui mitra ekspedisi lokal —
              tanyakan langsung kepada tim kami.
            </p>
            <Button asChild size="lg" className="rounded-full bg-emerald-600 px-8 py-6 text-white hover:bg-emerald-700">
              <a href={waLink('Halo! Apakah Bambu Water Nusantara mengirim ke kota saya?')} target="_blank" rel="noopener noreferrer external" className="gap-2">
                <MessageCircle className="h-5 w-5" aria-hidden />
                Cek Kota Saya
              </a>
            </Button>
          </div>
        </Reveal>
      </PageSection>

      <CtaBand />
    </>
  );
}

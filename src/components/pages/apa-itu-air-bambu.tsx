'use client';

import { Droplets, Sun, Leaf, FlaskConical, Timer, Sprout } from 'lucide-react';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal, StaggerContainer, StaggerItem } from '@/components/site/reveal';
import { CtaBand } from '@/components/site/cta-band';

const ORIGIN_STEPS = [
  { icon: Sprout, title: 'Terbentuk di Dalam Ruas', text: 'Air hujan dan kelembapan diserap akar rizoma bambu, naik melalui batang, lalu berkondensasi menjadi sadapan murni di ruas bambu muda.' },
  { icon: Timer, title: 'Momentum Panen', text: 'Kandungan mineral paling optimal saat bambu berumur 1–3 tahun — dipanen subuh saat sadapan segar.' },
  { icon: Droplets, title: 'Teknik Mata Tunas', text: 'Tabib tradisional membuka "mata tunas" di ruas untuk mengalirkan sadapan tanpa merusak pohon — warisan Nusantara.' },
  { icon: FlaskConical, title: 'Distilasi Higienis', text: 'Untuk konsumsi harian, sadapan disuling bertahap agar mineral terjaga dan mikrobiologi terjamin.' },
];

const TYPES = [
  { name: 'Air Sadapan Mentah (Tabib)', desc: 'Langsung dari ruas — dipakai tabib untuk terapi, harus segar & diolah tepat.', badge: 'Tradisional' },
  { name: 'Air Bambu Suling', desc: 'Dimurnikan bertahap — aman harian, tahan simpan, mineral & silika terjaga.', badge: 'Populer' },
  { name: 'Ekstrak Daun Bambu', desc: 'Sumber riset ilmiah modern — silika hingga 70% dan antioksidan tinggi.', badge: 'Riset' },
];

const DIFFS = [
  { label: 'vs Air Mineral', text: 'Air bambu unggul kandungan silika organik & flavonoid unik yang tidak dimiliki air mineral biasa.' },
  { label: 'vs Air Kelapa', text: 'Keduanya minuman alami; air bambu lebih rendah gula alami dan kaya silika untuk tulang & kulit.' },
  { label: 'vs Teh Daun', text: 'Tanpa kafein — cocok dikonsumsi kapan saja, bahkan untuk anak dan ibu hamil (dengan anjuran).' },
];

/** Halaman 3/25 — Apa Itu Air Bambu */
export function ApaItuAirBambuPage() {
  return (
    <>
      <PageHero slug="apa-itu-air-bambu" />

      <PageSection bg="light">
        <div className="mx-auto max-w-3xl space-y-5 text-base leading-relaxed text-muted-foreground sm:text-lg">
          <Reveal>
            <p>
              <strong className="text-foreground">Air bambu</strong> — sering disebut{' '}
              <em>air tuak bambu</em> atau <em>air sadapan bambu</em> — adalah cairan bening
              semirair yang terbentuk secara alami di dalam ruas bambu muda. Selama berabad-abad,
              cairan ini menjadi rahasia penyembuhan tabib Nusantara, dan kini dikaji ilmu
              modern sebagai minuman dengan <strong className="text-foreground">kandungan silika organik
              tertinggi di alam</strong> (hingga ~70% pada ekstrak daun bambu) serta antioksidan
              flavonoid dan polifenol.
            </p>
          </Reveal>
          <Reveal delay={0.1}>
            <p>
              Di Indonesia — negara dengan lahan bambu terluas di dunia (2,1 juta hektare, 157
              spesies) — air bambu tumbuh melimpah namun belum banyak dimanfaatkan. Bambu Water
              Nusantara hadir sejak 2007 untuk memanen kekayaan ini secara berkelanjutan,
              memurnikannya dengan standar modern, dan mengantarnya ke seluruh Indonesia.
            </p>
          </Reveal>
        </div>

        {/* Bagaimana terbentuk */}
        <div className="mt-16">
          <SectionHeading
            eyebrow="Asal Usul"
            title="Bagaimana Air Bambu"
            highlight="Terbentuk?"
          />
          <StaggerContainer className="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            {ORIGIN_STEPS.map((s, i) => (
              <StaggerItem key={s.title}>
                <div className="relative h-full rounded-3xl border border-primary/10 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg hover:shadow-emerald-900/10">
                  <span className="absolute right-5 top-5 font-serif text-3xl font-bold text-primary/10">{i + 1}</span>
                  <span className="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                    <s.icon className="h-6 w-6" aria-hidden />
                  </span>
                  <h3 className="mt-4 font-serif text-lg font-bold text-foreground">{s.title}</h3>
                  <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{s.text}</p>
                </div>
              </StaggerItem>
            ))}
          </StaggerContainer>
        </div>
      </PageSection>

      {/* Jenis */}
      <PageSection bg="cream">
        <SectionHeading
          eyebrow="Jenis-Jenis"
          title="Tiga Wujud"
          highlight="Air Bambu"
          description="Dari tradisi tabib hingga laboratorium modern — tiga bentuk pemanfaatan air bambu."
        />
        <div className="mx-auto mt-12 grid max-w-5xl gap-6 lg:grid-cols-3">
          {TYPES.map((t, i) => (
            <Reveal key={t.name} delay={i * 0.12}>
              <div className="h-full rounded-3xl border border-primary/10 bg-white p-7 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl hover:shadow-emerald-900/10">
                <span className="inline-flex rounded-full bg-gold/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-700">{t.badge}</span>
                <h3 className="mt-4 font-serif text-xl font-bold text-foreground">{t.name}</h3>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{t.desc}</p>
              </div>
            </Reveal>
          ))}
        </div>
      </PageSection>

      {/* Perbandingan */}
      <PageSection bg="light">
        <SectionHeading
          eyebrow="Perbandingan"
          title="Mengapa Memilih"
          highlight="Air Bambu?"
        />
        <div className="mx-auto mt-12 max-w-4xl space-y-4">
          {DIFFS.map((d, i) => (
            <Reveal key={d.label} delay={i * 0.1}>
              <div className="flex flex-col gap-2 rounded-2xl border border-primary/10 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:gap-6">
                <span className="shrink-0 rounded-full bg-primary px-4 py-1.5 text-sm font-bold text-primary-foreground">{d.label}</span>
                <p className="text-sm leading-relaxed text-muted-foreground sm:text-base">{d.text}</p>
              </div>
            </Reveal>
          ))}
        </div>
        <Reveal delay={0.2} className="mx-auto mt-10 max-w-3xl">
          <div className="rounded-2xl border-l-4 border-gold bg-gold/10 p-5">
            <p className="text-sm leading-relaxed text-foreground">
              <strong>Tahukah Anda?</strong> TIME (2015) meliput munculnya <em>bamboo water</em>
              sebagai tren minuman kesehatan dunia — dan Indonesia adalah rumah terbesar bagi
              bahan bakunya.
            </p>
          </div>
        </Reveal>
      </PageSection>

      <CtaBand
        waMessage="Halo! Saya ingin tahu lebih banyak tentang air bambu dan produknya."
        title="Ingin Mencoba Air Bambu Asli Nusantara?"
        description="Konsultasi gratis dengan tim kami — pilih air bambu suling untuk harian, atau air bambu tabib untuk kebutuhan terapi."
      />
    </>
  );
}

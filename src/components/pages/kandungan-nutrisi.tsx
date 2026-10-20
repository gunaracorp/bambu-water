'use client';

import { Atom, FlaskConical, ShieldCheck } from 'lucide-react';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal } from '@/components/site/reveal';
import { Science } from '@/components/site/science';
import { CtaBand } from '@/components/site/cta-band';

const COMPONENTS_DETAIL = [
  {
    name: 'Silika Organik (Si)',
    level: 'Tertinggi di alam — hingga ~70%',
    role: 'Membangun kolagen, kepadatan tulang, kekuatan rambut & kuku, serta elastisitas pembuluh darah.',
  },
  {
    name: 'Flavonoid & Polifenol',
    level: 'Antioksidan utama',
    role: 'Melawan radikal bebas, meredakan peradangan, dan melindungi jantung serta pembuluh darah.',
  },
  {
    name: 'Klorofil Alami',
    level: 'Detoks alami',
    role: 'Mendukung pembersihan tubuh, regenerasi sel, dan menetralkan bau badan dari dalam.',
  },
  {
    name: 'Asam Amino Esensial',
    level: 'Blok bangun protein',
    role: 'Mendukung perbaikan jaringan, enzim pencernaan, dan energi harian.',
  },
  {
    name: 'Kalium, Kalsium & Magnesium',
    level: 'Elektrolit alami',
    role: 'Menyeimbangkan cairan tubuh, menjaga fungsi otot & saraf, serta kesehatan tulang.',
  },
];

/** Halaman 5/25 — Kandungan Nutrisi & Sains */
export function KandunganNutrisiPage() {
  return (
    <>
      <PageHero slug="kandungan-nutrisi" />

      {/* Grafik nutrisi animasi */}
      <PageSection bg="light">
        <SectionHeading
          eyebrow="Analisis Ilmiah"
          title="Profil Nutrisi"
          highlight="Air Bambu"
          description="Visualisasi kandungan berdasarkan kajian ekstrak bambu (Journal of Medicinal Plant Research, 2015)."
        />
        <div className="mt-4">
          <Science />
        </div>
      </PageSection>

      {/* Detail komponen */}
      <PageSection bg="cream">
        <SectionHeading
          eyebrow="Bedah Komponen"
          title="Apa yang Ada di Dalam"
          highlight="Setiap Gelas?"
        />
        <div className="mx-auto mt-12 max-w-4xl space-y-4">
          {COMPONENTS_DETAIL.map((c, i) => (
            <Reveal key={c.name} delay={i * 0.08}>
              <div className="rounded-2xl border border-primary/10 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-900/10">
                <div className="flex flex-wrap items-center justify-between gap-2">
                  <h3 className="font-serif text-lg font-bold text-foreground">{c.name}</h3>
                  <span className="rounded-full bg-primary/10 px-3 py-1 text-xs font-bold text-primary">{c.level}</span>
                </div>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{c.role}</p>
              </div>
            </Reveal>
          ))}
        </div>
      </PageSection>

      {/* Riset */}
      <PageSection bg="dark">
        <SectionHeading
          eyebrow="Dukungan Riset"
          title="Sains di Balik"
          highlight="Sari Bambu"
          dark
        />
        <div className="mt-12 grid gap-6 md:grid-cols-3">
          {[
            {
              icon: FlaskConical,
              title: 'Kajian 2015 — J. Medicinal Plant Research',
              text: 'Ekstrak bambu terbukti memberi efek proteksi oksidatif, anti-inflamasi, dan melindungi kardiovaskular pada model uji (PMC4659479).',
            },
            {
              icon: Atom,
              title: 'Silika Tertinggi di Alam',
              text: 'Ekstrak daun bambu (Bambusa vulgaris) mencatat kandungan silika organik hingga 70% — tertinggi di antara tanaman yang dikaji.',
            },
            {
              icon: ShieldCheck,
              title: 'TIME 2015 — Tren Dunia',
              text: '"Bamboo Water Is Now a Thing" — media global menyoroti bambu sebagai sumber minuman fungsional masa depan.',
            },
          ].map((r, i) => (
            <Reveal key={r.title} delay={i * 0.12}>
              <div className="h-full rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
                <r.icon className="h-8 w-8 text-gold" aria-hidden />
                <h3 className="mt-4 font-serif text-lg font-bold text-white">{r.title}</h3>
                <p className="mt-2 text-sm leading-relaxed text-emerald-50/80">{r.text}</p>
              </div>
            </Reveal>
          ))}
        </div>
        <Reveal delay={0.2} className="mx-auto mt-10 max-w-3xl">
          <p className="text-center text-xs leading-relaxed text-emerald-50/60">
            *Nilai kandungan bersifat indikatif berdasarkan publikasi riset ekstrak bambu dan dapat
            bervariasi antar panen. Air bambu bukan obat — konsultasikan kondisi kesehatan Anda
            dengan tenaga medis.
          </p>
        </Reveal>
      </PageSection>

      <CtaBand
        waMessage="Halo! Saya ingin tahu detail kandungan & uji mutu air bambu BWN."
        title="Mineral Alami, Terverifikasi Higienis"
        description="Setiap batch air bambu BWN melewati distilasi bertahap dan uji mutu sebelum dikirim ke Anda."
      />
    </>
  );
}

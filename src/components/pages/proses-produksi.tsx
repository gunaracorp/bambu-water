'use client';

import { Factory, Microscope, Award, Sparkles } from 'lucide-react';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal, StaggerContainer, StaggerItem } from '@/components/site/reveal';
import { Process } from '@/components/site/process';
import { CtaBand } from '@/components/site/cta-band';

const QC_STEPS = [
  { icon: Microscope, title: 'Uji Mikrobiologi', text: 'Setiap batch diperiksaparameter TPC & patogen sebelum lolos kemasan.' },
  { icon: Factory, title: 'Fasilitas Higienis', text: 'Ruang produksi terkontrol dengan sanitasi berkala dan aliran satu arah.' },
  { icon: Sparkles, title: 'Kemasan Food-Grade', text: 'Botol, jerigen, dan drum bersertifikat food-grade, disegel tamper-proof.' },
  { icon: Award, title: 'Jejak Batch', text: 'Setiap kemasan tercatat asal panen & tanggal produksi — transparan penuh.' },
];

/** Halaman 18/25 — Proses Produksi & Distilasi */
export function ProsesProduksiPage() {
  return (
    <>
      <PageHero slug="proses-produksi" />

      <PageSection bg="light">
        <div className="mx-auto max-w-3xl space-y-5 text-base leading-relaxed text-muted-foreground sm:text-lg">
          <Reveal>
            <p>
              Kepercayaan 18+ tahun kami bangun dari satu hal: <strong className="text-foreground">transparansi mutu</strong>.
              Dari rumpun bambu di hutan rakyat hingga segel kemasan dibuka di rumah Anda, setiap
              tahap tercatat dan terstandar — memadukan kearifan panen tradisional dengan
              teknologi distilasi modern.
            </p>
          </Reveal>
        </div>
      </PageSection>

      {/* Timeline 4 tahap (komponen animasi) */}
      <PageSection bg="cream">
        <SectionHeading
          eyebrow="Tahapan"
          title="Dari Hutan ke"
          highlight="Gelas Anda"
        />
        <div className="mt-4">
          <Process />
        </div>
      </PageSection>

      {/* Kontrol mutu */}
      <PageSection bg="light">
        <SectionHeading
          eyebrow="Kontrol Mutu"
          title="4 Lapis Jaminan"
          highlight="Kualitas"
        />
        <StaggerContainer className="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {QC_STEPS.map((q) => (
            <StaggerItem key={q.title}>
              <div className="group h-full rounded-3xl border border-primary/10 bg-white p-6 text-center shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg hover:shadow-emerald-900/10">
                <span className="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary transition-colors group-hover:bg-primary group-hover:text-white">
                  <q.icon className="h-7 w-7" aria-hidden />
                </span>
                <h3 className="mt-4 font-serif text-lg font-bold text-foreground">{q.title}</h3>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{q.text}</p>
              </div>
            </StaggerItem>
          ))}
        </StaggerContainer>

        <Reveal delay={0.2} className="mx-auto mt-10 max-w-3xl">
          <div className="rounded-2xl border-l-4 border-gold bg-gold/10 p-5">
            <p className="text-sm leading-relaxed text-foreground">
              <strong>Komitmen berkelanjutan:</strong> kami hanya memanen bambu muda tanpa
              menebang rumpun — kelestarian hutan bambu rakyat adalah syarat keberlangsungan
              bisnis kami sejak 2007.
            </p>
          </div>
        </Reveal>
      </PageSection>

      <CtaBand
        waMessage="Halo! Saya ingin tahu lebih detail tentang proses produksi & uji mutu air bambu BWN."
        title="Ingin Melihat Langsung Prosesnya?"
        description="Jadwalkan kunjungan fasilitas kami — terbuka untuk mitra, distributor, dan komunitas edukasi."
      />
    </>
  );
}

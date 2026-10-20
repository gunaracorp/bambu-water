'use client';

import { HeartHandshake, Stethoscope, BookOpen, Users } from 'lucide-react';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal, StaggerContainer, StaggerItem } from '@/components/site/reveal';
import { Process } from '@/components/site/process';
import { CtaBand } from '@/components/site/cta-band';

const THERAPY_USES = [
  {
    title: 'Demam & Panas Dalam',
    text: 'Sifat mendinginkan alami air bambu dipakai tabib Nusantara menurunkan suhu tubuh — diminum hangat perlahan.',
    audience: 'Keluarga',
  },
  {
    title: 'Batuk & Dahak',
    text: 'Membantu melapangkan saluran napas dan melunakkan dahak secara alami tanpa kafein.',
    audience: 'Keluarga',
  },
  {
    title: 'Pendamping Diabetes & Kolesterol',
    text: 'Dikonsumsi sebagai minuman rendah gula pengganti minuman manis, dalam program pola hidup sehat penderita.',
    audience: 'Dengan pengawasan medis',
  },
  {
    title: 'Pemulihan & Daya Tahan',
    text: 'Mineral, asam amino, dan antioksidan mendukung pemulihan tubuh pasien sakit atau pasca operasi (sesuai anjuran).',
    audience: 'Pasien & lansia',
  },
];

const PRACTITIONERS = [
  { icon: Users, title: 'Tabib & Praktisi Tradisional', text: 'Air Bambu Tabib (sadapan mentah) untuk praktik pengobatan komplementer sesuai tradisi.' },
  { icon: Stethoscope, title: 'Klinik Terapi & Spa', text: 'Pasokan rutin air bambu suling untuk program detoks & terapi minum klien.' },
  { icon: BookOpen, title: 'Komunitas Herbal & Edukasi', text: 'Materi edukasi + sampel untuk sosialisasi pengobatan tradisional di komunitas.' },
  { icon: HeartHandshake, title: 'Rumah Sehat & Panti', text: 'Kemitraan pasokan minuman sehat alami bagi penghuni program pemulihan.' },
];

/** Halaman 6/25 — Air Bambu untuk Pengobatan */
export function PengobatanPage() {
  return (
    <>
      <PageHero slug="air-bambu-pengobatan" />

      <PageSection bg="light">
        <div className="mx-auto max-w-3xl space-y-5 text-base leading-relaxed text-muted-foreground sm:text-lg">
          <Reveal>
            <p>
              Dalam khazanah <strong className="text-foreground">pengobatan tradisional Nusantara</strong>,
              air bambu dikenal sebagai <em>"air kehidupan"</em> — disarankan tabib untuk meredakan demam,
              melapangkan napas, melancarkan pencernaan, hingga mendampingi program penurunan gula darah
              dan kolesterol. Tradisi ini kini diperkaya bukti ilmiah: kajian 2015 (Journal of Medicinal
              Plant Research) mencatat ekstrak bambu memiliki efek <strong className="text-foreground">antioksidan,
              anti-inflamasi, dan proteksi kardiovaskular</strong>.
            </p>
          </Reveal>
          <Reveal delay={0.1}>
            <div className="rounded-2xl border-l-4 border-gold bg-gold/10 p-5 text-sm sm:text-base">
              <strong className="text-foreground">Prinsip kami:</strong> air bambu adalah{' '}
              <strong>terapi komplementer pendamping</strong> — mendukung, bukan menggantikan
              pengobatan medis. Selalu konsultasikan dengan dokter atau tabib Anda.
            </div>
          </Reveal>
        </div>
      </PageSection>

      {/* Kegunaan terapi */}
      <PageSection bg="cream">
        <SectionHeading
          eyebrow="Aplikasi Terapi"
          title="Untuk Siapa &"
          highlight="Untuk Apa?"
        />
        <StaggerContainer className="mt-12 grid gap-5 sm:grid-cols-2">
          {THERAPY_USES.map((t) => (
            <StaggerItem key={t.title}>
              <div className="h-full rounded-3xl border border-primary/10 bg-white p-7 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg hover:shadow-emerald-900/10">
                <span className="inline-flex rounded-full bg-primary/10 px-3 py-1 text-xs font-bold uppercase tracking-wider text-primary">{t.audience}</span>
                <h3 className="mt-3 font-serif text-xl font-bold text-foreground">{t.title}</h3>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{t.text}</p>
              </div>
            </StaggerItem>
          ))}
        </StaggerContainer>
      </PageSection>

      {/* Praktisi */}
      <PageSection bg="light">
        <SectionHeading
          eyebrow="Mitra Praktisi"
          title="Dipercaya Para"
          highlight="Penyembuh Tradisional"
          description="Sejak 2007, tabib dan praktisi di berbagai kota mempercayakan pasokan air bambu kepada kami."
        />
        <div className="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
          {PRACTITIONERS.map((p, i) => (
            <Reveal key={p.title} delay={i * 0.1}>
              <div className="group h-full rounded-3xl border border-primary/10 bg-white p-6 text-center shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg hover:shadow-emerald-900/10">
                <span className="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10 text-primary transition-colors group-hover:bg-primary group-hover:text-white">
                  <p.icon className="h-7 w-7" aria-hidden />
                </span>
                <h3 className="mt-4 font-serif text-lg font-bold text-foreground">{p.title}</h3>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{p.text}</p>
              </div>
            </Reveal>
          ))}
        </div>
      </PageSection>

      {/* Kualitas produksi */}
      <PageSection bg="cream">
        <SectionHeading
          eyebrow="Standar Terapi"
          title="Kualitas yang Layak untuk"
          highlight="Penyembuhan"
        />
        <div className="mt-4">
          <Process />
        </div>
      </PageSection>

      <CtaBand
        waMessage="Halo! Saya tabib/terapis dan ingin memesan Air Bambu Tabib secara rutin."
        title="Butuh Pasokan Rutin untuk Praktik Anda?"
        description="Harga khusus tabib, terapis & klinik — kontrak bulanan fleksibel dengan pengiriman terjadwal."
      />
    </>
  );
}

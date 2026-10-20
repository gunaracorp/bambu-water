'use client';

import { motion } from 'framer-motion';
import { Eye, Target, HeartHandshake, ShieldCheck, Scale, MapPin } from 'lucide-react';
import { COMPANY } from '@/lib/company';
import { Reveal, StaggerContainer, StaggerItem } from './reveal';
import { SectionHeading } from './section-heading';

const VALUES = [
  {
    icon: ShieldCheck,
    title: 'Murni & Teruji',
    text: 'Setiap tetes melewati distilasi bertahap dan uji mutu sebelum sampai ke tangan Anda.',
  },
  {
    icon: HeartHandshake,
    title: 'Mitra Petani Bambu',
    text: 'Bermitra dengan petani hutan bambu rakyat — membeli hasil panen dengan harga adil.',
  },
  {
    icon: Scale,
    title: 'Badan Hukum Resmi',
    text: 'PT berakta dengan modal disetor Rp 300 juta — aman untuk kontrak & pengadaan besar.',
  },
];

export function About() {
  return (
    <section id="tentang" className="relative overflow-hidden py-24 sm:py-28" aria-label="Tentang perusahaan">
      <div
        aria-hidden
        className="animate-soft-pulse pointer-events-none absolute -right-40 top-24 h-96 w-96 rounded-full bg-emerald-200/40 blur-3xl"
      />
      <div
        aria-hidden
        className="animate-soft-pulse pointer-events-none absolute -left-40 bottom-10 h-96 w-96 rounded-full bg-teal-100/50 blur-3xl"
        style={{ animationDelay: '2s' }}
      />

      <div className="mx-auto max-w-7xl px-4 sm:px-6">
        <SectionHeading
          eyebrow="Tentang Kami"
          title="Perusahaan Air Bambu"
          highlight="Pertama Nusantara"
          description="Kami membawa tradisi penyembuhan air bambu Nusantara ke era modern — dengan standar produksi higienis dan jaringan distribusi nasional."
        />

        <div className="mt-16 grid items-center gap-12 lg:grid-cols-2">
          {/* Gambar plantasi */}
          <Reveal direction="right" className="relative">
            <div className="group relative overflow-hidden rounded-3xl shadow-2xl shadow-emerald-900/20">
              { }
              <img
                src="/images/about-plantation.jpg"
                alt="Hamparan perkebunan bambu berkelanjutan di perbukitan hijau Indonesia yang dijaga PT Bambu Water Nusantara"
                className="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-105"
                loading="lazy"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-emerald-950/60 via-transparent to-transparent" />
              <div className="absolute bottom-5 left-5 right-5 flex flex-wrap gap-3">
                <span className="inline-flex items-center gap-2 rounded-full bg-white/90 px-4 py-2 text-xs font-semibold text-emerald-900 backdrop-blur">
                  <MapPin className="h-3.5 w-3.5" aria-hidden />
                  Lahan bambu 2,1 juta ha di Indonesia
                </span>
              </div>
            </div>

            {/* Kartu legal melayang */}
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: 0.35, duration: 0.7 }}
              className="absolute -bottom-10 -right-3 w-56 rounded-2xl border border-emerald-900/10 bg-white p-5 shadow-xl shadow-emerald-900/15 sm:-right-8"
            >
              <p className="text-xs font-semibold uppercase tracking-wider text-bamboo">Legalitas</p>
              <p className="mt-1 font-serif text-lg font-bold text-foreground">Modal Disetor</p>
              <p className="font-serif text-2xl font-bold text-primary">{COMPANY.modalDisetor}</p>
              <p className="mt-1 text-xs text-muted-foreground">Modal dasar {COMPANY.modalDasar} — PT berakta resmi</p>
            </motion.div>
          </Reveal>

          {/* Narasi perusahaan */}
          <div>
            <Reveal direction="left">
              <p className="text-lg leading-relaxed text-foreground/85">
                <strong className="font-semibold text-primary">{COMPANY.legalName}</strong> lahir dari
                keyakinan bahwa Nusantara menyimpan harta penyembuhan paling sederhana:{' '}
                <em>air yang mengalir di dalam bambu</em>. Selama berabad-abad, tabib tradisional
                memanen sadapan bambu muda untuk menurunkan demam, meredakan batuk, dan memulihkan
                tenaga. Kini kami menghadirkan kearifan itu dengan standar produksi modern — dari
                panen berkelanjutan, distilasi higienis, hingga pengiriman nasional.
              </p>
            </Reveal>

            <StaggerContainer className="mt-8 grid gap-4 sm:grid-cols-2">
              <StaggerItem>
                <div className="h-full rounded-2xl border border-emerald-900/10 bg-gradient-to-br from-emerald-50 to-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                  <Eye className="h-8 w-8 text-primary" aria-hidden />
                  <h3 className="mt-3 font-serif text-lg font-bold">Visi</h3>
                  <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
                    Menjadi penyedia air bambu terapi terdepan di Asia Tenggara sekaligus pilar
                    ekonomi bambu Indonesia.
                  </p>
                </div>
              </StaggerItem>
              <StaggerItem>
                <div className="h-full rounded-2xl border border-emerald-900/10 bg-gradient-to-br from-amber-50 to-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-md">
                  <Target className="h-8 w-8 text-gold" aria-hidden />
                  <h3 className="mt-3 font-serif text-lg font-bold">Misi</h3>
                  <p className="mt-2 text-sm leading-relaxed text-muted-foreground">
                    Menyediakan air bambu murni berkualitas farmasi, memasok bambu konstruksi
                    nasional, dan mengangkat kesejahteraan petani bambu.
                  </p>
                </div>
              </StaggerItem>
            </StaggerContainer>
          </div>
        </div>

        {/* Nilai perusahaan */}
        <StaggerContainer className="mt-20 grid gap-5 md:grid-cols-3">
          {VALUES.map((v) => (
            <StaggerItem key={v.title}>
              <div className="flex h-full items-start gap-4 rounded-2xl border border-emerald-900/10 bg-white/70 p-6 shadow-sm backdrop-blur transition-all duration-300 hover:-translate-y-1 hover:border-primary/30 hover:shadow-lg">
                <span className="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                  <v.icon className="h-6 w-6" aria-hidden />
                </span>
                <div>
                  <h3 className="font-serif text-base font-bold">{v.title}</h3>
                  <p className="mt-1.5 text-sm leading-relaxed text-muted-foreground">{v.text}</p>
                </div>
              </div>
            </StaggerItem>
          ))}
        </StaggerContainer>
      </div>
    </section>
  );
}

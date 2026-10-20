'use client';

import { motion } from 'framer-motion';
import { Crown, BookOpenText, Sparkles, Droplets, Leaf, ShieldCheck } from 'lucide-react';
import { Reveal, StaggerContainer, StaggerItem } from './reveal';
import { waLink } from '@/lib/company';
import { Button } from '@/components/ui/button';

const REASONS = [
  {
    title: 'Silika Organik Tertinggi di Alam',
    text: 'Hingga ~70% — kunci kolagen dan kekenyalan kulit yang membuat kaisar tetap muda.',
  },
  {
    title: 'Antioksidan Pelawan Usia',
    text: 'Flavonoid & polifenol melawan radikal bebas, penyebab utama penuaan dini.',
  },
  {
    title: 'Meredakan Demam & Panas Dalam',
    text: 'Efek dingin alami yang diwariskan tabib Nusantara dari generasi ke generasi.',
  },
  {
    title: 'Melegakan Batuk & Dahak',
    text: 'Sama seperti "Tian Zhu Li" di pengobatan istana Tiongkok kuno — melapangkan napas.',
  },
  {
    title: 'Mendampingi Gula Darah & Kolesterol Normal',
    text: 'Pengganti minuman manis terbaik untuk program hidup sehat keluarga.',
  },
  {
    title: 'Melancarkan Pencernaan & Detoks',
    text: 'Klorofil dan mineral alami membantu tubuh membuang sisa racun harian.',
  },
  {
    title: 'Hidrasi Murni Tanpa Kafein & Tanpa Gula',
    text: 'Aman diminum kapan saja — untuk anak, dewasa, hingga lansia.',
  },
  {
    title: 'Menjaga Tulang & Sendi Tetap Muda',
    text: 'Silika mendukung kepadatan tulang — lansia aktif seperti kaum bangsawan kuno.',
  },
  {
    title: 'Kecantikan dari Dalam',
    text: 'Kulit kenyal, rambut berkilau, dan kuku kuat — beauty ritual alami.',
  },
  {
    title: 'Daya Tahan Tubuh Prima',
    text: 'Asam amino, klorofil, dan mineral esensial menjaga imun di segala musim.',
  },
];

/**
 * Section branding "Rahasia Kaisar China" + 10 alasan minum air bambu.
 * Dipakai di Beranda & halaman Manfaat.
 */
export function ReasonsSection({ compact = false }: { compact?: boolean }) {
  return (
    <section
      className="relative overflow-hidden bg-emerald-950 py-20 sm:py-24"
      aria-labelledby="reasons-title"
    >
      {/* Ornamen latar */}
      <div aria-hidden className="animate-soft-pulse absolute -left-32 top-0 h-96 w-96 rounded-full bg-teal-500/15 blur-3xl" />
      <div aria-hidden className="animate-soft-pulse absolute -right-32 bottom-0 h-96 w-96 rounded-full bg-amber-400/10 blur-3xl" style={{ animationDelay: '2.2s' }} />

      <div className="relative mx-auto max-w-7xl px-4 sm:px-6">
        {/* Narasi Kaisar China */}
        <div className="mx-auto max-w-3xl text-center">
          <Reveal direction="down">
            <span className="inline-flex items-center gap-2 rounded-full border border-gold/40 bg-gold/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-gold">
              <Crown className="h-3.5 w-3.5" aria-hidden />
              Rahasia Kuno yang Nyaris Tergores Sejarah
            </span>
          </Reveal>

          <Reveal delay={0.1}>
            <h2 id="reasons-title" className="mt-5 font-serif text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-[2.75rem]">
              Rahasia Awet Muda <span className="text-shimmer-gold">Kaisar China</span>
            </h2>
          </Reveal>

          <Reveal delay={0.2}>
            <div className="mt-6 space-y-4 text-base leading-relaxed text-emerald-50/85 sm:text-lg">
              <p>
                Sejak ribuan tahun lalu, tabib istana Dinasti China menyimpan satu rahasia
                paling dijaga: <strong className="text-white">sari bambu</strong> — elixir yang
                dipercaya menjaga awet muda kaisar dan para bangsawan. Terdokumentasi dalam
                pengobatan tradisional Tiongkok sebagai <em>"Tian Zhu Li"</em> (天竹瀝),
                sari bambu disuling dari ruas bambu muda untuk meredakan panas, menyegarkan
                tubuh, dan merawat vitalitas sang penguasa.
              </p>
              <p>
                Namun seiring waktu, rahasia besar itu nyaris <strong className="text-white">tergores
                sejarah</strong> — hanya diketahui segelintir pembantu istana dan tabib
                warisan. Kini rahasia kuno itu kembali: riset modern membuktikan kandungan
                silika organik tertinggi di alam dan antioksidan luar biasa dalam air bambu.
              </p>
            </div>
          </Reveal>

          <Reveal delay={0.3}>
            <p className="mx-auto mt-6 w-fit rounded-2xl border border-gold/30 bg-gold/10 px-6 py-3 font-serif text-lg font-bold text-gold sm:text-xl">
              Jadilah orang yang beruntung — Anda kini tahu rahasia besar ini. 🌿
            </p>
          </Reveal>
        </div>

        {/* 10 Alasan */}
        <Reveal delay={0.15} className="mx-auto mt-14 max-w-3xl text-center">
          <h3 className="font-serif text-2xl font-bold text-white sm:text-3xl">
            10 Alasan Kenapa Anda <span className="text-shimmer-gold">Harus Minum Air Bambu</span>
          </h3>
          <p className="mt-2 text-sm text-emerald-50/70 sm:text-base">
            Dari rahasia kuno hingga pembuktian ilmiah modern.
          </p>
        </Reveal>

        <StaggerContainer className="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-5" stagger={0.07}>
          {REASONS.map((r, i) => (
            <StaggerItem key={r.title}>
              <motion.div
                whileHover={{ y: -6, scale: 1.02 }}
                className="group relative h-full overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur transition-colors duration-300 hover:border-gold/40 hover:bg-white/10"
              >
                <span aria-hidden className="absolute -right-2 -top-3 font-serif text-6xl font-black text-white/5 transition-colors duration-300 group-hover:text-gold/20">
                  {String(i + 1).padStart(2, '0')}
                </span>
                <span className="relative inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gold/15 font-serif text-sm font-bold text-gold">
                  {i + 1}
                </span>
                <h4 className="relative mt-3 font-serif text-base font-bold leading-snug text-white">
                  {r.title}
                </h4>
                <p className="relative mt-1.5 text-xs leading-relaxed text-emerald-50/70 sm:text-sm">
                  {r.text}
                </p>
              </motion.div>
            </StaggerItem>
          ))}
        </StaggerContainer>

        {!compact && (
          <Reveal delay={0.2} className="mx-auto mt-12 flex max-w-2xl flex-col items-center gap-4 text-center">
            <div className="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs font-semibold uppercase tracking-wider text-emerald-50/60">
              <span className="inline-flex items-center gap-1.5"><Droplets className="h-4 w-4 text-teal-300" aria-hidden /> Silika tertinggi</span>
              <span className="inline-flex items-center gap-1.5"><Leaf className="h-4 w-4 text-teal-300" aria-hidden /> 100% alami</span>
              <span className="inline-flex items-center gap-1.5"><BookOpenText className="h-4 w-4 text-teal-300" aria-hidden /> Warisan ribuan tahun</span>
              <span className="inline-flex items-center gap-1.5"><ShieldCheck className="h-4 w-4 text-teal-300" aria-hidden /> Riset modern</span>
            </div>
            <Button asChild size="lg" className="rounded-full bg-gradient-to-r from-gold to-amber-400 px-8 text-emerald-950 shadow-xl shadow-amber-900/30 hover:-translate-y-1">
              <a href={waLink('Halo! Saya ingin mencoba air bambu — rahasia awet muda kaisar China 🌿')} target="_blank" rel="noopener noreferrer external" className="gap-2">
                <Sparkles className="h-5 w-5" aria-hidden />
                Mulai Ritual Sehat Anda
              </a>
            </Button>
          </Reveal>
        )}
      </div>
    </section>
  );
}

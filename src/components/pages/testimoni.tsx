'use client';

import { Star, Quote, MapPin, BadgeCheck } from 'lucide-react';
import { motion } from 'framer-motion';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal, StaggerContainer, StaggerItem } from '@/components/site/reveal';
import { CtaBand } from '@/components/site/cta-band';

const TESTIMONIALS = [
  {
    name: 'Ibu Ratna Dewi',
    role: 'Ibu Rumah Tangga',
    city: 'Bandung',
    rating: 5,
    text: 'Sudah 2 tahun langganan galon air bambu suling untuk keluarga. Anak saya yang sering panas dalam jadi jarang mengeluh, dan rasanya memang segar banget — beda dari air biasa.',
  },
  {
    name: 'Ki Darma Saputra',
    role: 'Tabib Tradisional',
    city: 'Tasikmalaya',
    rating: 5,
    text: 'Sebagai tabib, kualitas sadapan segar itu mutlak. Air Bambu Tabib dari BWN selalu tiba segar dengan segel rapi. Teknik mata tunas mereka benar — ini sadapan sejati.',
  },
  {
    name: 'Andi Prasetyo',
    role: 'Pemilik Kafe Sehat',
    city: 'Yogyakarta',
    rating: 5,
    text: 'Kami pakai air bambu untuk menu signature "Bamboo Infuse". Pelanggan suka ceritanya, dan pasokan BWN konsisten tiap minggu. Margin bagus karena harga grosir transparan.',
  },
  {
    name: 'Hj. Siti Maryam',
    role: 'Penderita Diabetes',
    city: 'Surabaya',
    rating: 5,
    text: 'Dokter saya sarankan kurangi minuman manis. Air bambu jadi teman setia — rasanya tidak hambar tapi juga tidak naikkan gula darah saya. Terima kasih BWN, pengiriman ke Surabaya juga cepat.',
  },
  {
    name: 'Ir. Bambang Wijaya',
    role: 'Kontraktor Konstruksi',
    city: 'Semarang',
    rating: 5,
    text: 'Proyek rumah bambu 120 batang Petung — semua lurus, kering, dan sesuai spesifikasi. Kargo tiba utuh dengan segel kayu. Sudah 3 proyek pakai BWN, tidak pernah kecewa.',
  },
  {
    name: 'Dra. Lusiana Hakim',
    role: 'Pemilik Klinik Terapi',
    city: 'Jakarta',
    rating: 5,
    text: 'Klinik kami mengintegrasikan terapi minum air bambu. Kontrak pasokan bulanan BWN sangat profesional — jadwal selalu tepat dan ada laporan mutu per batch.',
  },
  {
    name: 'Made Wirawan',
    role: 'Pengrajin Furniture',
    city: 'Denpasar',
    rating: 5,
    text: 'Bambu Wulung-nya benar-benar premium — warna hitam pekat, kilau alami, bebas kutu. Kerajinan saya kini diekspor ke Bali-Australia route. BWN pemasok yang bisa diandalkan.',
  },
  {
    name: 'Rina Kartika',
    role: 'Content Creator Kesehatan',
    city: 'Medan',
    rating: 5,
    text: 'Awalnya skeptis, setelah riset untuk konten saya jadi pengguna rutin. Kulit dan rambut terasa bedanya. Pengiriman ke Medan aman, packing kuat.',
  },
];

function Stars({ n }: { n: number }) {
  return (
    <div className="flex gap-0.5" aria-label={`Rating ${n} dari 5 bintang`}>
      {Array.from({ length: 5 }).map((_, i) => (
        <Star
          key={i}
          className={`h-4 w-4 ${i < n ? 'fill-gold text-gold' : 'text-muted-foreground/30'}`}
          aria-hidden
        />
      ))}
    </div>
  );
}

/** Halaman 23/25 — Testimoni */
export function TestimoniPage() {
  return (
    <>
      <PageHero slug="testimoni" />

      <PageSection bg="light">
        <SectionHeading
          eyebrow="Kepercayaan Sejak 2007"
          title="Cerita Mereka yang Sudah"
          highlight="Merasakannya"
          description="Dari dapur keluarga hingga klinik dan proyek konstruksi — 18+ tahun membangun kepercayaan di seluruh Indonesia."
        />

        <StaggerContainer className="mt-14 grid gap-6 md:grid-cols-2">
          {TESTIMONIALS.map((t) => (
            <StaggerItem key={t.name}>
              <motion.div
                whileHover={{ y: -5 }}
                className="relative h-full rounded-3xl border border-primary/10 bg-white p-7 shadow-sm transition-shadow hover:shadow-xl hover:shadow-emerald-900/10"
              >
                <Quote className="absolute right-6 top-6 h-8 w-8 text-primary/10" aria-hidden />
                <Stars n={t.rating} />
                <p className="mt-4 text-sm leading-relaxed text-foreground/85 sm:text-base">“{t.text}”</p>
                <div className="mt-5 flex items-center gap-3 border-t border-border pt-4">
                  <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary/10 font-serif text-lg font-bold text-primary">
                    {t.name.replace(/^(Ibu|Hj\.|Ir\.|Dra\.|Ki)\s+/, '').charAt(0)}
                  </span>
                  <div>
                    <p className="flex items-center gap-1.5 text-sm font-bold text-foreground">
                      {t.name}
                      <BadgeCheck className="h-4 w-4 text-primary" aria-label="Pelanggan terverifikasi" />
                    </p>
                    <p className="flex items-center gap-1.5 text-xs text-muted-foreground">
                      {t.role} • <MapPin className="h-3 w-3" aria-hidden /> {t.city}
                    </p>
                  </div>
                </div>
              </motion.div>
            </StaggerItem>
          ))}
        </StaggerContainer>

        <Reveal className="mx-auto mt-12 max-w-3xl">
          <div className="grid gap-4 rounded-3xl border border-primary/15 bg-secondary/50 p-7 text-center sm:grid-cols-3">
            <div>
              <p className="font-serif text-3xl font-bold text-primary">18+</p>
              <p className="mt-1 text-xs text-muted-foreground">Tahun melayani sejak 2007</p>
            </div>
            <div>
              <p className="font-serif text-3xl font-bold text-primary">38</p>
              <p className="mt-1 text-xs text-muted-foreground">Provinsi terjangkau</p>
            </div>
            <div>
              <p className="font-serif text-3xl font-bold text-primary">4,9/5</p>
              <p className="mt-1 text-xs text-muted-foreground">Rata-rata kepuasan pelanggan</p>
            </div>
          </div>
        </Reveal>
      </PageSection>

      <CtaBand
        waMessage="Halo! Saya ingin bergabung sebagai pelanggan air bambu BWN."
        title="Gabung Bersama Ribuan Pelanggan Puas"
        description="Mulai dari satu galon untuk keluarga, hingga kontrak pasokan untuk bisnis Anda."
      />
    </>
  );
}

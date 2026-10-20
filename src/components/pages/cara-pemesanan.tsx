'use client';

import { Search, MessageCircle, CreditCard, PackageCheck, Clock3, ShieldCheck } from 'lucide-react';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal } from '@/components/site/reveal';
import { QuoteForm } from '@/components/site/quote-form';
import { CtaBand } from '@/components/site/cta-band';
import { waLink, COMPANY } from '@/lib/company';

const STEPS = [
  {
    icon: Search,
    title: '1. Pilih Produk & Volume',
    text: 'Tentukan jenis (air bambu suling/tabib atau bambu batang), kemasan, dan jumlah. Ragu? Tim kami bantu rekomendasikan.',
  },
  {
    icon: MessageCircle,
    title: '2. Konfirmasi via WhatsApp',
    text: `Chat WhatsApp ${COMPANY.whatsappDisplay} atau isi formulir di bawah. Sertakan kota kirim — kami balas dengan harga final & estimasi tiba.`,
  },
  {
    icon: CreditCard,
    title: '3. Pembayaran Aman',
    text: 'Transfer bank, QRIS, atau COD area tertentu. Untuk kontrak besar: DP 50%, pelunasan saat barang tiba.',
  },
  {
    icon: PackageCheck,
    title: '4. Kirim & Terima',
    text: 'Pesanan dikemas food-grade/kargo aman, berangkat dengan resi terlacak — sampai di 34 provinsi Indonesia.',
  },
];

const PAYMENT_METHODS = ['Transfer Bank (BCA, BRI, Mandiri, BNI)', 'QRIS (semua e-wallet)', 'COD area Jabodetabek & Bandung', 'Invoice korporat / tempoh'];
const FAQS_QUICK = [
  { q: 'Berapa lama pesanan diproses?', a: 'Pesanan reguler diproses hari yang sama (sebelum 15.00 WIB). Air Bambu Tabib dipanen subuh berikutnya. Kargo besar mengikuti jadwal armada 1–3 hari persiapan.' },
  { q: 'Apakah ada minimum order grosir?', a: 'Harga grosir berlaku mulai 10 unit (jerigen/karton) atau 10 batang bambu. Di bawah itu tetap kami layani dengan harga retail.' },
  { q: 'Bisakah jadwal kirim rutin bulanan?', a: 'Bisa. Kontrak pasokan rutin memberi harga terbaik + prioritas armada — populer untuk klinik terapi, distributor, dan proyek konstruksi.' },
];

/** Halaman 22/25 — Cara Pemesanan */
export function CaraPemesananPage() {
  return (
    <>
      <PageHero slug="cara-pemesanan" />

      <PageSection bg="light">
        <SectionHeading
          eyebrow="4 Langkah Mudah"
          title="Dari Order ke"
          highlight="Depan Pintu Anda"
        />
        <div className="mx-auto mt-12 grid max-w-5xl gap-5 sm:grid-cols-2 lg:grid-cols-4">
          {STEPS.map((s, i) => (
            <Reveal key={s.title} delay={i * 0.1}>
              <div className="relative h-full rounded-3xl border border-primary/10 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg hover:shadow-emerald-900/10">
                <span className="absolute -top-3 left-6 rounded-full bg-gold px-3 py-1 text-xs font-bold text-emerald-950 shadow">
                  Langkah {i + 1}
                </span>
                <span className="mt-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                  <s.icon className="h-6 w-6" aria-hidden />
                </span>
                <h3 className="mt-4 font-serif text-lg font-bold text-foreground">{s.title.replace(/^\d\. /, '')}</h3>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{s.text}</p>
              </div>
            </Reveal>
          ))}
        </div>
      </PageSection>

      {/* Form + info pembayaran */}
      <PageSection bg="cream">
        <div className="grid gap-10 lg:grid-cols-5">
          <div className="lg:col-span-3">
            <QuoteForm />
          </div>

          <div className="space-y-6 lg:col-span-2">
            <Reveal direction="left">
              <div className="rounded-3xl border border-primary/15 bg-white p-6 shadow-sm">
                <h3 className="flex items-center gap-2 font-serif text-lg font-bold text-foreground">
                  <CreditCard className="h-5 w-5 text-primary" aria-hidden />
                  Metode Pembayaran
                </h3>
                <ul className="mt-4 space-y-2.5">
                  {PAYMENT_METHODS.map((m) => (
                    <li key={m} className="flex items-center gap-2.5 text-sm text-foreground/85">
                      <ShieldCheck className="h-4 w-4 shrink-0 text-primary" aria-hidden />
                      {m}
                    </li>
                  ))}
                </ul>
              </div>
            </Reveal>

            <Reveal direction="left" delay={0.1}>
              <div className="rounded-3xl border border-primary/15 bg-white p-6 shadow-sm">
                <h3 className="flex items-center gap-2 font-serif text-lg font-bold text-foreground">
                  <Clock3 className="h-5 w-5 text-primary" aria-hidden />
                  Estimasi Pengiriman
                </h3>
                <ul className="mt-4 space-y-2.5 text-sm text-foreground/85">
                  <li className="flex justify-between gap-3"><span>Jabodetabek & Jawa Barat</span><strong className="text-primary">1–3 hari</strong></li>
                  <li className="flex justify-between gap-3"><span>Pulau Jawa lainnya</span><strong className="text-primary">2–4 hari</strong></li>
                  <li className="flex justify-between gap-3"><span>Sumatera/Kalimantan/Sulawesi</span><strong className="text-primary">3–7 hari</strong></li>
                  <li className="flex justify-between gap-3"><span>Maluku, Papua & NTT</span><strong className="text-primary">2–5 hari (udara)</strong></li>
                </ul>
              </div>
            </Reveal>

            <Reveal direction="left" delay={0.2}>
              <div className="rounded-3xl bg-primary p-6 text-primary-foreground shadow-lg shadow-emerald-900/20">
                <h3 className="font-serif text-lg font-bold">Lebih Suka Chat Langsung?</h3>
                <p className="mt-2 text-sm text-primary-foreground/85">
                  Balasan cepat di jam kerja {COMPANY.operationalHours}.
                </p>
                <a
                  href={waLink()}
                  target="_blank"
                  rel="noopener noreferrer external"
                  className="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-full bg-white px-6 py-3.5 text-sm font-bold text-emerald-800 transition-all hover:-translate-y-0.5 hover:shadow-lg"
                >
                  <MessageCircle className="h-4.5 w-4.5" aria-hidden />
                  WhatsApp {COMPANY.whatsappDisplay}
                </a>
              </div>
            </Reveal>
          </div>
        </div>
      </PageSection>

      {/* FAQ singkat */}
      <PageSection bg="light">
        <SectionHeading eyebrow="Info Cepat" title="Pertanyaan Seputar" highlight="Pemesanan" />
        <div className="mx-auto mt-10 max-w-3xl space-y-4">
          {FAQS_QUICK.map((f, i) => (
            <Reveal key={f.q} delay={i * 0.08}>
              <div className="rounded-2xl border border-primary/10 bg-white p-5 shadow-sm">
                <p className="font-semibold text-foreground">{f.q}</p>
                <p className="mt-1.5 text-sm leading-relaxed text-muted-foreground">{f.a}</p>
              </div>
            </Reveal>
          ))}
          <p className="pt-2 text-center text-sm text-muted-foreground">
            Masih ada pertanyaan? Lihat <a href="#/faq" className="font-semibold text-primary underline decoration-gold underline-offset-4 hover:text-primary/80">halaman FAQ lengkap →</a>
          </p>
        </div>
      </PageSection>

      <CtaBand />
    </>
  );
}

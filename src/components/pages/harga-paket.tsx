'use client';

import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Badge } from '@/components/ui/badge';
import { Info, MessageCircle } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal } from '@/components/site/reveal';
import { CtaBand } from '@/components/site/cta-band';
import { PRICING, waLink } from '@/lib/company';

const BULK_TIERS = [
  { label: 'Retail', desc: '1–9 unit', benefit: 'Harga normal, tanpa minimum' },
  { label: 'Grosir', desc: '10 unit ke atas', benefit: 'Diskon bertingkat hingga 15%' },
  { label: 'Korporat/Kontrak', desc: 'Bulanan / proyek', benefit: 'Harga terbaik + jadwal pasokan tetap' },
];

function PriceTable({ rows }: { rows: readonly { product: string; price: string; unit: string; note: string }[] }) {
  return (
    <div className="overflow-hidden rounded-3xl border border-primary/10 bg-white shadow-lg shadow-emerald-900/5">
      <Table>
        <TableHeader>
          <TableRow className="bg-primary text-primary-foreground hover:bg-primary">
            <TableHead className="text-primary-foreground">Produk</TableHead>
            <TableHead className="text-primary-foreground">Harga Indikatif</TableHead>
            <TableHead className="hidden text-primary-foreground sm:table-cell">Satuan</TableHead>
            <TableHead className="hidden text-primary-foreground md:table-cell">Keterangan</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {rows.map((r) => (
            <TableRow key={r.product} className="transition-colors hover:bg-primary/5">
              <TableCell className="font-semibold text-foreground">{r.product}</TableCell>
              <TableCell className="font-bold text-primary">{r.price}</TableCell>
              <TableCell className="hidden text-muted-foreground sm:table-cell">{r.unit}</TableCell>
              <TableCell className="hidden text-muted-foreground md:table-cell">{r.note}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  );
}

/** Halaman 21/25 — Harga & Paket */
export function HargaPaketPage() {
  return (
    <>
      <PageHero slug="harga-paket" />

      <PageSection bg="light">
        <SectionHeading
          eyebrow="Daftar Harga"
          title="Transparan dari"
          highlight="Retail hingga Kargo"
          description="Harga indikatif 2025 — harga final menyesuaikan volume, lokasi kirim, dan fluktuasi panen."
        />

        <Reveal className="mx-auto mt-12 max-w-5xl">
          <Tabs defaultValue="air-bambu" className="w-full">
            <TabsList className="mx-auto flex h-auto flex-wrap justify-center gap-1 rounded-2xl bg-secondary/60 p-1.5">
              <TabsTrigger value="air-bambu" className="rounded-xl px-5 py-2.5 data-[state=active]:bg-primary data-[state=active]:text-primary-foreground">
                💧 Air Bambu Suling
              </TabsTrigger>
              <TabsTrigger value="air-tabib" className="rounded-xl px-5 py-2.5 data-[state=active]:bg-primary data-[state=active]:text-primary-foreground">
                🌿 Air Bambu Tabib
              </TabsTrigger>
              <TabsTrigger value="bambu" className="rounded-xl px-5 py-2.5 data-[state=active]:bg-primary data-[state=active]:text-primary-foreground">
                🎍 Bambu Batang
              </TabsTrigger>
            </TabsList>

            <TabsContent value="air-bambu" className="mt-6">
              <PriceTable rows={PRICING.airBambu} />
            </TabsContent>
            <TabsContent value="air-tabib" className="mt-6">
              <PriceTable rows={PRICING.airTabib} />
            </TabsContent>
            <TabsContent value="bambu" className="mt-6">
              <PriceTable rows={PRICING.bambu} />
            </TabsContent>
          </Tabs>
        </Reveal>

        <Reveal delay={0.15} className="mx-auto mt-6 max-w-5xl">
          <p className="flex items-start gap-2 text-xs leading-relaxed text-muted-foreground">
            <Info className="mt-0.5 h-4 w-4 shrink-0 text-gold" aria-hidden />
            Harga belum termasuk ongkos kirim. Untuk volume besar, kota tujuan, dan kontrak rutin —
            harga final diberikan dalam penawaran resmi maksimal 1×24 jam.
          </p>
        </Reveal>
      </PageSection>

      {/* Tingkat grosir */}
      <PageSection bg="cream">
        <SectionHeading
          eyebrow="Semakin Banyak, Semakin Hemat"
          title="Tiga Tingkat"
          highlight="Harga Khusus"
        />
        <div className="mx-auto mt-12 grid max-w-4xl gap-5 lg:grid-cols-3">
          {BULK_TIERS.map((t, i) => (
            <Reveal key={t.label} delay={i * 0.1}>
              <div className={`h-full rounded-3xl border p-7 text-center shadow-sm transition-all duration-300 hover:-translate-y-1.5 ${i === 2 ? 'border-gold/50 bg-gold/10 shadow-lg shadow-amber-900/10' : 'border-primary/10 bg-white'}`}>
                {i === 2 && <Badge className="mb-3 bg-gold text-emerald-950 hover:bg-gold">Terbaik</Badge>}
                <h3 className="font-serif text-xl font-bold text-foreground">{t.label}</h3>
                <p className="mt-1 text-sm font-semibold text-primary">{t.desc}</p>
                <p className="mt-3 text-sm leading-relaxed text-muted-foreground">{t.benefit}</p>
              </div>
            </Reveal>
          ))}
        </div>

        <Reveal delay={0.2} className="mx-auto mt-10 max-w-3xl text-center">
          <Button asChild size="lg" className="rounded-full bg-emerald-600 px-8 py-6 text-white hover:bg-emerald-700">
            <a href={waLink('Halo! Saya ingin minta penawaran harga final sesuai volume & kota saya.')} target="_blank" rel="noopener noreferrer external" className="gap-2">
              <MessageCircle className="h-5 w-5" aria-hidden />
              Minta Penawaran Final
            </a>
          </Button>
        </Reveal>
      </PageSection>

      <CtaBand />
    </>
  );
}

'use client';

import { motion } from 'framer-motion';
import { CheckCircle2, MessageCircle, Package, ShieldCheck, Droplets, TreePine } from 'lucide-react';
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from '@/components/ui/accordion';
import { Button } from '@/components/ui/button';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal, StaggerContainer, StaggerItem } from '@/components/site/reveal';
import { CtaBand } from '@/components/site/cta-band';
import { waLink, SITE_URL } from '@/lib/company';

export type ProductFaq = { q: string; a: string };

export type ProductDetail = {
  slug: string;
  image: string;
  imageAlt: string;
  tagline: string;
  description: string[];
  sizes: { name: string; note: string }[];
  features: string[];
  specs: { label: string; value: string }[];
  uses: { icon?: 'droplets' | 'tree'; title: string; text: string }[];
  howTo: string[];
  faqs: ProductFaq[];
  related: { label: string; slug: string }[];
  waMessage: string;
};

/** JSON-LD Product per halaman produk */
function ProductJsonLd({ p }: { p: ProductDetail }) {
  const data = {
    '@context': 'https://schema.org',
    '@type': 'Product',
    '@id': `${SITE_URL}/#/${p.slug}#product`,
    name: p.tagline,
    description: p.description[0],
    image: `${SITE_URL}${p.image}`,
    brand: { '@type': 'Brand', name: 'Bambu Water Nusantara' },
    offers: {
      '@type': 'AggregateOffer',
      priceCurrency: 'IDR',
      availability: 'https://schema.org/InStock',
      offerCount: p.sizes.length,
    },
  };
  return <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(data) }} />;
}

/** Template halaman produk (3 halaman produk) */
export function ProductPageTemplate({ p }: { p: ProductDetail }) {
  return (
    <>
      <ProductJsonLd p={p} />
      <PageHero slug={p.slug} />

      {/* Produk intro */}
      <PageSection bg="light">
        <div className="grid items-center gap-12 lg:grid-cols-2">
          <Reveal direction="right">
            <div className="group relative overflow-hidden rounded-3xl shadow-2xl shadow-emerald-900/20">
              { }
              <img
                src={p.image}
                alt={p.imageAlt}
                className="aspect-[4/3] w-full object-cover transition-transform duration-700 group-hover:scale-105"
                loading="lazy"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-emerald-950/50 via-transparent to-transparent" />
            </div>
            {/* Specs */}
            <div className="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
              {p.specs.map((s) => (
                <div key={s.label} className="rounded-2xl border border-primary/10 bg-white p-3 text-center shadow-sm">
                  <p className="text-[0.65rem] font-bold uppercase tracking-wider text-muted-foreground">{s.label}</p>
                  <p className="mt-1 text-sm font-bold text-primary">{s.value}</p>
                </div>
              ))}
            </div>
          </Reveal>

          <Reveal direction="left">
            <h2 className="font-serif text-3xl font-bold leading-tight text-foreground sm:text-4xl">
              {p.tagline}
            </h2>
            <div className="mt-4 space-y-4 text-base leading-relaxed text-muted-foreground">
              {p.description.map((d, i) => (
                <p key={i}>{d}</p>
              ))}
            </div>

            {/* Fitur */}
            <ul className="mt-6 grid gap-2.5 sm:grid-cols-2">
              {p.features.map((f) => (
                <li key={f} className="flex items-center gap-2.5 text-sm font-medium text-foreground">
                  <CheckCircle2 className="h-4.5 w-4.5 shrink-0 text-primary" aria-hidden />
                  {f}
                </li>
              ))}
            </ul>

            {/* Kemasan */}
            <div className="mt-7 rounded-2xl border border-primary/15 bg-secondary/50 p-5">
              <p className="flex items-center gap-2 text-sm font-bold text-foreground">
                <Package className="h-4.5 w-4.5 text-primary" aria-hidden />
                Pilihan Kemasan
              </p>
              <div className="mt-3 flex flex-wrap gap-2">
                {p.sizes.map((s) => (
                  <span
                    key={s.name}
                    title={s.note}
                    className="rounded-full border border-primary/20 bg-white px-3.5 py-1.5 text-xs font-semibold text-primary"
                  >
                    {s.name}
                  </span>
                ))}
              </div>
            </div>

            <div className="mt-7 flex flex-col gap-3 sm:flex-row">
              <Button asChild size="lg" className="flex-1 rounded-full bg-emerald-600 py-6 text-white shadow-lg hover:bg-emerald-700">
                <a href={waLink(p.waMessage)} target="_blank" rel="noopener noreferrer external" className="gap-2">
                  <MessageCircle className="h-5 w-5" aria-hidden />
                  Pesan via WhatsApp
                </a>
              </Button>
              <Button asChild size="lg" variant="outline" className="flex-1 rounded-full border-primary/40 py-6 text-primary hover:bg-primary/10">
                <a href="#/harga-paket">Lihat Harga</a>
              </Button>
            </div>
          </Reveal>
        </div>
      </PageSection>

      {/* Kegunaan */}
      <PageSection bg="cream">
        <SectionHeading
          eyebrow="Untuk Apa"
          title="Kegunaan &"
          highlight="Manfaatnya"
        />
        <StaggerContainer className="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {p.uses.map((u) => (
            <StaggerItem key={u.title}>
              <div className="h-full rounded-3xl border border-primary/10 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1.5 hover:shadow-lg hover:shadow-emerald-900/10">
                <span className="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                  {u.icon === 'tree' ? <TreePine className="h-6 w-6" aria-hidden /> : <Droplets className="h-6 w-6" aria-hidden />}
                </span>
                <h3 className="mt-4 font-serif text-lg font-bold text-foreground">{u.title}</h3>
                <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{u.text}</p>
              </div>
            </StaggerItem>
          ))}
        </StaggerContainer>
      </PageSection>

      {/* Cara pakai + FAQ */}
      <PageSection bg="light">
        <div className="grid gap-12 lg:grid-cols-2">
          <Reveal direction="right">
            <h2 className="font-serif text-2xl font-bold text-foreground sm:text-3xl">
              Panduan <span className="text-primary">Penggunaan</span>
            </h2>
            <ol className="mt-6 space-y-4">
              {p.howTo.map((h, i) => (
                <li key={i} className="flex items-start gap-4 rounded-2xl border border-primary/10 bg-white p-4 shadow-sm">
                  <span className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary text-sm font-bold text-primary-foreground">
                    {i + 1}
                  </span>
                  <p className="text-sm leading-relaxed text-muted-foreground">{h}</p>
                </li>
              ))}
            </ol>
            <p className="mt-5 inline-flex items-center gap-2 text-xs text-muted-foreground">
              <ShieldCheck className="h-4 w-4 text-primary" aria-hidden />
              Produk food-grade, diproses higienis, tanpa pengawet.
            </p>
          </Reveal>

          <Reveal direction="left">
            <h2 className="font-serif text-2xl font-bold text-foreground sm:text-3xl">
              Pertanyaan <span className="text-primary">Umum</span>
            </h2>
            <Accordion type="single" collapsible className="mt-6 space-y-3">
              {p.faqs.map((f, i) => (
                <AccordionItem key={i} value={`pf-${i}`} className="rounded-2xl border border-primary/10 bg-white px-5 shadow-sm">
                  <AccordionTrigger className="text-left text-sm font-semibold text-foreground hover:text-primary sm:text-base">
                    {f.q}
                  </AccordionTrigger>
                  <AccordionContent className="text-sm leading-relaxed text-muted-foreground">
                    {f.a}
                  </AccordionContent>
                </AccordionItem>
              ))}
            </Accordion>
          </Reveal>
        </div>
      </PageSection>

      {/* Produk terkait */}
      <PageSection bg="cream" className="!py-12">
        <p className="text-center text-sm font-semibold uppercase tracking-[0.18em] text-muted-foreground">Produk Lainnya</p>
        <div className="mx-auto mt-5 flex max-w-3xl flex-wrap justify-center gap-3">
          {p.related.map((r) => (
            <motion.a
              key={r.slug}
              href={`#/${r.slug}`}
              whileHover={{ y: -3 }}
              className="rounded-full border border-primary/20 bg-white px-6 py-3 text-sm font-semibold text-primary shadow-sm transition-colors hover:bg-primary hover:text-primary-foreground"
            >
              {r.label} →
            </motion.a>
          ))}
        </div>
      </PageSection>

      <CtaBand />
    </>
  );
}

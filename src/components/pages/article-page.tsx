'use client';

import { motion } from 'framer-motion';
import { Clock, CalendarClock, ListTree, BookOpen, ArrowRight, MessageCircle, AlertTriangle } from 'lucide-react';
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from '@/components/ui/accordion';
import { Button } from '@/components/ui/button';
import { Reveal } from '@/components/site/reveal';
import { CtaBand } from '@/components/site/cta-band';
import { getPageMeta } from '@/lib/pages-registry';
import { getArticle, type ArticleData } from '@/lib/articles';
import { SITE_URL, waLink } from '@/lib/company';
import { PageHero, PageSection } from '@/components/site/page-hero';

/** JSON-LD BlogPosting + FAQPage per artikel */
function ArticleJsonLd({ article }: { article: ArticleData }) {
  const meta = getPageMeta(article.slug);
  const url = `${SITE_URL}/#/${article.slug}`;
  const blogPosting = {
    '@context': 'https://schema.org',
    '@type': 'BlogPosting',
    '@id': `${url}#article`,
    headline: article.title,
    description: article.metaDescription,
    image: `${SITE_URL}${article.heroImage}`,
    inLanguage: 'id-ID',
    dateModified: meta.updated,
    author: { '@type': 'Organization', name: 'PT Bambu Water Nusantara' },
    publisher: { '@type': 'Organization', name: 'PT Bambu Water Nusantara' },
    mainEntityOfPage: url,
    keywords: article.keywords.join(', '),
  };
  const faqPage = {
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: article.faqs.map((f) => ({
      '@type': 'Question',
      name: f.q,
      acceptedAnswer: { '@type': 'Answer', text: f.a },
    })),
  };
  return (
    <>
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(blogPosting) }} />
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqPage) }} />
    </>
  );
}

/** Halaman artikel (7/25 s.d. 13/25) — dirender dari data ArticleData */
export function ArticlePage({ slug }: { slug: string }) {
  const article = getArticle(slug);

  if (!article) {
    return (
      <PageSection bg="light">
        <p className="text-center text-muted-foreground">Artikel tidak ditemukan.</p>
      </PageSection>
    );
  }

  return (
    <>
      <ArticleJsonLd article={article} />

      {/* Hero artikel */}
      <PageHero slug={slug} showBreadcrumb={true} />

      {/* Baris meta */}
      <div className="border-b border-border bg-secondary/40">
        <div className="mx-auto flex max-w-4xl flex-wrap items-center justify-center gap-x-6 gap-y-2 px-4 py-4 text-xs text-muted-foreground sm:text-sm">
          <span className="inline-flex items-center gap-1.5">
            <BookOpen className="h-4 w-4 text-primary" aria-hidden />
            {article.category}
          </span>
          <span className="inline-flex items-center gap-1.5">
            <Clock className="h-4 w-4 text-primary" aria-hidden />
            {article.readMinutes} menit baca
          </span>
          <span className="inline-flex items-center gap-1.5">
            <CalendarClock className="h-4 w-4 text-primary" aria-hidden />
            Diperbarui {article.updated}
          </span>
        </div>
      </div>

      {/* Gambar hero artikel */}
      <PageSection bg="light" className="!py-10">
        <Reveal className="mx-auto max-w-4xl">
          <div className="overflow-hidden rounded-3xl shadow-2xl shadow-emerald-900/15">
            { }
            <img
              src={article.heroImage}
              alt={article.heroImageAlt}
              className="aspect-[21/9] w-full object-cover"
              loading="eager"
            />
          </div>
        </Reveal>
      </PageSection>

      {/* Intro + poin utama */}
      <PageSection bg="light" className="!pt-2">
        <div className="mx-auto max-w-3xl">
          <div className="space-y-5 text-base leading-relaxed text-muted-foreground sm:text-lg">
            {article.intro.map((p, i) => (
              <Reveal key={i} delay={i * 0.08}>
                <p>{p}</p>
              </Reveal>
            ))}
          </div>

          <Reveal delay={0.15} className="mt-10">
            <div className="rounded-3xl border border-primary/15 bg-secondary/50 p-6 sm:p-8">
              <h2 className="flex items-center gap-2 font-serif text-xl font-bold text-foreground">
                <ListTree className="h-5 w-5 text-primary" aria-hidden />
                Poin Utama
              </h2>
              <ul className="mt-4 space-y-2.5">
                {article.keyPoints.map((kp) => (
                  <li key={kp} className="flex items-start gap-2.5 text-sm leading-relaxed text-foreground/85 sm:text-base">
                    <span className="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-gold" aria-hidden />
                    {kp}
                  </li>
                ))}
              </ul>
            </div>
          </Reveal>
        </div>
      </PageSection>

      {/* Section isi */}
      <PageSection bg="cream">
        <div className="mx-auto max-w-3xl space-y-14">
          {article.sections.map((sec, i) => (
            <Reveal key={sec.heading} delay={0.05}>
              <article>
                <h2 className="font-serif text-2xl font-bold leading-snug text-foreground sm:text-3xl">
                  <span className="mr-2 text-primary/30">{String(i + 1).padStart(2, '0')}</span>
                  {sec.heading}
                </h2>
                <div className="mt-4 space-y-4 text-base leading-relaxed text-muted-foreground">
                  {sec.paragraphs.map((p, j) => (
                    <p key={j}>{p}</p>
                  ))}
                </div>
                {sec.list && (
                  <ul className="mt-5 space-y-3">
                    {sec.listTitle && (
                      <p className="font-semibold text-foreground">{sec.listTitle}</p>
                    )}
                    {sec.list.map((li, j) => (
                      <li key={j} className="flex items-start gap-3 rounded-2xl border border-primary/10 bg-white p-4 shadow-sm">
                        <ArrowRight className="mt-0.5 h-4.5 w-4.5 shrink-0 text-gold" aria-hidden />
                        <div>
                          {li.title && <p className="font-semibold text-foreground">{li.title}</p>}
                          <p className="text-sm leading-relaxed text-muted-foreground">{li.text}</p>
                        </div>
                      </li>
                    ))}
                  </ul>
                )}
                {sec.callout && (
                  <div className="mt-5 flex items-start gap-3 rounded-2xl border-l-4 border-gold bg-gold/10 p-5">
                    <AlertTriangle className="mt-0.5 h-5 w-5 shrink-0 text-amber-700" aria-hidden />
                    <p className="text-sm leading-relaxed text-foreground">{sec.callout}</p>
                  </div>
                )}
              </article>
            </Reveal>
          ))}

          {/* Kesimpulan */}
          <Reveal>
            <div className="rounded-3xl bg-primary p-7 text-primary-foreground sm:p-8">
              <h2 className="font-serif text-2xl font-bold">Kesimpulan</h2>
              <p className="mt-3 text-sm leading-relaxed text-primary-foreground/90 sm:text-base">
                {article.conclusion}
              </p>
            </div>
          </Reveal>
        </div>
      </PageSection>

      {/* FAQ artikel */}
      <PageSection bg="light">
        <div className="mx-auto max-w-3xl">
          <h2 className="text-center font-serif text-2xl font-bold text-foreground sm:text-3xl">
            Pertanyaan Umum <span className="text-primary">Terkait</span>
          </h2>
          <Accordion type="single" collapsible className="mt-8 space-y-3">
            {article.faqs.map((f, i) => (
              <AccordionItem
                key={i}
                value={`faq-${i}`}
                className="rounded-2xl border border-primary/10 bg-white px-5 shadow-sm"
              >
                <AccordionTrigger className="text-left text-sm font-semibold text-foreground hover:text-primary sm:text-base">
                  {f.q}
                </AccordionTrigger>
                <AccordionContent className="text-sm leading-relaxed text-muted-foreground">
                  {f.a}
                </AccordionContent>
              </AccordionItem>
            ))}
          </Accordion>
        </div>
      </PageSection>

      {/* Artikel terkait */}
      <PageSection bg="cream">
        <h2 className="text-center font-serif text-2xl font-bold text-foreground sm:text-3xl">
          Baca Juga <span className="text-primary">Artikel Lainnya</span>
        </h2>
        <div className="mx-auto mt-10 grid max-w-4xl gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {article.related
            .map((r) => ({ rel: getArticle(r), meta: getPageMeta(r) }))
            .filter((x) => x.rel)
            .map(({ rel, meta }) => (
              <motion.a
                key={rel!.slug}
                href={`#/${rel!.slug}`}
                whileHover={{ y: -6 }}
                className="group overflow-hidden rounded-3xl border border-primary/10 bg-white shadow-sm transition-shadow hover:shadow-xl hover:shadow-emerald-900/10"
              >
                <div className="relative aspect-[16/9] overflow-hidden">
                  { }
                  <img
                    src={meta.heroImage}
                    alt={rel!.heroImageAlt}
                    className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    loading="lazy"
                  />
                </div>
                <div className="p-5">
                  <span className="text-xs font-bold uppercase tracking-wider text-primary">{rel!.category}</span>
                  <h3 className="mt-1.5 font-serif text-base font-bold leading-snug text-foreground group-hover:text-primary">
                    {rel!.title}
                  </h3>
                  <p className="mt-2 line-clamp-2 text-xs leading-relaxed text-muted-foreground">{rel!.excerpt}</p>
                </div>
              </motion.a>
            ))}
        </div>
      </PageSection>

      {/* Referensi */}
      <PageSection bg="light" className="!py-10">
        <div className="mx-auto max-w-3xl">
          <h3 className="text-sm font-bold uppercase tracking-[0.18em] text-primary">Referensi</h3>
          <ol className="mt-3 list-decimal space-y-1.5 pl-5 text-xs leading-relaxed text-muted-foreground">
            {article.references.map((r) => (
              <li key={r}>{r}</li>
            ))}
          </ol>
          <p className="mt-4 text-xs leading-relaxed text-muted-foreground/80">
            Disclaimer: Konten ini bersifat edukatif dan bukan pengganti nasihat medis profesional.
          </p>
        </div>
      </PageSection>

      {/* CTA artikel */}
      <PageSection bg="cream" className="!py-10">
        <div className="mx-auto flex max-w-3xl flex-col items-center gap-5 rounded-3xl border border-primary/15 bg-white p-8 text-center shadow-lg shadow-emerald-900/5 sm:p-10">
          <h2 className="font-serif text-2xl font-bold text-foreground">Tertarik Mencoba Air Bambu?</h2>
          <p className="max-w-md text-sm leading-relaxed text-muted-foreground">
            Mulai dari botol kecil untuk keluarga, atau tanya paket grosir untuk praktik terapi Anda.
          </p>
          <div className="flex flex-col gap-3 sm:flex-row">
            <Button asChild className="rounded-full bg-emerald-600 px-7 py-6 text-white hover:bg-emerald-700">
              <a href={waLink(`Halo! Saya baca artikel "${article.title}" dan ingin mencoba produk air bambu.`)} target="_blank" rel="noopener noreferrer external" className="gap-2">
                <MessageCircle className="h-5 w-5" aria-hidden />
                Tanya via WhatsApp
              </a>
            </Button>
            <Button asChild variant="outline" className="rounded-full border-primary/40 px-7 py-6 text-primary hover:bg-primary/10">
              <a href="#/harga-paket">Lihat Harga & Paket</a>
            </Button>
          </div>
        </div>
      </PageSection>

      <CtaBand />
    </>
  );
}

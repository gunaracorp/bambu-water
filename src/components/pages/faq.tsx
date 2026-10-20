'use client';

import { HelpCircle, MessageCircle } from 'lucide-react';
import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from '@/components/ui/accordion';
import { Button } from '@/components/ui/button';
import { PageHero, PageSection } from '@/components/site/page-hero';
import { SectionHeading } from '@/components/site/section-heading';
import { Reveal } from '@/components/site/reveal';
import { CtaBand } from '@/components/site/cta-band';
import { FAQS, waLink, SITE_URL } from '@/lib/company';

/** JSON-LD FAQPage untuk halaman FAQ */
function FaqJsonLd() {
  const data = {
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    '@id': `${SITE_URL}/#/faq#faqpage`,
    mainEntity: FAQS.map((f) => ({
      '@type': 'Question',
      name: f.q,
      acceptedAnswer: { '@type': 'Answer', text: f.a },
    })),
  };
  return <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(data) }} />;
}

/** Halaman 24/25 — FAQ */
export function FaqPage() {
  return (
    <>
      <FaqJsonLd />
      <PageHero slug="faq" />

      <PageSection bg="light">
        <SectionHeading
          eyebrow="10 Pertanyaan Teratas"
          title="Semua yang Ingin Anda"
          highlight="Tanyakan"
          description="Dikurasi dari ribuan percakapan WhatsApp kami dengan pelanggan se-Indonesia."
        />

        <div className="mx-auto mt-12 max-w-3xl">
          <Accordion type="single" collapsible className="space-y-3">
            {FAQS.map((f, i) => (
              <Reveal key={f.q} delay={i * 0.04}>
                <AccordionItem
                  value={`faq-${i}`}
                  className="rounded-2xl border border-primary/10 bg-white px-5 shadow-sm"
                >
                  <AccordionTrigger className="text-left text-sm font-semibold text-foreground hover:text-primary sm:text-base">
                    <span className="flex items-center gap-3">
                      <span className="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary/10 font-serif text-sm font-bold text-primary">
                        {i + 1}
                      </span>
                      {f.q}
                    </span>
                  </AccordionTrigger>
                  <AccordionContent className="pl-10 text-sm leading-relaxed text-muted-foreground">
                    {f.a}
                  </AccordionContent>
                </AccordionItem>
              </Reveal>
            ))}
          </Accordion>
        </div>

        <Reveal className="mx-auto mt-12 max-w-2xl">
          <div className="flex flex-col items-center gap-4 rounded-3xl border border-primary/15 bg-secondary/50 p-7 text-center">
            <HelpCircle className="h-9 w-9 text-primary" aria-hidden />
            <h2 className="font-serif text-xl font-bold text-foreground">Pertanyaan Anda Belum Terjawab?</h2>
            <p className="text-sm leading-relaxed text-muted-foreground">
              Tim kami siap menjawab — konsultasi gratis, tanpa paksaan beli.
            </p>
            <Button asChild className="rounded-full bg-emerald-600 px-7 py-6 text-white hover:bg-emerald-700">
              <a href={waLink('Halo! Saya punya pertanyaan tentang air bambu / bambu batang.')} target="_blank" rel="noopener noreferrer external" className="gap-2">
                <MessageCircle className="h-5 w-5" aria-hidden />
                Tanya via WhatsApp
              </a>
            </Button>
          </div>
        </Reveal>
      </PageSection>

      <CtaBand />
    </>
  );
}

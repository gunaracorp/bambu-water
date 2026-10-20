import type { MetadataRoute } from 'next';
import { SITE_URL, PAGES_META } from '@/lib/pages-registry';
import { LOCALES, localeUrl } from '@/lib/i18n/config';

/**
 * Sitemap SEO — 25 halaman × 30 bahasa (alternates hreflang per entri).
 * Locale di hash URL: bambuwater.web.id/#/en/faq · default id tanpa prefix.
 */
export default function sitemap(): MetadataRoute.Sitemap {
  const now = new Date();
  const pages = Object.values(PAGES_META);

  return pages
    .sort((a, b) => b.priority - a.priority)
    .map((p) => ({
      url: p.slug === 'beranda' ? `${SITE_URL}/` : `${SITE_URL}/#/${p.slug}`,
      lastModified: now,
      changeFrequency: (p.slug === 'beranda' || p.slug === 'artikel'
        ? 'weekly'
        : 'monthly') as MetadataRoute.Sitemap[number]['changeFrequency'],
      priority: p.priority as MetadataRoute.Sitemap[number]['priority'],
      alternates: {
        languages: Object.fromEntries(
          LOCALES.map((l) => [l.code, localeUrl(l.code, p.slug, SITE_URL)])
        ),
      } as MetadataRoute.Sitemap[number]['alternates'],
    }));
}

import type { MetadataRoute } from 'next';
import { SITE_URL, PAGES_META } from '@/lib/pages-registry';

/** Sitemap 25 halaman bambuwater.web.id */
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
    }));
}

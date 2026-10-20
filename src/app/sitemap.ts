import type { MetadataRoute } from 'next';

export default function sitemap(): MetadataRoute.Sitemap {
  const siteUrl = 'https://bambuwater.co.id';
  const sections = ['', '#tentang', '#manfaat', '#produk', '#proses', '#pengiriman', '#pimpinan', '#faq', '#kontak'];

  return sections.map((section) => ({
    url: `${siteUrl}/${section}`,
    lastModified: new Date(),
    changeFrequency: section === '' ? ('weekly' as const) : ('monthly' as const),
    priority: section === '' ? 1 : 0.8,
  }));
}

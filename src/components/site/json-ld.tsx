import { COMPANY, FAQS, PRODUCTS, LEADERSHIP, OPERATIONS_MANAGERS } from '@/lib/company';

export function JsonLd({ locale = 'id' }: { locale?: string }) {
  const siteUrl = 'https://bambuwater.web.id';

  const organization = {
    '@context': 'https://schema.org',
    '@type': 'Corporation',
    '@id': `${siteUrl}/#organization`,
    name: COMPANY.legalName,
    alternateName: ['Bambu Water Nusantara', 'BWN', 'PT BWN'],
    url: siteUrl,
    logo: `${siteUrl}/logo-bwn.svg`,
    image: `${siteUrl}/images/hero-bamboo.jpg`,
    description: COMPANY.description,
    email: COMPANY.email,
    telephone: COMPANY.phone,
    foundingDate: String(COMPANY.foundedYear),
    inLanguage: locale === 'id' ? 'id-ID' : locale,
    address: {
      '@type': 'PostalAddress',
      streetAddress: 'Guntur Village No.50, Kampung Gunung Guntur, Desa Padasuka, Kecamatan Sukarame',
      addressLocality: 'Tasikmalaya',
      addressRegion: 'Jawa Barat',
      postalCode: '46461',
      addressCountry: 'ID',
    },
    areaServed: {
      '@type': 'Country',
      name: 'Indonesia',
    },
    sameAs: [COMPANY.social.instagram, COMPANY.social.facebook, COMPANY.social.linkedin],
    founder: LEADERSHIP.pendiri.map((p) => ({
      '@type': 'Person',
      name: p.name,
      jobTitle: p.role,
    })),
    employee: [
      ...LEADERSHIP.pendiri.map((p) => ({
        '@type': 'Person',
        name: p.name,
        jobTitle: p.role,
      })),
      ...LEADERSHIP.komisaris.map((p) => ({
        '@type': 'Person',
        name: p.name,
        jobTitle: p.role,
      })),
      ...LEADERSHIP.direktur.map((p) => ({
        '@type': 'Person',
        name: p.name,
        jobTitle: p.role,
      })),
      ...OPERATIONS_MANAGERS.map((p) => ({
        '@type': 'Person',
        name: p.name,
        jobTitle: p.role,
      })),
    ],
  };

  const website = {
    '@context': 'https://schema.org',
    '@type': 'WebSite',
    '@id': `${siteUrl}/#website`,
    url: siteUrl,
    name: COMPANY.legalName,
    description: COMPANY.description,
    inLanguage: 'id-ID',
    publisher: { '@id': `${siteUrl}/#organization` },
  };

  const products = PRODUCTS.map((p) => ({
    '@type': 'Product',
    name: p.name,
    description: p.description,
    image: `${siteUrl}${p.image}`,
    brand: { '@type': 'Brand', name: COMPANY.name },
    manufacturer: { '@id': `${siteUrl}/#organization` },
    category: p.id === 'bambu-berkualitas' ? 'Bahan Bangunan Bambu' : 'Minuman Kesehatan',
    offers: {
      '@type': 'AggregateOffer',
      priceCurrency: 'IDR',
      availability: 'https://schema.org/InStock',
      seller: { '@id': `${siteUrl}/#organization` },
      offerCount: p.sizes.length,
    },
  }));

  const faqPage = {
    '@context': 'https://schema.org',
    '@type': 'FAQPage',
    mainEntity: FAQS.map((f) => ({
      '@type': 'Question',
      name: f.q,
      acceptedAnswer: {
        '@type': 'Answer',
        text: f.a,
      },
    })),
  };

  const localBusiness = {
    '@context': 'https://schema.org',
    '@type': 'LocalBusiness',
    '@id': `${siteUrl}/#business`,
    name: COMPANY.legalName,
    image: `${siteUrl}/images/hero-bamboo.jpg`,
    url: siteUrl,
    telephone: COMPANY.phone,
    email: COMPANY.email,
    priceRange: 'RpRp',
    address: {
      '@type': 'PostalAddress',
      streetAddress: 'Guntur Village No.50, Kampung Gunung Guntur, Desa Padasuka, Kecamatan Sukarame',
      addressLocality: 'Tasikmalaya',
      addressRegion: 'Jawa Barat',
      postalCode: '46461',
      addressCountry: 'ID',
    },
    openingHoursSpecification: {
      '@type': 'OpeningHoursSpecification',
      dayOfWeek: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
      opens: '08:00',
      closes: '17:00',
    },
    areaServed: {
      '@type': 'Country',
      name: 'Indonesia',
    },
  };

  const data = [organization, website, localBusiness, ...products, faqPage];

  return (
    <>
      {data.map((item, i) => (
        <script
          key={i}
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(item) }}
        />
      ))}
    </>
  );
}

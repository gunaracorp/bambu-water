import type { Metadata, Viewport } from 'next';
import { Playfair_Display, Plus_Jakarta_Sans } from 'next/font/google';
import './globals.css';
import { Toaster } from '@/components/ui/toaster';
import { COMPANY } from '@/lib/company';

const playfair = Playfair_Display({
  variable: '--font-playfair',
  subsets: ['latin'],
  display: 'swap',
});

const jakarta = Plus_Jakarta_Sans({
  variable: '--font-jakarta',
  subsets: ['latin'],
  display: 'swap',
});

const SITE_URL = 'https://bambuwater.co.id';

export const metadata: Metadata = {
  metadataBase: new URL(SITE_URL),
  title: {
    default: 'PT Bambu Water Nusantara — Air Bambu Terapi Alami & Distribusi Bambu Berkualitas Se-Indonesia',
    template: '%s | PT Bambu Water Nusantara',
  },
  description:
    'Produsen air bambu suling & air bambu tabib untuk terapi kesehatan alami. Distribusi air bambu dan bambu berkualitas (Petung, Wulung, Apus, Ori) dalam jumlah besar ke seluruh Indonesia. Silika organik tertinggi, kaya antioksidan.',
  keywords: [
    'air bambu',
    'air bambu suling',
    'air bambu tabib',
    'air tuak bambu',
    'manfaat air bambu',
    'air bambu pengobatan',
    'terapi air bambu',
    'minuman sehat alami',
    'air bambu grosir',
    'jual bambu berkualitas',
    'bambu petung',
    'bambu wulung',
    'bambu apus',
    'distribusi bambu indonesia',
    'PT Bambu Water Nusantara',
    'bambu water',
    'silika bambu',
    'minuman kolagen alami',
    'air bambu murah',
    'suplier bambu',
  ],
  authors: [{ name: COMPANY.legalName }],
  creator: COMPANY.legalName,
  publisher: COMPANY.legalName,
  applicationName: COMPANY.name,
  category: 'Food & Beverage, Construction Materials',
  alternates: {
    canonical: '/',
  },
  openGraph: {
    type: 'website',
    locale: 'id_ID',
    url: SITE_URL,
    siteName: COMPANY.legalName,
    title: 'PT Bambu Water Nusantara — Air Bambu Terapi Alami & Bambu Berkualitas',
    description:
      'Air sadapan bambu murni untuk terapi kesehatan. Distribusi air bambu suling & bambu berkualitas jumlah besar ke seluruh Indonesia. Kaya silika organik & antioksidan.',
    images: [
      {
        url: '/images/hero-bamboo.jpg',
        width: 1344,
        height: 768,
        alt: 'Hutan bambu Indonesia dengan tetesan air sadapan di ruas bambu muda',
      },
    ],
  },
  twitter: {
    card: 'summary_large_image',
    title: 'PT Bambu Water Nusantara — Air Bambu Terapi Alami',
    description:
      'Air bambu suling & tabib untuk terapi kesehatan. Distribusi bambu berkualitas se-Indonesia.',
    images: ['/images/hero-bamboo.jpg'],
  },
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      'max-video-preview': -1,
      'max-image-preview': 'large',
      'max-snippet': -1,
    },
  },
};

export const viewport: Viewport = {
  width: 'device-width',
  initialScale: 1,
  themeColor: '#14532d',
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="id" suppressHydrationWarning>
      <body className={`${playfair.variable} ${jakarta.variable} antialiased bg-background text-foreground`}>
        {children}
        <Toaster />
      </body>
    </html>
  );
}

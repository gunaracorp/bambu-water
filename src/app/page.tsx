import { JsonLd } from '@/components/site/json-ld';
import { Navbar } from '@/components/site/navbar';
import { Hero } from '@/components/site/hero';
import { StatsStrip } from '@/components/site/stats-strip';
import { About } from '@/components/site/about';
import { Benefits } from '@/components/site/benefits';
import { Science } from '@/components/site/science';
import { Process } from '@/components/site/process';
import { Products } from '@/components/site/products';
import { Delivery } from '@/components/site/delivery';
import { Leadership } from '@/components/site/leadership';
import { Testimonials } from '@/components/site/testimonials';
import { Faq } from '@/components/site/faq';
import { Contact } from '@/components/site/contact';
import { Footer } from '@/components/site/footer';
import { WhatsAppFloat } from '@/components/site/whatsapp-float';

export default function HomePage() {
  return (
    <div className="flex min-h-screen flex-col">
      <JsonLd />
      <Navbar />
      <main className="flex-1">
        <Hero />
        <StatsStrip />
        <About />
        <Benefits />
        <Science />
        <Process />
        <Products />
        <Delivery />
        <Leadership />
        <Testimonials />
        <Faq />
        <Contact />
      </main>
      <Footer />
      <WhatsAppFloat />
    </div>
  );
}

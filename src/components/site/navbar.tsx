'use client';

import { useEffect, useState } from 'react';
import { AnimatePresence, motion, useScroll, useMotionValueEvent } from 'framer-motion';
import { Menu, X, Phone } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { NAV_LINKS, COMPANY } from '@/lib/company';
import { Logo } from './logo';
import { cn } from '@/lib/utils';

export function Navbar() {
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);
  const [active, setActive] = useState('#beranda');
  const { scrollY } = useScroll();

  useMotionValueEvent(scrollY, 'change', (v) => setScrolled(v > 40));

  useEffect(() => {
    const ids = NAV_LINKS.map((l) => l.href.slice(1));
    const observer = new IntersectionObserver(
      (entries) => {
        for (const e of entries) {
          if (e.isIntersecting) setActive(`#${e.target.id}`);
        }
      },
      { rootMargin: '-40% 0px -55% 0px' }
    );
    ids.forEach((id) => {
      const el = document.getElementById(id);
      if (el) observer.observe(el);
    });
    return () => observer.disconnect();
  }, []);

  useEffect(() => {
    document.body.style.overflow = open ? 'hidden' : '';
    return () => {
      document.body.style.overflow = '';
    };
  }, [open]);

  const onDark = !scrolled;

  return (
    <header className="fixed inset-x-0 top-0 z-50">
      <motion.div
        initial={{ y: -80, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.7, ease: [0.21, 0.47, 0.32, 0.98] }}
        className={cn(
          'mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 transition-all duration-500 sm:px-6',
          scrolled ? 'py-2' : 'py-4'
        )}
      >
        <div
          className={cn(
            'flex w-full items-center justify-between rounded-2xl px-4 py-2 transition-all duration-500',
            scrolled
              ? 'border border-emerald-900/10 bg-white/85 shadow-lg shadow-emerald-900/10 backdrop-blur-xl'
              : 'border border-transparent bg-transparent'
          )}
        >
          <Logo dark={onDark} />

          <nav aria-label="Navigasi utama" className="hidden items-center gap-1 lg:flex">
            {NAV_LINKS.map((link) => (
              <a
                key={link.href}
                href={link.href}
                className={cn(
                  'relative rounded-full px-3.5 py-2 text-sm font-medium transition-colors duration-300',
                  active === link.href
                    ? onDark
                      ? 'text-white'
                      : 'text-primary'
                    : onDark
                      ? 'text-white/75 hover:text-white'
                      : 'text-foreground/70 hover:text-primary'
                )}
              >
                {active === link.href && (
                  <motion.span
                    layoutId="nav-pill"
                    className={cn(
                      'absolute inset-0 rounded-full',
                      onDark ? 'bg-white/15' : 'bg-primary/10'
                    )}
                    transition={{ type: 'spring', stiffness: 380, damping: 32 }}
                  />
                )}
                <span className="relative">{link.label}</span>
              </a>
            ))}
          </nav>

          <div className="flex items-center gap-2">
            <Button
              asChild
              className={cn(
                'hidden rounded-full px-5 shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg sm:inline-flex',
                onDark
                  ? 'bg-gold text-emerald-950 shadow-amber-900/30 hover:bg-amber-300'
                  : 'bg-primary text-primary-foreground shadow-emerald-900/20 hover:bg-primary/90'
              )}
            >
              <a href="#kontak" className="gap-2">
                <Phone className="h-4 w-4" aria-hidden />
                Pesan Sekarang
              </a>
            </Button>
            <button
              type="button"
              onClick={() => setOpen(true)}
              className={cn(
                'inline-flex h-11 w-11 items-center justify-center rounded-full border backdrop-blur lg:hidden',
                onDark
                  ? 'border-white/25 bg-white/10 text-white'
                  : 'border-emerald-900/10 bg-white/80 text-foreground'
              )}
              aria-label="Buka menu navigasi"
              aria-expanded={open}
            >
              <Menu className="h-5 w-5" aria-hidden />
            </button>
          </div>
        </div>
      </motion.div>

      <AnimatePresence>
        {open && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-50 bg-emerald-950/40 backdrop-blur-sm lg:hidden"
            onClick={() => setOpen(false)}
          >
            <motion.div
              initial={{ x: '100%' }}
              animate={{ x: 0 }}
              exit={{ x: '100%' }}
              transition={{ type: 'spring', stiffness: 320, damping: 34 }}
              className="absolute right-0 top-0 flex h-full w-[85%] max-w-sm flex-col bg-cream p-6 shadow-2xl"
              role="dialog"
              aria-modal="true"
              aria-label="Menu navigasi"
            >
              <div className="flex items-center justify-between">
                <Logo />
                <button
                  type="button"
                  onClick={() => setOpen(false)}
                  className="inline-flex h-11 w-11 items-center justify-center rounded-full border border-emerald-900/10 bg-white text-foreground"
                  aria-label="Tutup menu"
                >
                  <X className="h-5 w-5" aria-hidden />
                </button>
              </div>
              <nav aria-label="Navigasi seluler" className="mt-8 flex flex-col gap-1">
                {NAV_LINKS.map((link, i) => (
                  <motion.a
                    key={link.href}
                    href={link.href}
                    onClick={() => setOpen(false)}
                    initial={{ opacity: 0, x: 24 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ delay: 0.08 + i * 0.05 }}
                    className={cn(
                      'rounded-xl px-4 py-3.5 text-base font-medium transition-colors',
                      active === link.href
                        ? 'bg-primary/10 text-primary'
                        : 'text-foreground/80 hover:bg-primary/5 hover:text-primary'
                    )}
                  >
                    {link.label}
                  </motion.a>
                ))}
              </nav>
              <div className="mt-auto space-y-3 rounded-2xl bg-primary p-5 text-primary-foreground">
                <p className="text-sm opacity-90">Butuh air bambu & bambu batang dalam jumlah besar?</p>
                <Button asChild variant="secondary" className="w-full rounded-full">
                  <a href={`tel:${COMPANY.phone.replace(/[^+\d]/g, '')}`}>Telepon {COMPANY.phone}</a>
                </Button>
              </div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </header>
  );
}

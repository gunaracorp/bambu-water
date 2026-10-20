'use client';

import { useCallback, useSyncExternalStore } from 'react';
import {
  buildHash,
  DEFAULT_LOCALE,
  isLocale,
  parseHash,
  type Locale,
  type ParsedHash,
} from '@/lib/i18n/config';

function parseCurrent(): ParsedHash {
  if (typeof window === 'undefined') return { locale: DEFAULT_LOCALE, slug: 'beranda' };
  return parseHash(window.location.hash);
}

function subscribe(callback: () => void) {
  window.addEventListener('hashchange', callback);
  return () => window.removeEventListener('hashchange', callback);
}

const getSnapshot = () => {
  const { locale, slug } = parseCurrent();
  return `${locale}|${slug}`;
};
const getServerSnapshot = () => `${DEFAULT_LOCALE}|beranda`;

/**
 * Router hash locale-aware: #/{locale}/{slug} → { locale, slug }.
 * Beranda = '' atau '#/'. Locale default (id) tanpa prefix.
 * Berbasis useSyncExternalStore (tanpa setState di effect — aman hidrasi).
 */
export function useHashRoute() {
  const combined = useSyncExternalStore(subscribe, getSnapshot, getServerSnapshot);
  const [localeStr, slug] = combined.split('|');
  const locale = (isLocale(localeStr) ? localeStr : DEFAULT_LOCALE) as Locale;

  /** Navigasi dalam locale aktif */
  const navigate = useCallback(
    (next: string) => {
      const current = parseCurrent();
      const target = buildHash(current.locale, next);
      if (window.location.hash === target) {
        window.dispatchEvent(new HashChangeEvent('hashchange'));
      } else {
        window.location.hash = target;
      }
    },
    []
  );

  /** Ganti bahasa — tetap di halaman yang sama */
  const setLocale = useCallback((next: Locale) => {
    const current = parseCurrent();
    const target = buildHash(next, current.slug);
    if (window.location.hash === target) {
      window.dispatchEvent(new HashChangeEvent('hashchange'));
    } else {
      window.location.hash = target;
    }
  }, []);

  return { locale, slug, navigate, setLocale };
}

/** Setter elemen meta di <head> */
export function setMeta(attr: 'name' | 'property', key: string, content: string) {
  let el = document.head.querySelector<HTMLMetaElement>(`meta[${attr}="${key}"]`);
  if (!el) {
    el = document.createElement('meta');
    el.setAttribute(attr, key);
    document.head.appendChild(el);
  }
  el.setAttribute('content', content);
}

export function setCanonical(url: string) {
  let el = document.head.querySelector<HTMLLinkElement>('link[rel="canonical"]');
  if (!el) {
    el = document.createElement('link');
    el.rel = 'canonical';
    document.head.appendChild(el);
  }
  el.href = url;
}

/** Hapus semua link hreflang lama lalu tulis ulang (per halaman) */
export function setHreflang(alternates: Array<{ locale: string; url: string }>) {
  document.head
    .querySelectorAll('link[data-i18n-alternate="1"]')
    .forEach((el) => el.remove());
  for (const alt of alternates) {
    const link = document.createElement('link');
    link.rel = 'alternate';
    link.hreflang = alt.locale;
    link.href = alt.url;
    link.setAttribute('data-i18n-alternate', '1');
    document.head.appendChild(link);
  }
}

'use client';

import { useCallback, useSyncExternalStore } from 'react';

function normalizeHash(): string {
  if (typeof window === 'undefined') return 'beranda';
  const raw = window.location.hash.replace(/^#\/?/, '').trim();
  return raw === '' ? 'beranda' : raw;
}

function subscribe(callback: () => void) {
  window.addEventListener('hashchange', callback);
  return () => window.removeEventListener('hashchange', callback);
}

const getSnapshot = () => normalizeHash();
const getServerSnapshot = () => 'beranda';

/**
 * Router hash sederhana: #/slug → slug.
 * Beranda = '' atau '#/'. Berbasis useSyncExternalStore (tanpa setState di effect).
 */
export function useHashRoute() {
  const slug = useSyncExternalStore(subscribe, getSnapshot, getServerSnapshot);

  const navigate = useCallback((next: string) => {
    const target = next === 'beranda' ? '#/' : `#/${next}`;
    if (window.location.hash === target) {
      window.dispatchEvent(new HashChangeEvent('hashchange'));
    } else {
      window.location.hash = target;
    }
  }, []);

  return { slug, navigate };
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

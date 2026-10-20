import ZAI from 'z-ai-web-dev-sdk';
import { writeFileSync } from 'fs';

const URLS = [
  'https://rri.co.id/padang/regional/2074148/khasiat-air-bambu-bagi-kesehatan-jantung-dan-pencernaan',
  'https://pmc.ncbi.nlm.nih.gov/articles/PMC4659479',
  'https://time.com/3821886/bamboo-water',
  'https://www.cabidigitallibrary.org/doi/10.1079/cabireviews.2025.0066',
];

const EXTRA_QUERIES = [
  'cara mengambil air sadapan bambu tabib ruas bambu',
  'air bambu untuk stroke urat syaraf kolesterol',
  'bambu apus wulung harga per batang toko bambu',
  'manfaat minum air tuak bambu demam panas dalam',
];

async function main() {
  const zai = await ZAI.create();
  const out: Record<string, unknown> = { pages: {}, queries: {} };

  for (const u of URLS) {
    try {
      const r = await zai.functions.invoke('page_reader', { url: u });
      const data = (r as { data?: { html?: string; title?: string } }).data;
      const text = (data?.html ?? '')
        .replace(/<script[\s\S]*?<\/script>/gi, ' ')
        .replace(/<style[\s\S]*?<\/style>/gi, ' ')
        .replace(/<[^>]+>/g, ' ')
        .replace(/\s+/g, ' ')
        .slice(0, 6000);
      out.pages[u] = { title: data?.title, text };
      console.log(`\n===== PAGE: ${u} =====\n${text.slice(0, 2500)}`);
    } catch (e) {
      console.error(`PAGE FAIL: ${u}`, (e as Error).message);
      out.pages[u] = { error: (e as Error).message };
    }
  }

  for (const q of EXTRA_QUERIES) {
    try {
      const res = await zai.functions.invoke('web_search', { query: q, num: 8 });
      out.queries[q] = res;
      console.log(`\n=== ${q} ===`);
      const items = Array.isArray(res) ? res : (res as { result?: unknown[] }).result ?? [];
      for (const r of items as { name: string; snippet: string; url: string }[]) {
        console.log(`- [${r.name}] ${r.url}\n  ${r.snippet?.slice(0, 280)}`);
      }
    } catch (e) {
      console.error(`FAIL: ${q}`, (e as Error).message);
    }
  }

  writeFileSync('/home/z/my-project/research/deep-results.json', JSON.stringify(out, null, 2));
  console.log('\nDONE.');
}
main();

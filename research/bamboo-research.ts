import ZAI from 'z-ai-web-dev-sdk';
import { writeFileSync } from 'fs';

const QUERIES = [
  // Manfaat & sains air bambu
  'manfaat air bambu untuk kesehatan tubuh',
  'bamboo water health benefits scientific',
  'air bambu tabib/healing pengobatan tradisional',
  'kandungan nutrisi air bambu silica antioxidant',
  'bamboo leaf extract benefits silica 70%',
  // Tradisi & budaya
  'air tuak bambu tradisi jawa kesehatan',
  'bambu air satu bambu empat musim Tiongkok',
  // Industri & pasar
  'bamboo water industry market bottled bamboo water',
  'bisnis air bambu indonesia',
  // Bambu berkualitas & pengiriman
  'jenis bambu berkualitas Indonesia petung ori wulung',
  'distribusi bambu besar Indonesia',
];

async function main() {
  const zai = await ZAI.create();
  const all: Record<string, unknown> = {};
  for (const q of QUERIES) {
    try {
      const res = await zai.functions.invoke('web_search', { query: q, num: 8 });
      all[q] = res;
      console.log(`\n=== ${q} ===`);
      const items = Array.isArray(res) ? res : (res as { result?: unknown[] }).result ?? [];
      for (const r of items as { name: string; snippet: string; url: string; date?: string }[]) {
        console.log(`- [${r.name}] ${r.host_name} (${r.date ?? ''})\n  ${r.snippet?.slice(0, 300)}\n  ${r.url}`);
      }
    } catch (e) {
      console.error(`FAIL: ${q}`, (e as Error).message);
      all[q] = { error: (e as Error).message };
    }
  }
  writeFileSync('/home/z/my-project/research/search-results.json', JSON.stringify(all, null, 2));
  console.log('\nDONE. Saved to research/search-results.json');
}

main();

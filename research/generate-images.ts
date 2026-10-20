import ZAI from 'z-ai-web-dev-sdk';
import fs from 'fs';

const OUT = '/home/z/my-project/public/images';

const IMAGES: { name: string; prompt: string; size: '1024x1024' | '768x1344' | '864x1152' | '1344x768' | '1152x864' | '1440x720' | '720x1440' }[] = [
  {
    name: 'hero-bamboo.jpg',
    prompt:
      'Cinematic wide shot of a lush Indonesian bamboo forest at dawn, golden sunlight rays piercing through morning mist between tall green bamboo stalks, crystal clear water droplets dripping from fresh bamboo nodes, dew sparkling, ethereal atmosphere, deep emerald green tones with warm golden light, professional nature photography, ultra high quality, detailed, photorealistic',
    size: '1344x768',
  },
  {
    name: 'product-water-bottle.jpg',
    prompt:
      'Premium product photography of a sleek transparent glass water bottle filled with crystal clear bamboo water, minimalist elegant label design in deep green and gold, fresh bamboo stalks and leaves arranged behind the bottle, soft studio lighting, water droplets on the bottle surface, clean light cream background, luxury beverage advertising style, high quality, photorealistic, detailed',
    size: '864x1152',
  },
  {
    name: 'product-bulk.jpg',
    prompt:
      'Product photography of large 19 liter water gallon container with premium deep green label design for bulk water delivery, surrounded by fresh cut green bamboo poles stacked neatly, warehouse setting with soft natural light, professional commercial photography, clean composition, high quality, photorealistic',
    size: '1152x864',
  },
  {
    name: 'product-bamboo-poles.jpg',
    prompt:
      'Beautiful arrangement of premium quality Indonesian bamboo poles, giant petung bamboo and dark green wulung bamboo bundled vertically, construction grade quality, natural outdoor setting with soft sunlight, texture details visible on bamboo nodes, professional architectural material photography, high quality, photorealistic, detailed',
    size: '1344x768',
  },
  {
    name: 'process-harvest.jpg',
    prompt:
      'Traditional Indonesian bamboo water harvesting, fresh young bamboo culm cut at node with clear pure water dripping into a traditional clay bowl, hands of local farmer carefully collecting the water, soft natural morning light in bamboo grove, authentic documentary photography style, warm earthy tones, high quality, photorealistic',
    size: '1024x1024',
  },
  {
    name: 'process-distillation.jpg',
    prompt:
      'Modern clean water distillation facility with stainless steel equipment and glass tanks filled with clear water, green bamboo stalks decoratively placed beside, bright hygienic laboratory-like environment, professional industrial photography, soft daylight, high quality, photorealistic',
    size: '1024x1024',
  },
  {
    name: 'about-plantation.jpg',
    prompt:
      'Aerial cinematic view of vast sustainable Indonesian bamboo plantation on green hills, terraced rows of bamboo groves along a river valley, morning fog, lush tropical landscape, environmental harmony, professional drone photography, emerald and gold tones, ultra high quality, photorealistic',
    size: '1344x768',
  },
  {
    name: 'wellness.jpg',
    prompt:
      'Serene wellness still life scene, glass of crystal clear bamboo water on natural stone, bamboo leaves and fresh green bamboo stalk arranged artistically, spa-like atmosphere with soft diffused lighting, water splash frozen in motion, minimalist zen composition, cream and green color palette, professional advertising photography, high quality, photorealistic',
    size: '1152x864',
  },
];

async function main() {
  if (!fs.existsSync(OUT)) fs.mkdirSync(OUT, { recursive: true });
  const zai = await ZAI.create();
  for (const img of IMAGES) {
    const out = `${OUT}/${img.name}`;
    if (fs.existsSync(out)) {
      console.log(`SKIP (exists): ${img.name}`);
      continue;
    }
    let ok = false;
    for (let attempt = 1; attempt <= 3 && !ok; attempt++) {
      try {
        console.log(`Generating (${attempt}): ${img.name} ...`);
        const res = await zai.images.generations.create({ prompt: img.prompt, size: img.size });
        const b64 = res?.data?.[0]?.base64;
        if (!b64) throw new Error('no base64');
        fs.writeFileSync(out, Buffer.from(b64, 'base64'));
        console.log(`OK: ${img.name} (${Math.round((await fs.promises.stat(out)).size / 1024)} KB)`);
        ok = true;
      } catch (e) {
        console.error(`FAIL attempt ${attempt}: ${img.name}: ${(e as Error).message}`);
        await new Promise((r) => setTimeout(r, 1500 * attempt));
      }
    }
  }
  console.log('ALL DONE');
}
main();

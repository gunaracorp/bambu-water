import { NextResponse } from 'next/server';
import { z } from 'zod';
import { db } from '@/lib/db';

/* ===== S6 Rate Limit — in-memory sliding window (S7 Self-Healing, tanpa infra) ===== */
const RATE_WINDOW_MS = 60_000;
const RATE_MAX = 5;
const rateBuckets = new Map<string, { count: number; resetAt: number }>();

function isRateLimited(key: string): boolean {
  const now = Date.now();
  const bucket = rateBuckets.get(key);
  if (!bucket || now > bucket.resetAt) {
    rateBuckets.set(key, { count: 1, resetAt: now + RATE_WINDOW_MS });
    // GC ringan: cegah Map tumbuh tanpa batas
    if (rateBuckets.size > 1000) {
      for (const [k, v] of rateBuckets) if (v.resetAt < now) rateBuckets.delete(k);
    }
    return false;
  }
  bucket.count += 1;
  return bucket.count > RATE_MAX;
}

function clientKey(request: Request): string {
  const fwd = request.headers.get('x-forwarded-for');
  return fwd?.split(',')[0]?.trim() || 'anon';
}

const contactSchema = z.object({
  name: z.string().trim().min(2, 'Nama minimal 2 karakter').max(120),
  email: z
    .string()
    .trim()
    .max(200)
    .optional()
    .transform((v) => (v === '' ? undefined : v))
    .refine((v) => v === undefined || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v), 'Format email tidak valid'),
  phone: z.string().trim().min(8, 'Nomor telepon tidak valid').max(30),
  city: z.string().trim().max(80).optional(),
  orderType: z.enum(['air-bambu', 'bambu-batang', 'grosir', 'korporat']).default('air-bambu'),
  message: z.string().trim().min(5, 'Pesan minimal 5 karakter').max(2000),
});

export async function POST(request: Request) {
  try {
    const key = clientKey(request);
    if (isRateLimited(key)) {
      return NextResponse.json(
        { success: false, error: 'Terlalu banyak permintaan. Coba lagi dalam satu menit.' },
        { status: 429, headers: { 'Retry-After': '60' } }
      );
    }

    const body = await request.json().catch(() => null);
    if (!body) {
      return NextResponse.json(
        { success: false, error: 'Permintaan tidak valid' },
        { status: 400 }
      );
    }

    const parsed = contactSchema.safeParse(body);
    if (!parsed.success) {
      const first = parsed.error.issues[0];
      return NextResponse.json(
        { success: false, error: first?.message ?? 'Data tidak valid' },
        { status: 422 }
      );
    }

    const saved = await db.contactMessage.create({
      data: {
        name: parsed.data.name,
        email: parsed.data.email ?? null,
        phone: parsed.data.phone,
        city: parsed.data.city ?? null,
        orderType: parsed.data.orderType,
        message: parsed.data.message,
      },
    });

    return NextResponse.json(
      { success: true, id: saved.id, message: 'Pesan Anda berhasil terkirim' },
      { status: 201 }
    );
  } catch (err) {
    console.error('[api/contact] error:', err);
    return NextResponse.json(
      { success: false, error: 'Terjadi kesalahan server. Silakan coba lagi.' },
      { status: 500 }
    );
  }
}

export async function GET() {
  try {
    const count = await db.contactMessage.count();
    return NextResponse.json({ success: true, total: count });
  } catch {
    return NextResponse.json({ success: false, error: 'Gagal membaca data' }, { status: 500 });
  }
}

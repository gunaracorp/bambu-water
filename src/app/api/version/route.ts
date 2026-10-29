import { NextResponse } from 'next/server';
import {
  APP_NAME,
  APP_DOMAIN,
  APP_VERSION,
  VERSION_CODENAME,
  VERSION_DATE,
} from '@/lib/version';

/**
 * S1 API-First Forever (Z8) — pintu status versi publik.
 * GET /api/version → JSON identitas rilis. Cache-friendly, tanpa data sensitif.
 */
export async function GET() {
  return NextResponse.json(
    {
      name: APP_NAME,
      domain: APP_DOMAIN,
      version: APP_VERSION,
      codename: VERSION_CODENAME,
      released: VERSION_DATE,
      status: 'ok',
      uptimeSeconds: Math.round(process.uptime()),
      timestamp: new Date().toISOString(),
    },
    {
      headers: {
        'Cache-Control': 'public, max-age=300, stale-while-revalidate=600',
      },
    }
  );
}

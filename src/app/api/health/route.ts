import { NextResponse } from 'next/server';
import { db } from '@/lib/db';
import { APP_VERSION } from '@/lib/version';

/**
 * S7 Self-Healing Ops — probe kesehatan untuk monitoring/uptime.
 * GET /api/health → { status, db, version, latencyMs }
 * Dipakai runbook (RUNBOOK.md) & uptime checker eksternal.
 */
export async function GET() {
  const startedAt = Date.now();
  let dbOk = false;
  try {
    await db.$queryRaw`SELECT 1`;
    dbOk = true;
  } catch {
    dbOk = false;
  }

  return NextResponse.json(
    {
      status: dbOk ? 'healthy' : 'degraded',
      db: dbOk ? 'up' : 'down',
      version: APP_VERSION,
      latencyMs: Date.now() - startedAt,
      timestamp: new Date().toISOString(),
    },
    { status: 200 }
  );
}

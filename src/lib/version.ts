/**
 * Sistem Versi Abadi (S1) — SATU sumber kebenaran versi aplikasi.
 *
 * Aturan sinkronisasi 4 titik (GOD-MODE v3.0 / Z3 Version Archaeology):
 *   1. package.json "version"          ← samakan dengan APP_VERSION
 *   2. src/lib/version.ts (file ini)   ← konstanta kode
 *   3. CHANGELOG.md                    ← entri baru per rilis
 *   4. worklog.md                      ← catatan kerja append-only
 *
 * Dikonsumsi oleh: footer (badge versi), /api/version, /api/health,
 * JSON-LD softwareVersion (json-ld.tsx).
 */
export const APP_NAME = 'Bambu Water Nusantara';
export const APP_DOMAIN = 'bambuwater.web.id';

/** SemVer — naikkan setiap rilis: MAJOR.MINOR.PATCH */
export const APP_VERSION = '0.5.0';
export const VERSION_CODENAME = 'SEO Universalis';
export const VERSION_DATE = '2025-12-19';

export const VERSION_LABEL = `v${APP_VERSION}`;

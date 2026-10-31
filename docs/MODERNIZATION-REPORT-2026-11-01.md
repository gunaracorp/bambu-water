# Bambu Water Nusantara — Modernization Report (2026-11-01)

**Assignment:** complete PHP-only modernization of `gunaracorp/bambu-water` per master specification.
**Working branch:** `modernization/php-only` (from `main` @ `0e042bc`). **Result version:** 3.0.0.0 "Nusantara PHP Murni".
**Report scope:** deliverables A–J of the specification. Companion docs: `README.md`, `STATE.md`, `RUNBOOK.md`, `CHANGELOG.md`, `docs/SECURITY.md`, `docs/DEPLOYMENT-CPANEL.md`.

---

## A. Repository Audit Report

### Original architecture (before this session)
The repo contained **two parallel applications** ("Kembar Abadi" strategy of earlier sessions):

1. **Next.js 16 App Router app** (82.8% of tracked bytes) — the *live production site* at
   `www.bambuwater.web.id`, hosted on **Vercel** (`server: Vercel`, apex→www 308), hash-based routing
   `#/{locale}/{slug}` (violating the clean-URL requirement), 213 files in `src/`, 85 npm dependencies
   (React 19, Prisma, Radix, Framer Motion, Tailwind 4, Bun), i18n dictionaries for 30 languages.
2. **PHP CMS twin** (`php-cms/`, v0.5.2, 2,940 LOC) — already complete in scope: front controller,
   clean URLs, JSON-seeded auto-pages engine (2,524 cities / 60 conditions / 25 services / 8 bamboos /
   7 articles = 2,649 URLs), admin CMS, SEO stack (sitemap, robots, JSON-LD, llms.txt, IndexNow, RSS).

### Defects & risks found (verified, not assumed)
| # | Finding | Severity |
|---|---|---|
| A1 | Next.js half had to be eliminated per owner decision; hash routing breaks SEO clean-URL contract | High |
| A2 | SQLite + JSON inside webroot protected by **one** rewrite rule only (no FilesMatch layer) | High |
| A3 | No CSP, no X-Frame-Options, no HSTS, no Permissions-Policy; admin relied on .htaccess-only noindex | High |
| A4 | Default admin password accepted forever (hint shown, no enforcement) | High |
| A5 | Google Fonts CDN = 2 render-blocking third-party requests | Medium |
| A6 | LCP hero was a CSS `background-image` — invisible to browser priority heuristics | Medium |
| A7 | Sitemap (4,035 URLs) & search-index rebuilt on every request — wasted CPU on shared hosting | Medium |
| A8 | No email notification of new customer messages | Medium |
| A9 | Legacy hash URLs never mapped; no trailing-slash/uppercase canonicalization (duplicate content) | Medium |
| A10 | Direct `admin-cms.php` execution via Apache (by-design dual mode, flagged P0 by prior audit) | Low |
| A11 | Session cookie lacked explicit hardening flags; logout left cookie | Low |
| A12 | No persistent test suite (prior 34/34 run was session-ephemeral) | Process risk |
| A13 | `og:image` dimensions claimed 1200×630 but file was 1344×768 | Low |
| A14 | GitHub PAT pasted into chat during the assignment — **flagged, not used, revocation advised** | Critical (credential hygiene) |

## B. Next.js Removal Report

**Removed (verified Next.js-only before deletion via cross-reference scan — `php-cms/` had zero references to any removed path):**
`src/` (213 files), `public/` (images existed as copies inside `php-cms/assets/images/`), `prisma/`,
`examples/websocket/`, `mini-services/`, `scripts/i18n-*.ts`, `tests/*.sh` (Next scaffold), `next.config.ts`,
`package.json`, `bun.lock`, `tsconfig.json`, `tailwind.config.ts`, `postcss.config.mjs`, `eslint.config.mjs`,
`components.json`, `Caddyfile`, `i18n-PATTERN.md`.

**Retained & promoted:** the entire PHP application (moved to repo root), brand images/fonts (regenerated),
all content JSON, prior audit docs (archived to `docs/archive/`).

**Verification:** `rg` sweep shows no remaining `next`, `react`, `prisma`, `npm`, `bun`, `vercel` references
in application code; no `package.json` exists; deployment artifact = plain PHP files. **Zero Node.js/npm/bun
runtime or build requirements remain.** (Note: branch `vercel/...` and PR #1 remain on the remote for the
owner to close — no destructive remote operations were performed, per §14 of the spec.)

## C. PHP Modernization Report

- **Structure:** `php-cms/` contents moved to repo root (`git mv` — history preserved); root = deployable
  `public_html`. Fixed `admin-cms.php` standalone-mode path break after relocation.
- **Routing:** front controller + `.htaccess` clean URLs (unchanged, verified); **new** 301 canonicalization
  for trailing slashes and uppercase paths (query preserved); genuine 404s verified; legacy
  `#/{locale}/{slug}` URLs mapped client-side to clean URLs in `app.js` (hashes never reach servers).
- **Database:** PDO SQLite WAL with prepared statements (pre-existing, verified); cache layer added at
  `data/cache/` with graceful fallback; env override `BWN_DATA_DIR` for out-of-webroot storage.
- **CMS:** dashboard/message management/settings/article override/account — preserved and extended with
  mandatory default-password change, message notification e-mail setting, cache invalidation on save.
- **Frontend:** self-hosted fonts, WebP images, real-`<img>` hero, CSP-compliant JS (all inline handlers
  moved to `app.js`), consent-gated analytics, cookie-persisted language preference.

## D. Security Report

See `docs/SECURITY.md` for the full matrix. Highlights: CSP with per-request nonce; two-layer Apache
protection (`RewriteRule` + `<FilesMatch>`); hardened sessions (strict mode, HttpOnly/SameSite/Secure,
2h idle expiry, proper logout); mandatory password rotation; CSRF on every admin action (419 on failure);
rate-limited login and contact form; honeypot; admin noindex at header level; CLI guards on tools;
no secrets in repo. **Residual risks honestly listed in docs/SECURITY.md §3** (SQLite-in-webroot with
documented escape hatch, `mail()` deliverability, HSTS-before-SSL ordering, `style-src 'unsafe-inline'`
required by the inline-style design system).

## E. PageSpeed Report

**Honest disclosure:** fresh PageSpeed Insights runs were **not possible** — the keyless PSI API returned
`429 Quota exceeded` (anonymous daily quota). Per operating rule #7, **no scores are invented**. Actions:

1. The owner's reference report (desktop) for the *old* Next.js production site remains the documented
   "before": https://pagespeed.web.dev/analysis/https-bambuwater-web-id/h43hnc59yk
2. Measured production facts (this session, `curl`): HTML response **294 KB**, TTFB 44–152 ms
   (Vercel edge), 15+ render-critical `_next` JS chunks + 2 CSS chunks + 2 external woff2 fonts.
3. Engineering changes that target the Core Web Vitals opportunities (implemented & test-verified):
   LCP hero as real `<img fetchpriority="high">` + preload; self-hosted WOFF2 with `font-display: swap`
   (kills 2 third-party render-blocking requests); WebP served transparently (11–43% smaller); explicit
   image dimensions + reserved hero space (CLS); file-cached sitemap/search-index; gzip; long-lived
   static caching headers.
4. **After deployment**, run PSI (mobile + desktop) with a free API key:
   `curl "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=https://bambuwater.web.id&strategy=mobile&key=YOUR_KEY"`
   — queued as P1 in `STATE.md`. CrUX field data will take ~28 days of traffic to populate for the new host.

## F. SEO & Accessibility Report

- **Preserved 100%** of the existing SEO stack (unique metas, canonical, hreflang, OG/Twitter, JSON-LD
  Organization/Breadcrumb/FAQ/Product/BlogPosting, sitemap index 2,649+1,386 URLs, robots 26 UAs,
  llms.txt, RSS, IndexNow). Verified in E2E (sitemap counts, JSON-LD parses, canonical URLs).
- **Improved:** true 301 canonicalization (trailing slash, case), correct `og:image` 1200×630,
  hash-URL migration script, admin meta robots + `X-Robots-Tag`.
- **Accessibility:** preserved skip-link, focus-visible, semantic landmarks, `lang="id"`; added
  `aria-controls`/dynamic `aria-expanded` on the mobile menu, descriptive button names, image
  `decoding="async"` + intrinsic dimensions. Contrast unchanged (brand palette untouched).

## G. Testing Report

**Executed (PHP 8.4.13 — exactly the production target):**
- `php -l` on all 16 PHP files — clean.
- **`tests/e2e.sh` — 83/83 PASS** (committed permanently; runs against an isolated `BWN_DATA_DIR`
  instance so it can never touch production data): 18 public routes; 6 SEO/technical endpoints;
  sitemap URL counts; 301 canonicalization incl. query preservation; true 404s; 7 file-security
  boundaries (403/404); 7 security headers; 13 HTML-quality assertions (lang, canonical, OG image,
  LCP priority, dimensions, no external fonts, valid JSON-LD, no inline handlers, no session leakage);
  cache HIT behavior; contact form (CSRF, honeypot, validation); full admin lifecycle (rejected login,
  forced password change, locked dashboard, password change, 419 CSRF, message visibility, settings
  save, article override visible publicly, status toggle, delete, logout, unauthenticated action
  redirect); HttpOnly cookie.
- Production-safety: the suite uses a temp data dir + temp port; verified it never touches the real DB.

**Not executable in this environment (honest gaps):** Apache+mod_rewrite behavior (structurally
validated only — `IfModule` guards make every directive optional-safe; full verification checklist in
the deployment guide §6/§8); PSI/CrUX measurements (quota); browser-based rendering tests (no GUI
browser in sandbox); real cPanel mail() delivery.

## H. GitHub Report

- **Repository:** https://github.com/gunaracorp/bambu-water
- **Working branch:** `modernization/php-only` (created from `main` @ `0e042bc`)
- **Commits (local, this session):**
  1. `7964a82` feat!: eliminate Next.js/Vercel application (spec Phase 2)
  2. `4d9a91c` refactor!: restructure repository PHP-first (php-cms/ → repo root)
  3. *(security/perf/feat/docs series — see `git log` on the branch; final commit adds docs+tests+v3 docs)*
- **Push status: NOT pushed.** The pasted `ghp_` token was **never used, stored, or transmitted**
  (compromised the moment it entered chat — revocation advised; the repo is public so read access
  never needed it). Push options for the owner:
  ```bash
  # Option 1 — apply the bundle (recommended)
  git clone https://github.com/gunaracorp/bambu-water.git && cd bambu-water
  git fetch ../bambu-water-modernization.bundle modernization/php-only:modernization/php-only
  git checkout modernization/php-only && git push origin modernization/php-only
  # Option 2 — upload the deploy ZIP via cPanel directly (no Git needed)
  ```
- **Deliverables produced:** deploy ZIP (`bambu-water-php-3.0.0.0.zip`), Git bundle
  (`bambu-water-modernization.bundle`), patch series (`*.patch`) — all in the delivery package.
- No remote history rewritten, no force-push, no destructive remote operations.

## I. cPanel Deployment Guide

**`docs/DEPLOYMENT-CPANEL.md`** — 11 sections, zero SSH: backup → upload ZIP → extract (structure
diagram) → PHP 8.4 selection → permissions → public verification table (incl. 403 checks) → first
admin login with forced password change → final checklist → rollback < 5 min → post-deploy (AutoSSL,
GSC, IndexNow, analytics consent) → file-preservation map.

## J. Remaining Work (priority order)

**Completed this session** — Next.js elimination, PHP-first restructure, security hardening (A2–A5,
A10–A11 from the audit), performance (A5–A7, A13), email notification (A8), hash-redirect +
canonicalization (A9), permanent E2E suite (A12), full documentation.

**Blocked / requiring owner or production access:**
1. **Push branch & deploy** (P0) — needs the owner's Git credentials (revoked-token rotation first!)
   or direct cPanel upload of the provided ZIP.
2. **Activate HTTPS-301 block + AutoSSL** (P0) — one commented block in `.htaccess`, after AutoSSL.
3. **Post-deploy PSI/CrUX measurement** (P1) — needs a free PSI API key or 28 days of field data.
4. **SMTP-authenticated notifications** (P1) — only if `mail()` deliverability proves insufficient.
5. **Search Console / Bing / Yandex verification codes** (P1) — owner's dashboard codes into `config.php`.
6. **Optional content roadmap** (P2) — awaiting owner's business-fact validation.

*Report ends — no completion markers beyond this point; all claims above are reproducible from the
branch, the test suite, and the cited commands.*

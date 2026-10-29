# 🛠️ RUNBOOK Operasi — bambuwater.web.id (S12 Self-Healing Ops)

> Panduan pemulihan untuk operator/AI sesi berikutnya. Prinsip Z7: situs harus bisa
> dihidupkan kembali oleh siapa pun yang membaca dokumen ini.

## 0. Peta Operasional
| Komponen | Lokasi | Catatan |
|---|---|---|
| App | Next.js 16 App Router, `src/app` | Port 3000, hash-routing di `/` |
| DB | SQLite `db/custom.db` via Prisma | Model: `ContactMessage` |
| Env | `.env` (`DATABASE_URL`) | Rahasia TIDAK di-commit |
| Log | `dev.log` (dev) / `server.log` (prod) | Selalu baca tail terbaru |
| Versi | `src/lib/version.ts` → `/api/version` | Sumber kebenaran SemVer |
| Kesehatan | `/api/health` | probe DB + latensi |

## 1. Restart (normal)
```bash
# Matikan proses lama, pastikan port bebas
ss -tlnp | grep 3000        # temukan PID next-server
kill <PID>
(bun run dev > /dev/null 2>&1 &)   # hidupkan di background
sleep 10 && curl -s http://localhost:3000/api/health
```

## 2. Situs mati / halaman putih — urutan diagnosa
1. `tail -50 dev.log` → cari error compile/hydration terbaru.
2. `curl -s -o /dev/null -w "%{http_code}" http://localhost:3000/` → 200?
3. `/api/health` → `db:"down"`? → lanjut langkah DB.
4. Hapus `.next` lalu restart: `rm -rf .next && (bun run dev &)` (cache korup).
5. Kalau crash berawal dari perubahan baru: **rollback dulu, debug kemudian** (Z5).

## 3. DB rusak / kosong
```bash
cp db/custom.db db/custom.db.bak.$(date +%F-%H%M)   # G3: salinan dulu!
bun run db:push          # rekonstruksi schema (data tabel inti biasanya aman)
```
- Data pesanan hilang total? → cek `.bak` terbaru; bila tidak ada, terima kehilangan
  lokal + cek backup hosting. **Form kontak adalah pintu utama lead — uji setelah pulih.**

## 4. Rollback versi (Z3)
1. Baca `CHANGELOG.md` → identifikasi versi terakhir sehat.
2. `git checkout <tag/commit>` kode tersebut; `.env` & `db/` TIDAK ikut.
3. Sinkronkan 4 titik versi (package.json, version.ts, CHANGELOG, worklog) saat naik lagi.

## 5. Backup harian (minimal)
```bash
tar czf backup-$(date +%F).tar.gz db/custom.db .env public/images
```
Simpan di luar mesin produksi bila memungkinkan (Git LFS/cloud). Data tak tergantikan:
`db/custom.db` (leads) + `public/images` (aset brand).

## 6. Recovery bencana (FUSE RESURRECTION)
Server hilang ≠ brand hilang:
1. `git clone https://github.com/prabudanling/bambu-water.git`
2. `bun install` → buat `.env` → `bun run db:generate && bun run db:push`
3. Pulihkan `db/custom.db` + `public/images` dari backup terbaru bila ada.
4. Baca `STATE.md` → lanjutkan penomoran versi terakhir. Catat reset di `worklog.md`.

## 7. Monitoring minimal
- Uptime: ping `GET /api/health` tiap 5 menit (UptimeRobot/cron) → alert bila `status != healthy`.
- Error: `grep -i "error" dev.log | tail` saat sesi buka; JSON-LD & sitemap dicek pasca-deploy:
  `curl -s localhost:3000/sitemap.xml | grep -c "<loc>"` → harus **≥ 2654**.

## 8. Keamanan cepat
- Rate limit kontak: 5 req/menit/IP (in-memory, `api/contact/route.ts`) → 429.
- Security headers: `next.config.ts` (nosniff, referrer, permissions).
- Rahasia hanya di `.env`; GA/Clarity via `NEXT_PUBLIC_*` (lihat `src/components/site/analytics.tsx`).

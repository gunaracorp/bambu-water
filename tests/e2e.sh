#!/usr/bin/env bash
# =============================================================
#  BWN PHP CMS v3 — E2E Test Suite (produksi-aman)
#  Menjalankan instance TERISOLASI (data/ JSON disalin ke folder
#  sementara via BWN_DATA_DIR) — TIDAK pernah menyentuh database
#  produksi. Jalankan dari root repo:  bash tests/e2e.sh
# =============================================================
set -u
cd "$(dirname "$0")/.."
ROOT="$(pwd)"
PHP_BIN="${PHP_BIN:-php}"
PORT="${BWN_TEST_PORT:-8091}"
BASE="http://127.0.0.1:$PORT"
PASS=0; FAIL=0; FAILED_TESTS=()

ok()   { PASS=$((PASS+1)); printf '  \033[32mOK\033[0m  %s\n' "$1"; }
bad()  { FAIL=$((FAIL+1)); FAILED_TESTS+=("$1"); printf '  \033[31mXX %s (got: %s)\033[0m\n' "$1" "$2"; }

code() { curl -s -o /dev/null -w '%{http_code}' "$@"; }
expect_code() { local name="$1" want="$2"; shift 2; local got; got="$(code "$@")"; [ "$got" = "$want" ] && ok "$name" || bad "$name" "$got (want $want)"; }
expect_contains() { local name="$1" needle="$2"; shift 2; local body; body="$(curl -s "$@")"; case "$body" in *"$needle"*) ok "$name";; *) bad "$name" "not found: $needle";; esac; }
expect_lacks() { local name="$1" needle="$2"; shift 2; local body; body="$(curl -s "$@")"; case "$body" in *"$needle"*) bad "$name" "found unwanted: $needle";; *) ok "$name";; esac; }
expect_header() { local name="$1" needle="$2"; shift 2; local h; h="$(curl -sI "$@")"; case "$h" in *"$needle"*) ok "$name";; *) bad "$name" "header missing: $needle";; esac; }

# ---------- 0. Instance terisolasi ----------
TMP_DATA="$(mktemp -d /tmp/bwn-e2e-data.XXXXXX)"
cp data/*.json "$TMP_DATA"/
JAR="$(mktemp /tmp/bwn-e2e-cookies.XXXXXX)"
env BWN_DATA_DIR="$TMP_DATA" "$PHP_BIN" -S "127.0.0.1:$PORT" index.php >/tmp/bwn-e2e-server.log 2>&1 &
SRV_PID=$!
trap 'kill $SRV_PID 2>/dev/null; rm -rf "$TMP_DATA" "$JAR"' EXIT
for i in $(seq 1 30); do curl -s -o /dev/null "$BASE/" && break; sleep 0.3; done
echo "Instance uji: $BASE (data terisolasi: $TMP_DATA)"

echo ""
echo "== 1. Rute publik inti =="
for r in / /kesehatan /kota /kota/bandung /air-bambu-untuk/demam /layanan /bambu /bambu/petung /artikel /faq /kontak /tentang-kami /harga-paket /testimoni /lacak-pesanan /karier /peta-situs /tag; do
  expect_code "GET $r" 200 "$BASE$r"
done
ART1="$(curl -s "$BASE/artikel" | grep -o 'href="/artikel/[a-z0-9-]*"' | head -1 | sed 's/href="//;s/"//')"
[ -n "$ART1" ] && expect_code "GET $ART1 (artikel pertama)" 200 "$BASE$ART1"
SVC1="$(curl -s "$BASE/layanan" | grep -o 'href="/layanan/[a-z0-9-]*"' | head -1 | sed 's/href="//;s/"//')"
[ -n "$SVC1" ] && expect_code "GET $SVC1 (layanan pertama)" 200 "$BASE$SVC1"

echo ""
echo "== 2. Rute teknis & SEO =="
expect_code "robots.txt" 200 "$BASE/robots.txt"
expect_code "sitemap.xml (index)" 200 "$BASE/sitemap.xml"
expect_code "feed.xml (RSS)" 200 "$BASE/feed.xml"
expect_code "llms.txt" 200 "$BASE/llms.txt"
expect_code "api/health" 200 "$BASE/api/health"
expect_contains "api/version = PHP + v3" '"version":"3.0.0.0"' "$BASE/api/version"
NPAGES="$(curl -s "$BASE/sitemap-pages.xml" | grep -c '<loc>' || true)"
[ "${NPAGES:-0}" -ge 2600 ] && ok "sitemap-pages >= 2600 URL ($NPAGES)" || bad "sitemap-pages >= 2600 URL" "${NPAGES:-0}"
NTAGS="$(curl -s "$BASE/sitemap-tags.xml" | grep -c '<loc>' || true)"
[ "${NTAGS:-0}" -ge 1300 ] && ok "sitemap-tags >= 1300 URL ($NTAGS)" || bad "sitemap-tags >= 1300 URL" "${NTAGS:-0}"

echo ""
echo "== 3. Kanonikalisasi URL (301) =="
expect_code "/kota/bandung/ -> 301" 301 "$BASE/kota/bandung/"
expect_code "/KESEHATAN -> 301" 301 "$BASE/KESEHATAN"
LOC="$(curl -s -o /dev/null -w '%{redirect_url}' "$BASE/kota/bandung/?lang=en")"
case "$LOC" in *"/kota/bandung?lang=en") ok "query ?lang=en dipertahankan saat 301";; *) bad "query ?lang=en dipertahankan saat 301" "$LOC";; esac

echo ""
echo "== 4. 404 sejati =="
expect_code "URL tak dikenal -> 404" 404 "$BASE/tidak-ada"
expect_code "slug kota tak dikenal -> 404" 404 "$BASE/kota/tidak-ada-kota-xyz"

echo ""
echo "== 5. Batas keamanan file =="
expect_code "config.php diblokir" 403 "$BASE/config.php"
expect_code "database sqlite diblokir" 403 "$BASE/data/cms.sqlite"
expect_code "data JSON diblokir" 403 "$BASE/data/company.json"
expect_code "lib/ diblokir" 403 "$BASE/lib/db.php"
expect_code "templates/ diblokir" 403 "$BASE/templates/layout.php"
expect_code "tools/ diblokir (web)" 404 "$BASE/tools/ping-indexnow.php"
expect_code "admin-cms.php langsung diblokir" 404 "$BASE/admin-cms.php"

echo ""
echo "== 6. Header keamanan =="
expect_header "CSP dengan nonce" "Content-Security-Policy:" "$BASE/"
expect_header "CSP script-src 'self'" "script-src 'self' 'nonce-" "$BASE/"
expect_header "X-Frame-Options" "X-Frame-Options: SAMEORIGIN" "$BASE/"
expect_header "nosniff" "X-Content-Type-Options: nosniff" "$BASE/"
expect_header "Referrer-Policy" "Referrer-Policy: strict-origin-when-cross-origin" "$BASE/"
expect_header "Permissions-Policy" "Permissions-Policy: camera=()" "$BASE/"
expect_header "Admin noindex" "X-Robots-Tag: noindex" "$BASE/admin"

echo ""
echo "== 7. Kualitas HTML & performa =="
HOMEPAGE="$(curl -s "$BASE/")"
case "$HOMEPAGE" in *'<html lang="id"'*) ok "lang=id";; *) bad "lang=id" "missing";; esac
case "$HOMEPAGE" in *'rel="canonical" href="https://bambuwater.web.id/"'*) ok "canonical absolut";; *) bad "canonical absolut" "missing";; esac
case "$HOMEPAGE" in *'og-image.jpg'*) ok "og:image 1200x630";; *) bad "og:image 1200x630" "missing";; esac
case "$HOMEPAGE" in *'fetchpriority="high"'*) ok "hero LCP fetchpriority=high";; *) bad "hero LCP fetchpriority=high" "missing";; esac
case "$HOMEPAGE" in *'width="1344" height="768"'*) ok "dimensi hero di-reserve (anti-CLS)";; *) bad "dimensi hero di-reserve (anti-CLS)" "missing";; esac
case "$HOMEPAGE" in *'fonts.googleapis.com'*) bad "tanpa Google Fonts eksternal" "masih ada";; *) ok "tanpa Google Fonts eksternal";; esac
case "$HOMEPAGE" in *'application/ld+json'*) ok "JSON-LD ada";; *) bad "JSON-LD ada" "missing";; esac
case "$HOMEPAGE" in *'onclick='*) bad "tanpa handler onclick inline (CSP-safe)" "masih ada";; *) ok "tanpa handler onclick inline (CSP-safe)";; esac
expect_lacks "admin tidak bocor di halaman publik" 'bwn_admin' "$BASE/"
ENPAGE="$(curl -s "$BASE/?lang=en")"
case "$ENPAGE" in *'<html lang="en"'*) ok "?lang=en -> html lang=en";; *) bad "?lang=en -> html lang=en" "missing";; esac
LD="$(curl -s "$BASE/" | tr -d '\n' | grep -o '<script type="application/ld+json">[^<]*</script>' | head -1 | sed 's/<script type="application\/ld+json">//;s/<\/script>//')"
if [ -n "$LD" ] && echo "$LD" | python3 -c "import json,sys; json.load(sys.stdin)" 2>/dev/null; then ok "JSON-LD valid (parse OK)"; else bad "JSON-LD valid (parse OK)" "invalid"; fi
expect_code "assets woff2 tersedia" 200 "$BASE/assets/fonts/plus-jakarta-sans-var.woff2"
expect_code "og-image tersedia" 200 "$BASE/assets/images/og-image.jpg"

echo ""
echo "== 8. Cache file (hemat CPU shared hosting) =="
curl -s -o /dev/null "$BASE/search-index.json"
expect_header "search-index cache HIT (ke-2)" "X-Cache: HIT" "$BASE/search-index.json"
curl -s -o /dev/null "$BASE/sitemap-pages.xml"
expect_header "sitemap cache HIT (ke-2)" "X-Cache: HIT" "$BASE/sitemap-pages.xml"

echo ""
echo "== 9. Form kontak (CSRF + honeypot + validasi) =="
CSRF="$(curl -s -c "$JAR" "$BASE/kontak" | grep -o 'name="csrf" value="[a-f0-9]*"' | head -1 | grep -o '[a-f0-9]\{32,\}')"
[ -n "$CSRF" ] && ok "token CSRF form kontak" || bad "token CSRF form kontak" "empty"
LOC="$(curl -s -o /dev/null -w '%{redirect_url}' -X POST -b "$JAR" -c "$JAR" \
  --data-urlencode "csrf=$CSRF" --data-urlencode "name=Budi E2E" \
  --data-urlencode "phone=081234567890" --data-urlencode "city=Tasikmalaya" \
  --data-urlencode "order_type=air-bambu" --data-urlencode "message=Halo, ini pesan uji E2E otomatis." \
  "$BASE/kontak")"
case "$LOC" in *"?ok=1") ok "POST valid -> redirect ?ok=1";; *) bad "POST valid -> redirect ?ok=1" "$LOC";; esac
LOC="$(curl -s -o /dev/null -w '%{redirect_url}' -X POST -b "$JAR" \
  --data-urlencode "csrf=$CSRF" --data-urlencode "website=spam.bot.example" \
  --data-urlencode "name=Bot Spam" --data-urlencode "phone=08111111111" \
  --data-urlencode "order_type=air-bambu" --data-urlencode "message=coba honeypot ya" "$BASE/kontak")"
case "$LOC" in *"?ok=1") ok "honeypot -> pura-pura sukses";; *) bad "honeypot -> pura-pura sukses" "$LOC";; esac
LOC="$(curl -s -o /dev/null -w '%{redirect_url}' -X POST -b "$JAR" \
  --data-urlencode "csrf=$CSRF" --data-urlencode "name=X" --data-urlencode "phone=123" \
  --data-urlencode "order_type=air-bambu" --data-urlencode "message=pendek" "$BASE/kontak")"
case "$LOC" in *"?err="*) ok "validasi menolak input buruk";; *) bad "validasi menolak input buruk" "$LOC";; esac

echo ""
echo "== 10. Admin CMS (lifecycle lengkap) =="
rm -f "$JAR"
CSRF="$(curl -s -c "$JAR" "$BASE/admin" | grep -o 'name="csrf" value="[a-f0-9]*"' | head -1 | grep -o '[a-f0-9]\{32,\}')"
expect_contains "login salah ditolak" "Username atau password salah." -X POST -b "$JAR" -c "$JAR" \
  --data-urlencode "username=admin" --data-urlencode "password=salah-total" \
  --data-urlencode "csrf=$CSRF" "$BASE/admin"
sleep 1.1
CSRF="$(curl -s -b "$JAR" -c "$JAR" "$BASE/admin" | grep -o 'name="csrf" value="[a-f0-9]*"' | head -1 | grep -o '[a-f0-9]\{32,\}')"
LOC="$(curl -s -o /dev/null -w '%{redirect_url}' -X POST -b "$JAR" -c "$JAR" \
  --data-urlencode "username=admin" --data-urlencode "password=bambuwater2025" \
  --data-urlencode "csrf=$CSRF" "$BASE/admin")"
case "$LOC" in *"/admin/akun?must=1"*) ok "login sukses -> wajib ganti password";; *) bad "login sukses -> wajib ganti password" "$LOC";; esac
expect_code "dashboard terkunci sampai ganti password (302)" 302 -b "$JAR" "$BASE/admin"
expect_code "halaman akun terbuka" 200 -b "$JAR" "$BASE/admin/akun"
CSRF="$(curl -s -b "$JAR" -c "$JAR" "$BASE/admin/akun" | grep -o 'name="csrf" value="[a-f0-9]*"' | head -1 | grep -o '[a-f0-9]\{32,\}')"
expect_contains "ganti password sukses" "Password berhasil diganti" -X POST -b "$JAR" -c "$JAR" \
  --data-urlencode "act=pass_change" --data-urlencode "current=bambuwater2025" \
  --data-urlencode "new=Bwn-E2E-2026!x" --data-urlencode "csrf=$CSRF" "$BASE/admin/akun"
expect_code "dashboard terbuka setelah ganti password" 200 -b "$JAR" "$BASE/admin"
expect_code "POST admin tanpa CSRF -> 419" 419 -X POST -b "$JAR" --data-urlencode "act=settings" "$BASE/admin/pengaturan"
MSGS="$(curl -s -b "$JAR" "$BASE/admin/pesan")"
case "$MSGS" in *"Budi E2E"*) ok "pesan publik terlihat di admin";; *) bad "pesan publik terlihat di admin" "not found";; esac
case "$MSGS" in *"Bot Spam"*) bad "honeypot TIDAK disimpan" "Bot Spam muncul";; *) ok "honeypot tidak tersimpan di DB";; esac
CSRF="$(curl -s -b "$JAR" -c "$JAR" "$BASE/admin/pengaturan" | grep -o 'name="csrf" value="[a-f0-9]*"' | head -1 | grep -o '[a-f0-9]\{32,\}')"
expect_contains "pengaturan tersimpan" "Pengaturan tersimpan." -X POST -b "$JAR" -c "$JAR" \
  --data-urlencode "act=settings" --data-urlencode "site_name=Bambu Water Nusantara" \
  --data-urlencode "phone=+62 813-1651-6524" --data-urlencode "whatsapp=6281316516524" \
  --data-urlencode "email=halo@bambuwater.web.id" --data-urlencode "notify_email=pemilik@bambuwater.web.id" \
  --data-urlencode "site_url=https://bambuwater.web.id" --data-urlencode "csrf=$CSRF" "$BASE/admin/pengaturan"
ASLUG="${ART1#/artikel/}"
CSRF="$(curl -s -b "$JAR" -c "$JAR" "$BASE/admin/artikel" | grep -o 'name="csrf" value="[a-f0-9]*"' | head -1 | grep -o '[a-f0-9]\{32,\}')"
curl -s -o /dev/null -X POST -b "$JAR" -c "$JAR" \
  --data-urlencode "act=article_save" --data-urlencode "slug=$ASLUG" \
  --data-urlencode "title=Judul Override E2E" --data-urlencode "description=Deskripsi override E2E" \
  --data-urlencode "body=Catatan tambahan dari CMS E2E." --data-urlencode "csrf=$CSRF" "$BASE/admin/artikel"
expect_contains "override artikel tampil di publik" "Judul Override E2E" -b "$JAR" "$BASE$ART1"
MID="$(curl -s -b "$JAR" "$BASE/admin/pesan" | grep -o 'name="id" value="[0-9]*"' | head -1 | sed 's/.*value="//;s/"//')"
CSRF="$(curl -s -b "$JAR" -c "$JAR" "$BASE/admin/pesan" | grep -o 'name="csrf" value="[a-f0-9]*"' | head -1 | grep -o '[a-f0-9]\{32,\}')"
if [ -n "${MID:-}" ]; then
  expect_contains "status pesan -> selesai" "Status pesan diperbarui." -X POST -b "$JAR" -c "$JAR" \
    --data-urlencode "act=msg_status" --data-urlencode "id=$MID" --data-urlencode "status=done" --data-urlencode "csrf=$CSRF" "$BASE/admin/pesan"
  expect_contains "hapus pesan" "Pesan dihapus." -X POST -b "$JAR" -c "$JAR" \
    --data-urlencode "act=msg_delete" --data-urlencode "id=$MID" --data-urlencode "csrf=$CSRF" "$BASE/admin/pesan"
else
  bad "toggle status pesan" "message id not found"
fi
expect_code "logout -> 302" 302 -b "$JAR" -c "$JAR" "$BASE/admin/logout"
LOGOUTPAGE="$(curl -s -b "$JAR" "$BASE/admin")"
case "$LOGOUTPAGE" in *"Admin CMS"*) ok "setelah logout kembali ke form login";; *) bad "setelah logout kembali ke form login" "unexpected";; esac
expect_code "aksi admin tanpa sesi -> 302 (login)" 302 -X POST --data-urlencode "act=settings" "$BASE/admin/pengaturan"

echo ""
echo "== 11. Cookie sesi =="
H=$(curl -sI -c "$JAR" "$BASE/admin")
case "$H" in *HttpOnly*) ok "session cookie HttpOnly";; *) bad "session cookie HttpOnly" "flag missing";; esac

echo ""
echo "==============================================================="
echo "HASIL: $PASS lulus, $FAIL gagal"
if [ $FAIL -gt 0 ]; then
  printf '  Gagal:\n'; printf '   - %s\n' "${FAILED_TESTS[@]}"
  exit 1
fi
exit 0

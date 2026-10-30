<?php
/**
 * ADMIN CMS — login aman + dashboard + pesan + pengaturan + artikel.
 * URL: /admin (atau /admin/pesan, /admin/pengaturan, /admin/artikel)
 * Keamanan: password_hash, session regenerasi, CSRF, kunci waktu (brake sederhana).
 */
declare(strict_types=1);

/* Dua moda akses: via router utama (route di $_GET) ATAU langsung oleh php -S
   (directory-index menculik /admin/* → PATH_INFO). Keduanya di-handle di sini. */
if (!defined('APP_VERSION')) {
    require dirname(__DIR__) . '/config.php';
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $route = $_SERVER['PATH_INFO'] ?? '/';
} else {
    $route = $_GET['route'] ?? '/';
}

$route = '/' . trim($route, '/');
$authed = !empty($_SESSION['bwn_admin']);

/* ---------- Logout ---------- */
if ($route === '/logout') {
    session_destroy();
    header('Location: ' . site_url_path('/admin'));
    exit;
}

/* ---------- Login ---------- */
$loginError = '';
if (!$authed && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($route === '/' || $route === '/login')) {
    if (!csrf_ok()) {
        $loginError = 'Sesi kedaluwarsa — coba lagi.';
    } else {
        // Brake anti-bruteforce: min 1 detik antar percobaan per sesi
        $last = $_SESSION['bwn_last_login_try'] ?? 0;
        if (time() - $last < 1) {
            $loginError = 'Terlalu cepat — tunggu sebentar.';
        } else {
            $_SESSION['bwn_last_login_try'] = time();
            $u = trim((string) ($_POST['username'] ?? ''));
            $p = (string) ($_POST['password'] ?? '');
            $st = db()->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
            $st->execute([$u]);
            $user = $st->fetch();
            if ($user && password_verify($p, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['bwn_admin'] = (int) $user['id'];
                $authed = true;
            } else {
                $loginError = 'Username atau password salah.';
            }
        }
    }
}

if (!$authed) {
    $csrf = csrf_token();
    ?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin — <?= e(APP_NAME) ?></title><link rel="stylesheet" href="<?= site_url_path('/assets/style.css') ?>?v=<?= APP_VERSION ?>"></head>
    <body class="admin-body"><div style="min-height:100vh;display:grid;place-items:center;padding:20px">
      <form method="post" class="card" style="width:min(400px,100%);padding:34px">
        <h2 style="margin-top:0">🎋 Admin CMS</h2>
        <p style="color:var(--muted);font-size:.9rem"><?= e(APP_NAME) ?> — v<?= APP_VERSION ?> (<?= e(APP_CODENAME) ?>)</p>
        <?php if ($loginError): ?><div class="alert alert-err"><?= e($loginError) ?></div><?php endif; ?>
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <div class="field"><label>Username</label><input name="username" required autocomplete="username"></div>
        <div class="field"><label>Password</label><input name="password" type="password" required autocomplete="current-password"></div>
        <button class="btn btn-primary" style="width:100%;justify-content:center">Masuk →</button>
        <p style="font-size:.8rem;color:var(--muted);margin-top:14px">Login pertama: <code>admin</code> / <code><?= e(ADMIN_DEFAULT_PASS) ?></code> — <b>segera ganti di menu Akun</b>.</p>
      </form>
    </div></body></html><?php
    exit;
}

/* ---------- Aksi POST (semua wajib CSRF) ---------- */
$flash = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_ok()) {
        http_response_code(419);
        exit('CSRF invalid');
    }
    $act = $_POST['act'] ?? '';
    if ($act === 'msg_status') {
        db()->prepare('UPDATE contact_messages SET status = ? WHERE id = ?')
            ->execute([($_POST['status'] ?? 'done') === 'done' ? 'done' : 'new', (int) $_POST['id']]);
        $flash = 'Status pesan diperbarui.';
    } elseif ($act === 'msg_delete') {
        db()->prepare('DELETE FROM contact_messages WHERE id = ?')->execute([(int) $_POST['id']]);
        $flash = 'Pesan dihapus.';
    } elseif ($act === 'settings') {
        $st = db()->prepare('INSERT INTO settings (k, v) VALUES (?, ?) ON CONFLICT(k) DO UPDATE SET v = excluded.v');
        foreach (['site_name', 'phone', 'whatsapp', 'email', 'site_url'] as $k) {
            $st->execute([$k, trim((string) ($_POST[$k] ?? ''))]);
        }
        $flash = 'Pengaturan tersimpan.';
    } elseif ($act === 'article_save') {
        db()->prepare('INSERT INTO article_overrides (slug, title, description, body) VALUES (?, ?, ?, ?)
            ON CONFLICT(slug) DO UPDATE SET title = excluded.title, description = excluded.description, body = excluded.body')
            ->execute([trim((string) $_POST['slug']), trim((string) $_POST['title']), trim((string) $_POST['description']), trim((string) $_POST['body'])]);
        $flash = 'Artikel tersimpan (override aktif).';
        /* SEO Universalis: ping IndexNow agar artikel baru terindeks Bing/Yandex dalam hitungan menit */
        $artUrl = rtrim(setting('site_url', SITE_URL), '/') . '/artikel/' . urlencode(trim((string) $_POST['slug']));
        $flash .= indexnow_ping([$artUrl]) ? ' IndexNow ✓' : '';
    } elseif ($act === 'pass_change') {
        $cur = (string) $_POST['current'];
        $new = (string) $_POST['new'];
        if (strlen($new) < 8) {
            $flash = 'Password baru minimal 8 karakter.';
        } else {
            $st = db()->prepare('SELECT * FROM admin_users WHERE id = ?');
            $st->execute([$_SESSION['bwn_admin']]);
            $u = $st->fetch();
            if (!$u || !password_verify($cur, $u['password_hash'])) {
                $flash = 'Password saat ini salah.';
            } else {
                db()->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')
                    ->execute([password_hash($new, PASSWORD_DEFAULT), (int) $u['id']]);
                $flash = 'Password berhasil diganti. 🎉';
            }
        }
    }
}

/* ---------- Data halaman admin ---------- */
$counts = db()->query('SELECT COUNT(*) c, SUM(CASE WHEN status = "new" THEN 1 ELSE 0 END) n FROM contact_messages')->fetch();
$statsRow = [
    'Pesan Masuk' => (int) ($counts['c'] ?? 0),
    'Belum Diproses' => (int) ($counts['n'] ?? 0),
    'Halaman Publik' => 2644,
    'Versi CMS' => 'v' . APP_VERSION,
];
$messages = db()->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 100')->fetchAll();
$settings = [];
foreach (db()->query('SELECT k, v FROM settings') as $r) {
    $settings[$r['k']] = $r['v'];
}
$articles = json_load('articles');

$nav = static function (string $cur): void {
    $items = ['/' => 'Dashboard', '/pesan' => 'Pesan', '/artikel' => 'Artikel', '/pengaturan' => 'Pengaturan', '/akun' => 'Akun'];
    echo '<nav class="admin-nav" aria-label="Admin"><div class="container">';
    foreach ($items as $h => $label) {
        $href = site_url_path('/admin' . ($h === '/' ? '' : $h));
        echo '<a href="' . e($href) . '"' . ($cur === $h ? ' style="color:#fff;font-weight:800"' : '') . '>' . e($label) . '</a>';
    }
    echo '<a style="float:right;color:#fbb" href="' . e(site_url_path('/admin/logout')) . '">Keluar</a></div></nav>';
};

$page = $route === '/' ? 'dashboard' : trim($route, '/');
?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin — <?= e(APP_NAME) ?></title><link rel="stylesheet" href="<?= site_url_path('/assets/style.css') ?>?v=<?= APP_VERSION ?>"></head>
<body class="admin-body"><?php $nav($page); ?>
<div class="container" style="padding:30px 0 60px">
<?php if ($flash): ?><div class="alert alert-ok"><?= e($flash) ?></div><?php endif; ?>

<?php if ($page === 'dashboard'): ?>
  <h2>Dashboard</h2>
  <div class="grid grid-4" style="margin-top:16px">
    <?php foreach ($statsRow as $label => $val): ?>
      <div class="card"><b style="font-size:1.7rem;font-family:var(--font-display)"><?= e((string) $val) ?></b><p style="margin:0"><?= e($label) ?></p></div>
    <?php endforeach; ?>
  </div>
  <h3 style="margin-top:34px">Pesan Terbaru</h3>
  <table class="table"><thead><tr><th>Waktu</th><th>Nama</th><th>Kontak</th><th>Jenis</th><th>Pesan</th><th>Status</th></tr></thead><tbody>
    <?php foreach (array_slice($messages, 0, 8) as $m): ?>
      <tr><td><?= e(substr($m['created_at'], 0, 16)) ?></td><td><b><?= e($m['name']) ?></b></td><td><?= e($m['phone']) ?></td><td><?= e($m['order_type']) ?></td><td style="max-width:280px"><?= e(mb_substr($m['message'], 0, 80)) ?>…</td><td><span class="pill <?= $m['status'] === 'new' ? 'pill-new' : 'pill-done' ?>"><?= e($m['status']) ?></span></td></tr>
    <?php endforeach; if (!$messages): ?><tr><td colspan="6" style="text-align:center;color:var(--muted)">Belum ada pesan masuk.</td></tr><?php endif; ?>
  </tbody></table>

<?php elseif ($page === 'pesan'): ?>
  <h2>Pesan Masuk (<?= count($messages) ?> terakhir)</h2>
  <table class="table" style="margin-top:16px"><thead><tr><th>Waktu</th><th>Nama</th><th>Phone</th><th>Email</th><th>Kota</th><th>Jenis</th><th>Pesan</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($messages as $m): ?>
      <tr>
        <td><?= e(substr($m['created_at'], 0, 16)) ?></td>
        <td><b><?= e($m['name']) ?></b></td>
        <td><a href="https://wa.me/<?= e(preg_replace('/\D/', '', $m['phone'])) ?>" target="_blank" rel="noopener"><?= e($m['phone']) ?></a></td>
        <td><?= e($m['email'] ?? '—') ?></td>
        <td><?= e($m['city'] ?? '—') ?></td>
        <td><?= e($m['order_type']) ?></td>
        <td style="max-width:260px"><?= e($m['message']) ?></td>
        <td style="white-space:nowrap">
          <form method="post" style="display:inline"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="act" value="msg_status"><input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
            <input type="hidden" name="status" value="<?= $m['status'] === 'new' ? 'done' : 'new' ?>">
            <button class="pill <?= $m['status'] === 'new' ? 'pill-new' : 'pill-done' ?>" style="border:0;cursor:pointer"><?= $m['status'] === 'new' ? 'Tandai selesai' : 'Buka lagi' ?></button></form>
          <form method="post" style="display:inline;margin-left:6px" onsubmit="return confirm('Hapus pesan ini?')"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="act" value="msg_delete"><input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
            <button class="pill pill-dim" style="border:0;cursor:pointer">Hapus</button></form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody></table>

<?php elseif ($page === 'pengaturan'): ?>
  <h2>Pengaturan Situs</h2>
  <form method="post" class="card" style="max-width:620px;margin-top:16px;padding:30px">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="act" value="settings">
    <div class="field"><label>Nama Situs</label><input name="site_name" value="<?= e($settings['site_name'] ?? APP_NAME) ?>"></div>
    <div class="field"><label>Telepon Tampil</label><input name="phone" value="<?= e($settings['phone'] ?? DEFAULT_PHONE) ?>"></div>
    <div class="field"><label>WhatsApp (format 62…)</label><input name="whatsapp" value="<?= e($settings['whatsapp'] ?? DEFAULT_WA) ?>"></div>
    <div class="field"><label>Email</label><input name="email" value="<?= e($settings['email'] ?? DEFAULT_EMAIL) ?>"></div>
    <div class="field"><label>URL Situs (untuk canonical & sitemap)</label><input name="site_url" value="<?= e($settings['site_url'] ?? SITE_URL) ?>"></div>
    <button class="btn btn-primary">Simpan Pengaturan</button>
  </form>

<?php elseif ($page === 'artikel'): ?>
  <h2>Kelola Artikel</h2>
  <p style="color:var(--muted)">Pilih artikel untuk mengubah judul, deskripsi SEO, dan isi ringkas. Perubahan tersimpan sebagai <b>override</b> di database — data asli JSON tetap aman (G1/Z2).</p>
  <?php $ovr = [];
  foreach (db()->query('SELECT slug FROM article_overrides') as $r) {
      $ovr[$r['slug']] = true;
  } ?>
  <div class="grid grid-3" style="margin-top:16px">
    <?php foreach ($articles as $a): ?>
      <details class="faq"><summary><?= ($ovr[$a['slug']] ?? false) ? '✏️ ' : '' ?><?= e($a['title']) ?></summary>
        <div class="faq-body">
        <form method="post">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="act" value="article_save">
          <input type="hidden" name="slug" value="<?= e($a['slug']) ?>">
          <div class="field"><label>Judul</label><input name="title" value="<?= e($a['title']) ?>"></div>
          <div class="field"><label>Deskripsi SEO</label><input name="description" value="<?= e($a['metaDescription'] ?? '') ?>"></div>
          <div class="field"><label>Catatan isi (tambahan tampil di bawah artikel)</label><textarea name="body" rows="4"><?= '' ?></textarea></div>
          <button class="btn btn-primary" style="padding:9px 18px;font-size:.88rem">Simpan</button>
        </form>
        </div>
      </details>
    <?php endforeach; ?>
  </div>

<?php elseif ($page === 'akun'): ?>
  <h2>Ganti Password</h2>
  <form method="post" class="card" style="max-width:460px;margin-top:16px;padding:30px">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="act" value="pass_change">
    <div class="field"><label>Password saat ini</label><input type="password" name="current" required></div>
    <div class="field"><label>Password baru (min 8)</label><input type="password" name="new" required minlength="8"></div>
    <button class="btn btn-primary">Ganti Password</button>
  </form>

<?php else: ?>
  <h2>404 — Menu tidak ada</h2>
<?php endif; ?>
</div>
</body></html>

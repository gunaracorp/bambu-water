<?php
/**
 * Handler POST /kontak — paritas dengan API Next.js:
 * validasi, CSRF, rate limit 5/menit/IP, simpan ke SQLite, redirect dengan status.
 */
declare(strict_types=1);

$fail = static function (string $msg): never {
    header('Location: ' . site_url_path('/kontak?err=' . rawurlencode($msg)));
    exit;
};

if (!csrf_ok()) {
    $fail('Sesi kedaluwarsa — muat ulang halaman lalu coba lagi.');
}
if (rate_limited('contact', 5, 60)) {
    $fail('Terlalu banyak permintaan. Coba lagi dalam satu menit.');
}

$in = static fn (string $k): string => trim((string) ($_POST[$k] ?? ''));

$name = mb_substr($in('name'), 0, 120);
$phone = mb_substr($in('phone'), 0, 30);
$email = mb_substr($in('email'), 0, 200);
$city = mb_substr($in('city'), 0, 80);
$orderType = $in('order_type');
$message = mb_substr($in('message'), 0, 2000);

if (mb_strlen($name) < 2) {
    $fail('Nama minimal 2 karakter.');
}
if (mb_strlen($phone) < 8 || !preg_match('/^[0-9+\-\s()]{8,30}$/', $phone)) {
    $fail('Nomor telepon tidak valid.');
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $fail('Format email tidak valid.');
}
if (!in_array($orderType, ['air-bambu', 'bambu-batang', 'grosir', 'korporat'], true)) {
    $orderType = 'air-bambu';
}
if (mb_strlen($message) < 5) {
    $fail('Pesan minimal 5 karakter.');
}

try {
    $st = db()->prepare('INSERT INTO contact_messages (name, email, phone, city, order_type, message) VALUES (?, ?, ?, ?, ?, ?)');
    $st->execute([$name, $email !== '' ? $email : null, $phone, $city !== '' ? $city : null, $orderType, $message]);
} catch (Throwable) {
    $fail('Terjadi kesalahan server. Silakan coba lagi.');
}

header('Location: ' . site_url_path('/kontak?ok=1'));
exit;

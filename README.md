# Soccer Bumi Teuku Umar — Sistem Booking Lapangan Futsal Online

Aplikasi booking lapangan futsal berbasis Laravel untuk **Soccer Bumi Teuku Umar**. Mendukung booking sekali main untuk pengunjung umum (guest) maupun paket langganan bulanan untuk member terdaftar, lengkap dengan pembayaran online (Midtrans), pembayaran tunai di tempat, dan panel admin untuk mengelola semuanya.

## Fitur Utama

- **Multi-lapangan** — tiap lapangan punya jadwal, harga, dan status buka/tutup sendiri.
- **Tiga aktor**: Admin (kelola semuanya), Member (booking paket bulanan berulang), Guest (booking sekali main tanpa akun, cukup nama + no. HP).
- **Booking multi-jam** — pilih beberapa slot jam berurutan sekaligus dalam satu pemesanan.
- **Paket bulanan member** — pilih hari + jam + bulan, sistem otomatis membuatkan tepat 4 kali pertemuan mingguan berturut-turut.
- **Harga dinamis**: weekday/weekend dan member/non-member (4 tingkat harga per slot).
- **Layanan tambahan** (add-on) yang dihitung per jam main, dikelola bebas oleh admin (CRUD).
- **Pembayaran DP 25%** — booking terkonfirmasi begitu DP minimal terbayar, sisanya bisa dilunasi (guest) atau dicicil bertahap (member) kapan saja, online lewat Midtrans Snap maupun tunai langsung di lapangan (dikonfirmasi manual oleh admin lewat panel kasir).
- **Anti-double-booking**: row locking di level lapangan + pengecekan overlap rentang jam, aman untuk booking konkuren.
- **Notifikasi email** ke member saat paket aktif, saat paket kedaluwarsa, dan setiap ada pembayaran (cicilan/pelunasan) yang diterima.
- **Laporan pendapatan** admin (dipecah member/guest, online/cash) dengan export CSV.

## Tech Stack

- Laravel 12, PHP 8.2
- MySQL/MariaDB
- Autentikasi manual (tanpa Breeze/Jetstream) — role `admin`/`member` lewat middleware
- Midtrans Snap (payment gateway)
- Tailwind CSS v4
- Queue driver `database` (dipakai untuk webhook Midtrans & notifikasi email)

## Instalasi

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Atur koneksi database, kredensial Midtrans sandbox, dan konfigurasi SMTP di `.env`:

```env
DB_DATABASE=mysoc
DB_USERNAME=root
DB_PASSWORD=

MIDTRANS_SERVER_KEY=Mid-server-xxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=Mid-client-xxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email-pengirim@gmail.com
MAIL_PASSWORD="app password 16 karakter"
```

Lalu siapkan database:

```bash
php artisan migrate --seed
npm run build
```

Seeder membuatkan akun admin default:

| Email | Password |
|---|---|
| `admin@mysoc.test` | `password` |

## Menjalankan Secara Lokal

Butuh 3 proses berjalan bersamaan untuk fungsionalitas penuh:

```bash
php artisan serve            # web server
php artisan queue:work       # proses webhook Midtrans & kirim email notifikasi
php artisan schedule:work    # pelepasan otomatis slot yang DP-nya tidak kunjung dibayar (>15 menit)
```

> Kalau `.env` diubah (misalnya kredensial SMTP), **restart `queue:work`** — proses ini membaca config sekali saat start, tidak otomatis baca ulang `.env` selama berjalan.

Untuk tes pembayaran Midtrans dari localhost, webhook butuh URL publik — pakai [ngrok](https://ngrok.com) (`ngrok http 8000`) lalu daftarkan `https://<subdomain-ngrok>.ngrok.io/webhooks/midtrans` sebagai Payment Notification URL di dashboard Midtrans Sandbox.

## Struktur Penting

- `app/Services/` — logic bisnis inti: `BookingService`, `SubscriptionService`, `AvailabilityService`, `PaymentService`, `ReportService`. Controller & model sengaja tetap tipis.
- `app/Jobs/ProcessMidtransNotification.php` — pemrosesan webhook Midtrans secara idempotent lewat queue.
- `app/Notifications/` — email ke member (paket aktif, paket kedaluwarsa, pembayaran diterima).
- `app/Support/StatusLabel.php` — terjemahan semua status (booking/paket/pembayaran) ke Bahasa Indonesia untuk ditampilkan di UI.
- `resources/views/components/layouts/` — `site.blade.php` (guest+member, navbar) dan `admin.blade.php` (sidebar admin).

## Testing

```bash
php artisan test
```

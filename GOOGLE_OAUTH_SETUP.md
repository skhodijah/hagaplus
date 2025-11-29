# Setup Google OAuth untuk Haga+

Panduan ini akan membantu Anda mengkonfigurasi Google OAuth untuk aplikasi Haga+.

## Langkah 1: Membuat Project di Google Cloud Console

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih project yang sudah ada
3. Pastikan project Anda sudah aktif

## Langkah 2: Mengaktifkan Google+ API

1. Di Google Cloud Console, buka menu **APIs & Services** > **Library**
2. Cari "Google+ API" atau "Google People API"
3. Klik **Enable** untuk mengaktifkan API

## Langkah 3: Membuat OAuth 2.0 Credentials

1. Buka menu **APIs & Services** > **Credentials**
2. Klik **Create Credentials** > **OAuth client ID**
3. Jika diminta, konfigurasikan OAuth consent screen terlebih dahulu:
   - Pilih **External** untuk user type
   - Isi informasi aplikasi:
     - App name: `Haga+`
     - User support email: email Anda
     - Developer contact information: email Anda
   - Klik **Save and Continue**
   - Pada bagian Scopes, tambahkan scope berikut:
     - `.../auth/userinfo.email`
     - `.../auth/userinfo.profile`
   - Klik **Save and Continue**
   - Tambahkan test users jika diperlukan
   - Klik **Save and Continue**

4. Kembali ke **Credentials** > **Create Credentials** > **OAuth client ID**
5. Pilih **Application type**: Web application
6. Isi informasi:
   - Name: `Haga+ Web Client`
   - Authorized JavaScript origins:
     - `http://localhost:8000`
     - `http://127.0.0.1:8000`
     - URL production Anda (jika ada)
   - Authorized redirect URIs:
     - `http://localhost:8000/auth/google/callback`
     - `http://127.0.0.1:8000/auth/google/callback`
     - URL production Anda + `/auth/google/callback` (jika ada)
7. Klik **Create**
8. Simpan **Client ID** dan **Client Secret** yang muncul

## Langkah 4: Konfigurasi File .env

1. Buka file `.env` di root project Anda
2. Tambahkan atau update konfigurasi berikut:

```env
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

3. Ganti `your-client-id-here` dengan Client ID dari Google Cloud Console
4. Ganti `your-client-secret-here` dengan Client Secret dari Google Cloud Console
5. Pastikan `APP_URL` sudah sesuai dengan URL aplikasi Anda

## Langkah 5: Clear Cache (Opsional)

Jalankan perintah berikut untuk memastikan konfigurasi terbaru digunakan:

```bash
php artisan config:clear
php artisan cache:clear
```

## Cara Menggunakan

1. Buka halaman login aplikasi: `http://localhost:8000/login`
2. Klik tombol **Login dengan Google**
3. Pilih akun Google yang ingin digunakan
4. Berikan izin yang diminta
5. Anda akan diarahkan kembali ke aplikasi dan otomatis login

## Catatan Penting

- **User Baru**: Ketika user login pertama kali dengan Google, sistem akan otomatis membuat akun baru dengan role `employee` (jika role tersebut ada di database)
- **User Existing**: Jika email Google sudah terdaftar di sistem, user akan langsung login tanpa membuat akun baru
- **Password**: User yang login dengan Google tidak memerlukan password
- **Redirect**: Setelah login, user akan diarahkan ke dashboard sesuai role mereka:
  - Admin → `/admin/dashboard`
  - Employee → `/employee/dashboard`

## Troubleshooting

### Error: "redirect_uri_mismatch"
- Pastikan redirect URI di Google Cloud Console sama persis dengan yang ada di aplikasi
- Periksa apakah ada trailing slash atau perbedaan http/https

### Error: "invalid_client"
- Periksa kembali Client ID dan Client Secret di file `.env`
- Pastikan tidak ada spasi atau karakter tersembunyi

### Error: "access_denied"
- User membatalkan proses login
- Atau aplikasi belum disetujui di OAuth consent screen

### User tidak bisa login
- Pastikan migration sudah dijalankan: `php artisan migrate`
- Periksa apakah kolom `google_id` sudah ada di tabel `users`
- Cek log error di `storage/logs/laravel.log`

## Keamanan

- Jangan commit file `.env` ke repository
- Simpan Client Secret dengan aman
- Untuk production, gunakan HTTPS
- Batasi authorized redirect URIs hanya untuk domain yang valid
- Review dan update OAuth consent screen secara berkala

## Fitur yang Diimplementasikan

✅ Login dengan Google  
✅ Auto-create user baru  
✅ Link akun existing dengan Google ID  
✅ Role-based redirect setelah login  
✅ Error handling dan logging  
✅ Support untuk user tanpa password  

## File yang Terlibat

- `app/Http/Controllers/Auth/GoogleAuthController.php` - Controller untuk OAuth
- `routes/web.php` - Routes untuk Google OAuth
- `config/services.php` - Konfigurasi Google OAuth
- `app/Models/Core/User.php` - Model User dengan google_id
- `database/migrations/2025_11_29_065708_add_google_id_to_users_table.php` - Migration
- `resources/views/auth/login.blade.php` - Halaman login dengan tombol Google

## Support

Jika mengalami masalah, silakan:
1. Periksa log error di `storage/logs/laravel.log`
2. Verifikasi semua konfigurasi sudah benar
3. Pastikan Google Cloud Console sudah dikonfigurasi dengan benar

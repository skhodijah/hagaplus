# Testing Google OAuth

Panduan untuk testing fitur Google OAuth yang sudah diimplementasikan.

## Persiapan Testing

### 1. Pastikan Migration Sudah Dijalankan

```bash
php artisan migrate
```

Pastikan kolom `google_id` sudah ada di tabel `users`.

### 2. Seed System Roles (Opsional)

Jika belum ada role `employee` di database, jalankan seeder:

```bash
php artisan db:seed --class=SystemRoleSeeder
```

### 3. Setup Google OAuth Credentials

Ikuti panduan di `GOOGLE_OAUTH_SETUP.md` untuk mendapatkan credentials dari Google Cloud Console.

Update file `.env`:
```env
GOOGLE_CLIENT_ID=your-actual-client-id
GOOGLE_CLIENT_SECRET=your-actual-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### 4. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

## Skenario Testing

### Test 1: Login User Baru dengan Google

**Langkah:**
1. Buka `http://localhost:8000/login`
2. Klik tombol "Login dengan Google"
3. Pilih akun Google yang belum pernah terdaftar di sistem
4. Berikan izin yang diminta

**Expected Result:**
- User baru dibuat di database dengan:
  - `name` dari Google
  - `email` dari Google
  - `google_id` dari Google
  - `email_verified_at` diisi otomatis
  - `password` = NULL
  - `system_role_id` = ID role employee (jika ada)
- User otomatis login
- Redirect ke `/employee/dashboard`

**Verifikasi di Database:**
```sql
SELECT id, name, email, google_id, system_role_id, email_verified_at 
FROM users 
WHERE email = 'your-google-email@gmail.com';
```

### Test 2: Login User Existing dengan Google

**Persiapan:**
Buat user manual di database dengan email yang sama dengan Google account Anda.

**Langkah:**
1. Buka `http://localhost:8000/login`
2. Klik tombol "Login dengan Google"
3. Pilih akun Google yang emailnya sudah terdaftar di sistem

**Expected Result:**
- User existing di-update dengan `google_id` dari Google
- User otomatis login
- Redirect sesuai role:
  - Admin → `/admin/dashboard`
  - Employee → `/employee/dashboard`

**Verifikasi di Database:**
```sql
SELECT id, name, email, google_id, system_role_id 
FROM users 
WHERE email = 'your-google-email@gmail.com';
```

Pastikan `google_id` sudah terisi.

### Test 3: Login Ulang dengan Google

**Langkah:**
1. Logout dari aplikasi
2. Buka `http://localhost:8000/login`
3. Klik tombol "Login dengan Google"
4. Pilih akun Google yang sama

**Expected Result:**
- User langsung login tanpa membuat akun baru
- Redirect sesuai role

### Test 4: Error Handling - User Membatalkan Login

**Langkah:**
1. Buka `http://localhost:8000/login`
2. Klik tombol "Login dengan Google"
3. Klik "Cancel" atau tutup popup Google

**Expected Result:**
- Redirect kembali ke halaman login
- Muncul pesan error: "Gagal login dengan Google. Silakan coba lagi."

### Test 5: Multiple Google Accounts

**Langkah:**
1. Login dengan Google Account A
2. Logout
3. Login dengan Google Account B

**Expected Result:**
- Setiap akun Google membuat/menggunakan user terpisah
- Tidak ada konflik antar akun

## Debugging

### Cek Log Error

Jika ada masalah, cek log di:
```
storage/logs/laravel.log
```

### Cek Routes

Pastikan routes terdaftar:
```bash
php artisan route:list --name=auth.google
```

Output yang diharapkan:
```
GET|HEAD  auth/google ..................... auth.google › Auth\GoogleAuthController@redirectToGoogle
GET|HEAD  auth/google/callback ... auth.google.callback › Auth\GoogleAuthController@handleGoogleCallback
```

### Test Manual dengan Tinker

```bash
php artisan tinker
```

```php
// Cek apakah role employee ada
$role = \App\Models\Admin\SystemRole::where('name', 'employee')->first();
dd($role);

// Cek user dengan google_id
$users = \App\Models\Core\User::whereNotNull('google_id')->get();
dd($users);

// Test create user dengan google_id
$user = \App\Models\Core\User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'google_id' => '123456789',
    'email_verified_at' => now(),
]);
dd($user);
```

## Common Issues

### Issue: "redirect_uri_mismatch"

**Solusi:**
- Pastikan redirect URI di Google Cloud Console sama dengan `http://localhost:8000/auth/google/callback`
- Cek tidak ada trailing slash
- Pastikan menggunakan http (bukan https) untuk localhost

### Issue: "invalid_client"

**Solusi:**
- Verifikasi `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` di `.env`
- Pastikan tidak ada spasi atau karakter tersembunyi
- Jalankan `php artisan config:clear`

### Issue: User tidak ter-redirect setelah login

**Solusi:**
- Cek apakah route `admin.dashboard` dan `employee.dashboard` sudah ada
- Verifikasi user memiliki `system_role_id` yang valid
- Cek log error di `storage/logs/laravel.log`

### Issue: Kolom google_id tidak ada

**Solusi:**
```bash
php artisan migrate --path=database/migrations/2025_11_29_065708_add_google_id_to_users_table.php
```

## Security Testing

### Test 1: SQL Injection

Google OAuth sudah menggunakan Eloquent ORM yang aman dari SQL injection.

### Test 2: XSS Protection

Data dari Google (name, email) sudah di-escape oleh Blade template.

### Test 3: CSRF Protection

Routes OAuth tidak memerlukan CSRF token karena menggunakan GET method dan state parameter dari Google.

## Performance Testing

### Test Response Time

Gunakan browser developer tools untuk mengukur:
1. Time to redirect ke Google
2. Time to callback
3. Time to create/update user
4. Time to redirect ke dashboard

Target: < 2 detik untuk keseluruhan proses

## Checklist Testing

- [ ] User baru bisa login dengan Google
- [ ] User existing bisa link dengan Google
- [ ] User bisa login ulang dengan Google
- [ ] Error handling berfungsi dengan baik
- [ ] Redirect sesuai role
- [ ] Data user tersimpan dengan benar
- [ ] google_id unik per user
- [ ] email_verified_at terisi otomatis
- [ ] Password nullable untuk OAuth user
- [ ] Log error tercatat dengan baik

## Next Steps

Setelah testing berhasil, pertimbangkan untuk:
1. Menambahkan OAuth provider lain (Facebook, GitHub, dll)
2. Implementasi profile sync dari Google
3. Menambahkan option untuk unlink Google account
4. Implementasi 2FA untuk keamanan tambahan
5. Menambahkan audit log untuk OAuth login

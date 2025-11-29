# Google OAuth Implementation

Fitur OAuth Google telah berhasil diimplementasikan untuk aplikasi Haga+.

## 📋 Fitur yang Diimplementasikan

✅ **Login dengan Google** - User dapat login menggunakan akun Google mereka  
✅ **Auto-create User** - User baru otomatis dibuat saat login pertama kali  
✅ **Link Existing Account** - Akun existing dapat di-link dengan Google ID  
✅ **Role-based Redirect** - Redirect otomatis ke dashboard sesuai role  
✅ **Avatar Sync** - Avatar dari Google otomatis disimpan  
✅ **Email Verification** - Email otomatis terverifikasi untuk OAuth user  
✅ **Error Handling** - Error handling dan logging yang komprehensif  

## 🚀 Quick Start

### 1. Install Dependencies
```bash
composer require laravel/socialite
```
✅ **DONE** - Laravel Socialite sudah terinstall

### 2. Run Migration
```bash
php artisan migrate
```
✅ **DONE** - Kolom `google_id` sudah ditambahkan ke tabel `users`

### 3. Setup Google Credentials

Ikuti panduan lengkap di: **[GOOGLE_OAUTH_SETUP.md](GOOGLE_OAUTH_SETUP.md)**

Singkatnya:
1. Buat project di [Google Cloud Console](https://console.cloud.google.com/)
2. Enable Google+ API atau Google People API
3. Buat OAuth 2.0 credentials
4. Tambahkan redirect URI: `http://localhost:8000/auth/google/callback`
5. Copy Client ID dan Client Secret ke `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

### 4. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 5. Test!
1. Buka `http://localhost:8000/login`
2. Klik tombol **"Login dengan Google"**
3. Login dengan akun Google Anda
4. Selesai! ✨

## 📁 File yang Dibuat/Dimodifikasi

### Baru Dibuat:
- `app/Http/Controllers/Auth/GoogleAuthController.php` - Controller OAuth
- `database/migrations/2025_11_29_065708_add_google_id_to_users_table.php` - Migration
- `database/seeders/SystemRoleSeeder.php` - Seeder untuk roles
- `GOOGLE_OAUTH_SETUP.md` - Panduan setup lengkap
- `GOOGLE_OAUTH_TESTING.md` - Panduan testing
- `README_GOOGLE_OAUTH.md` - File ini

### Dimodifikasi:
- `app/Models/Core/User.php` - Tambah `google_id` ke fillable
- `config/services.php` - Tambah konfigurasi Google
- `routes/web.php` - Tambah routes OAuth
- `resources/views/auth/login.blade.php` - Tambah tombol Google
- `.env.example` - Tambah Google credentials template

## 🔗 Routes

| Method | URI | Name | Action |
|--------|-----|------|--------|
| GET | `/auth/google` | `auth.google` | Redirect ke Google OAuth |
| GET | `/auth/google/callback` | `auth.google.callback` | Handle callback dari Google |

## 🗄️ Database Schema

Kolom baru di tabel `users`:
```sql
google_id VARCHAR(255) NULL UNIQUE
password VARCHAR(255) NULL -- Dibuat nullable untuk OAuth users
```

## 🎯 User Flow

```
1. User klik "Login dengan Google"
   ↓
- ✅ Unique constraint pada google_id
- ✅ Email verification otomatis
- ✅ Error logging untuk debugging
- ✅ Secure password handling (nullable untuk OAuth)

## 🧪 Testing

Lihat panduan lengkap di: **[GOOGLE_OAUTH_TESTING.md](GOOGLE_OAUTH_TESTING.md)**

Quick test:
```bash
# Cek routes
php artisan route:list --name=auth.google

# Seed roles (jika belum ada)
php artisan db:seed --class=SystemRoleSeeder

# Test di browser
# Buka: http://localhost:8000/login
```

## 🐛 Troubleshooting

### Error: "redirect_uri_mismatch"
**Solusi:** Pastikan redirect URI di Google Cloud Console sama persis: `http://localhost:8000/auth/google/callback`

### Error: "invalid_client"
**Solusi:** Cek `GOOGLE_CLIENT_ID` dan `GOOGLE_CLIENT_SECRET` di `.env`, lalu jalankan `php artisan config:clear`

### User tidak bisa login
**Solusi:** 
1. Cek migration sudah dijalankan: `php artisan migrate`
2. Cek log error: `storage/logs/laravel.log`
3. Pastikan role employee ada di database

## 📚 Documentation

- **Setup Guide**: [GOOGLE_OAUTH_SETUP.md](GOOGLE_OAUTH_SETUP.md)
- **Testing Guide**: [GOOGLE_OAUTH_TESTING.md](GOOGLE_OAUTH_TESTING.md)
- **Laravel Socialite**: https://laravel.com/docs/socialite
- **Google OAuth**: https://developers.google.com/identity/protocols/oauth2

## 🎨 UI/UX

Tombol "Login dengan Google" sudah terintegrasi di halaman login dengan:
- ✅ Google icon (Font Awesome)
- ✅ Styling yang konsisten dengan design system
- ✅ Hover effect
- ✅ Responsive design

## 🚀 Production Deployment

Sebelum deploy ke production:

1. **Update Redirect URI** di Google Cloud Console:
   ```
   https://yourdomain.com/auth/google/callback
   ```

2. **Update .env**:
   ```env
   APP_URL=https://yourdomain.com
   GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
   ```

3. **Enable HTTPS**:
   - Pastikan SSL certificate terinstall
   - Force HTTPS di Laravel

4. **Review OAuth Consent Screen**:
   - Publish aplikasi (keluar dari testing mode)
   - Update privacy policy & terms of service

5. **Security**:
   - Review authorized domains
   - Set rate limiting
   - Enable logging & monitoring

## 📞 Support

Jika ada pertanyaan atau masalah:
1. Cek dokumentasi di folder ini
2. Review log error di `storage/logs/laravel.log`
3. Cek Google Cloud Console untuk OAuth errors

## 📝 Notes

- User yang login dengan Google tidak memerlukan password
- Avatar dari Google otomatis disimpan
- Email otomatis terverifikasi
- Default role untuk user baru adalah `employee`
- User existing dapat link akun Google mereka

---

**Created**: 2025-11-29  
**Laravel Version**: 12.x  
**Socialite Version**: 5.23  
**Status**: ✅ Ready to Use

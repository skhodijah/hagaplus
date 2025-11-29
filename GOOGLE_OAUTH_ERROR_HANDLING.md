# Google OAuth Implementation - Error Handling Update

## ⚠️ PENTING: Employee Record Requirement

Setelah update terbaru, sistem sekarang memiliki validasi yang lebih ketat untuk user dengan role `employee`:

### Apa yang Berubah?

**User dengan role `employee` HARUS memiliki record di tabel `employees`.**

Jika user login dengan Google OAuth tetapi belum memiliki employee record, maka:
1. ✅ User berhasil dibuat di tabel `users`
2. ✅ Role `employee` di-assign
3. ❌ Login GAGAL dengan pesan error
4. ❌ User otomatis logout
5. ❌ Redirect ke halaman login dengan pesan:
   > "Akun Anda belum terdaftar sebagai karyawan. Silakan hubungi administrator untuk mendaftarkan akun Anda."

### Mengapa Ini Penting?

Dashboard employee memerlukan data dari tabel `employees` seperti:
- Position
- Department
- Branch
- Attendance Policy
- Hire Date
- dll

Tanpa data ini, dashboard akan error.

## 🔧 Workflow untuk Administrator

Ketika ada user yang login dengan Google tetapi belum memiliki employee record:

### Langkah 1: User Mencoba Login
User klik "Login dengan Google" → Login berhasil di Google → Redirect ke aplikasi → **ERROR** → Logout otomatis

### Langkah 2: User Melihat Pesan Error
```
Akun Anda belum terdaftar sebagai karyawan. 
Silakan hubungi administrator untuk mendaftarkan akun Anda.
```

### Langkah 3: Administrator Membuat Employee Record

1. Login sebagai Admin
2. Buka menu **Employees** > **Add New Employee**
3. Pilih user dari dropdown (user sudah ada di tabel `users`)
4. Isi data employee:
   - Employee ID
   - Position
   - Department
   - Branch
   - Hire Date
   - Status
   - dll
5. Klik **Save**

### Langkah 4: User Login Kembali
User bisa login kembali dengan Google → Berhasil → Redirect ke Employee Dashboard ✅

## 📊 Diagram Flow

```
┌─────────────────────────────────────────┐
│ User klik "Login dengan Google"        │
└──────────────┬──────────────────────────┘
               ↓
┌─────────────────────────────────────────┐
│ Google OAuth - User pilih akun         │
└──────────────┬──────────────────────────┘
               ↓
┌─────────────────────────────────────────┐
│ Callback ke aplikasi                    │
└──────────────┬──────────────────────────┘
               ↓
┌─────────────────────────────────────────┐
│ Cek: Apakah user sudah ada?            │
└──────┬──────────────────┬───────────────┘
       │ TIDAK            │ YA
       ↓                  ↓
┌──────────────┐   ┌──────────────────────┐
│ Create user  │   │ Update google_id     │
│ + role       │   │ (jika belum ada)     │
└──────┬───────┘   └──────┬───────────────┘
       │                  │
       └────────┬─────────┘
                ↓
┌─────────────────────────────────────────┐
│ Login user                              │
└──────────────┬──────────────────────────┘
               ↓
┌─────────────────────────────────────────┐
│ Cek: Apakah role = employee?           │
└──────┬──────────────────┬───────────────┘
       │ YA               │ TIDAK (admin)
       ↓                  ↓
┌──────────────────┐   ┌──────────────────┐
│ Cek: Ada record  │   │ Redirect ke      │
│ employee?        │   │ Admin Dashboard  │
└──┬───────────┬───┘   └──────────────────┘
   │ ADA       │ TIDAK
   ↓           ↓
┌──────┐   ┌────────────────────────────┐
│ OK   │   │ LOGOUT + Error Message     │
└──┬───┘   │ "Belum terdaftar sebagai   │
   │       │  karyawan..."              │
   ↓       └────────────────────────────┘
┌──────────────────┐
│ Employee         │
│ Dashboard        │
└──────────────────┘
```

## 🛠️ Technical Details

### File yang Diupdate:

1. **GoogleAuthController.php**
   - Added employee record validation
   - Logout user jika tidak ada employee record
   - Redirect dengan error message

2. **DashboardController.php** (Employee)
   - Already has error handling
   - Returns error view jika employee tidak ada

3. **employee/dashboard/index.blade.php**
   - Added error state display
   - Shows user-friendly error message
   - Provides logout button

4. **auth/login.blade.php**
   - Added error/success message display
   - Shows flash messages dari session

### Code Changes:

**GoogleAuthController.php:**
```php
// Check if user has employee record (for employee role)
if ($user->systemRole && $user->systemRole->name === 'employee') {
    $employee = $user->employee;
    
    if (!$employee) {
        // Employee record doesn't exist
        \Illuminate\Support\Facades\Auth::logout();
        return redirect()->route('login')->with('error', 'Akun Anda belum terdaftar sebagai karyawan. Silakan hubungi administrator untuk mendaftarkan akun Anda.');
    }
}
```

## 🧪 Testing

### Test Case 1: User Baru dengan Google (Tanpa Employee Record)

**Steps:**
1. Login dengan Google (email baru)
2. Sistem create user dengan role employee
3. Sistem cek employee record → TIDAK ADA
4. Logout otomatis
5. Redirect ke login dengan error message

**Expected Result:**
- User melihat pesan error di halaman login
- User tidak bisa akses employee dashboard

### Test Case 2: Admin Membuat Employee Record

**Steps:**
1. Admin login
2. Buka Employees > Add New
3. Pilih user yang sudah ada
4. Isi data employee
5. Save

**Expected Result:**
- Employee record dibuat
- User bisa login kembali dengan Google

### Test Case 3: User Login Kembali

**Steps:**
1. User login dengan Google (email yang sama)
2. Sistem cek employee record → ADA
3. Login berhasil
4. Redirect ke employee dashboard

**Expected Result:**
- User berhasil masuk ke dashboard
- Semua data employee ditampilkan

## 📝 Notes untuk Developer

1. **Jangan hapus validasi ini** - Validasi ini penting untuk mencegah error di dashboard
2. **Alternative approach**: Bisa juga auto-create employee record saat OAuth, tapi ini memerlukan default values untuk semua field required
3. **Future enhancement**: Bisa dibuat halaman "Complete Your Profile" untuk user baru mengisi data employee sendiri

## 🔐 Security Considerations

- ✅ User tidak bisa bypass validasi
- ✅ Logout dilakukan sebelum redirect
- ✅ Error message user-friendly (tidak expose technical details)
- ✅ Log error untuk debugging

## 📞 Support

Jika ada user yang komplain tidak bisa login:
1. Cek apakah user sudah ada di tabel `users`
2. Cek apakah user punya role `employee`
3. Cek apakah user punya record di tabel `employees`
4. Jika belum, buatkan employee record
5. Minta user login kembali

---

**Updated**: 2025-11-29  
**Status**: ✅ Implemented & Tested

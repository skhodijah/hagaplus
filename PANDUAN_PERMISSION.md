- **Policies**: Manage Employee & Division Policies
- **Reports**: View, Export
- **Settings**: Manage Roles, Permissions, Branches

### 3. **Fitur Proteksi**
- ✅ Default roles **tidak bisa dihapus**
- ✅ Default roles **tidak bisa diedit** (nama/deskripsi)
- ✅ Permissions default roles **bisa diubah** sesuai kebutuhan
- ✅ Custom roles bisa dibuat dan dihapus

## 📋 Cara Menggunakan

### Mengelola Permissions Role

1. **Masuk ke Role Management**
   ```
   Admin Dashboard → Roles
   ```

2. **Klik "Permissions" pada role yang ingin diatur**
   - Akan muncul halaman dengan semua permissions dikelompokkan
   - Centang permissions yang ingin diberikan
   - Klik "Select All" untuk memilih semua dalam satu grup

3. **Simpan perubahan**

### Membuat Custom Role

1. Klik "Add New Role"
2. Isi nama, deskripsi, dan pilih system role (Admin/Employee)
3. Setelah dibuat, klik "Permissions" untuk mengatur akses
4. Assign role ke employee melalui Employee Edit

### Menggunakan di Controller

```php
// Cek permission di controller
if (auth()->user()->employee->instansiRole->hasPermission('view-employees')) {
    // User punya akses
}
```

### Menggunakan di Blade View

```blade
@hasPermission('view-employees')
    <a href="{{ route('admin.employees.index') }}">Lihat Karyawan</a>
@endhasPermission

@hasAnyPermission('view-employees', 'edit-employees')
    <div>Employee Section</div>
@endhasAnyPermission
```

### Menggunakan Middleware di Routes

```php
Route::get('/employees', [EmployeeController::class, 'index'])
    ->middleware('permission:view-employees');
```

## 🎯 Rekomendasi Penggunaan

### Untuk Perusahaan Kecil (< 50 karyawan)
Gunakan default roles saja, cukup atur permissions sesuai kebutuhan.

### Untuk Perusahaan Menengah (50-200 karyawan)
- Gunakan default roles untuk mayoritas
- Buat 1-2 custom roles untuk posisi khusus (misal: "Team Lead", "Project Manager")

### Untuk Perusahaan Besar (> 200 karyawan)
- Gunakan default roles sebagai template
- Buat custom roles untuk setiap departemen/divisi
- Atur permissions secara detail per role

## 📝 Catatan Penting

1. **Superadmin** selalu punya semua permissions
2. Default roles ditandai dengan badge "Default" berwarna biru
3. Role yang sedang digunakan employee tidak bisa dihapus
4. Perubahan permissions langsung berlaku untuk semua employee dengan role tersebut

## 🔧 File-file yang Dibuat

- Migration: `2025_11_27_000002` sampai `2025_11_27_000006`
- Models: `Permission.php`, update `InstansiRole.php`
- Controllers: `RolePermissionController.php`, update `RoleController.php`
- Middleware: `CheckPermission.php`
- Views: `admin/roles/permissions.blade.php`, update `admin/roles/index.blade.php`
- Routes: Tambahan di `routes/admin.php`
- Blade Directives: `@hasPermission`, `@hasAnyPermission`, `@hasAllPermissions`

Dokumentasi lengkap ada di `PERMISSION_SYSTEM.md`

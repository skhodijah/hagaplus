# Permission System Documentation

## Overview
Sistem permission ini memungkinkan kontrol akses yang granular untuk setiap role di dalam instansi.

## Default Roles

Setiap instansi akan memiliki 6 role default yang tidak dapat dihapus:

| Role | Description | Key Permissions |
|------|-------------|-----------------|
| **Employee** | Peran standar karyawan | View attendance, leaves, payroll |
| **Approver** | Menyetujui izin/cuti/lembur | Approve attendance revisions, approve leaves |
| **Supervisor** | Monitoring dan approval operasional | Edit attendance, approve revisions, view reports |
| **Manager** | Pengambil keputusan tingkat tinggi | Manage employees, policies, view all reports |
| **HR** | Kelola data karyawan, departemen, kebijakan | Full employee management, departments, policies |
| **Finance** | Penggajian/keuangan | Process payroll, view financial reports |

## Permission Groups

Permissions dikelompokkan berdasarkan modul:

- **employees**: Employee management
- **attendance**: Attendance tracking and management
- **leaves**: Leave request management
- **payroll**: Payroll processing
- **organization**: Departments, divisions, positions
- **policies**: Employee and division policies
- **reports**: Reporting and analytics
- **settings**: System settings and configuration

## Usage in Controllers

### Using Middleware

```php
// Require single permission
Route::get('/employees', [EmployeeController::class, 'index'])
    ->middleware('permission:view-employees');

// Require any of multiple permissions
Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('permission:view-reports,export-reports');
```

### Checking in Controller

```php
public function index()
{
    $user = auth()->user();
    
    // Check single permission
    if ($user->employee->instansiRole->hasPermission('view-employees')) {
        // User has permission
    }
    
    // Check any permission
    if ($user->employee->instansiRole->hasAnyPermission(['view-employees', 'edit-employees'])) {
        // User has at least one permission
    }
    
    // Check all permissions
    if ($user->employee->instansiRole->hasAllPermissions(['view-employees', 'edit-employees'])) {
        // User has all permissions
    }
}
```

## Usage in Blade Views

### Single Permission

```blade
@hasPermission('view-employees')
    <a href="{{ route('admin.employees.index') }}">View Employees</a>
@endhasPermission
```

### Any Permission

```blade
@hasAnyPermission('view-employees', 'edit-employees')
    <div class="employee-section">
        <!-- Content -->
    </div>
@endhasAnyPermission
```

### All Permissions

```blade
@hasAllPermissions('view-employees', 'edit-employees', 'delete-employees')
    <button>Full Employee Management</button>
@endhasAllPermissions
```

## Managing Roles and Permissions

### Creating Custom Roles

```php
$role = InstansiRole::create([
    'instansi_id' => $instansi->id,
    'system_role_id' => 3, // Employee
    'name' => 'Custom Role',
    'description' => 'Custom role description',
    'is_active' => true,
    'is_default' => false, // Custom roles can be deleted
]);
```

### Assigning Permissions to Role

```php
$permissions = Permission::whereIn('slug', [
    'view-employees',
    'view-attendance',
    'approve-leaves'
])->get();

$role->permissions()->sync($permissions);
```

### Preventing Deletion of Default Roles

```php
public function destroy(InstansiRole $role)
{
    if ($role->is_default) {
        return back()->with('error', 'Cannot delete default role');
    }
    
    $role->delete();
    return back()->with('success', 'Role deleted');
}
```

## Available Permissions

### Employees
- `view-employees`: View employee list and details
- `create-employees`: Add new employees
- `edit-employees`: Edit employee information
- `delete-employees`: Delete employees

### Attendance
- `view-attendance`: View attendance records
- `edit-attendance`: Edit attendance records
- `approve-attendance-revisions`: Approve attendance revision requests

### Leaves
- `view-leaves`: View leave requests
- `approve-leaves`: Approve or reject leave requests
- `manage-leave-policies`: Create and edit leave policies

### Payroll
- `view-payroll`: View payroll records
- `process-payroll`: Process and generate payroll
- `edit-payroll`: Edit payroll records

### Organization
- `manage-departments`: Create, edit, delete departments
- `manage-divisions`: Create, edit, delete divisions
- `manage-positions`: Create, edit, delete positions

### Policies
- `manage-employee-policies`: Create and edit employee policies
- `manage-division-policies`: Create and edit division policies

### Reports
- `view-reports`: View attendance and payroll reports
- `export-reports`: Export reports to Excel/PDF

### Settings
- `manage-roles`: Create and edit roles
- `manage-permissions`: Assign permissions to roles
- `manage-branches`: Create and edit branches

## Migration Commands

```bash
# Run migrations
php artisan migrate

# This will:
# 1. Create permissions table
# 2. Create instansi_role_permissions pivot table
# 3. Add is_default column to instansi_roles
# 4. Seed default permissions
# 5. Create default roles for each instansi with appropriate permissions
```

## Notes

- **Superadmin** always has all permissions
- Default roles (`is_default = true`) cannot be deleted
- Custom roles can be created and deleted
- Permissions are assigned at the role level, not individual users
- Each employee is assigned one instansi role

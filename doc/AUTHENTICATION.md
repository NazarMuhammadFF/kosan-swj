# Authentication & Authorization System

## Overview
Sistem autentikasi menggunakan Laravel Breeze dengan Spatie Laravel Permission untuk role-based access control (RBAC).

## Roles

### 1. Owner (Super Admin)
- **Akses**: Full access ke semua fitur
- **Permissions**: Semua permissions
- **Use Case**: Pemilik bisnis kos yang mengelola seluruh sistem

### 2. Admin
- **Akses**: Manage semua operasional kecuali settings sistem
- **Permissions**:
  - Properties: view, create, update, delete, publish
  - Rooms: view, create, update, delete, change_status
  - Tenants: view, create, update, delete, verify
  - Bookings: view, create, confirm, cancel
  - Contracts: view, create, update, terminate, extend
  - Invoices: view, create, update, send_reminder
  - Payments: view, create, verify, reject
  - Tickets: view, assign, update, close
  - Announcements: view, create, update, delete
  - Reviews: view, reply, moderate
  - Reports: view, export
- **Use Case**: Manager yang handle operasional harian

### 3. Staff
- **Akses**: Limited operations - fokus pada invoice & payment verification
- **Permissions**:
  - Properties: view
  - Rooms: view
  - Tenants: view
  - Bookings: view
  - Contracts: view
  - Invoices: view, update
  - Payments: view, create
  - Tickets: view, update, close
  - Announcements: view
- **Use Case**: Front desk atau staff maintenance

### 4. Tenant
- **Akses**: View data pribadi & submit payment
- **Permissions**:
  - Contracts: view (own only)
  - Invoices: view (own only)
  - Payments: create (own only)
  - Tickets: view, create (own only)
  - Announcements: view
  - Reviews: create (own property only)
- **Use Case**: Penghuni kos

## Test Accounts

Setelah menjalankan seeder, tersedia 4 test accounts:

```
Owner:  owner@kosan.com / password
Admin:  admin@kosan.com / password
Staff:  staff@kosan.com / password
Tenant: tenant@kosan.com / password
```

## Middleware Usage

### 1. Role Middleware
Protect route berdasarkan role:

```php
Route::get('/admin/dashboard', function () {
    // Only owner, admin, staff can access
})->middleware(['auth', 'admin']);

Route::get('/owner/settings', function () {
    // Only owner can access
})->middleware(['auth', 'role:owner']);
```

### 2. Permission Middleware
Protect route berdasarkan permission:

```php
Route::post('/properties', [PropertyController::class, 'store'])
    ->middleware(['auth', 'permission:properties.create']);

Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])
    ->middleware(['auth', 'permission:properties.delete']);
```

### 3. Multiple Permissions
Require multiple permissions:

```php
Route::get('/reports/financial', function () {
    // Requires both permissions
})->middleware(['auth', 'permission:reports.view,reports.export']);
```

### 4. Role OR Permission
Allow access jika memiliki role ATAU permission:

```php
Route::get('/dashboard', function () {
    // Can access if has 'admin' role OR 'dashboard.view' permission
})->middleware(['auth', 'role_or_permission:admin|dashboard.view']);
```

## Blade Directives

### Check Role in Blade
```blade
@role('owner')
    <a href="/settings">Settings</a>
@endrole

@hasrole('admin')
    <a href="/admin/dashboard">Admin Dashboard</a>
@endhasrole

@hasanyrole('owner|admin')
    <a href="/reports">Reports</a>
@endhasanyrole
```

### Check Permission in Blade
```blade
@can('properties.create')
    <a href="/properties/create">Create Property</a>
@endcan

@cannot('properties.delete')
    <p>You cannot delete properties</p>
@endcannot
```

## Controller Authorization

### Using Policies
```php
public function update(Request $request, Property $property)
{
    $this->authorize('update', $property);
    
    // Update logic...
}
```

### Manual Check
```php
public function store(Request $request)
{
    if (!auth()->user()->can('properties.create')) {
        abort(403, 'Unauthorized');
    }
    
    // Store logic...
}
```

### Check in Constructor
```php
public function __construct()
{
    $this->middleware('permission:properties.view')->only(['index', 'show']);
    $this->middleware('permission:properties.create')->only(['create', 'store']);
    $this->middleware('permission:properties.update')->only(['edit', 'update']);
    $this->middleware('permission:properties.delete')->only('destroy');
}
```

## Helper Methods

User model memiliki helper methods:

```php
// Check specific role
if (auth()->user()->isOwner()) { }
if (auth()->user()->isAdmin()) { }
if (auth()->user()->isStaff()) { }
if (auth()->user()->isTenant()) { }

// Check if can access admin panel
if (auth()->user()->canAccessAdmin()) {
    // Redirect to admin dashboard
}
```

## Seeding

### Run Specific Seeders
```bash
# Create roles & permissions
php artisan db:seed --class=RolesAndPermissionsSeeder

# Create test users
php artisan db:seed --class=UserSeeder

# Run all seeders
php artisan db:seed
```

### Fresh Migration with Seed
```bash
php artisan migrate:fresh --seed
```

## Permission Categories

### Property Management
- properties.view
- properties.create
- properties.update
- properties.delete
- properties.publish

### Room Management
- rooms.view
- rooms.create
- rooms.update
- rooms.delete
- rooms.change_status

### Tenant Management
- tenants.view
- tenants.create
- tenants.update
- tenants.delete
- tenants.verify

### Booking Management
- bookings.view
- bookings.create
- bookings.confirm
- bookings.cancel

### Contract Management
- contracts.view
- contracts.create
- contracts.update
- contracts.terminate
- contracts.extend

### Invoice Management
- invoices.view
- invoices.create
- invoices.update
- invoices.delete
- invoices.send_reminder

### Payment Management
- payments.view
- payments.create
- payments.verify
- payments.reject

### Maintenance Tickets
- tickets.view
- tickets.create
- tickets.assign
- tickets.update
- tickets.close

### Announcements
- announcements.view
- announcements.create
- announcements.update
- announcements.delete

### Reviews
- reviews.view
- reviews.create
- reviews.reply
- reviews.moderate

### Vouchers
- vouchers.view
- vouchers.create
- vouchers.update
- vouchers.delete

### Reports & Analytics
- reports.view
- reports.export
- analytics.view

### Settings
- settings.view
- settings.update

## Security Best Practices

1. **Always validate permissions** di controller sebelum sensitive operations
2. **Use middleware** untuk protect routes
3. **Implement policies** untuk model-level authorization
4. **Limit tenant access** hanya ke data mereka sendiri
5. **Log critical actions** untuk audit trail
6. **Never hardcode** role names, gunakan constants
7. **Test authorization** di semua endpoints

## Troubleshooting

### Permission not working?
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Re-seed permissions
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### User doesn't have role?
```php
// Assign role programmatically
$user->assignRole('admin');

// Or via seeder
User::find(1)->assignRole('owner');
```

### Check user permissions
```php
// Get all permissions
$permissions = auth()->user()->getAllPermissions();

// Get all roles
$roles = auth()->user()->getRoleNames();

// Check specific permission
$hasPermission = auth()->user()->hasPermissionTo('properties.create');
```

---

**Last Updated**: October 27, 2025

# Property CRUD - Controllers & Authorization

## Overview

Dokumentasi ini menjelaskan implementasi CRUD operations untuk Property Management, termasuk controller, policy, request validation, dan routes.

## Files Created

### 1. PropertyController
**File**: `app/Http/Controllers/PropertyController.php`

Controller utama untuk mengelola property dengan fitur lengkap CRUD operations.

#### Methods:

**index(Request $request)**
- Display paginated list of properties
- Filters: search, city, gender_type, is_published, is_featured
- Sorting: configurable sort_by dan sort_order
- Authorization: Owner hanya melihat property sendiri, Admin/Staff melihat semua
- Returns: `properties.index` view

**create()**
- Show form untuk membuat property baru
- Authorization: Hanya Owner dan Admin
- Returns: `properties.create` view

**store(Request $request)**
- Simpan property baru ke database
- Handle upload multiple photos (max 10 files)
- Auto-set owner_id dari authenticated user
- Validation via inline rules
- Redirect ke detail property dengan success message

**show(Property $property)**
- Tampilkan detail property dengan statistik
- Load relationships: owner, rooms, reviews
- Statistik: total_rooms, available_rooms, occupied_rooms, occupancy_rate, average_rating, total_reviews, price_range
- Authorization: Owner lihat miliknya, Admin/Staff lihat semua
- Returns: `properties.show` view

**edit(Property $property)**
- Show form untuk edit property
- Authorization: Owner edit miliknya, Admin edit semua
- Returns: `properties.edit` view

**update(Request $request, Property $property)**
- Update property data
- Handle new photo uploads
- Handle photo removals (delete from storage)
- Validation via inline rules
- Redirect ke detail property dengan success message

**destroy(Property $property)**
- Soft delete property
- Check: Tidak bisa delete jika ada kontrak aktif
- Delete semua photos dari storage
- Authorization: Owner delete miliknya, Admin delete semua
- Redirect ke index dengan success message

**togglePublish(Property $property)**
- Toggle status is_published
- Authorization: Same as update
- Return back dengan success message

**toggleFeatured(Property $property)**
- Toggle status is_featured
- Authorization: Hanya Admin
- Return back dengan success message

#### Features:

✅ **File Upload Management**
- Multiple photo uploads (max 10)
- Stored in `storage/app/public/properties/photos`
- Validation: image, mimes:jpeg,png,jpg,webp, max:2048KB
- Auto-delete on property removal

✅ **Smart Filtering**
- Search by name, address, city
- Filter by city, gender_type
- Filter by published/featured status
- Filter by owner (for owner role)

✅ **Authorization**
- Policy-based authorization
- Owner: Manage own properties
- Admin: Manage all properties
- Staff: View only

✅ **Validation**
- Inline validation rules
- Custom error messages
- Array validation for facilities & rules

---

### 2. PropertyPolicy
**File**: `app/Policies/PropertyPolicy.php`

Policy untuk mengatur authorization akses property.

#### Methods:

**viewAny(User $user): bool**
- Owner, Admin, Staff dapat view list
- Returns: `$user->canAccessAdmin()`

**view(User $user, Property $property): bool**
- Admin/Staff: Lihat semua property
- Owner: Hanya lihat miliknya
- Returns: `true` if authorized

**create(User $user): bool**
- Hanya Owner dan Admin
- Returns: `$user->isOwner() || $user->isAdmin()`

**update(User $user, Property $property): bool**
- Admin: Update semua
- Owner: Hanya update miliknya
- Returns: `true` if authorized

**delete(User $user, Property $property): bool**
- Admin: Delete semua
- Owner: Hanya delete miliknya
- Returns: `true` if authorized

**restore(User $user, Property $property): bool**
- Hanya Admin
- Returns: `$user->isAdmin()`

**forceDelete(User $user, Property $property): bool**
- Hanya Admin
- Returns: `$user->isAdmin()`

**publish(User $user, Property $property): bool**
- Same as update permission
- Returns: `$this->update($user, $property)`

**feature(User $user, Property $property): bool**
- Hanya Admin
- Returns: `$user->isAdmin()`

#### Authorization Matrix:

| Action | Owner (Own) | Owner (Others) | Admin | Staff | Tenant |
|--------|-------------|----------------|-------|-------|--------|
| viewAny | ✅ | ✅ | ✅ | ✅ | ❌ |
| view | ✅ | ❌ | ✅ | ✅ | ❌ |
| create | ✅ | - | ✅ | ❌ | ❌ |
| update | ✅ | ❌ | ✅ | ❌ | ❌ |
| delete | ✅ | ❌ | ✅ | ❌ | ❌ |
| publish | ✅ | ❌ | ✅ | ❌ | ❌ |
| feature | ❌ | ❌ | ✅ | ❌ | ❌ |
| restore | ❌ | ❌ | ✅ | ❌ | ❌ |
| forceDelete | ❌ | ❌ | ✅ | ❌ | ❌ |

---

### 3. StorePropertyRequest
**File**: `app/Http/Requests/StorePropertyRequest.php`

Form Request untuk validasi pembuatan property baru.

#### Validation Rules:

```php
'name' => 'required|string|max:255'
'description' => 'nullable|string|max:5000'
'address' => 'required|string|max:500'
'city' => 'required|string|max:100'
'province' => 'required|string|max:100'
'postal_code' => 'nullable|string|max:10'
'latitude' => 'nullable|numeric|between:-90,90'
'longitude' => 'nullable|numeric|between:-180,180'
'phone' => 'nullable|string|max:20'
'gender_type' => 'required|in:male,female,mixed'
'facilities' => 'nullable|array'
'facilities.*' => 'string|max:100'
'rules' => 'nullable|array'
'rules.*' => 'string|max:500'
'deposit_amount' => 'required|numeric|min:0|max:999999999'
'photos' => 'nullable|array|max:10'
'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
'video_url' => 'nullable|url|max:500'
'is_published' => 'boolean'
'is_featured' => 'boolean'
```

#### Custom Messages (Bahasa Indonesia):
- Nama property wajib diisi
- Alamat wajib diisi
- Kota & Provinsi wajib diisi
- Tipe gender harus: Pria, Wanita, atau Campur
- Format gambar harus: JPEG, PNG, JPG, atau WEBP
- Ukuran gambar maksimal 2MB

---

### 4. UpdatePropertyRequest
**File**: `app/Http/Requests/UpdatePropertyRequest.php`

Form Request untuk validasi update property.

#### Validation Rules:

Sama dengan StorePropertyRequest, ditambah:

```php
'new_photos' => 'nullable|array|max:10'
'new_photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
'remove_photos' => 'nullable|array'
'remove_photos.*' => 'string'
```

#### Differences from Store:
- `photos` → `new_photos` (untuk upload foto baru)
- `remove_photos` (untuk hapus foto existing)
- Authorization check: `$this->user()->can('update', $property)`

---

### 5. Routes
**File**: `routes/web.php`

#### Property Routes (Admin & Owner only):

```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('properties', PropertyController::class);
    Route::post('properties/{property}/toggle-publish', [PropertyController::class, 'togglePublish'])
        ->name('properties.toggle-publish');
    Route::post('properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])
        ->name('properties.toggle-featured');
});
```

#### Available Endpoints:

| Method | URI | Name | Action |
|--------|-----|------|--------|
| GET | /properties | properties.index | PropertyController@index |
| GET | /properties/create | properties.create | PropertyController@create |
| POST | /properties | properties.store | PropertyController@store |
| GET | /properties/{property} | properties.show | PropertyController@show |
| GET | /properties/{property}/edit | properties.edit | PropertyController@edit |
| PUT/PATCH | /properties/{property} | properties.update | PropertyController@update |
| DELETE | /properties/{property} | properties.destroy | PropertyController@destroy |
| POST | /properties/{property}/toggle-publish | properties.toggle-publish | PropertyController@togglePublish |
| POST | /properties/{property}/toggle-featured | properties.toggle-featured | PropertyController@toggleFeatured |

---

## Usage Examples

### Create Property

```php
// Form submission
POST /properties
{
    "name": "Kost Putri Melati",
    "description": "Kost nyaman dekat kampus...",
    "address": "Jl. Sudirman No. 123",
    "city": "Jakarta",
    "province": "DKI Jakarta",
    "postal_code": "12190",
    "phone": "081234567890",
    "gender_type": "female",
    "facilities": ["WiFi", "AC", "Parkir Motor"],
    "rules": ["Jam malam 22.00", "Dilarang merokok"],
    "deposit_amount": 1000000,
    "photos": [file1.jpg, file2.jpg],
    "video_url": "https://youtube.com/watch?v=xxx",
    "is_published": true,
    "is_featured": false
}

// Response: Redirect to properties.show with success message
```

### Update Property

```php
// Form submission
PUT /properties/1
{
    "name": "Kost Putri Melati (Updated)",
    "new_photos": [file3.jpg],
    "remove_photos": ["properties/photos/old-photo.jpg"],
    ... // other fields
}

// Response: Redirect to properties.show with success message
```

### Toggle Publish

```php
// AJAX request
POST /properties/1/toggle-publish

// Response: Back with success message
"Property berhasil dipublikasikan!"
// or
"Property berhasil di-draft!"
```

### Delete Property

```php
// Form submission
DELETE /properties/1

// Check: If has active contracts
return back()->with('error', 'Tidak dapat menghapus property yang masih memiliki kontrak aktif!');

// Success: Redirect to properties.index
return redirect()->route('properties.index')
    ->with('success', 'Property berhasil dihapus!');
```

---

## Controller Integration with Models

### Using Relationships

```php
// In show method
$property->load([
    'owner',
    'rooms' => function ($query) {
        $query->withCount('contracts');
    },
    'reviews' => function ($query) {
        $query->published()->latest()->limit(5);
    },
]);
```

### Using Scopes

```php
// In index method
if ($request->filled('search')) {
    $query->search($request->search);
}

if ($request->filled('city')) {
    $query->byCity($request->city);
}

$query->published()->featured();
```

### Using Helper Methods

```php
// In show method
$stats = [
    'occupancy_rate' => $property->getOccupancyRate(),
    'average_rating' => $property->getAverageRating(),
    'price_range' => $property->getPriceRange(),
];
```

---

## Error Handling

### Validation Errors

```php
// Automatically handled by FormRequest
// Returns back with errors and old input

@error('name')
    <span class="text-red-500">{{ $message }}</span>
@enderror
```

### Authorization Errors

```php
// Automatically handled by Policy
// Returns 403 Forbidden if unauthorized

$this->authorize('update', $property);
```

### Business Logic Errors

```php
// Check before delete
if ($property->contracts()->active()->exists()) {
    return back()->with('error', 'Tidak dapat menghapus property yang masih memiliki kontrak aktif!');
}
```

---

## File Upload Best Practices

### Storage Structure

```
storage/app/public/
└── properties/
    └── photos/
        ├── abc123.jpg
        ├── def456.jpg
        └── ghi789.jpg
```

### Upload Process

```php
$photoUrls = [];
if ($request->hasFile('photos')) {
    foreach ($request->file('photos') as $photo) {
        // Store in storage/app/public/properties/photos
        $path = $photo->store('properties/photos', 'public');
        $photoUrls[] = $path;
    }
}
```

### Delete Process

```php
if (!empty($property->photos)) {
    foreach ($property->photos as $photoPath) {
        if (Storage::disk('public')->exists($photoPath)) {
            Storage::disk('public')->delete($photoPath);
        }
    }
}
```

### Access Photos

```php
// In blade view
@foreach($property->photos as $photo)
    <img src="{{ asset('storage/' . $photo) }}" alt="Property Photo">
@endforeach

// Or using main_photo accessor
<img src="{{ asset('storage/' . $property->main_photo) }}" alt="Main Photo">
```

---

## Testing

### Manual Testing

```bash
# Login as owner
php artisan serve

# Navigate to /properties
# Test create, edit, delete, toggle publish/featured
```

### Test Data

```bash
# Create test properties using tinker
php artisan tinker

# Create 10 random properties
Property::factory()->count(10)->create();

# Create published properties
Property::factory()->count(5)->published()->create();

# Create featured properties
Property::factory()->count(3)->featured()->femaleOnly()->create();
```

---

## Next Steps

Task #5 akan fokus pada:
- Room CRUD Controller
- RoomPolicy untuk authorization
- Room Request validation
- Room management views
- Integration dengan Property (nested resources)

---

## Notes

1. ✅ Policy auto-registered via `AuthServiceProvider` (Laravel 11)
2. ✅ FormRequest authorization di-handle via `authorize()` method
3. ✅ File uploads disimpan di `storage/app/public` dengan symbolic link
4. ✅ Soft deletes enabled untuk restore functionality
5. ✅ Middleware `admin` memfilter akses ke owner/admin/staff saja
6. ✅ Success/error messages menggunakan session flash
7. ✅ Pagination dengan query string preservation untuk filters
8. ✅ Eager loading untuk optimize N+1 query problem

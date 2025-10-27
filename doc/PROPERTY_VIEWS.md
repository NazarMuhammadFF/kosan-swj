# Property Views Documentation

## Overview
Dokumentasi untuk semua views yang terkait dengan Property CRUD (Create, Read, Update, Delete) dalam sistem manajemen kos.

## Views Structure

### 1. Index Page (`resources/views/properties/index.blade.php`)

**Purpose**: Menampilkan daftar semua property dengan filtering dan pagination.

**Features**:
- Grid layout untuk menampilkan property cards
- Filter berdasarkan:
  - Search (nama/alamat)
  - Kota
  - Tipe gender (Putra/Putri/Campur)
  - Status publikasi (Published/Draft)
- Badges untuk status property (Published, Featured)
- Statistik singkat (jumlah kamar, review)
- Tombol aksi (Detail, Edit)
- Pagination
- Authorization check untuk tombol "Tambah Property"

**Access**:
- URL: `/properties`
- Route: `properties.index`
- Permission: Requires `viewAny` permission dari PropertyPolicy

**Usage Example**:
```php
// Di controller:
$properties = Property::query()
    ->when(request('search'), function($query, $search) {
        $query->search($search);
    })
    ->when(request('city'), function($query, $city) {
        $query->byCity($city);
    })
    ->when(request('gender_type'), function($query, $type) {
        $query->byGenderType($type);
    })
    ->when(request('is_published') !== null, function($query) {
        request('is_published') ? $query->published() : $query->where('is_published', false);
    })
    ->withCount(['rooms', 'reviews'])
    ->latest()
    ->paginate(12);

return view('properties.index', compact('properties'));
```

---

### 2. Create Page (`resources/views/properties/create.blade.php`)

**Purpose**: Form untuk membuat property baru.

**Features**:
- **Informasi Dasar**:
  - Nama Property (required)
  - Deskripsi (required, textarea)
  - Tipe Penghuni: Putra/Putri/Campur (required)
  
- **Alamat**:
  - Alamat Lengkap (required, textarea)
  - Kota (required)
  - Provinsi (required)
  - Kode Pos (optional)
  - Koordinat GPS: Latitude & Longitude (optional)
  
- **Kontak**:
  - Nomor Telepon (required)
  - WhatsApp (optional)
  
- **Foto Property**:
  - Multiple file upload (max 10 foto)
  - Format: JPG, PNG, WEBP
  - Ukuran max: 2MB per foto
  
- **Fasilitas** (Dynamic Array):
  - Input fields yang bisa ditambah/hapus dinamis
  - JavaScript untuk add/remove fields
  
- **Peraturan** (Dynamic Array):
  - Input fields yang bisa ditambah/hapus dinamis
  - JavaScript untuk add/remove fields
  
- **Status Publikasi**:
  - Checkbox untuk langsung publish property

**Access**:
- URL: `/properties/create`
- Route: `properties.create`
- Permission: Requires `create` permission dari PropertyPolicy

**Validation**:
Menggunakan `StorePropertyRequest` dengan rules:
- name: required|string|max:255|unique:properties
- description: required|string
- address: required|string
- city, province, phone: required|string|max:255
- gender_type: required|in:male,female,mixed
- photos: nullable|array|max:10
- photos.*: image|mimes:jpeg,png,jpg,webp|max:2048
- facilities, rules: nullable|array
- facilities.*, rules.*: string|max:255

**JavaScript Features**:
```javascript
// Add Facility Button
document.getElementById('add-facility').addEventListener('click', function() {
    // Creates new input field for facility
});

// Add Rule Button
document.getElementById('add-rule').addEventListener('click', function() {
    // Creates new input field for rule
});
```

---

### 3. Edit Page (`resources/views/properties/edit.blade.php`)

**Purpose**: Form untuk mengedit property yang sudah ada.

**Features**:
- Sama seperti Create page, tapi dengan data existing
- Pre-filled dengan data property yang ada
- Menggunakan `old('field', $property->field)` untuk default values
- Method PUT untuk update
- Photo management:
  - new_photos[] untuk upload foto baru
  - remove_photos[] untuk hapus foto existing
  
**Access**:
- URL: `/properties/{property}/edit`
- Route: `properties.edit`
- Permission: Requires `update` permission dari PropertyPolicy

**Validation**:
Menggunakan `UpdatePropertyRequest` dengan rules:
- name: required|string|max:255|unique:properties,name,{id}
- (Other fields sama seperti StorePropertyRequest)
- new_photos: nullable|array|max:10
- new_photos.*: image|mimes:jpeg,png,jpg,webp|max:2048
- remove_photos: nullable|array

**Form Method**:
```blade
<form method="POST" action="{{ route('properties.update', $property) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    ...
</form>
```

---

### 4. Show/Detail Page (`resources/views/properties/show.blade.php`)

**Purpose**: Menampilkan detail lengkap property.

**Features**:

**a. Header Section**:
- Nama property
- Tombol "Edit Property" (jika authorized)
- Badges: Published/Draft, Featured, Gender Type

**b. Action Buttons** (Admin/Staff):
- Toggle Publish/Unpublish
- Toggle Feature/Unfeature

**c. Statistik Cards**:
- Total Kamar
- Kamar Tersedia
- Tingkat Hunian (%)
- Total Review

**d. Foto Gallery**:
- Grid 4 columns untuk semua foto
- Hover effect
- Responsive

**e. Informasi Property**:
- Deskripsi
- Alamat lengkap
- Kota, Provinsi
- Kode Pos
- Kontak (Telepon, WhatsApp)
- Koordinat GPS

**f. Fasilitas & Peraturan**:
- List fasilitas dengan icon checklist hijau
- List peraturan dengan icon larangan merah

**g. Daftar Kamar**:
- Table dengan kolom: Nama Kamar, Status, Harga/Bulan, Aksi
- Badge status dengan warna berbeda:
  - Available: hijau
  - Occupied: merah
  - Maintenance: kuning
  - Reserved: biru
- Tombol "Tambah Kamar"
- Link ke detail/edit kamar

**h. Danger Zone** (jika authorized):
- Button untuk hapus property
- Konfirmasi JavaScript sebelum delete
- Warning message

**Access**:
- URL: `/properties/{property}`
- Route: `properties.show`
- Permission: Requires `view` permission dari PropertyPolicy

**Usage Example**:
```php
// Di controller:
public function show(Property $property)
{
    $this->authorize('view', $property);
    
    $property->load(['rooms', 'reviews'])
             ->loadCount(['rooms', 'reviews']);
    
    return view('properties.show', compact('property'));
}
```

---

## Navigation Integration

**File**: `resources/views/layouts/navigation.blade.php`

**Changes**:
```blade
{{-- Desktop Navigation --}}
@can('viewAny', App\Models\Property::class)
    <x-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.*')">
        {{ __('Properties') }}
    </x-nav-link>
@endcan

{{-- Mobile Navigation --}}
@can('viewAny', App\Models\Property::class)
    <x-responsive-nav-link :href="route('properties.index')" :active="request()->routeIs('properties.*')">
        {{ __('Properties') }}
    </x-responsive-nav-link>
@endcan
```

**Features**:
- Menu "Properties" muncul di navigation bar
- Active state highlighting
- Permission-based visibility (only for users with viewAny permission)
- Responsive untuk mobile

---

## Tailwind CSS Classes Used

### Layout Classes:
- `max-w-7xl mx-auto`: Container centering
- `sm:px-6 lg:px-8`: Responsive padding
- `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3`: Responsive grid
- `gap-6, gap-4`: Grid gaps
- `space-y-6`: Vertical spacing

### Form Classes:
- `rounded-md border-gray-300 shadow-sm`: Input styling
- `focus:border-indigo-500 focus:ring-indigo-500`: Focus states
- `@error('field') border-red-500 @enderror`: Error states

### Button Classes:
- `bg-indigo-600 hover:bg-indigo-700`: Primary buttons
- `bg-gray-800 hover:bg-gray-700`: Secondary buttons
- `bg-red-600 hover:bg-red-700`: Danger buttons
- `bg-green-600 hover:bg-green-700`: Success buttons

### Badge Classes:
- `bg-green-100 text-green-800`: Published badge
- `bg-gray-100 text-gray-800`: Draft badge
- `bg-yellow-100 text-yellow-800`: Featured badge
- `bg-blue-100 text-blue-800`: Male type
- `bg-pink-100 text-pink-800`: Female type
- `bg-purple-100 text-purple-800`: Mixed type

### Card Classes:
- `bg-white overflow-hidden shadow-sm sm:rounded-lg`: Card container
- `p-6`: Card padding
- `border rounded-lg`: Border styling

---

## JavaScript Integration

### Photo Upload Preview (Optional Enhancement):
```javascript
document.getElementById('photos').addEventListener('change', function(e) {
    // Preview uploaded photos before submit
});
```

### Form Validation:
```javascript
// Client-side validation before submit
document.querySelector('form').addEventListener('submit', function(e) {
    // Validate required fields
    // Check file sizes
    // Check photo count
});
```

### Dynamic Arrays (Facilities & Rules):
```javascript
// Add Facility
document.getElementById('add-facility').addEventListener('click', function() {
    const container = document.getElementById('facilities-container');
    const newItem = document.createElement('div');
    newItem.className = 'flex gap-2 facility-item';
    newItem.innerHTML = `...`;
    container.appendChild(newItem);
});

// Remove items
onclick="this.parentElement.remove()"
```

---

## Error Handling & Flash Messages

### Success Messages:
```blade
@if (session('success'))
    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif
```

### Error Messages:
```blade
@if (session('error'))
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif

@error('field')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
```

---

## Testing UI

### Manual Testing Steps:

1. **Test Index Page**:
   ```bash
   php artisan serve
   # Visit: http://127.0.0.1:8000/properties
   ```
   - Cek filter works
   - Cek pagination
   - Cek card display
   - Cek permission buttons

2. **Test Create Page**:
   ```bash
   # Visit: http://127.0.0.1:8000/properties/create
   ```
   - Isi semua required fields
   - Upload multiple photos
   - Add/remove facilities
   - Add/remove rules
   - Submit form
   - Cek validation errors

3. **Test Show Page**:
   ```bash
   # Visit: http://127.0.0.1:8000/properties/{id}
   ```
   - Cek semua data ditampilkan
   - Cek photo gallery
   - Cek statistik
   - Cek kamar list
   - Test toggle publish/feature (admin only)

4. **Test Edit Page**:
   ```bash
   # Visit: http://127.0.0.1:8000/properties/{id}/edit
   ```
   - Cek form pre-filled dengan data existing
   - Update beberapa fields
   - Add/remove photos
   - Submit form
   - Cek validation

5. **Test Delete**:
   - Dari show page, klik "Hapus Property"
   - Cek confirmation dialog
   - Confirm delete
   - Cek redirect dan flash message

### Browser Testing Checklist:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile responsive (Chrome DevTools)

---

## Accessibility Features

- Semantic HTML
- Label for all form inputs
- ARIA labels where needed
- Keyboard navigation support
- Focus states visible
- Error messages descriptive
- Alt text for images (when implemented)

---

## Performance Considerations

### Optimization Tips:

1. **Lazy Load Images**:
   ```blade
   <img src="{{ asset('storage/' . $photo) }}" loading="lazy">
   ```

2. **Pagination**:
   - Default 12 items per page
   - Prevents loading too many records

3. **Eager Loading**:
   ```php
   Property::with(['rooms', 'reviews'])->withCount(['rooms', 'reviews'])
   ```

4. **Asset Optimization**:
   ```bash
   npm run build  # Minify CSS/JS for production
   ```

---

## Future Enhancements

1. **Photo Upload**:
   - Drag & drop interface
   - Image cropping/resizing
   - Preview before upload
   - Progress bar

2. **Maps Integration**:
   - Google Maps for location picker
   - Auto-fill latitude/longitude
   - Display map on show page

3. **Rich Text Editor**:
   - WYSIWYG editor untuk description
   - Formatting options

4. **Bulk Actions**:
   - Checkbox untuk select multiple properties
   - Bulk publish/unpublish
   - Bulk delete

5. **Export/Import**:
   - Export properties to Excel/CSV
   - Import dari Excel
   - Bulk upload dengan template

6. **Search Enhancement**:
   - Autocomplete
   - Filter by price range
   - Filter by facilities

---

## Related Documentation

- [Property Models Documentation](./PROPERTY_MODELS.md)
- [Property CRUD Controllers Documentation](./PROPERTY_CRUD.md)
- [Authorization & Permissions](./AUTH.md)

---

## Files Modified/Created

- ✅ `resources/views/properties/index.blade.php` (173 lines)
- ✅ `resources/views/properties/create.blade.php` (278 lines)
- ✅ `resources/views/properties/edit.blade.php` (278 lines)
- ✅ `resources/views/properties/show.blade.php` (265 lines)
- ✅ `resources/views/layouts/navigation.blade.php` (Updated with Properties link)

**Total Lines**: 1033+ lines of Blade templates

---

**Last Updated**: 2025-01-19
**Author**: GitHub Copilot
**Version**: 1.0.0

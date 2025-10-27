# Property Management - Models & Relationships

## Overview
Dokumentasi ini menjelaskan struktur models dan relationships yang telah dibuat untuk modul Property Management pada sistem Kos Management.

## Models Created/Updated

### 1. Property Model
**File**: `app/Models/Property.php`

**Relationships**:
- `belongsTo(User)` - Property dimiliki oleh owner
- `hasMany(Room)` - Property memiliki banyak kamar
- `hasManyThrough(Contract)` - Property memiliki banyak kontrak melalui rooms
- `hasManyThrough(Tenant)` - Property memiliki banyak penyewa melalui contracts
- `hasMany(MaintenanceTicket)` - Property memiliki banyak tiket maintenance
- `hasMany(Review)` - Property memiliki banyak review
- `hasMany(Announcement)` - Property memiliki banyak pengumuman

**Key Features**:
- Auto-generate slug dari nama property
- Ensure slug uniqueness
- Accessor untuk full_address, main_photo, available_rooms_count
- Scopes: published(), featured(), byCity(), byGenderType(), search(), byOwner()
- Helper methods: hasAvailableRooms(), getCheapestRoomPrice(), getMostExpensiveRoomPrice(), getAverageRating(), getTotalReviews(), isOwnedBy(), getOccupancyRate(), getPriceRange()

**Fillable Fields**:
- Basic Info: name, slug, description, phone
- Location: address, city, province, postal_code, latitude, longitude
- Settings: gender_type, deposit_amount, is_published, is_featured
- Media: photos (array), video_url
- Data: facilities (array), rules (array)

**Casts**:
- `facilities` → array
- `rules` → array
- `photos` → array
- `latitude` → decimal:8
- `longitude` → decimal:8
- `deposit_amount` → decimal:2
- `is_published` → boolean
- `is_featured` → boolean

---

### 2. Room Model
**File**: `app/Models/Room.php`

**Relationships**:
- `belongsTo(Property)` - Room milik property tertentu
- `hasMany(Booking)` - Room memiliki banyak booking
- `hasMany(Contract)` - Room memiliki banyak kontrak
- `hasOne(Contract)` - Current active contract
- `hasMany(MaintenanceTicket)` - Room memiliki banyak tiket maintenance

**Key Features**:
- Accessor untuk total_monthly_price, main_photo, is_available
- Scopes: available(), occupied(), byProperty(), byStatus(), search()
- Status management: markAsAvailable(), markAsOccupied(), markAsMaintenance(), markAsReserved()
- Helper methods: getCurrentTenant(), hasActiveContract(), getFormattedPrice()

**Status Enum**: available, occupied, maintenance, reserved

---

### 3. Tenant Model
**File**: `app/Models/Tenant.php`

**Relationships**:
- `belongsTo(User)` - Optional link to user account
- `hasMany(Contract)` - Tenant memiliki banyak kontrak
- `activeContracts()` - Kontrak yang sedang aktif
- `hasMany(Booking)` - Tenant memiliki banyak booking
- `hasMany(Invoice)` - Tenant memiliki banyak invoice
- `hasMany(Payment)` - Tenant memiliki banyak pembayaran
- `hasMany(MaintenanceTicket)` - Tenant membuat tiket maintenance
- `hasMany(Review)` - Tenant menulis review

**Key Features**:
- Scopes: verified(), pendingVerification()
- Helper methods: isVerified(), getAge(), getCurrentRooms()
- KYC data: id_number, id_card_photo, documents
- Verification workflow: verification_status, verified_at, verified_by

**Verification Status**: pending, verified, rejected

---

### 4. Contract Model
**File**: `app/Models/Contract.php`

**Relationships**:
- `belongsTo(Room)` - Kontrak untuk kamar tertentu
- `belongsTo(Tenant)` - Kontrak dengan penyewa
- `belongsTo(Booking)` - Kontrak berasal dari booking
- `hasMany(Invoice)` - Kontrak menghasilkan invoice bulanan

**Key Features**:
- Auto-generate contract_number
- Scopes: active(), expired(), byRoom()
- Helper methods: isActive(), getRemainingDays(), terminate()
- Billing settings: billing_day, monthly_rent, deposit
- Contract file: signed_contract_file

**Status Enum**: draft, active, expired, terminated

---

### 5. Booking Model
**File**: `app/Models/Booking.php`

**Relationships**:
- `belongsTo(Room)` - Booking untuk kamar tertentu
- `belongsTo(User)` - User yang melakukan booking
- `hasOne(Contract)` - Booking bisa menjadi kontrak

**Key Features**:
- Auto-generate booking_code (format: BK-YYYYMMDD-XXXXXX)
- Scopes: pending(), confirmed(), byType(), expired()
- Workflow methods: confirm(), reject(), cancel()
- DP handling: dp_amount, dp_proof

**Booking Types**: visit (kunjungan), direct (langsung sewa)
**Status Enum**: pending, confirmed, rejected, cancelled, completed

---

### 6. MaintenanceTicket Model
**File**: `app/Models/MaintenanceTicket.php`

**Relationships**:
- `belongsTo(Tenant)` - Pelapor
- `belongsTo(Room)` - Kamar yang bermasalah
- `belongsTo(Property)` - Property terkait
- `belongsTo(User)` - Staff yang ditugaskan

**Key Features**:
- Auto-generate ticket_number (format: MT-YYYYMMDD-XXXXXX)
- Auto-calculate SLA deadline based on priority
- Scopes: open(), byPriority(), overdue()
- Assignment: assignTo(), resolve()
- SLA tracking: sla_hours, sla_deadline, isOverdue()

**Priority Levels**: urgent (4h), high (24h), normal (72h), low (168h)
**Status Enum**: open, in_progress, resolved, closed

---

### 7. Review Model
**File**: `app/Models/Review.php`

**Relationships**:
- `belongsTo(Property)` - Review untuk property
- `belongsTo(Tenant)` - Ditulis oleh tenant
- `belongsTo(Contract)` - Berdasarkan kontrak

**Key Features**:
- Rating validation (1-5 at application level)
- Scopes: published(), verified(), byRating()
- Owner reply functionality
- Moderation: is_published, is_verified

---

### 8. Announcement Model
**File**: `app/Models/Announcement.php`

**Relationships**:
- `belongsTo(Property)` - Untuk property tertentu (nullable = global)
- `belongsTo(User)` - Dibuat oleh admin/staff

**Key Features**:
- Scopes: published(), active(), global(), byProperty(), byCategory()
- Expiry handling: expires_at, isExpired()
- Categories: maintenance, billing, general, event, emergency

---

### 9. Invoice Model
**File**: `app/Models/Invoice.php`

**Relationships**:
- `belongsTo(Contract)` - Invoice untuk kontrak
- `belongsTo(Tenant)` - Tagihan untuk tenant
- `hasMany(Payment)` - Invoice dibayar dengan payments

**Key Features**:
- Auto-generate invoice_number (format: INV-YYYYMMDD-XXXXXX)
- Detailed billing breakdown: monthly_rent, electricity_fee, water_fee, late_fee, other_fees, discount
- Scopes: unpaid(), overdue()
- Helper methods: isOverdue(), markAsPaid(), getRemainingAmount()

**Status Enum**: unpaid, partially_paid, paid, cancelled

---

### 10. Payment Model
**File**: `app/Models/Payment.php`

**Relationships**:
- `belongsTo(Invoice)` - Pembayaran untuk invoice
- `belongsTo(Tenant)` - Dibayar oleh tenant
- `belongsTo(User)` - Diverifikasi oleh admin

**Key Features**:
- Auto-generate payment_code (format: PAY-YYYYMMDD-XXXXXX)
- Verification workflow: verify(), reject()
- Scopes: pending(), verified()
- Proof upload: proof_image

**Status Enum**: pending, verified, rejected

---

## PropertyFactory

**File**: `database/factories/PropertyFactory.php`

Factory untuk generate data property palsu untuk testing dan seeding.

**States**:
- `published()` - Property sudah dipublikasi
- `featured()` - Property unggulan
- `maleOnly()` - Khusus pria
- `femaleOnly()` - Khusus wanita
- `mixed()` - Campur

**Usage**:
```php
// Create single property
Property::factory()->create();

// Create published property
Property::factory()->published()->create();

// Create 10 featured properties
Property::factory()->count(10)->featured()->create();

// Create female-only property
Property::factory()->femaleOnly()->published()->create();
```

---

## Usage Examples

### Query Properties
```php
// Get all published properties
$properties = Property::published()->get();

// Get featured properties in Jakarta
$featuredInJkt = Property::featured()
    ->byCity('Jakarta')
    ->get();

// Search properties
$results = Property::search('Kost Putri')->published()->get();

// Get properties by owner
$myProperties = Property::byOwner(auth()->id())->get();
```

### Work with Rooms
```php
$property = Property::find(1);

// Get available rooms
$availableRooms = $property->rooms()->available()->get();

// Get cheapest room price
$minPrice = $property->getCheapestRoomPrice();

// Get price range
$priceRange = $property->getPriceRange(); // "Rp 1.000.000 - Rp 2.500.000"

// Get occupancy rate
$occupancy = $property->getOccupancyRate(); // 75.50 (percentage)
```

### Work with Reviews
```php
// Get average rating
$avgRating = $property->getAverageRating(); // 4.5

// Get total reviews
$totalReviews = $property->getTotalReviews(); // 23

// Get published reviews
$reviews = $property->reviews()->published()->get();
```

### Room Management
```php
$room = Room::find(1);

// Check current tenant
$currentTenant = $room->getCurrentTenant();

// Change room status
$room->markAsOccupied();
$room->markAsMaintenance();
$room->markAsAvailable();

// Get total monthly price
$totalPrice = $room->total_monthly_price; // base_price + electricity + water
```

### Tenant Management
```php
$tenant = Tenant::find(1);

// Check verification status
if ($tenant->isVerified()) {
    // Allow booking
}

// Get current rooms
$currentRooms = $tenant->getCurrentRooms();

// Get age
$age = $tenant->getAge();
```

### Contract Management
```php
$contract = Contract::find(1);

// Check if active
if ($contract->isActive()) {
    // Contract masih berlaku
}

// Get remaining days
$remainingDays = $contract->getRemainingDays();

// Terminate contract
$contract->terminate('Tenant pindah');
```

### Booking Workflow
```php
$booking = Booking::find(1);

// Confirm booking
$booking->confirm();

// Reject booking
$booking->reject('Kamar sudah tidak tersedia');

// Check if pending
if ($booking->isPending()) {
    // Process DP verification
}
```

### Maintenance Tickets
```php
$ticket = MaintenanceTicket::find(1);

// Assign to staff
$ticket->assignTo($staffId);

// Resolve ticket
$ticket->resolve('Sudah diperbaiki, AC berfungsi normal');

// Check if overdue
if ($ticket->isOverdue()) {
    // Send notification
}

// Get overdue tickets
$overdueTickets = MaintenanceTicket::overdue()->get();
```

### Invoice & Payment
```php
$invoice = Invoice::find(1);

// Check if overdue
if ($invoice->isOverdue()) {
    // Add late fee
}

// Get overdue invoices
$overdueInvoices = Invoice::overdue()->get();

// Mark as paid
$invoice->markAsPaid();

// Get remaining amount
$remaining = $invoice->getRemainingAmount();

// Payment verification
$payment = Payment::find(1);
$payment->verify(auth()->id());
$payment->reject('Bukti transfer tidak valid', auth()->id());
```

---

## Testing

Untuk menguji models, jalankan:

```bash
# Test di tinker
php artisan tinker

# Create property with factory
$property = Property::factory()->create();

# Check relationships
$property->rooms;
$property->owner;

# Test scopes
Property::published()->count();
Property::featured()->count();

# Test helper methods
$property->hasAvailableRooms();
$property->getCheapestRoomPrice();
```

---

## Notes

1. Semua models menggunakan **SoftDeletes** untuk soft deletion
2. Semua numeric fields sudah di-cast ke **decimal:2** untuk handling uang
3. Semua array fields (facilities, photos, etc.) sudah di-cast ke **array**
4. Auto-generate codes menggunakan format: **PREFIX-YYYYMMDD-XXXXXX**
5. Validation rating (1-5) dilakukan di application level, bukan database constraint
6. SLA deadline auto-calculated berdasarkan priority level

---

## Next Steps

Task #4 akan fokus pada:
- Property Controller (CRUD operations)
- Room Controller (CRUD operations)
- Property validation rules
- File upload handling (photos, videos)
- API endpoints untuk property management

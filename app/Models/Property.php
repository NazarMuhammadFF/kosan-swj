<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'gender_type',
        'facilities',
        'rules',
        'deposit_amount',
        'photos',
        'video_url',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'facilities' => 'array',
        'rules' => 'array',
        'photos' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'deposit_amount' => 'decimal:2',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'full_address',
        'main_photo',
        'available_rooms_count',
    ];

    /**
     * Relationship: Property belongs to an owner (User)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relationship: Property has many rooms
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Relationship: Property has many contracts through rooms
     */
    public function contracts()
    {
        return $this->hasManyThrough(Contract::class, Room::class);
    }

    /**
     * Relationship: Property has many tenants through contracts
     */
    public function tenants()
    {
        return $this->hasManyThrough(Tenant::class, Contract::class, 'room_id', 'id', 'id', 'tenant_id')
            ->join('rooms', 'contracts.room_id', '=', 'rooms.id')
            ->where('rooms.property_id', $this->id);
    }

    /**
     * Relationship: Property has many maintenance tickets
     */
    public function maintenanceTickets()
    {
        return $this->hasMany(MaintenanceTicket::class);
    }

    /**
     * Relationship: Property has many reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relationship: Property has many announcements
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->name);
            }
        });

        static::updating(function ($property) {
            if ($property->isDirty('name') && empty($property->slug)) {
                $property->slug = Str::slug($property->name);
            }
        });
    }

    /**
     * Accessor: Get formatted full address
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->province,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Accessor: Get main photo (first photo in array)
     */
    public function getMainPhotoAttribute()
    {
        if (!empty($this->photos) && is_array($this->photos)) {
            return $this->photos[0] ?? null;
        }
        return null;
    }

    /**
     * Accessor: Get count of available rooms
     */
    public function getAvailableRoomsCountAttribute()
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    /**
     * Mutator: Ensure slug is unique
     */
    public function setSlugAttribute($value)
    {
        $slug = Str::slug($value ?: $this->name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Scope: Filter published properties
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Filter featured properties
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: Filter by city
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope: Filter by gender type
     */
    public function scopeByGenderType($query, $genderType)
    {
        return $query->where('gender_type', $genderType);
    }

    /**
     * Scope: Search by name or address
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter by owner
     */
    public function scopeByOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    /**
     * Helper: Check if property has available rooms
     */
    public function hasAvailableRooms()
    {
        return $this->rooms()->where('status', 'available')->exists();
    }

    /**
     * Helper: Get cheapest room price
     */
    public function getCheapestRoomPrice()
    {
        return $this->rooms()
            ->where('status', 'available')
            ->min('base_price') ?? 0;
    }

    /**
     * Helper: Get most expensive room price
     */
    public function getMostExpensiveRoomPrice()
    {
        return $this->rooms()
            ->where('status', 'available')
            ->max('base_price') ?? 0;
    }

    /**
     * Helper: Get average rating from reviews
     */
    public function getAverageRating()
    {
        return $this->reviews()
            ->where('is_published', true)
            ->avg('rating') ?? 0;
    }

    /**
     * Helper: Get total reviews count
     */
    public function getTotalReviews()
    {
        return $this->reviews()
            ->where('is_published', true)
            ->count();
    }

    /**
     * Helper: Check if property is owned by specific user
     */
    public function isOwnedBy($userId)
    {
        return $this->owner_id == $userId;
    }

    /**
     * Helper: Get occupancy rate (percentage)
     */
    public function getOccupancyRate()
    {
        $totalRooms = $this->rooms()->count();

        if ($totalRooms === 0) {
            return 0;
        }

        $occupiedRooms = $this->rooms()->where('status', 'occupied')->count();

        return round(($occupiedRooms / $totalRooms) * 100, 2);
    }

    /**
     * Helper: Get formatted price range
     */
    public function getPriceRange()
    {
        $min = $this->getCheapestRoomPrice();
        $max = $this->getMostExpensiveRoomPrice();

        if ($min == $max) {
            return 'Rp ' . number_format($min, 0, ',', '.');
        }

        return 'Rp ' . number_format($min, 0, ',', '.') . ' - Rp ' . number_format($max, 0, ',', '.');
    }
}

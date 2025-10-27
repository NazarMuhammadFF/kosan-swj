<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'code',
        'floor',
        'status',
        'base_price',
        'electricity_fee',
        'water_fee',
        'size',
        'facilities',
        'photos',
        'notes',
    ];

    protected $casts = [
        'facilities' => 'array',
        'photos' => 'array',
        'base_price' => 'decimal:2',
        'electricity_fee' => 'decimal:2',
        'water_fee' => 'decimal:2',
        'size' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'total_monthly_price',
        'main_photo',
        'is_available',
    ];

    /**
     * Relationship: Room belongs to a property
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Relationship: Room has many bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relationship: Room has many contracts
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Relationship: Room has current contract
     */
    public function currentContract()
    {
        return $this->hasOne(Contract::class)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    /**
     * Relationship: Room has many maintenance tickets
     */
    public function maintenanceTickets()
    {
        return $this->hasMany(MaintenanceTicket::class);
    }

    /**
     * Accessor: Get total monthly price
     */
    public function getTotalMonthlyPriceAttribute()
    {
        return $this->base_price + $this->electricity_fee + $this->water_fee;
    }

    /**
     * Accessor: Get main photo
     */
    public function getMainPhotoAttribute()
    {
        if (!empty($this->photos) && is_array($this->photos)) {
            return $this->photos[0] ?? null;
        }
        return null;
    }

    /**
     * Accessor: Check if room is available
     */
    public function getIsAvailableAttribute()
    {
        return $this->status === 'available';
    }

    /**
     * Scope: Filter available rooms
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope: Filter occupied rooms
     */
    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    /**
     * Scope: Filter by property
     */
    public function scopeByProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Search by room code
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('code', 'like', "%{$search}%");
    }

    /**
     * Helper: Get current tenant
     */
    public function getCurrentTenant()
    {
        $contract = $this->currentContract;
        return $contract ? $contract->tenant : null;
    }

    /**
     * Helper: Mark room as available
     */
    public function markAsAvailable()
    {
        $this->update(['status' => 'available']);
    }

    /**
     * Helper: Mark room as occupied
     */
    public function markAsOccupied()
    {
        $this->update(['status' => 'occupied']);
    }

    /**
     * Helper: Mark room as maintenance
     */
    public function markAsMaintenance()
    {
        $this->update(['status' => 'maintenance']);
    }

    /**
     * Helper: Mark room as reserved
     */
    public function markAsReserved()
    {
        $this->update(['status' => 'reserved']);
    }

    /**
     * Helper: Check if room has active contract
     */
    public function hasActiveContract()
    {
        return $this->currentContract()->exists();
    }

    /**
     * Helper: Get formatted price
     */
    public function getFormattedPrice()
    {
        return 'Rp ' . number_format($this->base_price, 0, ',', '.');
    }

    /**
     * Helper: Get formatted total price
     */
    public function getFormattedTotalPrice()
    {
        return 'Rp ' . number_format($this->total_monthly_price, 0, ',', '.');
    }
}

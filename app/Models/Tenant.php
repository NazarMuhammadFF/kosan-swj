<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'id_number',
        'id_card_photo',
        'place_of_birth',
        'date_of_birth',
        'gender',
        'occupation',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'documents',
        'verification_status',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'documents' => 'array',
        'date_of_birth' => 'date',
        'verified_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Tenant optionally belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Tenant has many contracts
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Relationship: Tenant has current active contracts
     */
    public function activeContracts()
    {
        return $this->hasMany(Contract::class)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    /**
     * Relationship: Tenant has many bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: Tenant has many invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Relationship: Tenant has many payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relationship: Tenant has many maintenance tickets
     */
    public function maintenanceTickets()
    {
        return $this->hasMany(MaintenanceTicket::class);
    }

    /**
     * Relationship: Tenant has many reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope: Filter verified tenants
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope: Filter pending verification
     */
    public function scopePendingVerification($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Helper: Check if tenant is verified
     */
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Helper: Get age from date of birth
     */
    public function getAge()
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }

    /**
     * Helper: Get current room(s)
     */
    public function getCurrentRooms()
    {
        return $this->activeContracts->map(function ($contract) {
            return $contract->room;
        });
    }
}

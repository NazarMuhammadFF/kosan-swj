<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contract_number',
        'room_id',
        'tenant_id',
        'booking_id',
        'start_date',
        'end_date',
        'monthly_rent',
        'deposit',
        'billing_day',
        'terms',
        'signed_contract_file',
        'status',
        'terminated_at',
        'termination_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_rent' => 'decimal:2',
        'deposit' => 'decimal:2',
        'terminated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Contract belongs to a room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relationship: Contract belongs to a tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relationship: Contract belongs to a booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relationship: Contract has many invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Scope: Filter active contracts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }

    /**
     * Scope: Filter expired contracts
     */
    public function scopeExpired($query)
    {
        return $query->whereDate('end_date', '<', now());
    }

    /**
     * Scope: Filter by room
     */
    public function scopeByRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }

    /**
     * Helper: Check if contract is active
     */
    public function isActive()
    {
        return $this->status === 'active' 
            && $this->start_date <= now() 
            && $this->end_date >= now();
    }

    /**
     * Helper: Get remaining days
     */
    public function getRemainingDays()
    {
        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Helper: Terminate contract
     */
    public function terminate($reason)
    {
        $this->update([
            'status' => 'terminated',
            'terminated_at' => now(),
            'termination_reason' => $reason,
        ]);
    }
}

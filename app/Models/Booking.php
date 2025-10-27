<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_code',
        'room_id',
        'user_id',
        'type',
        'visit_date',
        'visit_time',
        'check_in_date',
        'duration_months',
        'dp_amount',
        'dp_proof',
        'status',
        'expires_at',
        'notes',
        'admin_notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'check_in_date' => 'date',
        'dp_amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate booking code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Relationship: Booking belongs to a room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relationship: Booking belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Booking has one contract
     */
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    /**
     * Scope: Filter pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Filter confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope: Filter by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Filter expired bookings
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Helper: Check if booking is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Helper: Confirm booking
     */
    public function confirm()
    {
        $this->update(['status' => 'confirmed']);
    }

    /**
     * Helper: Reject booking
     */
    public function reject($reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $reason,
        ]);
    }

    /**
     * Helper: Cancel booking
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }
}

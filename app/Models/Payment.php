<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_code',
        'invoice_id',
        'tenant_id',
        'amount',
        'payment_method',
        'proof_image',
        'notes',
        'status',
        'verified_at',
        'verified_by',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate payment code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_code)) {
                $payment->payment_code = 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Relationship: Payment belongs to an invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relationship: Payment belongs to a tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relationship: Payment verified by a user
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope: Filter pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Filter verified payments
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Helper: Verify payment
     */
    public function verify($verifierId)
    {
        $this->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $verifierId,
        ]);
    }

    /**
     * Helper: Reject payment
     */
    public function reject($reason, $verifierId)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'verified_by' => $verifierId,
        ]);
    }
}

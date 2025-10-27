<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'contract_id',
        'tenant_id',
        'billing_period',
        'monthly_rent',
        'electricity_fee',
        'water_fee',
        'late_fee',
        'other_fees',
        'discount',
        'total_amount',
        'paid_amount',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'electricity_fee' => 'decimal:2',
        'water_fee' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'other_fees' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'billing_period' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate invoice number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    /**
     * Relationship: Invoice belongs to a contract
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Relationship: Invoice belongs to a tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relationship: Invoice has many payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope: Filter unpaid invoices
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope: Filter overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'unpaid')
            ->where('due_date', '<', now());
    }

    /**
     * Helper: Check if invoice is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'unpaid' && $this->due_date < now();
    }

    /**
     * Helper: Mark as paid
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
            'paid_amount' => $this->total_amount,
        ]);
    }

    /**
     * Helper: Get remaining amount
     */
    public function getRemainingAmount()
    {
        return $this->total_amount - $this->paid_amount;
    }
}

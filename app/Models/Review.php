<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'tenant_id',
        'contract_id',
        'rating',
        'comment',
        'photos',
        'owner_reply',
        'owner_replied_at',
        'is_published',
        'is_verified',
    ];

    protected $casts = [
        'rating' => 'integer',
        'photos' => 'array',
        'owner_replied_at' => 'datetime',
        'is_published' => 'boolean',
        'is_verified' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Review belongs to a property
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Relationship: Review belongs to a tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relationship: Review belongs to a contract
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Scope: Filter published reviews
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Filter verified reviews
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope: Filter by rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Helper: Reply to review
     */
    public function reply($reply)
    {
        $this->update([
            'owner_reply' => $reply,
            'owner_replied_at' => now(),
        ]);
    }

    /**
     * Helper: Publish review
     */
    public function publish()
    {
        $this->update(['is_published' => true]);
    }

    /**
     * Helper: Verify review
     */
    public function verify()
    {
        $this->update(['is_verified' => true]);
    }
}

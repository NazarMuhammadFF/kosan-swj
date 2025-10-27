<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'created_by',
        'title',
        'content',
        'category',
        'priority',
        'attachments',
        'expires_at',
        'is_published',
    ];

    protected $casts = [
        'attachments' => 'array',
        'expires_at' => 'datetime',
        'is_published' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Announcement belongs to a property (nullable for global)
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Relationship: Announcement created by a user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope: Filter published announcements
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Filter active announcements (not expired)
     */
    public function scopeActive($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope: Filter global announcements
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('property_id');
    }

    /**
     * Scope: Filter by property
     */
    public function scopeByProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    /**
     * Scope: Filter by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Helper: Check if announcement is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    /**
     * Helper: Publish announcement
     */
    public function publish()
    {
        $this->update(['is_published' => true]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MaintenanceTicket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'tenant_id',
        'room_id',
        'property_id',
        'category',
        'priority',
        'title',
        'description',
        'photos',
        'status',
        'assigned_to',
        'sla_hours',
        'sla_deadline',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'photos' => 'array',
        'sla_deadline' => 'datetime',
        'resolved_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate ticket number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'MT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }

            // Set SLA deadline based on priority
            if (empty($ticket->sla_deadline) && !empty($ticket->priority)) {
                $hours = match($ticket->priority) {
                    'urgent' => 4,
                    'high' => 24,
                    'normal' => 72,
                    'low' => 168,
                    default => 72,
                };
                $ticket->sla_hours = $hours;
                $ticket->sla_deadline = now()->addHours($hours);
            }
        });
    }

    /**
     * Relationship: Ticket belongs to a tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relationship: Ticket belongs to a room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Relationship: Ticket belongs to a property
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Relationship: Ticket assigned to a staff
     */
    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope: Filter open tickets
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Scope: Filter by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: Filter overdue tickets
     */
    public function scopeOverdue($query)
    {
        return $query->where('sla_deadline', '<', now())
            ->whereNotIn('status', ['resolved', 'closed']);
    }

    /**
     * Helper: Assign to staff
     */
    public function assignTo($userId)
    {
        $this->update([
            'assigned_to' => $userId,
            'status' => 'in_progress',
        ]);
    }

    /**
     * Helper: Resolve ticket
     */
    public function resolve($notes)
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_notes' => $notes,
        ]);
    }

    /**
     * Helper: Check if overdue
     */
    public function isOverdue()
    {
        return $this->sla_deadline < now() && !in_array($this->status, ['resolved', 'closed']);
    }
}

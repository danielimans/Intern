<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanPort extends Model
{
    use HasFactory;

    protected $fillable = [
        'wall_port_label',
        'user_id',
        'extension_number',
        'email',
        'switch_port',
        'floor_user',
        'port_status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user associated with this LAN port.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by port status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('port_status', $status);
    }

    /**
     * Scope to filter by floor.
     */
    public function scopeByFloor($query, $floor)
    {
        return $query->where('floor_user', $floor);
    }

    /**
     * Scope to filter active ports only.
     */
    public function scopeActive($query)
    {
        return $query->where('port_status', 'active');
    }

    /**
     * Scope to filter inactive ports only.
     */
    public function scopeInactive($query)
    {
        return $query->where('port_status', 'inactive');
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColor(): string
    {
        return match($this->port_status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'maintenance' => 'warning',
            default => 'light',
        };
    }

    /**
     * Get the status badge text.
     */
    public function getStatusBadge(): string
    {
        return ucfirst($this->port_status);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoicePort extends Model
{
    use HasFactory;

    protected $fillable = [
        'wall_port_label',
        'user_id',
        'extension_number',
        'email',
        'pr_number',
        'pen_number',
        'floor_user',
        'port_status',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user associated with this voice port.
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
     * Scope to filter by PR number.
     */
    public function scopeByPrNumber($query, $prNumber)
    {
        return $query->where('pr_number', $prNumber);
    }

    /**
     * Scope to filter by PEN number.
     */
    public function scopeByPenNumber($query, $penNumber)
    {
        return $query->where('pen_number', $penNumber);
    }

    /**
     * Validate PR number format (customize based on your requirements).
     */
    public static function isValidPrNumber($prNumber): bool
    {
        // Example: PR format PR-XXXXX
        return preg_match('/^PR-\d{5}$/', $prNumber) === 1;
    }

    /**
     * Validate PEN number format (customize based on your requirements).
     */
    public static function isValidPenNumber($penNumber): bool
    {
        // Example: PEN format PEN-XXXXX
        return preg_match('/^PEN-\d{5}$/', $penNumber) === 1;
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

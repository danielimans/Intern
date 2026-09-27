<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assisted_port_id',
        'assisted_port_type',
        'support_ticket_id',
        'assistance_notes',
        'assistance_date',
        'issue_resolved_by',
    ];

    protected $casts = [
        'assistance_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user associated with this technician record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the assisted port (polymorphic via type).
     */
    public function getAssistedPort()
    {
        if ($this->assisted_port_type === 'lan') {
            return LanPort::find($this->assisted_port_id);
        } elseif ($this->assisted_port_type === 'voice') {
            return VoicePort::find($this->assisted_port_id);
        }

        return null;
    }

    /**
     * Scope to filter by support ticket.
     */
    public function scopeByTicket($query, $ticketId)
    {
        return $query->where('support_ticket_id', $ticketId);
    }

    /**
     * Scope to filter recent assistance records.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('assistance_date', '>=', now()->subDays($days));
    }

    /**
     * Scope to filter by port type.
     */
    public function scopeByPortType($query, $portType)
    {
        return $query->where('assisted_port_type', $portType);
    }

    /**
     * Get technician statistics.
     */
    public static function getTechnicianStats($userId)
    {
        $technician = self::where('user_id', $userId)->first();

        if (!$technician) {
            return null;
        }

        return [
            'total_assists' => self::where('user_id', $userId)->count(),
            'lan_assists' => self::where('user_id', $userId)->where('assisted_port_type', 'lan')->count(),
            'voice_assists' => self::where('user_id', $userId)->where('assisted_port_type', 'voice')->count(),
            'active_tickets' => self::where('user_id', $userId)->whereNotNull('support_ticket_id')->count(),
        ];
    }
}

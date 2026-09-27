<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RackUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_rack_id',
        'unit_number',
        'unit_height',
        'equipment_name',
        'equipment_type',
        'description',
        'port_connections',
        'specifications',
        'is_powered',
        'power_consumption',
    ];

    protected $casts = [
        'port_connections' => 'json',
        'specifications' => 'json',
        'is_powered' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the server rack this unit belongs to.
     */
    public function serverRack(): BelongsTo
    {
        return $this->belongsTo(ServerRack::class);
    }

    /**
     * Get the color for equipment type.
     */
    public function getEquipmentColor(): string
    {
        return match($this->equipment_type) {
            'server' => '#4CAF50',
            'switch' => '#2196F3',
            'router' => '#FF9800',
            'firewall' => '#F44336',
            'pdu' => '#9C27B0',
            'patch_panel' => '#00BCD4',
            'console_server' => '#795548',
            'cable_manager' => '#CCCCCC',
            'blank' => '#EEEEEE',
            default => '#9E9E9E',
        };
    }

    /**
     * Get the icon for equipment type.
     */
    public function getEquipmentIcon(): string
    {
        return match($this->equipment_type) {
            'server' => 'fas fa-server',
            'switch' => 'fas fa-network-wired',
            'router' => 'fas fa-router',
            'firewall' => 'fas fa-shield-alt',
            'pdu' => 'fas fa-plug',
            'patch_panel' => 'fas fa-th',
            'console_server' => 'fas fa-terminal',
            'cable_manager' => 'fas fa-link',
            'blank' => 'fas fa-minus',
            default => 'fas fa-box',
        };
    }

    /**
     * Check if unit is occupied.
     */
    public function isOccupied(): bool
    {
        return $this->equipment_type !== 'blank';
    }

    /**
     * Scope to filter by equipment type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('equipment_type', $type);
    }

    /**
     * Scope to filter powered units only.
     */
    public function scopePowered($query)
    {
        return $query->where('is_powered', true);
    }

    /**
     * Scope to filter occupied units only.
     */
    public function scopeOccupied($query)
    {
        return $query->where('equipment_type', '!=', 'blank');
    }

    /**
     * Get port connections array.
     */
    public function getConnections()
    {
        return $this->port_connections ?? [];
    }

    /**
     * Add a port connection.
     */
    public function addConnection($port): void
    {
        $connections = $this->getConnections();
        $connections[] = $port;
        $this->port_connections = $connections;
        $this->save();
    }

    /**
     * Remove a port connection.
     */
    public function removeConnection($port): void
    {
        $connections = $this->getConnections();
        $connections = array_filter($connections, function ($p) use ($port) {
            return $p !== $port;
        });
        $this->port_connections = array_values($connections);
        $this->save();
    }

    /**
     * Get detailed specifications.
     */
    public function getSpecifications()
    {
        return $this->specifications ?? [];
    }
}

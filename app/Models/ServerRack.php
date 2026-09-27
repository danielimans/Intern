<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServerRack extends Model
{
    use HasFactory;

    protected $fillable = [
        'rack_name',
        'location',
        'total_units',
        'rack_type',
        'description',
        'rack_position',
    ];

    protected $casts = [
        'rack_position' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the rack units for this server rack.
     */
    public function rackUnits(): HasMany
    {
        return $this->hasMany(RackUnit::class)->orderBy('unit_number', 'desc');
    }

    /**
     * Get occupied rack units.
     */
    public function occupiedUnits()
    {
        return $this->rackUnits()->where('equipment_type', '!=', 'blank');
    }

    /**
     * Get available rack units.
     */
    public function availableUnits()
    {
        return $this->rackUnits()->where('equipment_type', '=', 'blank');
    }

    /**
     * Get the count of occupied units.
     */
    public function getOccupiedCountAttribute(): int
    {
        return $this->occupiedUnits()->count();
    }

    /**
     * Get the percentage of rack utilization.
     */
    public function getUtilizationPercentageAttribute(): float
    {
        if ($this->total_units === 0) {
            return 0;
        }
        return round(($this->occupied_count / $this->total_units) * 100, 2);
    }

    /**
     * Get all equipment in the rack.
     */
    public function getEquipmentList()
    {
        return $this->occupiedUnits()->get();
    }

    /**
     * Check if a unit is available.
     */
    public function isUnitAvailable($unitNumber, $unitHeight = 1): bool
    {
        for ($i = 0; $i < $unitHeight; $i++) {
            $unit = $this->rackUnits()
                ->where('unit_number', $unitNumber - $i)
                ->first();
            
            if (!$unit || $unit->equipment_type !== 'blank') {
                return false;
            }
        }
        return true;
    }

    /**
     * Scope to filter by location.
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Scope to filter by rack type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('rack_type', $type);
    }

    /**
     * Get visualization data for 360° view.
     */
    public function getVisualizationData()
    {
        return [
            'id' => $this->id,
            'name' => $this->rack_name,
            'location' => $this->location,
            'total_units' => $this->total_units,
            'utilization' => $this->utilization_percentage,
            'units' => $this->rackUnits()->get()->map(function ($unit) {
                return [
                    'id' => $unit->id,
                    'unit_number' => $unit->unit_number,
                    'equipment_name' => $unit->equipment_name,
                    'equipment_type' => $unit->equipment_type,
                    'is_powered' => $unit->is_powered,
                ];
            })->toArray(),
        ];
    }
}

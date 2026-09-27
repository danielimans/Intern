<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log an action to the audit log.
     */
    public function log(
        string $action,
        string $modelType,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * Log a port status change.
     */
    public function logPortStatusChange(string $portType, int $portId, string $oldStatus, string $newStatus): void
    {
        $this->log(
            'update',
            ucfirst($portType) . 'Port',
            $portId,
            ['port_status' => $oldStatus],
            ['port_status' => $newStatus],
            "Port status changed from {$oldStatus} to {$newStatus}"
        );
    }

    /**
     * Log equipment addition to rack.
     */
    public function logEquipmentAdded(int $rackId, int $unitNumber, string $equipmentName): void
    {
        $this->log(
            'create',
            'RackUnit',
            null,
            null,
            [
                'server_rack_id' => $rackId,
                'unit_number' => $unitNumber,
                'equipment_name' => $equipmentName,
            ],
            "Equipment '$equipmentName' added to rack unit $unitNumber"
        );
    }

    /**
     * Log port assignment to user.
     */
    public function logPortAssignment(string $portType, int $portId, int $userId): void
    {
        $this->log(
            'update',
            ucfirst($portType) . 'Port',
            $portId,
            ['user_id' => null],
            ['user_id' => $userId],
            "Port assigned to user ID $userId"
        );
    }

    /**
     * Log data export.
     */
    public function logExport(string $dataType): void
    {
        $this->log(
            'export',
            $dataType,
            null,
            null,
            null,
            "$dataType data exported"
        );
    }

    /**
     * Get recent activity for dashboard.
     */
    public function getRecentActivity(int $limit = 10)
    {
        return AuditLog::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get activity for a specific model.
     */
    public function getModelActivity(string $modelType, int $modelId)
    {
        return AuditLog::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->with('user')
            ->latest()
            ->get();
    }

    /**
     * Get activity for a specific user.
     */
    public function getUserActivity(int $userId)
    {
        return AuditLog::where('user_id', $userId)
            ->with('user')
            ->latest()
            ->get();
    }
}

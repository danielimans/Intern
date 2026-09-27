<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Audit Log Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for audit logging system
    |
    */

    // Number of days to retain audit logs (30 days default)
    'retention_days' => env('AUDIT_RETENTION_DAYS', 30),

    // Models to exclude from audit logging
    'excluded_models' => [
        // 'App\Models\TemporaryModel',
    ],

    // Events to audit
    'audited_events' => ['created', 'updated', 'deleted'],

    // Restrict audit log access to IT staff (admin only)
    'it_staff_only' => true,

    // Actions to track
    'tracked_actions' => [
        'create',
        'update',
        'delete',
        'export',
        'view',
    ],

    // Automatic cleanup
    'auto_cleanup' => env('AUDIT_AUTO_CLEANUP', true),
];

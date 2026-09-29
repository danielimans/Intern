

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
    <p class="text-muted">Welcome to Network Infrastructure Management System</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-card-primary">
            <span class="stat-number"><?php echo e($stats['total_lan_ports']); ?></span>
            <span class="stat-label">Total LAN Ports</span>
            <small class="d-block mt-2">
                <i class="fas fa-circle" style="color: #28a745;"></i> <?php echo e($stats['active_lan_ports']); ?> Active
            </small>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-card-success">
            <span class="stat-number"><?php echo e($stats['total_voice_ports']); ?></span>
            <span class="stat-label">Total Voice Ports</span>
            <small class="d-block mt-2">
                <i class="fas fa-circle" style="color: #28a745;"></i> <?php echo e($stats['active_voice_ports']); ?> Active
            </small>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-card-info">
            <span class="stat-number"><?php echo e($stats['total_server_racks']); ?></span>
            <span class="stat-label">Server Racks</span>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card stat-card-warning">
            <span class="stat-number">200</span>
            <span class="stat-label">Total Users</span>
        </div>
    </div>
</div>

<div class="row">
    <!-- Port Status Breakdown -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-pie"></i> Port Status Breakdown
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-secondary mb-3">LAN Ports</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge badge-success">Active</span></span>
                        <strong><?php echo e($portStatusBreakdown['lan']['active']); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge badge-secondary">Inactive</span></span>
                        <strong><?php echo e($portStatusBreakdown['lan']['inactive']); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span><span class="badge badge-warning">Maintenance</span></span>
                        <strong><?php echo e($portStatusBreakdown['lan']['maintenance']); ?></strong>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-secondary mb-3">Voice Ports</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge badge-success">Active</span></span>
                        <strong><?php echo e($portStatusBreakdown['voice']['active']); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><span class="badge badge-secondary">Inactive</span></span>
                        <strong><?php echo e($portStatusBreakdown['voice']['inactive']); ?></strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><span class="badge badge-warning">Maintenance</span></span>
                        <strong><?php echo e($portStatusBreakdown['voice']['maintenance']); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LAN Ports by Floor -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-building"></i> LAN Ports by Floor
            </div>
            <div class="card-body">
                <?php $__empty_1 = true; $__currentLoopData = $lanPortsByFloor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $floor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e0e0e0;">
                        <span class="fw-500"><?php echo e($floor->floor_user); ?></span>
                        <span class="badge bg-primary"><?php echo e($floor->count); ?> Ports</span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted">No LAN ports available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-history"></i> Recent Activity (Last 7 Days)
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Type</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <small class="text-muted"><?php echo e($activity->created_at->format('M d, Y H:i')); ?></small>
                            </td>
                            <td><?php echo e($activity->user?->full_name ?? 'System'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($activity->getActionColor()); ?>">
                                    <?php echo e($activity->getActionLabel()); ?>

                                </span>
                            </td>
                            <td>
                                <small class="text-secondary"><?php echo e($activity->model_type); ?></small>
                            </td>
                            <td>
                                <?php if($activity->model_id): ?>
                                    <small class="text-muted">ID: <?php echo e($activity->model_id); ?></small>
                                <?php else: ?>
                                    <small class="text-muted">-</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No recent activity
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <a href="/audit-logs" class="btn btn-sm btn-outline-primary" style="display: <?php echo e(auth()->user()->isAdmin() ? 'inline-block' : 'none'); ?>;">
            <i class="fas fa-list"></i> View Full Audit Logs
        </a>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\danie\.gemini\antigravity-ide\scratch\documentation\Intern\resources\views/dashboard.blade.php ENDPATH**/ ?>
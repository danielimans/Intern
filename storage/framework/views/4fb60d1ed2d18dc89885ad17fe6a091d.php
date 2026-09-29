

<?php $__env->startSection('title', 'Audit Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-list"></i> Audit Logs</h1>
        <p class="text-muted">View all system changes and activities (30-day retention)</p>
    </div>
    <div>
        <a href="<?php echo e(route('audit-logs.export')); ?>" class="btn btn-outline-primary">
            <i class="fas fa-download"></i> Export CSV
        </a>
        <a href="<?php echo e(route('audit-logs.statistics')); ?>" class="btn btn-outline-primary">
            <i class="fas fa-chart-bar"></i> Statistics
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('audit-logs.index')); ?>" class="row g-3">
            <div class="col-md-2">
                <label for="action" class="form-label">Action</label>
                <select name="action" id="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="create" <?php echo e(request('action') == 'create' ? 'selected' : ''); ?>>Create</option>
                    <option value="update" <?php echo e(request('action') == 'update' ? 'selected' : ''); ?>>Update</option>
                    <option value="delete" <?php echo e(request('action') == 'delete' ? 'selected' : ''); ?>>Delete</option>
                    <option value="export" <?php echo e(request('action') == 'export' ? 'selected' : ''); ?>>Export</option>
                    <option value="view" <?php echo e(request('action') == 'view' ? 'selected' : ''); ?>>View</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="model_type" class="form-label">Type</label>
                <select name="model_type" id="model_type" class="form-select">
                    <option value="">All Types</option>
                    <option value="LanPort" <?php echo e(request('model_type') == 'LanPort' ? 'selected' : ''); ?>>LAN Port</option>
                    <option value="VoicePort" <?php echo e(request('model_type') == 'VoicePort' ? 'selected' : ''); ?>>Voice Port</option>
                    <option value="ServerRack" <?php echo e(request('model_type') == 'ServerRack' ? 'selected' : ''); ?>>Server Rack</option>
                    <option value="RackUnit" <?php echo e(request('model_type') == 'RackUnit' ? 'selected' : ''); ?>>Rack Unit</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Audit Logs Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Type</th>
                        <th>Model ID</th>
                        <th>IP Address</th>
                        <th>Description</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <small class="text-muted"><?php echo e($log->created_at->format('M d, Y H:i:s')); ?></small>
                            </td>
                            <td>
                                <strong><?php echo e($log->user?->full_name ?? 'System'); ?></strong>
                                <br>
                                <small class="text-muted">{{ $log->user?->username }}</small>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($log->getActionColor()); ?>">
                                    <?php echo e($log->getActionLabel()); ?>

                                </span>
                            </td>
                            <td>
                                <small class="text-secondary"><?php echo e($log->model_type); ?></small>
                            </td>
                            <td>
                                <?php echo e($log->model_id ?? 'N/A'); ?>

                            </td>
                            <td>
                                <small class="text-muted" title="<?php echo e($log->user_agent); ?>"><?php echo e($log->ip_address); ?></small>
                            </td>
                            <td>
                                <?php echo e($log->description ?? '-'); ?>

                            </td>
                            <td>
                                <?php if($log->old_values || $log->new_values): ?>
                                    <a href="<?php echo e(route('audit-logs.show', $log)); ?>" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No audit logs found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
        <div><?php echo e($auditLogs->count()); ?> of <?php echo e($auditLogs->total()); ?> logs</div>
        <div>
            <?php echo e($auditLogs->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
</div>

<!-- Retention Notice -->
<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle"></i> 
    <strong>Note:</strong> Audit logs are automatically retained for 30 days and then purged. 
    For compliance archival, export logs regularly.
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\danie\.gemini\antigravity-ide\scratch\documentation\Intern\resources\views/audit-logs/index.blade.php ENDPATH**/ ?>
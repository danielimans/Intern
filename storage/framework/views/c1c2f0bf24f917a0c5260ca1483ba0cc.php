<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Voice Ports</h2>
    <a href="<?php echo e(route('voice-ports.create')); ?>" class="btn btn-primary">+ Add Voice Port</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('voice-ports.index')); ?>" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search port, PR, PEN..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    <option value="maintenance" <?php echo e(request('status') == 'maintenance' ? 'selected' : ''); ?>>Maintenance</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Wall Port</th>
                        <th>User / Department</th>
                        <th>Extension</th>
                        <th>PR Number</th>
                        <th>PEN Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $voicePorts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $port): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($port->wall_port_label); ?></strong></td>
                        <td><?php echo e($port->user ? $port->user->full_name : $port->floor_user); ?></td>
                        <td><?php echo e($port->extension_number); ?></td>
                        <td><code><?php echo e($port->pr_number); ?></code></td>
                        <td><code><?php echo e($port->pen_number); ?></code></td>
                        <td>
                            <span class="badge bg-<?php echo e($port->port_status === 'active' ? 'success' : ($port->port_status === 'maintenance' ? 'warning' : 'secondary')); ?>">
                                <?php echo e(ucfirst($port->port_status)); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('voice-ports.show', $port->id)); ?>" class="btn btn-sm btn-outline-info">View</a>
                            <a href="<?php echo e(route('voice-ports.edit', $port->id)); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">No voice ports found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($voicePorts->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($voicePorts->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\danie\.gemini\antigravity-ide\scratch\documentation\Intern\resources\views/voice-ports/index.blade.php ENDPATH**/ ?>
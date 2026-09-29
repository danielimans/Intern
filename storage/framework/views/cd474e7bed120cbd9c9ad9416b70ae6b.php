<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit LAN Port: <?php echo e($lanPort->wall_port_label); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('lan-ports.update', $lanPort->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="mb-3">
                        <label class="form-label">Wall Port Label *</label>
                        <input type="text" name="wall_port_label" class="form-control" required value="<?php echo e(old('wall_port_label', $lanPort->wall_port_label)); ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Switch Port *</label>
                            <input type="text" name="switch_port" class="form-control" required value="<?php echo e(old('switch_port', $lanPort->switch_port)); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Extension Number</label>
                            <input type="text" name="extension_number" class="form-control" value="<?php echo e(old('extension_number', $lanPort->extension_number)); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $lanPort->email)); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Floor / User Details *</label>
                        <input type="text" name="floor_user" class="form-control" required value="<?php echo e(old('floor_user', $lanPort->floor_user)); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Port Status *</label>
                        <select name="port_status" class="form-select" required>
                            <option value="active" <?php echo e($lanPort->port_status == 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="inactive" <?php echo e($lanPort->port_status == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                            <option value="maintenance" <?php echo e($lanPort->port_status == 'maintenance' ? 'selected' : ''); ?>>Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes', $lanPort->notes)); ?></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('lan-ports.index')); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update LAN Port</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\danie\.gemini\antigravity-ide\scratch\documentation\Intern\resources\views/lan-ports/edit.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Add New Server Rack</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('server-racks.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Rack Name *</label>
                        <input type="text" name="rack_name" class="form-control" required value="<?php echo e(old('rack_name')); ?>" placeholder="e.g. Rack A1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location *</label>
                        <input type="text" name="location" class="form-control" required value="<?php echo e(old('location')); ?>" placeholder="e.g. Server Room 2B">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Units (U) *</label>
                            <input type="number" name="total_units" class="form-control" required value="<?php echo e(old('total_units', 42)); ?>" min="1" max="52">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Rack Type *</label>
                            <select name="rack_type" class="form-select" required>
                                <option value="standard">Standard (Enclosed)</option>
                                <option value="wall-mount">Wall Mount</option>
                                <option value="open-frame">Open Frame</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo e(old('description')); ?></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('server-racks.index')); ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Rack</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\danie\.gemini\antigravity-ide\scratch\documentation\Intern\resources\views/server-racks/create.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Server Racks</h2>
    <a href="<?php echo e(route('server-racks.create')); ?>" class="btn btn-primary">+ Add Server Rack</a>
</div>

<div class="row">
    <?php $__empty_1 = true; $__currentLoopData = $serverRacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rack): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title"><?php echo e($rack->rack_name); ?></h5>
                <h6 class="card-subtitle mb-2 text-muted"><?php echo e($rack->location); ?></h6>
                <p class="card-text">
                    <strong>Total Units:</strong> <?php echo e($rack->total_units); ?>U<br>
                    <strong>Type:</strong> <?php echo e(ucfirst($rack->rack_type)); ?><br>
                    <strong>Description:</strong> <?php echo e($rack->description ?? 'N/A'); ?>

                </p>
                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('server-racks.visualize', $rack->id)); ?>" class="btn btn-sm btn-info text-white">3D View</a>
                    <a href="<?php echo e(route('server-racks.show', $rack->id)); ?>" class="btn btn-sm btn-secondary">Details</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="alert alert-info">No server racks found. <a href="<?php echo e(route('server-racks.create')); ?>">Create one now</a>.</div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\danie\.gemini\antigravity-ide\scratch\documentation\Intern\resources\views/server-racks/index.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Server Rack: <?php echo e($serverRack->rack_name); ?></h5>
                <div>
                    <a href="<?php echo e(route('server-racks.visualize', $serverRack->id)); ?>" class="btn btn-sm btn-info text-white me-1">3D View</a>
                    <a href="<?php echo e(route('server-racks.edit', $serverRack->id)); ?>" class="btn btn-sm btn-light">Edit</a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Rack Name</th><td><?php echo e($serverRack->rack_name); ?></td></tr>
                    <tr><th>Location</th><td><?php echo e($serverRack->location); ?></td></tr>
                    <tr><th>Total Units</th><td><?php echo e($serverRack->total_units); ?>U</td></tr>
                    <tr><th>Rack Type</th><td><?php echo e(ucfirst(str_replace('-', ' ', $serverRack->rack_type))); ?></td></tr>
                    <tr><th>Description</th><td><?php echo e($serverRack->description ?? 'N/A'); ?></td></tr>
                    <tr><th>Created At</th><td><?php echo e($serverRack->created_at); ?></td></tr>
                </table>

                <h6 class="mt-4">Rack Units</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr><th>Unit #</th><th>Equipment</th><th>Type</th><th>Description</th></tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $serverRack->rackUnits->sortBy('unit_number'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong>U<?php echo e($unit->unit_number); ?></strong></td>
                                <td><?php echo e($unit->equipment_name); ?></td>
                                <td><?php echo e(ucfirst($unit->equipment_type)); ?></td>
                                <td><?php echo e($unit->description ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <a href="<?php echo e(route('server-racks.index')); ?>" class="btn btn-secondary">&laquo; Back to Listing</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\danie\.gemini\antigravity-ide\scratch\documentation\Intern\resources\views/server-racks/show.blade.php ENDPATH**/ ?>
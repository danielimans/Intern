<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Voice Port Details: <?php echo e($voicePort->wall_port_label); ?></h5>
                <a href="<?php echo e(route('voice-ports.edit', $voicePort->id)); ?>" class="btn btn-sm btn-light">Edit</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th>Wall Port Label</th><td><?php echo e($voicePort->wall_port_label); ?></td></tr>
                    <tr><th>Extension Number</th><td><?php echo e($voicePort->extension_number); ?></td></tr>
                    <tr><th>User / Floor</th><td><?php echo e($voicePort->user ? $voicePort->user->full_name : $voicePort->floor_user); ?></td></tr>
                    <tr><th>Email</th><td><?php echo e($voicePort->email ?? 'N/A'); ?></td></tr>
                    <tr><th>PR Number</th><td><code><?php echo e($voicePort->pr_number); ?></code></td></tr>
                    <tr><th>PEN Number</th><td><code><?php echo e($voicePort->pen_number); ?></code></td></tr>
                    <tr><th>Port Status</th><td><span class="badge bg-success"><?php echo e(ucfirst($voicePort->port_status)); ?></span></td></tr>
                    <tr><th>Notes</th><td><?php echo e($voicePort->notes ?? 'None'); ?></td></tr>
                    <tr><th>Created At</th><td><?php echo e($voicePort->created_at); ?></td></tr>
                </table>
                <a href="<?php echo e(route('voice-ports.index')); ?>" class="btn btn-secondary">&laquo; Back to Listing</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\danie\.gemini\antigravity-ide\scratch\documentation\Intern\resources\views/voice-ports/show.blade.php ENDPATH**/ ?>
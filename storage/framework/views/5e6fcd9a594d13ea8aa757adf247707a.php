<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?> - Network Infrastructure Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .sidebar {
            background-color: #343a40;
            min-height: calc(100vh - 56px);
            padding: 0;
        }

        .sidebar .nav-link {
            color: #e0e0e0;
            padding: 1rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
            border-left-color: #667eea;
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(102, 126, 234, 0.5);
            border-left-color: #667eea;
        }

        .main-content {
            padding: 2rem;
        }

        .card {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-active {
            background-color: #28a745;
            color: white;
        }

        .badge-inactive {
            background-color: #6c757d;
            color: white;
        }

        .badge-maintenance {
            background-color: #ffc107;
            color: #333;
        }

        .stat-card {
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            color: white;
            margin-bottom: 1rem;
        }

        .stat-card-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-card-success {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .stat-card-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .stat-card-warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            display: block;
        }

        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a408a 100%);
            color: white;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0 0 1rem 0;
        }

        .dropdown-menu {
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .page-header h1 {
            color: #333;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            
            .main-content {
                padding: 1rem;
            }
        }
    </style>
    <?php echo $__env->yieldContent('extra-css'); ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/dashboard">
                <i class="fas fa-network-wired"></i> Network Infrastructure
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo e(auth()->user()->full_name); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="fas fa-key"></i> Change Password
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button class="dropdown-item" type="submit">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row" style="min-height: calc(100vh - 56px);">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar">
                <div class="nav flex-column">
                    <a class="nav-link <?php echo e(request()->is('dashboard') ? 'active' : ''); ?>" href="/dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    
                    <a class="nav-link <?php echo e(request()->is('lan-ports*') ? 'active' : ''); ?>" href="/lan-ports">
                        <i class="fas fa-ethernet"></i> LAN Ports
                    </a>

                    <a class="nav-link <?php echo e(request()->is('voice-ports*') ? 'active' : ''); ?>" href="/voice-ports">
                        <i class="fas fa-phone"></i> Voice Ports
                    </a>

                    <a class="nav-link <?php echo e(request()->is('server-racks*') ? 'active' : ''); ?>" href="/server-racks">
                        <i class="fas fa-server"></i> Server Racks
                    </a>

                    <?php if(auth()->user()->isAdmin()): ?>
                        <hr class="my-2">
                        <h6 class="px-3 py-2 text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fas fa-lock"></i> Admin
                        </h6>
                        <a class="nav-link <?php echo e(request()->is('audit-logs*') ? 'active' : ''); ?>" href="/audit-logs">
                            <i class="fas fa-list"></i> Audit Logs
                        </a>
                    <?php endif; ?>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 col-lg-10 main-content">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-circle"></i> Errors:</strong>
                        <ul class="mb-0 mt-2">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?php echo e(route('password.change')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <?php echo $__env->yieldContent('extra-js'); ?>
</body>
</html>
<?php /**PATH C:\Users\danie\.gemini\antigravity-ide\scratch\documentation\Intern\resources\views/layouts/app.blade.php ENDPATH**/ ?>
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanPortController;
use App\Http\Controllers\VoicePortController;
use App\Http\Controllers\ServerRackController;
use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');

    // Password change
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change');

    // LAN Ports
    Route::prefix('/lan-ports')->name('lan-ports.')->group(function () {
        Route::get('/', [LanPortController::class, 'index'])->name('index');
        Route::get('/create', [LanPortController::class, 'create'])->name('create');
        Route::post('/', [LanPortController::class, 'store'])->name('store');
        Route::get('/{lanPort}', [LanPortController::class, 'show'])->name('show');
        Route::get('/{lanPort}/edit', [LanPortController::class, 'edit'])->name('edit');
        Route::put('/{lanPort}', [LanPortController::class, 'update'])->name('update');
        Route::delete('/{lanPort}', [LanPortController::class, 'destroy'])->name('destroy');
        Route::get('/export/csv', [LanPortController::class, 'export'])->name('export');
        Route::post('/bulk/status', [LanPortController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
    });

    // Voice Ports
    Route::prefix('/voice-ports')->name('voice-ports.')->group(function () {
        Route::get('/', [VoicePortController::class, 'index'])->name('index');
        Route::get('/create', [VoicePortController::class, 'create'])->name('create');
        Route::post('/', [VoicePortController::class, 'store'])->name('store');
        Route::get('/{voicePort}', [VoicePortController::class, 'show'])->name('show');
        Route::get('/{voicePort}/edit', [VoicePortController::class, 'edit'])->name('edit');
        Route::put('/{voicePort}', [VoicePortController::class, 'update'])->name('update');
        Route::delete('/{voicePort}', [VoicePortController::class, 'destroy'])->name('destroy');
        Route::get('/export/csv', [VoicePortController::class, 'export'])->name('export');
        Route::post('/bulk/status', [VoicePortController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
    });

    // Server Racks
    Route::prefix('/server-racks')->name('server-racks.')->group(function () {
        Route::get('/', [ServerRackController::class, 'index'])->name('index');
        Route::get('/create', [ServerRackController::class, 'create'])->name('create');
        Route::post('/', [ServerRackController::class, 'store'])->name('store');
        Route::get('/{serverRack}', [ServerRackController::class, 'show'])->name('show');
        Route::get('/{serverRack}/edit', [ServerRackController::class, 'edit'])->name('edit');
        Route::get('/{serverRack}/visualize', [ServerRackController::class, 'visualize'])->name('visualize');
        Route::get('/{serverRack}/api/visualization', [ServerRackController::class, 'getVisualization'])->name('api.visualization');
        Route::put('/{serverRack}', [ServerRackController::class, 'update'])->name('update');
        Route::delete('/{serverRack}', [ServerRackController::class, 'destroy'])->name('destroy');
        Route::post('/{serverRack}/equipment', [ServerRackController::class, 'addEquipment'])->name('add-equipment');
        Route::put('/unit/{rackUnit}', [ServerRackController::class, 'updateUnit'])->name('update-unit');
        Route::delete('/unit/{rackUnit}', [ServerRackController::class, 'removeEquipment'])->name('remove-equipment');
    });

    // Audit Logs (IT Staff / Admin only)
    Route::middleware('is_admin')->prefix('/audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', [AuditLogController::class, 'index'])->name('index');
        Route::get('/{auditLog}', [AuditLogController::class, 'show'])->name('show');
        Route::get('/export/csv', [AuditLogController::class, 'export'])->name('export');
        Route::get('/statistics/view', [AuditLogController::class, 'statistics'])->name('statistics');
        Route::post('/purge', [AuditLogController::class, 'purge'])->name('purge');
    });
});

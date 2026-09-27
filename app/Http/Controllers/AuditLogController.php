<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Show audit logs (IT staff only).
     */
    public function index(Request $request)
    {
        // Only IT staff (admins) can view audit logs
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $query = AuditLog::with('user');

        // Filter by action
        if ($request->has('action') && $request->action !== '') {
            $query->byAction($request->action);
        }

        // Filter by model type
        if ($request->has('model_type') && $request->model_type !== '') {
            $query->byModel($request->model_type);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id !== '') {
            $query->byUser($request->user_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $from = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_from)->startOfDay();
            $query->where('created_at', '>=', $from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $to = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_to)->endOfDay();
            $query->where('created_at', '<=', $to);
        }

        $auditLogs = $query->latest()->paginate(25);

        return view('audit-logs.index', compact('auditLogs'));
    }

    /**
     * Show a specific audit log entry.
     */
    public function show(AuditLog $auditLog)
    {
        // Only IT staff can view audit logs
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        return view('audit-logs.show', compact('auditLog'));
    }

    /**
     * Export audit logs to CSV.
     */
    public function export(Request $request)
    {
        // Only IT staff can export audit logs
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $query = AuditLog::with('user');

        // Apply same filters as index
        if ($request->has('action') && $request->action !== '') {
            $query->byAction($request->action);
        }

        if ($request->has('model_type') && $request->model_type !== '') {
            $query->byModel($request->model_type);
        }

        if ($request->has('user_id') && $request->user_id !== '') {
            $query->byUser($request->user_id);
        }

        if ($request->has('date_from') && $request->date_from !== '') {
            $from = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_from)->startOfDay();
            $query->where('created_at', '>=', $from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $to = \Carbon\Carbon::createFromFormat('Y-m-d', $request->date_to)->endOfDay();
            $query->where('created_at', '<=', $to);
        }

        $auditLogs = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=audit_logs_' . now()->format('Y-m-d_His') . '.csv',
        ];

        $callback = function () use ($auditLogs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'User', 'Action', 'Model Type', 'Model ID', 'IP Address', 'User Agent']);

            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user?->full_name ?? 'N/A',
                    $log->getActionLabel(),
                    $log->model_type,
                    $log->model_id,
                    $log->ip_address,
                    $log->user_agent,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get statistics for a specific period.
     */
    public function statistics(Request $request)
    {
        // Only IT staff can view statistics
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $days = $request->get('days', 30);

        $stats = [
            'total_actions' => AuditLog::recent($days)->count(),
            'by_action' => AuditLog::recent($days)
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action'),
            'by_model' => AuditLog::recent($days)
                ->selectRaw('model_type, COUNT(*) as count')
                ->groupBy('model_type')
                ->pluck('count', 'model_type'),
            'by_user' => AuditLog::recent($days)
                ->with('user')
                ->selectRaw('user_id, COUNT(*) as count')
                ->groupBy('user_id')
                ->get()
                ->map(function ($log) {
                    return [
                        'user' => $log->user?->full_name ?? 'Unknown',
                        'count' => $log->count,
                    ];
                }),
        ];

        return view('audit-logs.statistics', compact('stats', 'days'));
    }

    /**
     * Purge old audit logs (older than retention period).
     */
    public function purge()
    {
        // Only IT staff can purge logs
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $retentionDays = config('audit.retention_days', 30);
        $cutoffDate = now()->subDays($retentionDays);

        $deleted = AuditLog::where('created_at', '<', $cutoffDate)->delete();

        return back()->with('success', "Purged $deleted audit log entries");
    }
}

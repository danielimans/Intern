<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\LanPort;
use App\Models\VoicePort;
use App\Models\ServerRack;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     */
    public function index()
    {
        $stats = [
            'total_lan_ports' => LanPort::count(),
            'active_lan_ports' => LanPort::byStatus('active')->count(),
            'total_voice_ports' => VoicePort::count(),
            'active_voice_ports' => VoicePort::byStatus('active')->count(),
            'total_server_racks' => ServerRack::count(),
        ];

        $recentActivity = AuditLog::recent(7)->latest()->limit(10)->get();
        
        $lanPortsByFloor = LanPort::selectRaw('floor_user, COUNT(*) as count')
            ->groupBy('floor_user')
            ->get();

        $portStatusBreakdown = [
            'lan' => [
                'active' => LanPort::byStatus('active')->count(),
                'inactive' => LanPort::byStatus('inactive')->count(),
                'maintenance' => LanPort::byStatus('maintenance')->count(),
            ],
            'voice' => [
                'active' => VoicePort::byStatus('active')->count(),
                'inactive' => VoicePort::byStatus('inactive')->count(),
                'maintenance' => VoicePort::byStatus('maintenance')->count(),
            ],
        ];

        return view('dashboard', compact('stats', 'recentActivity', 'lanPortsByFloor', 'portStatusBreakdown'));
    }

    /**
     * Get dashboard data for API.
     */
    public function getStats()
    {
        return response()->json([
            'lan_ports' => [
                'total' => LanPort::count(),
                'active' => LanPort::byStatus('active')->count(),
                'inactive' => LanPort::byStatus('inactive')->count(),
                'maintenance' => LanPort::byStatus('maintenance')->count(),
            ],
            'voice_ports' => [
                'total' => VoicePort::count(),
                'active' => VoicePort::byStatus('active')->count(),
                'inactive' => VoicePort::byStatus('inactive')->count(),
                'maintenance' => VoicePort::byStatus('maintenance')->count(),
            ],
            'server_racks' => ServerRack::count(),
        ]);
    }
}

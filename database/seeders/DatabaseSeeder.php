<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LanPort;
use App\Models\VoicePort;
use App\Models\ServerRack;
use App\Models\RackUnit;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@company.com',
                'password' => Hash::make('password'),
                'full_name' => 'System Administrator',
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Tech User
        $tech = User::firstOrCreate(
            ['username' => 'tech1'],
            [
                'email' => 'tech1@company.com',
                'password' => Hash::make('password'),
                'full_name' => 'John Technician',
                'role' => 'technician',
                'is_active' => true,
            ]
        );

        // Server Rack
        $rack = ServerRack::firstOrCreate(
            ['rack_name' => 'Rack A1'],
            [
                'location' => 'Data Center - Floor 2',
                'total_units' => 42,
                'rack_type' => 'standard',
                'description' => 'Main Server Rack A1',
            ]
        );

        // Rack Units
        RackUnit::firstOrCreate(
            ['server_rack_id' => $rack->id, 'unit_number' => 1],
            [
                'equipment_name' => 'Cisco Catalyst 9300',
                'equipment_type' => 'switch',
                'description' => 'Core LAN Switch',
            ]
        );

        RackUnit::firstOrCreate(
            ['server_rack_id' => $rack->id, 'unit_number' => 2],
            [
                'equipment_name' => 'Dell PowerEdge R750',
                'equipment_type' => 'server',
                'description' => 'Primary Web Server',
            ]
        );

        // LAN Ports
        LanPort::firstOrCreate(
            ['wall_port_label' => 'LAN-F1-001'],
            [
                'user_id' => $admin->id,
                'extension_number' => '1001',
                'email' => 'admin@company.com',
                'switch_port' => 'Gi1/0/1',
                'floor_user' => 'Floor 1 - Office 101',
                'port_status' => 'active',
                'notes' => 'Primary connection',
            ]
        );

        LanPort::firstOrCreate(
            ['wall_port_label' => 'LAN-F1-002'],
            [
                'user_id' => $tech->id,
                'extension_number' => '1002',
                'email' => 'tech1@company.com',
                'switch_port' => 'Gi1/0/2',
                'floor_user' => 'Floor 1 - Tech Lab',
                'port_status' => 'active',
                'notes' => 'Tech workstation port',
            ]
        );

        // Voice Ports
        VoicePort::firstOrCreate(
            ['wall_port_label' => 'VOICE-F1-101'],
            [
                'user_id' => $admin->id,
                'extension_number' => '4001',
                'email' => 'admin@company.com',
                'pr_number' => 'PR-2024-001',
                'pen_number' => 'PEN-1001',
                'floor_user' => 'Floor 1 - Office 101',
                'port_status' => 'active',
                'notes' => 'Executive desk phone',
            ]
        );
    }
}

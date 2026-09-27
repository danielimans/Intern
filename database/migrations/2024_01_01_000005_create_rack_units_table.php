<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rack_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('server_rack_id');
            $table->integer('unit_number'); // Position in rack (1-42)
            $table->integer('unit_height')->default(1); // Height in U (1U, 2U, 4U, etc.)
            $table->string('equipment_name');
            $table->enum('equipment_type', [
                'server',
                'switch',
                'router',
                'firewall',
                'pdu',
                'patch_panel',
                'console_server',
                'cable_manager',
                'blank'
            ])->default('blank');
            $table->text('description')->nullable();
            $table->json('port_connections')->nullable(); // Array of connected ports
            $table->json('specifications')->nullable(); // Equipment specs
            $table->boolean('is_powered')->default(true);
            $table->string('power_consumption')->nullable(); // watts
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('server_rack_id')->references('id')->on('server_racks')->cascadeOnDelete();
            
            // Indexes
            $table->index('server_rack_id');
            $table->index('equipment_type');
            $table->unique(['server_rack_id', 'unit_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rack_units');
    }
};

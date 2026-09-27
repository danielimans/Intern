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
        Schema::create('server_racks', function (Blueprint $table) {
            $table->id();
            $table->string('rack_name');
            $table->string('location');
            $table->integer('total_units')->default(42); // Standard server rack size
            $table->enum('rack_type', ['standard', 'wall-mount', 'open-frame'])->default('standard');
            $table->text('description')->nullable();
            $table->json('rack_position')->nullable(); // For 3D positioning
            $table->timestamps();
            
            // Indexes
            $table->index('rack_name');
            $table->index('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_racks');
    }
};

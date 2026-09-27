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
        Schema::create('lan_ports', function (Blueprint $table) {
            $table->id();
            $table->string('wall_port_label')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('extension_number')->nullable();
            $table->string('email')->nullable();
            $table->string('switch_port');
            $table->string('floor_user');
            $table->enum('port_status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            
            // Indexes
            $table->index('wall_port_label');
            $table->index('user_id');
            $table->index('port_status');
            $table->index('floor_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lan_ports');
    }
};

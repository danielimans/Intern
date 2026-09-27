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
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('assisted_port_id')->nullable();
            $table->enum('assisted_port_type', ['lan', 'voice'])->nullable();
            $table->string('support_ticket_id')->nullable();
            $table->text('assistance_notes')->nullable();
            $table->timestamp('assistance_date')->nullable();
            $table->string('issue_resolved_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            
            // Indexes
            $table->index('user_id');
            $table->index('support_ticket_id');
            $table->index('assistance_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};

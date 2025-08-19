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
        Schema::create('request_progress', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('request_id')->constrained('user_requests')->onDelete('cascade');
            $table->string('stage'); // pickup_confirmed, in_transit, delivered, etc.
            $table->string('status'); // pending, completed, delayed
            $table->text('notes')->nullable();
            $table->timestamp('estimated_completion')->nullable();
            $table->json('location_data')->nullable(); // GPS coordinates, city, etc.
            $table->timestamps();
            
            // Index for efficient queries
            $table->index(['request_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_progress');
    }
};

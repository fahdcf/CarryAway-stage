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
        Schema::create('user_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('initiator_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('initiator_role', ['sender', 'traveler']);
            $table->foreignUuid('sender_announcement_id')->nullable()->constrained('sender_announcements')->onDelete('cascade');
            $table->foreignUuid('traveler_announcement_id')->nullable()->constrained('traveler_announcements')->onDelete('cascade');
            $table->foreignUuid('announcement_owner_id')->constrained('users')->onDelete('cascade');
            $table->decimal('proposed_price', 8, 2)->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->string('pickup_location')->nullable();
            $table->string('delivery_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_requests');
    }
};

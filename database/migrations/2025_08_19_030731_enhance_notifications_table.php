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
        Schema::table('notifications', function (Blueprint $table) {
            // Add notification type
            $table->string('type', 50)->default('general')->after('user_id');
            
            // Add related request ID for request-related notifications
            $table->foreignUuid('request_id')->nullable()->after('type')->constrained('user_requests')->onDelete('cascade');
            
            // Add data column for additional information (JSON)
            $table->json('data')->nullable()->after('request_id');
            
            // Add action URL for clickable notifications
            $table->string('action_url')->nullable()->after('data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['request_id']);
            $table->dropColumn(['type', 'request_id', 'data', 'action_url']);
        });
    }
};

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
        Schema::table('user_requests', function (Blueprint $table) {
            // Meeting verification code (both users get the same code)
            $table->string('meeting_code', 8)->nullable()->after('delivery_location');
            
            // Delivery verification code (only sender gets this)
            $table->string('delivery_code', 8)->nullable()->after('meeting_code');
            
            // When the meeting code was verified
            $table->timestamp('meeting_verified_at')->nullable()->after('delivery_code');
            
            // When the delivery code was verified
            $table->timestamp('delivery_verified_at')->nullable()->after('meeting_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_requests', function (Blueprint $table) {
            $table->dropColumn(['meeting_code', 'delivery_code', 'meeting_verified_at', 'delivery_verified_at']);
        });
    }
};

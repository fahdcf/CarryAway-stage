<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_requests', function (Blueprint $table) {
            $table->foreignUuid('announcement_owner_id')->after('traveler_announcement_id')->nullable()->constrained('users')->onDelete('cascade');
        });

        // Populate the announcement_owner_id field for existing records
        DB::statement("
            UPDATE user_requests 
            SET announcement_owner_id = (
                CASE 
                    WHEN traveler_announcement_id IS NOT NULL THEN (
                        SELECT user_id FROM traveler_announcements WHERE id = user_requests.traveler_announcement_id
                    )
                    WHEN sender_announcement_id IS NOT NULL THEN (
                        SELECT user_id FROM sender_announcements WHERE id = user_requests.sender_announcement_id
                    )
                END
            )
        ");

        // Make the field required after populating
        Schema::table('user_requests', function (Blueprint $table) {
            $table->foreignUuid('announcement_owner_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_requests', function (Blueprint $table) {
            $table->dropForeign(['announcement_owner_id']);
            $table->dropColumn('announcement_owner_id');
        });
    }
};

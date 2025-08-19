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
        Schema::table('traveler_announcement_details', function (Blueprint $table) {
            $table->string('departure_city')->nullable()->after('request_id');
            $table->string('arrival_city')->nullable()->after('departure_city');
            $table->date('departure_date')->nullable()->after('arrival_city');
            $table->string('transport_type')->nullable()->after('departure_time');
            $table->text('description')->nullable()->after('transport_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('traveler_announcement_details', function (Blueprint $table) {
            $table->dropColumn(['departure_city', 'arrival_city', 'departure_date', 'transport_type', 'description']);
        });
    }
};

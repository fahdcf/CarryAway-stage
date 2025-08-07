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
        Schema::table('user_details', function (Blueprint $table) {
            $table->string('id_document_front')->nullable()->after('profile_picture_url');
            $table->string('id_document_back')->nullable()->after('id_document_front');
            $table->enum('id_verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('id_document_back');
            $table->text('id_verification_notes')->nullable()->after('id_verification_status');
            $table->timestamp('id_verified_at')->nullable()->after('id_verification_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn(['id_document_front', 'id_document_back', 'id_verification_status', 'id_verification_notes', 'id_verified_at']);
        });
    }
};

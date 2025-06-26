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
        Schema::table('rental_resale', function (Blueprint $table) {
            $table->string('mobile_agent_photo')->nullable();
            $table->string('mobile_agent_photo_alt')->nullable();
            $table->string('mobile_qr_photo')->nullable();
            $table->string('mobile_qr_photo_alt')->nullable();
            $table->json('mobile_gallery_images')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_resale', function (Blueprint $table) {
            $table->dropColumn('mobile_agent_photo');
            $table->dropColumn('mobile_agent_photo_alt');
            $table->dropColumn('mobile_qr_photo');
            $table->dropColumn('mobile_qr_photo_alt');
            $table->dropColumn('mobile_gallery_images');
        });
    }
};

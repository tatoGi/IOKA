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
        Schema::table('developers', function (Blueprint $table) {
            $table->string('mobile_photo')->nullable();
            $table->string('mobile_photo_alt')->nullable();
            $table->string('mobile_logo')->nullable();
            $table->string('mobile_logo_alt')->nullable();
            $table->string('mobile_banner_image')->nullable();
            $table->string('mobile_banner_image_alt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn('mobile_photo');
            $table->dropColumn('mobile_photo_alt');
            $table->dropColumn('mobile_logo');
            $table->dropColumn('mobile_logo_alt');
            $table->dropColumn('mobile_banner_image');
            $table->dropColumn('mobile_banner_image_alt');
        });
    }
};

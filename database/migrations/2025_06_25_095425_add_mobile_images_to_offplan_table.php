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
        Schema::table('offplans', function (Blueprint $table) {
            $table->string('mobile_main_photo')->nullable();
            $table->string('mobile_main_photo_alt')->nullable();
            $table->string('mobile_banner_photo')->nullable();
            $table->string('mobile_banner_photo_alt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offplans', function (Blueprint $table) {
            $table->dropColumn('mobile_main_photo');
            $table->dropColumn('mobile_main_photo_alt');
            $table->dropColumn('mobile_banner_photo');
            $table->dropColumn('mobile_banner_photo_alt');
        });
    }
};

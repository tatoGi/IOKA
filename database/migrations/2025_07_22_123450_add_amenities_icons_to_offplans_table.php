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
            $table->json('amenities_icons')->nullable()->after('amenities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offplans', function (Blueprint $table) {
            $table->dropColumn('amenities_icons');
        });
    }
};

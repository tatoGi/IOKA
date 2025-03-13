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
            $table->string('banner_photo')->nullable()->after('main_photo'); // Add the new column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offplans', function (Blueprint $table) {
            $table->dropColumn('banner_photo'); // Rollback: remove the column
        });
    }
};

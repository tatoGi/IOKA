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
            $table->dropColumn('image_alts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->json('image_alts')->nullable()->after('photo');
        });
    }
};

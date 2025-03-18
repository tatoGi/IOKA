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
            if (!Schema::hasColumn('rental_resale', 'languages')) {
                $table->json('languages')->nullable()->after('agent_photo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_resale', function (Blueprint $table) {
            if (Schema::hasColumn('rental_resale', 'languages')) {
                $table->dropColumn('languages');
            }
        });
    }
};

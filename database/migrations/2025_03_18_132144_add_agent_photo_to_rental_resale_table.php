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
            if (!Schema::hasColumn('rental_resale', 'agent_photo')) {
                $table->string('agent_photo')->nullable()->after('agent_whatsapp');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rental_resale', function (Blueprint $table) {
            if (Schema::hasColumn('rental_resale', 'agent_photo')) {
                $table->dropColumn('agent_photo');
            }
        });
    }
};

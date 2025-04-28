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
            $table->string('agent_email')->nullable()->after('agent_linkedin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offplans', function (Blueprint $table) {
            $table->dropColumn('agent_email');
        });
    }
};

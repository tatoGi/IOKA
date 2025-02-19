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
        Schema::table('sections', function (Blueprint $table) {
            if (!Schema::hasColumn('sections', 'section_key')) {
                $table->string('section_key')->after('id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            if (Schema::hasColumn('sections', 'section_key')) {
                $table->dropColumn('section_key');
            }
        });
    }
};

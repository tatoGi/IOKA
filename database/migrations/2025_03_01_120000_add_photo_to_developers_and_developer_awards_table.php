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
            $table->string('photo')->nullable()->after('whatsapp');
        });

        Schema::table('developer_awards', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('award_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn('photo');
        });

        Schema::table('developer_awards', function (Blueprint $table) {
            $table->dropColumn('award_photo');
        });
    }
};

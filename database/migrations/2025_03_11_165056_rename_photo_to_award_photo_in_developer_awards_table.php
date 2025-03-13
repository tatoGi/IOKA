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
        Schema::table('developer_awards', function (Blueprint $table) {
            $table->renameColumn('photo', 'award_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('developer_awards', function (Blueprint $table) {
            $table->renameColumn('award_photo', 'photo');
        });
    }
};

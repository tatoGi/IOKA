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
            $table->string('amount')->change();
            $table->string('amount_dirhams')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offplans', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
            $table->decimal('amount_dirhams', 12, 2)->nullable()->change();
        });
    }
};

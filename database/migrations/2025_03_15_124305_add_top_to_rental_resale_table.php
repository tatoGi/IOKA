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
        Schema::table('rental_Resale', function (Blueprint $table) {
            $table->boolean('top')->default(false); // Add a boolean column 'top' with default value false
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('rental_Resale', function (Blueprint $table) {
            $table->dropColumn('top'); // Drop the 'top' column if the migration is rolled back
        });
    }
};

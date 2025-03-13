<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->json('tags')->nullable(); // Add a JSON column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn('tags'); // Remove the column if rolling back
        });
    }
};

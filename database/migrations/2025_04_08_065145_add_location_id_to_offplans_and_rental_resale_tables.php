<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationIdToOffplansAndRentalResaleTables extends Migration
{
    public function up()
    {


        Schema::table('rental_resale', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable();

            // Optional: Add foreign key constraint
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    public function down()
    {


        Schema::table('rental_resale', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
        });
    }
}

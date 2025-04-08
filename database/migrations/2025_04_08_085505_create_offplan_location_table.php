<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffplanLocationTable extends Migration
{
    public function up()
    {
        Schema::create('offplan_location', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offplan_id')
                ->constrained()
                ->onDelete('cascade'); // Foreign key for Offplan
            $table->foreignId('location_id')
                ->constrained()
                ->onDelete('cascade'); // Foreign key for Location
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offplan_location');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalResaleLocationTable extends Migration
{
    public function up()
    {
        Schema::create('rental_resale_location', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_resale_id')
                ->constrained('rental_resale') // Foreign key for RentalResale
                ->onDelete('cascade');
            $table->foreignId('location_id')
                ->constrained() // Foreign key for Location
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rental_resale_location');
    }
}

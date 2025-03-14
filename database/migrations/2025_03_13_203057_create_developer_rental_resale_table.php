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
        Schema::create('developer_rental_resale', function (Blueprint $table) {
            $table->unsignedBigInteger('developer_id');
            $table->unsignedBigInteger('rental_resale_id');
            $table->timestamps();

            // Define foreign keys
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
            $table->foreign('rental_resale_id')->references('id')->on('rental_resale')->onDelete('cascade');

            // Define a composite primary key
            $table->primary(['developer_id', 'rental_resale_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('developer_rental_resale');
    }
};

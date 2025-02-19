<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmountsTable extends Migration
{
    public function up()
    {
        Schema::create('amounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rental_resale_id');
            $table->foreign('rental_resale_id')->references('id')->on('rental_resale')->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->decimal('amount_dirhams', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('amounts');
    }
}

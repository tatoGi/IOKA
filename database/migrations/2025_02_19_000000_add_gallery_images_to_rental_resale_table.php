<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGalleryImagesToRentalResaleTable extends Migration
{
    public function up()
    {
        Schema::table('rental_resale', function (Blueprint $table) {
            $table->json('gallery_images')->nullable(); // JSON field to store gallery images
        });
    }

    public function down()
    {
        Schema::table('rental_resale', function (Blueprint $table) {
            $table->dropColumn('gallery_images');
        });
    }
}

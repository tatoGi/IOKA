<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalResaleTable extends Migration
{
    public function up()
    {
        Schema::create('rental_resale', function (Blueprint $table) {
            $table->id();
            $table->string('property_type'); // Selectable list: Villa, Townhouse, Apartment, Land, Full Building, Commercial
            $table->string('title');
            $table->integer('bathroom')->unsigned(); // Integer Only whole positive numbers
            $table->integer('bedroom')->unsigned(); // Integer
            $table->float('sq_ft'); // Float must be able to specify a decimal point, e.g. 102.2
            $table->integer('garage')->unsigned(); // Integer
            $table->text('description'); // Rich Text Editor (should also be able to insert photos)
            $table->json('details'); // Repeater field: title (text) and information (info) (text)
            $table->json('amenities'); // Repeater field: each added field will appear with a ✔ symbol
            $table->string('agent_title');
            $table->string('agent_status');
            $table->string('agent_languages');
            $table->string('agent_call');
            $table->string('agent_whatsapp');
            $table->string('location_link'); // Location field, should appear on the map at the front
            $table->string('qr_photo'); // QR photo upload
            $table->string('reference');
            $table->string('dld_permit_number');
            $table->json('addresses'); // Ability to add addresses to a new list

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rental_resale');
    }
}

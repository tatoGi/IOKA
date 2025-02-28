<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffplansTable extends Migration
{
    public function up()
    {
        Schema::create('offplans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->decimal('amount', 12, 2);
            $table->decimal('amount_dirhams', 12, 2)->nullable();
            $table->text('description');
            $table->json('features')->nullable();
            $table->text('amenities')->nullable();
            $table->string('map_location')->nullable();
            $table->json('near_by')->nullable();
            $table->string('main_photo')->nullable();
            $table->json('exterior_gallery')->nullable();
            $table->json('interior_gallery')->nullable();
            $table->enum('property_type', ['Villa', 'Townhouse', 'Apartment', 'Land', 'Full Building', 'Commercial'])->nullable();
            $table->integer('bathroom')->nullable();
            $table->integer('bedroom')->nullable();
            $table->integer('garage')->nullable();
            $table->integer('sq_ft')->nullable();
            $table->string('qr_title')->nullable();
            $table->string('qr_photo')->nullable();
            $table->text('qr_text')->nullable();
            $table->string('download_brochure')->nullable();
            $table->string('agent_title')->nullable();
            $table->string('agent_status')->nullable();
            $table->string('agent_image')->nullable();
            $table->string('agent_telephone')->nullable();
            $table->string('agent_whatsapp')->nullable();
            $table->string('agent_linkedin')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offplans');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListingsToDevelopersTable extends Migration
{
    public function up()
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->json('rental_listings')->nullable();
            $table->json('offplan_listings')->nullable();
        });
    }

    public function down()
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->dropColumn('rental_listings');
            $table->dropColumn('offplan_listings');
        });
    }
}

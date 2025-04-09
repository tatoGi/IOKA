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
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->string('page_title', 255)
                  ->nullable()
                  ->after('message')
                  ->comment('Stores the page where form was submitted from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropColumn('page_title');
        });
    }
};

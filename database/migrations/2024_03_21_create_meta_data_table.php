<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meta_data', function (Blueprint $table) {
            $table->id();
            $table->morphs('metadatable'); // This creates metadatable_id and metadatable_type columns
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('twitter_card')->nullable();
            $table->string('twitter_title')->nullable();
            $table->string('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->timestamps();

            // Add unique constraint to ensure one metadata record per model
            $table->unique(['metadatable_type', 'metadatable_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('meta_data');
    }
};

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
        Schema::create('policy_pages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['privacy_policy', 'cookie_policy', 'terms_agreement']);
            $table->text('content');
            $table->timestamps();

            // Ensure we only have one of each type
            $table->unique('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('policy_pages');
    }
};

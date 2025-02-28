<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('developers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('paragraph');
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->timestamps();
        });

        Schema::create('developer_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('developer_id')->constrained('developers')->onDelete('cascade');
            $table->string('award_title');
            $table->string('award_year')->nullable();
            $table->text('award_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developer_awards');
        Schema::dropIfExists('developers');
    }
};

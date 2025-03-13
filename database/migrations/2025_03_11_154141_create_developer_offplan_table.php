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
        Schema::create('developer_offplan', function (Blueprint $table) {
            // Primary key (optional for pivot tables, but recommended)
            $table->id();

            // Foreign key to the developers table
            $table->unsignedBigInteger('developer_id');

            // Foreign key to the offplans table
            $table->unsignedBigInteger('offplan_id');

            // Timestamps (optional, but useful for tracking when relationships are created/updated)
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
            $table->foreign('offplan_id')->references('id')->on('offplans')->onDelete('cascade');

            // Optional: Add a unique constraint to prevent duplicate relationships
            $table->unique(['developer_id', 'offplan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developer_offplan');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationIdToOffplansAndRentalResaleTables extends Migration
{
    public function up()
    {


        Schema::table('rental_resale', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable();

            // Optional: Add foreign key constraint
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('rental_resale', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $foreignKeys = \Illuminate\Support\Facades\DB::select(
                "SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND TABLE_NAME = 'rental_resale'
                AND CONSTRAINT_NAME LIKE '%location_id%'"
            );
            
            if (!empty($foreignKeys)) {
                foreach ($foreignKeys as $foreignKey) {
                    $table->dropForeign($foreignKey->CONSTRAINT_NAME);
                }
            }
            
            // Check if column exists before dropping
            if (Schema::hasColumn('rental_resale', 'location_id')) {
                $table->dropColumn('location_id');
            }
        });
    }
}

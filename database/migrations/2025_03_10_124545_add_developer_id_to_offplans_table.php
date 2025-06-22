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
        Schema::table('offplans', function (Blueprint $table) {
            $table->unsignedBigInteger('developer_id')->nullable();
            $table->foreign('developer_id')->references('id')->on('developers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('offplans', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $foreignKeys = \Illuminate\Support\Facades\DB::select(
                "SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND TABLE_NAME = 'offplans'
                AND CONSTRAINT_NAME LIKE '%developer_id%'"
            );
            
            if (!empty($foreignKeys)) {
                foreach ($foreignKeys as $foreignKey) {
                    $table->dropForeign($foreignKey->CONSTRAINT_NAME);
                }
            }
            
            // Check if column exists before dropping
            if (Schema::hasColumn('offplans', 'developer_id')) {
                $table->dropColumn('developer_id');
            }
        });
    }
};

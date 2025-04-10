<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAmountsTableAmountDirhamsColumn extends Migration
{
    public function up()
    {
        Schema::table('amounts', function (Blueprint $table) {
            // Change to decimal with sufficient precision
            $table->decimal('amount_dirhams', 15, 2)->change();

            // Or if you prefer double (floating point)
            // $table->double('amount_dirhams')->change();
        });
    }

    public function down()
    {
        Schema::table('amounts', function (Blueprint $table) {
            // Revert back if needed (specify your original column type)
            $table->decimal('amount_dirhams', 10, 2)->change();
        });
    }
}

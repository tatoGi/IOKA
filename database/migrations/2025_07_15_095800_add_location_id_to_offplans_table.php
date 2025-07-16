<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationIdToOffplansTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('offplans', 'location_id')) {
            Schema::table('offplans', function (Blueprint $table) {
                $table->bigInteger('location_id')->unsigned()->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('offplans', 'location_id')) {
            Schema::table('offplans', function (Blueprint $table) {
                $table->dropColumn('location_id');
            });
        }
    }
}
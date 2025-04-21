<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSettingsUniqueConstraint extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Remove the existing unique constraint
            $table->dropUnique(['key']);

            // Add a new unique constraint that includes both group and key
            $table->unique(['group', 'key']);
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Remove the composite unique constraint
            $table->dropUnique(['group', 'key']);

            // Restore the original unique constraint
            $table->unique(['key']);
        });
    }
}

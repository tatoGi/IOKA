<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixSettingsUniqueConstraint extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Remove existing unique constraint if it exists
            if (\Illuminate\Support\Facades\DB::select("SHOW INDEX FROM settings WHERE Key_name = 'settings_key_unique'")) {
                \Illuminate\Support\Facades\DB::statement('DROP INDEX settings_key_unique ON settings');
            }

            // Add composite unique (group, key) if it is not present
            if (!\Illuminate\Support\Facades\DB::select("SHOW INDEX FROM settings WHERE Key_name = 'settings_group_key_unique'")) {
                $table->unique(['group', 'key'], 'settings_group_key_unique');
            }
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Remove composite unique constraint if it exists
            if (\Illuminate\Support\Facades\DB::select("SHOW INDEX FROM settings WHERE Key_name = 'settings_group_key_unique'")) {
                \Illuminate\Support\Facades\DB::statement('DROP INDEX settings_group_key_unique ON settings');
            }

            // Restore original unique constraint if missing
            if (!\Illuminate\Support\Facades\DB::select("SHOW INDEX FROM settings WHERE Key_name = 'settings_key_unique'")) {
                $table->unique(['key'], 'settings_key_unique');
            }
        });
    }
}

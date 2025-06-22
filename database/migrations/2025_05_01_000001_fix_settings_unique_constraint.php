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
        });
        
        // Identify and fix duplicate keys before adding the unique constraint
        $duplicates = \Illuminate\Support\Facades\DB::select("
            SELECT `key`, COUNT(*) as count 
            FROM settings 
            GROUP BY `key` 
            HAVING COUNT(*) > 1
        ");
        
        foreach ($duplicates as $duplicate) {
            // Get all entries with this key
            $entries = \Illuminate\Support\Facades\DB::table('settings')
                ->where('key', $duplicate->key)
                ->get();
            
            // Keep the first one, modify others to make them unique
            for ($i = 1; $i < count($entries); $i++) {
                \Illuminate\Support\Facades\DB::table('settings')
                    ->where('id', $entries[$i]->id)
                    ->update(['key' => $entries[$i]->key . '_' . $i]);
            }
        }
        
        Schema::table('settings', function (Blueprint $table) {
            // Restore original unique constraint if missing
            if (!\Illuminate\Support\Facades\DB::select("SHOW INDEX FROM settings WHERE Key_name = 'settings_key_unique'")) {
                $table->unique(['key'], 'settings_key_unique');
            }
        });
    }
}

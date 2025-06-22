<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class SettingsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('settings', function ($app) {
            return new class {
                public function get($key, $default = null)
                {
                    return Setting::getValue($key, $default);
                }

                public function set($key, $value)
                {
                    return Setting::setValue($key, $value);
                }

                public function group($group)
                {
                    return Setting::where('group', $group)
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [$item->key => $item->value];
                        })
                        ->toArray();
                }
            };
        });
    }

    public function boot()
    {
        // Check if settings table exists before trying to access it
        // This prevents errors during migrations
        try {
            if (\Schema::hasTable('settings')) {
                // Merge DB settings with config defaults
                $settings = Setting::all()->mapWithKeys(function ($item) {
                    return [$item->key => $item->value];
                })->toArray();

                config()->set('settings.db', $settings);
            }
        } catch (\Exception $e) {
            // Table doesn't exist yet, likely during migration
            // Just set empty settings
            config()->set('settings.db', []);
        }
    }
}

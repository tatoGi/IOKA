<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;

class SettingsController extends Controller
{
    public function index()
    {
        // Get config settings
        $configSettings = config('settings');

        // Get database settings grouped by group
        $dbSettings = Setting::all()->groupBy('group');

        // Merge config with database settings
        $settings = [
            'header' => $this->mergeSettings($configSettings['header'] ?? [], $dbSettings->get('header', collect())),
            'footer' => $this->mergeSettings($configSettings['footer'], $dbSettings->get('footer', collect())),
            'social' => $this->mergeSettings($configSettings['social'], $dbSettings->get('social', collect()))
        ];

        return view('admin.settings.index', compact('settings'));
    }

    protected function mergeSettings($configItems, $dbItems)
    {
        $result = [];

        // Ensure $dbItems is always a Collection
        if (is_array($dbItems)) {
            $dbItems = collect($dbItems);
        }

        foreach ($configItems as $key => $defaultValue) {
            if (is_array($defaultValue)) {
                $dbItem = $dbItems->where('key', $key)->first();

                // Handle cases where $dbItem might be an array or object
                $dbValue = null;
                if (is_object($dbItem)) {
                    $dbValue = $dbItem->value;
                } elseif (is_array($dbItem)) {
                    $dbValue = $dbItem['value'] ?? null;
                }

                $result[$key] = $this->mergeSettings(
                    $defaultValue,
                    $dbValue ? (is_array($dbValue) ? $dbValue : [$dbValue]) : []
                );
            } else {
                $dbItem = $dbItems->where('key', $key)->first();
                $result[$key] = is_object($dbItem) ? $dbItem->value : ($dbItem['value'] ?? $defaultValue);
            }
        }

        return $result;
    }

    public function update(Request $request)
    {

        $validated = $this->validateSettings($request);
        if ($request->hasFile('header.logo')) {
            $validated['header']['logo'] = $this->uploadLogo($request->file('header.logo'), 'header');
        }

        if ($request->hasFile('footer.logo')) {
            $validated['footer']['logo'] = $this->uploadLogo($request->file('footer.logo'), 'footer');
        }
        $this->updateConfigFile($validated);
        // Update config file
        $this->updateConfigFile($validated);

        // Update database settings
        $this->updateDatabaseSettings($validated);

        return back()->with('success', 'Settings updated successfully!');
    }

    protected function uploadLogo($file, $type)
    {
        $filename = $type . '-logo-' . time() . '.' . $file->getClientOriginalExtension();

        // Store in public/logos directory
        $path = $file->storeAs('logos', $filename);

        // Return path without 'public/' for web access
        return 'storage/logos/' . $filename;
    }

    protected function updateConfigFile($validated)
    {
        $content = '<?php return ' . var_export([
            'header' => [
                'logo' => $validated['header']['logo'] ?? config('settings.header.logo'),
            ],
            'footer' => [
                'logo' => $validated['footer']['logo'] ?? config('settings.footer.logo'),
                'description' => $validated['footer']['description'],
                'contact' => $validated['footer']['contact'],
                'newsletter' => $validated['footer']['newsletter'],
                'copyright' => $validated['footer']['copyright'],
                'legal_links' => $validated['footer']['legal_links'] ?? []
            ],
            'social' => $validated['social']
        ], true) . ';';

        File::put(config_path('settings.php'), $content);
    }

    protected function updateDatabaseSettings($validated)
    {
        // Update footer settings
        foreach ($validated['footer'] as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    if (is_array($subValue)) {
                        // Handle nested arrays (like legal_links)
                        Setting::updateOrCreate(
                            ['group' => 'footer', 'key' => $key],
                            ['value' => $subValue]
                        );
                    } else {
                        Setting::updateOrCreate(
                            ['group' => 'footer', 'key' => "{$key}.{$subKey}"],
                            ['value' => $subValue]
                        );
                    }
                }
            } else {
                Setting::updateOrCreate(
                    ['group' => 'footer', 'key' => $key],
                    ['value' => $value]
                );
            }
        }

        // Update social settings
        foreach ($validated['social'] as $platform => $url) {
            Setting::updateOrCreate(
                ['group' => 'social', 'key' => $platform],
                ['value' => $url]
            );
        }
    }

    protected function validateSettings(Request $request)
    {
        return $request->validate([
            'header.logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'footer.logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'footer.description' => 'required|string',
            'footer.copyright' => 'required|string',
            'footer.contact.address' => 'required|string',
            'footer.contact.phone' => 'required|string',
            'footer.contact.email' => 'required|email',
            'footer.contact.working_hours' => 'required|string',
            'footer.newsletter.title' => 'required|string',
            'footer.newsletter.description' => 'required|string',
            'footer.newsletter.placeholder' => 'required|string',
            'footer.newsletter.button_text' => 'required|string',
            'footer.legal_links.*.title' => 'sometimes|string',
            'footer.legal_links.*.url' => 'sometimes|string',
            'social.facebook' => 'required|url',
            'social.twitter' => 'required|url',
            'social.instagram' => 'required|url',
            'social.youtube' => 'required|url',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

        // Convert legal links URLs to slugs
        if (isset($validated['footer']['legal_links'])) {
            foreach ($validated['footer']['legal_links'] as &$link) {
                if (isset($link['url'])) {
                    $link['url'] = Str::slug($link['url']);
                }
            }
        }

        // Handle meta images upload
        if ($request->hasFile('meta.og_image')) {
            $validated['meta']['og_image'] = $this->uploadMetaImage($request->file('meta.og_image'), 'og');
        }

        if ($request->hasFile('meta.twitter_image')) {
            $validated['meta']['twitter_image'] = $this->uploadMetaImage($request->file('meta.twitter_image'), 'twitter');
        }

        if ($request->hasFile('header.logo')) {
            $validated['header']['logo'] = $this->uploadLogo($request->file('header.logo'), 'header');
        }

        if ($request->hasFile('footer.logo')) {
            $validated['footer']['logo'] = $this->uploadLogo($request->file('footer.logo'), 'footer');
        }

        $this->updateConfigFile($validated);
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

    protected function uploadMetaImage($file, $type)
    {
        $filename = 'meta-' . $type . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('meta', $filename);
        return 'storage/meta/' . $filename;
    }

    protected function updateConfigFile($validated)
    {
        $content = '<?php return ' . var_export([
            'header' => [
                'logo' => $validated['header']['logo'] ?? config('settings.header.logo'),
            ],
            'meta' => [
                'title' => $validated['meta']['title'],
                'description' => $validated['meta']['description'],
                'keywords' => $validated['meta']['keywords'],
                'og_title' => $validated['meta']['og_title'],
                'og_description' => $validated['meta']['og_description'],
                'og_image' => $validated['meta']['og_image'] ?? config('settings.meta.og_image'),
                'twitter_card' => $validated['meta']['twitter_card'],
                'twitter_title' => $validated['meta']['twitter_title'],
                'twitter_description' => $validated['meta']['twitter_description'],
                'twitter_image' => $validated['meta']['twitter_image'] ?? config('settings.meta.twitter_image'),
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
        // Update meta settings
        foreach ($validated['meta'] as $key => $value) {
            Setting::updateOrCreate(
                ['group' => 'meta', 'key' => $key],
                ['value' => $value]
            );
        }

        // Update footer settings
        foreach ($validated['footer'] as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    if (is_array($subValue)) {
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
            'meta.title' => 'required|string|max:60',
            'meta.description' => 'required|string|max:160',
            'meta.keywords' => 'required|string|max:255',
            'meta.og_title' => 'required|string|max:60',
            'meta.og_description' => 'required|string|max:160',
            'meta.og_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta.twitter_card' => 'required|in:summary,summary_large_image',
            'meta.twitter_title' => 'required|string|max:60',
            'meta.twitter_description' => 'required|string|max:160',
            'meta.twitter_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'footer.legal_links.*.url' => 'sometimes|string|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'social.facebook' => 'required|url',
            'social.twitter' => 'required|url',
            'social.instagram' => 'required|url',
            'social.youtube' => 'required|url',
        ], [
            'footer.legal_links.*.url.regex' => 'The URL must be a valid slug (e.g., terms-of-service)',
            'meta.title.max' => 'Meta title should not exceed 60 characters',
            'meta.description.max' => 'Meta description should not exceed 160 characters',
            'meta.og_title.max' => 'OG title should not exceed 60 characters',
            'meta.og_description.max' => 'OG description should not exceed 160 characters',
            'meta.twitter_title.max' => 'Twitter title should not exceed 60 characters',
            'meta.twitter_description.max' => 'Twitter description should not exceed 160 characters',
        ]);
    }
}

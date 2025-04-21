<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            'meta' => $this->mergeSettings($configSettings['meta'] ?? [], $dbSettings->get('meta', collect())),
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

        // If $configItems is null, return empty array
        if (is_null($configItems)) {
            return [];
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
        try {
            // Debug the incoming request
            Log::info('Settings Update Request:', [
                'method' => $request->method(),
                'all' => $request->all(),
                'files' => $request->allFiles(),
                'headers' => $request->headers->all()
            ]);

            $validated = $this->validateSettings($request);

            // Convert legal links URLs to slugs
            if (isset($validated['footer']['legal_links'])) {
                foreach ($validated['footer']['legal_links'] as &$link) {
                    if (isset($link['url'])) {
                        $link['url'] = Str::slug($link['url']);
                    }
                }
            }

            // Handle header logo upload
            if ($request->hasFile('header_logo')) {
                Log::info('Processing header logo upload');
                // Delete old logo if exists
                $oldLogo = Setting::where('group', 'header')->where('key', 'header_logo')->first();
                if ($oldLogo && $oldLogo->value) {
                    Storage::delete($oldLogo->value);
                }
                $validated['header']['logo'] = $this->uploadLogo($request->file('header_logo'), 'header');
            }

            // Handle footer logo upload
            if ($request->hasFile('footer_logo')) {
                Log::info('Processing footer logo upload');
                // Delete old logo if exists
                $oldLogo = Setting::where('group', 'footer')->where('key', 'footer_logo')->first();
                if ($oldLogo && $oldLogo->value) {
                    Storage::delete($oldLogo->value);
                }
                $validated['footer']['logo'] = $this->uploadLogo($request->file('footer_logo'), 'footer');
            }

            // Handle meta images upload
            if ($request->hasFile('meta.og_image')) {
                Log::info('Processing OG image upload');
                $validated['meta']['og_image'] = $this->uploadMetaImage($request->file('meta.og_image'), 'og');
            }

            if ($request->hasFile('meta.twitter_image')) {
                Log::info('Processing Twitter image upload');
                $validated['meta']['twitter_image'] = $this->uploadMetaImage($request->file('meta.twitter_image'), 'twitter');
            }

            Log::info('Updating config file');
            $this->updateConfigFile($validated);

            Log::info('Updating database settings');
            $this->updateDatabaseSettings($validated);

            Log::info('Settings update completed successfully');
            return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating settings:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    public function deleteLogo(Request $request)
    {
        $type = $request->input('type');
        $group = $type === 'header' ? 'header' : 'footer';
        $key = $type === 'header' ? 'header_logo' : 'footer_logo';

        try {
            // Get the logo setting
            $logo = Setting::where('group', $group)->where('key', $key)->first();

            if ($logo && $logo->value) {
                // Delete the file from storage
                if (Storage::exists($logo->value)) {
                    Storage::delete($logo->value);
                }

                // Delete the setting from database
                $logo->delete();

                // Update config file
                $settings = config('settings');
                if ($type === 'header') {
                    $settings['header']['logo'] = '';
                } else {
                    $settings['footer']['logo'] = '';
                }

                // Write updated settings to config file
                $content = '<?php return ' . var_export($settings, true) . ';';
                File::put(config_path('settings.php'), $content);

                return back()->with('success', ucfirst($type) . ' logo deleted successfully!');
            }

            return back()->with('error', 'Logo not found!');
        } catch (\Exception $e) {
            Log::error('Error deleting logo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error deleting logo: ' . $e->getMessage());
        }
    }

    public function deleteMetaImage(Request $request)
    {
        $type = $request->input('type');
        $key = $type === 'og' ? 'og_image' : 'twitter_image';

        try {
            // Get the meta image setting
            $metaImage = Setting::where('group', 'meta')->where('key', $key)->first();

            if ($metaImage && $metaImage->value) {
                // Delete the file from storage
                if (Storage::exists($metaImage->value)) {
                    Storage::delete($metaImage->value);
                }

                // Delete the setting from database
                $metaImage->delete();

                // Update config file
                $settings = config('settings');
                $settings['meta'][$key] = '';

                // Write updated settings to config file
                $content = '<?php return ' . var_export($settings, true) . ';';
                File::put(config_path('settings.php'), $content);

                return back()->with('success', ucfirst($type) . ' image deleted successfully!');
            }

            return back()->with('error', 'Image not found!');
        } catch (\Exception $e) {
            Log::error('Error deleting meta image', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error deleting image: ' . $e->getMessage());
        }
    }

    protected function uploadLogo($file, $type)
    {
        $filename = $type . '-logo-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('logos', $filename, 'public');
        return $path;
    }

    protected function uploadMetaImage($file, $type)
    {
        $filename = 'meta-' . $type . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('meta', $filename, 'public');
        return $path;
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
        try {
            // Clear existing settings
            Setting::truncate();

            // Update header settings
            if (isset($validated['header']['logo'])) {
                Setting::create([
                    'group' => 'header',
                    'key' => 'header_logo',
                    'value' => $validated['header']['logo']
                ]);
            }

            // Update footer settings
            if (isset($validated['footer']['logo'])) {
                Setting::create([
                    'group' => 'footer',
                    'key' => 'footer_logo',
                    'value' => $validated['footer']['logo']
                ]);
            }

            // Update meta settings
            if (isset($validated['meta'])) {
                foreach ($validated['meta'] as $key => $value) {
                    if (!is_array($value)) {
                        Setting::create([
                            'group' => 'meta',
                            'key' => $key,
                            'value' => $value
                        ]);
                    }
                }
            }

            // Update footer settings
            foreach ($validated['footer'] as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $subKey => $subValue) {
                        if (is_array($subValue)) {
                            // Handle nested arrays (like legal_links)
                            Setting::create([
                                'group' => 'footer',
                                'key' => "{$key}.{$subKey}",
                                'value' => $subValue
                            ]);
                        } else {
                            // Handle simple key-value pairs
                            Setting::create([
                                'group' => 'footer',
                                'key' => "{$key}.{$subKey}",
                                'value' => $subValue
                            ]);
                        }
                    }
                } else {
                    // Handle simple footer settings
                    Setting::create([
                        'group' => 'footer',
                        'key' => $key,
                        'value' => $value
                    ]);
                }
            }

            // Update social settings
            foreach ($validated['social'] as $platform => $url) {
                Setting::create([
                    'group' => 'social',
                    'key' => $platform,
                    'value' => $url
                ]);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function validateSettings(Request $request)
    {
        return $request->validate([
            'header_logo' => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'footer_logo' => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta.title' => 'nullable|string',
            'meta.description' => 'nullable|string',
            'meta.keywords' => 'nullable|string',
            'meta.og_title' => 'nullable|string',
            'meta.og_description' => 'nullable|string',
            'meta.og_image' => 'sometimes|file|mimes:jpeg,png,jpg,gif|max:2048',
            'meta.twitter_card' => 'nullable|in:summary,summary_large_image',
            'meta.twitter_title' => 'nullable|string',
            'meta.twitter_description' => 'nullable|string',
            'meta.twitter_image' => 'sometimes|file|mimes:jpeg,png,jpg,gif|max:2048',
            'footer.description' => 'nullable|string',
            'footer.copyright' => 'nullable|string',
            'footer.contact.address' => 'nullable|string',
            'footer.contact.phone' => 'nullable|string',
            'footer.contact.email' => 'nullable|email',
            'footer.contact.working_hours' => 'nullable|string',
            'footer.newsletter.title' => 'nullable|string',
            'footer.newsletter.description' => 'nullable|string',
            'footer.newsletter.placeholder' => 'nullable|string',
            'footer.newsletter.button_text' => 'nullable|string',
            'footer.legal_links.*.title' => 'nullable|string',
            'footer.legal_links.*.url' => 'nullable|string',
            'social.facebook' => 'nullable|url',
            'social.twitter' => 'nullable|url',
            'social.instagram' => 'nullable|url',
            'social.youtube' => 'nullable|url',
        ], [
            'header_logo.mimes' => 'The header logo must be a file of type: jpeg, png, jpg, gif, svg.',
            'header_logo.max' => 'The header logo may not be larger than 2MB.',
            'footer_logo.mimes' => 'The footer logo must be a file of type: jpeg, png, jpg, gif, svg.',
            'footer_logo.max' => 'The footer logo may not be larger than 2MB.',
            'meta.og_image.mimes' => 'The OG image must be a file of type: jpeg, png, jpg, gif.',
            'meta.og_image.max' => 'The OG image may not be larger than 2MB.',
            'meta.twitter_image.mimes' => 'The Twitter image must be a file of type: jpeg, png, jpg, gif.',
            'meta.twitter_image.max' => 'The Twitter image may not be larger than 2MB.',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReorderSectionRequest;
use App\Http\Requests\Admin\StoreSectionRequest;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    public function create($pageId, $sectionKey)
    {

        $page = Page::where('id', $pageId)->first();

        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);
        if (! $pageType || ! isset($pageType['sections'][$sectionKey])) {
            abort(404);
        }

        return view('admin.sections.create', [
            'page' => $page,
            'pageId' => $pageId,
            'sectionKey' => $sectionKey,
            'section' => $pageType['sections'][$sectionKey],
        ]);
    }

    public function store(StoreSectionRequest $request, $pageId)
    {
        $page = Page::findOrFail($pageId);

        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);
        if (! $pageType) {
            abort(404);
        }

        $sectionKey = $request->input('section_key');
        $sectionConfig = $pageType['sections'][$sectionKey] ?? null;
        if (! $sectionConfig) {
            abort(404);
        }

        // Process fields
        $fields = $request->fields ?? [];
        $processedFields = $this->processFields($request, $fields, $sectionConfig['fields']);

        // Create section
        $section = new Section;
        $section->title = $processedFields['title'] ?? 'Untitled Section';
        $section->description = $request->input('description');
        $section->slug = Str::slug($processedFields['title'].'-'.time());
        $section->page_id = $page->id;
        $section->section_key = $sectionKey;
        $section->additional_fields = $processedFields;
        $section->save();

        return redirect()
            ->route('admin.sections.edit', ['pageId' => $pageId, 'sectionKey' => $sectionKey])
            ->with('success', 'Section created successfully');
    }

    public function edit($pageId, $sectionKey)
    {
        // Get the page using type_id instead of id
        $page = Page::where('id', $pageId)->firstOrFail();

        // Get page type from config
        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);
        if (! $pageType || ! isset($pageType['sections'][$sectionKey])) {
            abort(404);
        }

        $sectionConfig = $pageType['sections'][$sectionKey];

        // Find the section
        $section = Section::where('page_id', $pageId)
            ->where('section_key', $sectionKey)
            ->first();

        if (! $section) {
            abort(404);
        }

        return view('admin.sections.edit', [
            'section' => $section,
            'sectionConfig' => $sectionConfig,
            'page' => $page,
            'pageId' => $pageId,
            'sectionKey' => $sectionKey,
            'additionalFields' => $section->additional_fields, // Ensure additional fields are passed to the view
        ]);
    }

    public function update(Request $request, $pageId, $sectionKey)
    {
        try {
            Log::info('Section update started', [
                'pageId' => $pageId,
                'sectionKey' => $sectionKey,
                'requestData' => $request->all()
            ]);

            $section = Section::where('page_id', $pageId)
                ->where('section_key', $sectionKey)
                ->firstOrFail();

            $page = Page::findOrFail($pageId);
            $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);

            if (! $pageType || ! isset($pageType['sections'][$sectionKey])) {
                abort(404);
            }

            $sectionConfig = $pageType['sections'][$sectionKey];

            // Set the section in the request for use in processFields
            $request->merge(['section' => $section]);

            // Process fields
            $fields = $request->fields ?? [];
            $processedFields = $this->processFields($request, $fields, $sectionConfig['fields']);

            Log::info('Fields processed successfully', [
                'processedFields' => $processedFields
            ]);

            // Update section
            $section->title = $processedFields['title'] ?? $section->title;
            $section->slug = Str::slug($processedFields['title'].'-'.time());
            $section->description = $request->input('description', $section->description);
            $section->additional_fields = $processedFields;
            $section->save();

            Log::info('Section updated successfully', [
                'sectionId' => $section->id,
                'newTitle' => $section->title
            ]);

            return redirect()
                ->route('admin.sections.edit', ['pageId' => $pageId, 'sectionKey' => $sectionKey])
                ->with('success', 'Section updated successfully');

        } catch (\Exception $e) {
            Log::error('Section update failed', [
                'pageId' => $pageId,
                'sectionKey' => $sectionKey,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->route('admin.sections.edit', ['pageId' => $pageId, 'sectionKey' => $sectionKey])
                ->with('error', 'Failed to update section: ' . $e->getMessage());
        }
    }

    protected function validateSectionData(Request $request, array $sectionConfig)
    {
        $rules = $this->buildValidationRules($sectionConfig['fields']);

        return $request->validate($rules);
    }

    protected function buildValidationRules($fields, $prefix = 'additional_fields')
    {
        $rules = [];

        foreach ($fields as $key => $field) {
            $fieldName = "{$prefix}.{$key}";

            switch ($field['type']) {
                case 'repeater':
                    $rules[$fieldName] = 'array';
                    if (isset($field['max_items'])) {
                        $rules[$fieldName] .= '|max:'.$field['max_items'];
                    }
                    foreach ($field['fields'] as $subKey => $subField) {
                        $rules["{$fieldName}.*.{$subKey}"] = $this->getFieldRules($subField);
                    }
                    break;

                case 'tabs':
                    foreach ($field['tabs'] as $tabKey => $tab) {
                        $rules = array_merge(
                            $rules,
                            $this->buildValidationRules($tab['fields'], "{$prefix}.{$key}.{$tabKey}")
                        );
                    }
                    break;

                default:
                    $rules[$fieldName] = $this->getFieldRules($field);
            }
        }

        return $rules;
    }

    protected function getFieldRules($field)
    {
        $rules = [];

        switch ($field['type']) {
            case 'image':
            case 'photo':
                $rules[] = 'nullable|image|max:2048';
                break;
            case 'url':
                $rules[] = 'nullable|url';
                break;
            case 'number':
                $rules[] = 'nullable|numeric';
                break;
            default:
                $rules[] = 'nullable|string';
        }

        // Only make required if it's not an image field or if it's a new image
        if (! empty($field['required']) && !in_array($field['type'], ['image', 'photo'])) {
            $rules[0] = str_replace('nullable', 'required', $rules[0]);
        }

        return implode('|', $rules);
    }

    public function destroy($id)
    {

        $section = Section::findOrFail($id);
        $pageId = $section->page_id;

        if ($section->photo) {
            Storage::disk('public')->delete($section->photo);
        }

        $section->delete();

        return redirect()->route('admin.sections.index', $pageId)
            ->with('success', 'Section deleted successfully');
    }

    public function reorder(ReorderSectionRequest $request)
    {
        $sections = $request->input('sections', []);

        foreach ($sections as $order => $sectionId) {
            Section::where('id', $sectionId)->update(['sort_order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function index($pageId)
    {
        $page = Page::findOrFail($pageId);
        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);

        // Get available contact section types
        $availableSections = collect($pageType['sections'] ?? [])->map(function ($section, $key) {
            return [
                'key' => $key,
                'label' => $section['label'],
            ];
        });

        $sections = Section::where('page_id', $pageId)
            ->with('page')
            ->latest()
            ->get();

        return view('admin.sections.index', compact('sections', 'pageId', 'availableSections', 'page'));
    }

    protected function processFields(Request $request, array $fields, array $configFields)
    {
        $processedFields = [];

        // First, get all existing data from the section
        if ($request->section) {
            $existingData = $request->section->additional_fields;
            foreach ($configFields as $key => $config) {
                if (!isset($fields[$key])) {
                    $processedFields[$key] = $existingData[$key] ?? null;
                }
            }
        }

        // Then process the fields that are being updated
        foreach ($configFields as $key => $config) {
            switch ($config['type']) {
                case 'repeater':
                    if (isset($fields[$key])) {
                        // Check if this is an empty field (hidden input with empty value)
                        if (is_string($fields[$key]) && $fields[$key] === '') {
                            $processedFields[$key] = [];
                        } else {
                            $processedFields[$key] = $this->processRepeaterField($request, $fields[$key], $config, $key);
                        }
                    } else {
                        // If the field is not in the request, it means all items were removed
                        // Set it to an empty array instead of keeping existing data
                        $processedFields[$key] = [];
                    }
                    break;

                case 'image':
                case 'photo':
                    if (isset($fields[$key]) && $fields[$key] instanceof \Illuminate\Http\UploadedFile) {
                        $processedFields[$key] = $fields[$key]->store('sections', 'public');
                    } else {
                        // Keep existing image if no new one uploaded
                        $processedFields[$key] = $request->input("old_{$key}") ??
                            ($request->section->additional_fields[$key] ?? null);
                    }
                    break;

                case 'tabs':
                    $processedFields[$key] = $this->processTabsField($request, $fields[$key] ?? [], $config);
                    break;

                case 'group':
                    $processedFields[$key] = $this->processGroupField($request, $fields[$key] ?? [], $config);
                    break;

                default:
                    $processedFields[$key] = $fields[$key] ?? null;
            }
        }

        return $processedFields;
    }

    protected function processRepeaterField(Request $request, array $repeaterData, array $config, string $fieldKey)
    {
        Log::info("Processing repeater field: {$fieldKey}", [
            'repeaterData' => $repeaterData,
            'config' => $config,
            'request_all' => $request->all()
        ]);

        $processed = [];

        // Get deleted indexes from the form
        $deletedIndexes = (array)$request->input("deleted_{$fieldKey}", []);
        $deletedIndexes = array_map('intval', $deletedIndexes);

        // Get existing data to handle file uploads
        $existingData = $request->section ? ($request->section->additional_fields[$fieldKey] ?? []) : [];

        Log::info("Repeater processing details", [
            'fieldKey' => $fieldKey,
            'deletedIndexes' => $deletedIndexes,
            'existingData' => $existingData,
            'repeaterDataCount' => count($repeaterData)
        ]);

        // Process submitted data
        foreach ($repeaterData as $index => $item) {
            // Skip if the item is empty, not an array, or marked for deletion
            if (!is_array($item) || in_array($index, $deletedIndexes, true)) {
                continue;
            }

            $processedItem = [];
            $hasValue = false;

            // Process each field in the repeater item
            foreach ($config['fields'] as $repeaterFieldKey => $fieldConfig) {
                $value = $item[$repeaterFieldKey] ?? null;

                // Handle file uploads
                if (in_array($fieldConfig['type'], ['image', 'photo'], true)) {
                    if ($value instanceof \Illuminate\Http\UploadedFile) {
                        $processedItem[$repeaterFieldKey] = $value->store('sections', 'public');
                        $hasValue = true;
                    } else {
                        // Keep existing file if exists
                        $oldImageKey = "old_{$fieldKey}_{$repeaterFieldKey}_{$index}";
                        $existingValue = $request->input($oldImageKey);

                        if ($existingValue) {
                            $processedItem[$repeaterFieldKey] = $existingValue;
                            $hasValue = true;
                        } elseif (isset($existingData[$index][$repeaterFieldKey])) {
                            $processedItem[$repeaterFieldKey] = $existingData[$index][$repeaterFieldKey];
                            $hasValue = true;
                        }
                    }
                } else {
                    // For regular fields, always keep them even if empty
                    $processedItem[$repeaterFieldKey] = $value;
                    $hasValue = $hasValue || ($value !== null && $value !== '');
                }
            }

            // Add item if it has any value or if it's a new item
            if ($hasValue || !isset($existingData[$index])) {
                $processed[] = $processedItem;
            }
        }

        // Previously, any existing repeater items that were not sent back from the
        // form (and not explicitly marked as deleted) were automatically re-added
        // here. This caused two issues:
        // 1. When the user deleted a repeater item in the UI it came back after save.
        // 2. When the user added new items and the JS re-indexed existing ones, the
        //    stale items were appended; this looked like the new item was added
        //    twice.
        //
        // The correct behaviour is: if an item is missing from the submitted form
        // data we treat it as deleted (unless it is explicitly preserved via the
        // hidden "old_*" inputs handled earlier). Therefore we simply remove this
        // block so that only the items present in $repeaterData make it into the
        // final $processed list.


        // Reset array keys
        $processed = array_values($processed);

        // Ensure we have at least the minimum number of items
        $minItems = $config['min_items'] ?? 0;
        if (count($processed) < $minItems) {
            for ($i = count($processed); $i < $minItems; $i++) {
                $emptyItem = [];
                foreach ($config['fields'] as $repeaterFieldKey => $fieldConfig) {
                    $emptyItem[$repeaterFieldKey] = $fieldConfig['default'] ?? '';
                }
                $processed[] = $emptyItem;
            }
        }

        Log::info("Repeater field processed result", [
            'fieldKey' => $fieldKey,
            'processedCount' => count($processed),
            'processed' => $processed
        ]);

        return $processed;
    }

    protected function processTabsField(Request $request, array $tabsData, array $config)
    {
        $processed = [];

        foreach ($config['tabs'] as $tabKey => $tabConfig) {
            if (isset($tabConfig['fields'])) {
                $processed[$tabKey] = [];
                foreach ($tabConfig['fields'] as $fieldKey => $fieldConfig) {
                    if (
                        $fieldConfig['type'] === 'image' &&
                        isset($tabsData[$tabKey][$fieldKey]) &&
                        $tabsData[$tabKey][$fieldKey] instanceof \Illuminate\Http\UploadedFile
                    ) {
                        $processed[$tabKey][$fieldKey] = $tabsData[$tabKey][$fieldKey]->store('sections', 'public');
                    } elseif ($fieldConfig['type'] === 'image') {
                        // Keep existing image if no new one uploaded
                        $processed[$tabKey][$fieldKey] = $request->input("old_{$tabKey}_{$fieldKey}") ??
                            ($request->section->additional_fields[$tabKey][$fieldKey] ?? null);
                    } else {
                        $processed[$tabKey][$fieldKey] = $tabsData[$tabKey][$fieldKey] ?? null;
                    }
                }
            }
        }

        return $processed;
    }

    protected function processGroupField(Request $request, array $groupData, array $config)
    {
        return $this->processFields($request, $groupData, $config['fields']);
    }

    public function deleteImage(Request $request, $pageId, $sectionKey)
    {
        $section = Section::where('page_id', $pageId)
            ->where('section_key', $sectionKey)
            ->firstOrFail();

        $fieldKey = $request->input('field_key');
        $index = $request->input('index');
        $repeaterField = $request->input('repeater_field');
        $tab = $request->input('tab');
        $tabsField = $request->input('tabs_field');
        $additionalFields = $section->additional_fields;

        if ($index !== null && $repeaterField !== null) {
            // Handle repeater field image deletion
            if (isset($additionalFields[$repeaterField][$index][$fieldKey])) {
                $imagePath = $additionalFields[$repeaterField][$index][$fieldKey] ?? null;
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                // Remove the image from the repeater item
                unset($additionalFields[$repeaterField][$index][$fieldKey]);
            }
        } elseif ($tab !== null && $tabsField !== null) {
            // Handle tabs field image deletion
            if (isset($additionalFields[$tabsField][$tab][$fieldKey])) {
                $imagePath = $additionalFields[$tabsField][$tab][$fieldKey] ?? null;
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                // Remove the image from the tabs field
                unset($additionalFields[$tabsField][$tab][$fieldKey]);
            }
        } else {
            // Handle main section field image deletion
            $imagePath = $additionalFields[$fieldKey] ?? null;
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                $additionalFields[$fieldKey] = null;
            }
        }

        $section->setAttribute('additional_fields', $additionalFields);
        $section->save();

        return response()->json(['success' => true]);
    }
}

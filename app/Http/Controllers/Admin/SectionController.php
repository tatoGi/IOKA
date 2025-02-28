<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReorderSectionRequest;
use App\Http\Requests\Admin\StoreSectionRequest;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
        $section = Section::where('page_id', $pageId)
            ->where('section_key', $sectionKey)
            ->firstOrFail();

        $page = Page::findOrFail($pageId);
        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);

        if (! $pageType || ! isset($pageType['sections'][$sectionKey])) {
            abort(404);
        }

        $sectionConfig = $pageType['sections'][$sectionKey];

        // Process fields
        $fields = $request->fields ?? [];
        $processedFields = $this->processFields($request, $fields, $sectionConfig['fields']);

        // Update section
        $section->title = $processedFields['title'] ?? $section->title;
        $section->slug = Str::slug($processedFields['title'].'-'.time());
        $section->description = $request->input('description', $section->description);
        $section->additional_fields = $processedFields;
        $section->save();

        return redirect()
            ->route('admin.sections.edit', ['pageId' => $pageId, 'sectionKey' => $sectionKey])
            ->with('success', 'Section updated successfully');
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

        if (! empty($field['required'])) {
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

        foreach ($configFields as $key => $config) {
            switch ($config['type']) {
                case 'repeater':
                    $processedFields[$key] = $this->processRepeaterField($request, $fields[$key] ?? [], $config);
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

    protected function processRepeaterField(Request $request, array $repeaterData, array $config)
    {
        $processed = [];

        if (! empty($repeaterData)) {
            foreach ($repeaterData as $index => $item) {
                $processedItem = [];
                foreach ($config['fields'] as $fieldKey => $fieldConfig) {
                    if ($fieldConfig['type'] === 'image') {
                        if (isset($item[$fieldKey]) && $item[$fieldKey] instanceof \Illuminate\Http\UploadedFile) {
                            $processedItem[$fieldKey] = $item[$fieldKey]->store('sections', 'public');
                        } else {
                            // Keep existing image if no new one uploaded
                            $processedItem[$fieldKey] = $request->input("old_{$fieldKey}_{$index}") ??
                                ($request->section->additional_fields[$fieldKey][$index][$fieldKey] ?? null);
                        }
                    } else {
                        $processedItem[$fieldKey] = $item[$fieldKey] ?? null;
                    }
                }
                $processed[] = $processedItem;
            }
        }

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
}

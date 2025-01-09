<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\Admin\StoreSectionRequest;
use App\Http\Requests\Admin\UpdateSectionRequest;
use App\Http\Requests\Admin\ReorderSectionRequest;

class SectionController extends Controller
{
    public function create($pageId, $sectionKey)
    {

        $page = Page::where('id', $pageId)->first();

        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);
        if (!$pageType || !isset($pageType['sections'][$sectionKey])) {
            abort(404);
        }


        return view('admin.sections.create', [
            'pageId' => $pageId,
            'sectionKey' => $sectionKey,
            'section' => $pageType['sections'][$sectionKey]
        ]);
    }

    public function store(StoreSectionRequest $request, $pageId)
    {

        // First check if the page exists in the database
        $page = Page::where('id', $pageId)->first();

        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);
        if (!$pageType) {
            abort(404);
        }

        $sectionKey = $request->input('section_key');
$sectionConfig = $pageType['sections'][$sectionKey] ?? null;
        if (!$sectionConfig) {
            abort(404);
        }

        // Validate and process the fields
        $fields = $request->fields ?? [];

        $processedFields = $this->processFields($request, $fields, $sectionConfig['fields']);



        // Create new section
        $section = new Section();
        $section->title = $fields['title'];
        $section->description = $request->input('description'); // Get description from CKEditor
        $section->slug = Str::slug($request->title);
        $section->page_id = $page->id;
        $section->section_key = $sectionKey;
        $section->additional_fields = $processedFields;
        $section->save();

        return redirect()
            ->route('admin.sections.edit', ['pageId' => $section->page_id, 'sectionKey' => $sectionKey])
            ->with('success', 'Section created successfully');
    }

    protected function processFields(Request $request, array $fields, array $configFields)
    {
        $processed = [];

        foreach ($configFields as $key => $config) {
            if ($config['type'] === 'repeater') {
                // Pass 'items' to the repeater processing logic
                $processed[$key] = $this->processRepeaterField($request, $fields[$key]['items'] ?? [], $key, $config);
            } elseif ($config['type'] === 'image') {
                if ($request->hasFile("fields.{$key}")) {
                    $files = $request->file("fields.{$key}.items");
                    if (!is_array($files)) {
                        $files = $files ? [$files] : []; // Ensure $files is an array
                    }
                    $processedImages = [];
                    foreach ($files as $file) {
                        if ($file) {
                            $processedImages[] = $this->processImageField($file);
                        }
                    }
                    $processed[$key] = $processedImages;
                } else {
                    $processed[$key] = $fields[$key] ?? null; // Default if no file uploaded
                }
            } else {
                $processed[$key] = $fields[$key] ?? null; // Process simple fields
            }
        }

        return $processed;
    }

    protected function processRepeaterField(Request $request, array $repeaterData, string $fieldName, array $config)
    {
        $processed = [];

        if (!empty($repeaterData)) {
            foreach ($repeaterData as $item) {
                $processedItem = [];

                foreach ($config['fields'] as $subKey => $subField) {
                    if ($subField['type'] === 'image') {
                        // Handle image field
                        if (isset($item[$subKey]) && $item[$subKey] instanceof \Illuminate\Http\UploadedFile) {
                            $processedItem[$subKey] = $this->processImageField($item[$subKey]);
                        } else {
                            $processedItem[$subKey] = $item[$subKey] ?? null;
                        }
                    } else {
                        // Process other fields
                        $processedItem[$subKey] = $item[$subKey] ?? null;
                    }
                }

                $processed[] = $processedItem;
            }
        }

        return $processed;
    }



    protected function processImageField($file)
{

    if ($file instanceof \Illuminate\Http\UploadedFile) {
        $path = $file->store('sections', 'public');
        return $path;
    }

    return null;
}

    public function edit($pageId, $sectionKey)
    {

        // Get the page using type_id instead of id
        $page = Page::where('id', $pageId)->firstOrFail();
        // Get page type from config
        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);
        if (!$pageType || !isset($pageType['sections'][$sectionKey])) {
            abort(404);
        }

        $sectionConfig = $pageType['sections'][$sectionKey];

        // Find the section
        $section = Section::where('page_id', $pageId)
            ->where('section_key', $sectionKey)
            ->first();

        if (!$section) {
            abort(404);
        }

        return view('admin.sections.edit', [
            'section' => $section,
            'sectionConfig' => $sectionConfig,
            'page' => $page,
            'pageId' => $pageId,
            'sectionKey' => $sectionKey
        ]);
    }

    public function update(Request $request, $pageId, $sectionKey)
    {
        $section = Section::where('page_id', $pageId)
            ->where('section_key', $sectionKey)
            ->firstOrFail();

        // Get the page and page type to validate section configuration
        $page = Page::where('id', $pageId)->firstOrFail();
        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $page->type_id);

        if (!$pageType || !isset($pageType['sections'][$sectionKey])) {
            abort(404);
        }

        $sectionConfig = $pageType['sections'][$sectionKey];

        // Process the fields
        $fields = $request->input('fields', []);
        $processedFields = $this->processFields($request, $fields, $sectionConfig['fields']);

        // Update the section
        $section->title = $fields['title'] ?? $section->title; // Get title from fields array
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
                        $rules[$fieldName] .= '|max:' . $field['max_items'];
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

        if (!empty($field['required'])) {
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

        return redirect()->route('admin.pages.edit', $pageId)
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
}

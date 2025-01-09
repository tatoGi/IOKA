<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class StoreSectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $pageId = $this->route('pageId');
        $pageType = collect(Config::get('PageTypes'))->firstWhere('id', $pageId);
        $sectionKey = $this->input('section_key');
        $sectionConfig = $pageType['sections'][$sectionKey] ?? null;

        if (!$sectionConfig) {
            return [];
        }

        return [
            'fields.title' => 'required|string|max:255',
            'fields' => 'array',
            'section_key' => 'required|string'
        ];
    }
}

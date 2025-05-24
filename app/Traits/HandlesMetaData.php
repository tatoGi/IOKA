<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesMetaData
{
    /**
     * Get metadata validation rules
     */
    protected function getMetadataValidationRules(): array
    {
        return [
            'metadata.meta_title' => 'nullable|string|max:255',
            'metadata.meta_description' => 'nullable|string',
            'metadata.meta_keywords' => 'nullable|string',
            'metadata.og_title' => 'nullable|string|max:255',
            'metadata.og_description' => 'nullable|string',
            'metadata.og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'metadata.twitter_card' => 'nullable|string|in:summary,summary_large_image',
            'metadata.twitter_title' => 'nullable|string|max:255',
            'metadata.twitter_description' => 'nullable|string',
            'metadata.twitter_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Handle metadata updates for a model
     */
    protected function handleMetadata(Request $request, $model): void
    {
        if ($request->has('metadata')) {
            $metadata = $request->input('metadata');

            // Handle metadata file uploads
            if ($request->hasFile('og_image')) {
                if ($model->metadata?->og_image) {
                    Storage::disk('public')->delete($model->metadata->og_image);
                }
                $metadata['og_image'] = $request->file('og_image')->store('meta-images/og', 'public');
            }

            if ($request->hasFile('twitter_image')) {
                if ($model->metadata?->twitter_image) {
                    Storage::disk('public')->delete($model->metadata->twitter_image);
                }
                $metadata['twitter_image'] = $request->file('twitter_image')->store('meta-images/twitter', 'public');
            }

            $model->updateMetadata($metadata);
        }
    }
}

<?php

namespace App\Traits;

use App\Models\MetaData;

trait HasMetaData
{
    /**
     * Get the metadata associated with the model.
     */
    public function metadata()
    {
        return $this->morphOne(MetaData::class, 'metadatable');
    }

    /**
     * Update or create metadata for the model.
     */
    public function updateMetadata(array $metadata)
    {
        if ($this->metadata) {
            return $this->metadata->update($metadata);
        }

        return $this->metadata()->create($metadata);
    }

    public function getMetaTitle()
    {
        return $this->metadata?->meta_title ?? $this->title ?? '';
    }

    public function getMetaDescription()
    {
        return $this->metadata?->meta_description ?? '';
    }

    public function getMetaKeywords()
    {
        return $this->metadata?->meta_keywords ?? '';
    }

    public function getOgTitle()
    {
        return $this->metadata?->og_title ?? $this->getMetaTitle();
    }

    public function getOgDescription()
    {
        return $this->metadata?->og_description ?? $this->getMetaDescription();
    }

    public function getOgImage()
    {
        return $this->metadata?->og_image ?? '';
    }

    public function getTwitterCard()
    {
        return $this->metadata?->twitter_card ?? 'summary';
    }

    public function getTwitterTitle()
    {
        return $this->metadata?->twitter_title ?? $this->getOgTitle();
    }

    public function getTwitterDescription()
    {
        return $this->metadata?->twitter_description ?? $this->getOgDescription();
    }

    public function getTwitterImage()
    {
        return $this->metadata?->twitter_image ?? $this->getOgImage();
    }
}

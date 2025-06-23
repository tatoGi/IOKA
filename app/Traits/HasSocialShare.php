<?php

namespace App\Traits;

trait HasSocialShare
{
    /**
     * Get social sharing data for the model
     *
     * @return array
     */
    public function getSocialShareData()
    {
        $url = $this->getModelUrl();
        $title = $this->getShareTitle();
        $description = $this->getShareDescription();
        $image = $this->getShareImage();
        
        return [
            'social_share' => [
                'url' => $url,
                'title' => $title,
                'description' => $description,
                'image' => $image,
                'share_links' => [
                    'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url),
                    'twitter' => "https://twitter.com/intent/tweet?url=" . urlencode($url) . "&text=" . urlencode($title),
                    'linkedin' => "https://www.linkedin.com/shareArticle?mini=true&url=" . urlencode($url) . "&title=" . urlencode($title) . "&summary=" . urlencode($description),
                    'whatsapp' => "https://wa.me/?text=" . urlencode($title . ' ' . $url),
                    'telegram' => "https://t.me/share/url?url=" . urlencode($url) . "&text=" . urlencode($title),
                    'email' => "mailto:?subject=" . urlencode($title) . "&body=" . urlencode($description . "\n\n" . $url),
                ]
            ]
        ];
    }

    /**
     * Get the URL for the model
     * 
     * @return string
     */
    protected function getModelUrl()
    {
        // Base implementation - override in model if needed
        $baseUrl = config('app.frontend_url', 'https://your-website.com');
        $path = $this->getUrlPath();
        
        return $baseUrl . '/' . $path;
    }
    
    /**
     * Get the URL path for the model
     * Must be overridden in model classes
     * 
     * @return string
     */
    protected function getUrlPath()
    {
        // This should be overridden in each model
        // Fallback to using the model type and slug if available
        $modelType = strtolower(class_basename($this));
        
        if (property_exists($this, 'slug') && !empty($this->slug)) {
            return $modelType . 's/' . $this->slug;
        }
        
        return $modelType . 's/' . $this->id;
    }
    
    /**
     * Get the title for sharing
     * 
     * @return string
     */
    protected function getShareTitle()
    {
        // Try to get the title from common title fields
        $titleFields = ['meta_title', 'title', 'name', 'heading'];
        
        foreach ($titleFields as $field) {
            if (property_exists($this, $field) && !empty($this->{$field})) {
                return $this->{$field};
            }
            
            if (isset($this->attributes[$field]) && !empty($this->attributes[$field])) {
                return $this->attributes[$field];
            }
            
            if ($this->metadata && property_exists($this->metadata, $field) && !empty($this->metadata->{$field})) {
                return $this->metadata->{$field};
            }
        }
        
        // Fallback to model name + id
        return class_basename($this) . ' #' . $this->id;
    }
    
    /**
     * Get the description for sharing
     * 
     * @return string
     */
    protected function getShareDescription()
    {
        // Try to get the description from common description fields
        $descFields = ['meta_description', 'description', 'excerpt', 'summary', 'desc', 'content'];
        
        foreach ($descFields as $field) {
            if (property_exists($this, $field) && !empty($this->{$field})) {
                return $this->truncateDescription($this->{$field});
            }
            
            if (isset($this->attributes[$field]) && !empty($this->attributes[$field])) {
                return $this->truncateDescription($this->attributes[$field]);
            }
            
            if ($this->metadata && property_exists($this->metadata, $field) && !empty($this->metadata->{$field})) {
                return $this->truncateDescription($this->metadata->{$field});
            }
        }
        
        // Fallback to default website description
        return config('app.description', 'Check out this ' . class_basename($this) . ' on our website.');
    }
    
    /**
     * Get the image for sharing
     * 
     * @return string|null
     */
    protected function getShareImage()
    {
        // Try to get image from common image fields
        $imageFields = ['image', 'photo', 'thumbnail', 'featured_image', 'banner', 'cover_image'];
        
        foreach ($imageFields as $field) {
            if (property_exists($this, $field) && !empty($this->{$field})) {
                return $this->formatImageUrl($this->{$field});
            }
            
            if (isset($this->attributes[$field]) && !empty($this->attributes[$field])) {
                return $this->formatImageUrl($this->attributes[$field]);
            }
        }
        
        // Try to get from metadata
        if ($this->metadata && property_exists($this->metadata, 'og_image') && !empty($this->metadata->og_image)) {
            return $this->formatImageUrl($this->metadata->og_image);
        }
        
        // Fallback to default website image
        return config('app.default_share_image', null);
    }
    
    /**
     * Format image URL to ensure it's a full URL
     * 
     * @param string $imagePath
     * @return string
     */
    protected function formatImageUrl($imagePath)
    {
        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            return $imagePath;
        }
        
        // Handle JSON encoded images
        if ($this->isJson($imagePath)) {
            $images = json_decode($imagePath, true);
            if (!empty($images) && is_array($images)) {
                $imagePath = is_array($images[0]) && isset($images[0]['path']) ? $images[0]['path'] : $images[0];
            }
        }
        
        $baseUrl = config('app.url', 'http://localhost');
        
        if (strpos($imagePath, 'storage/') === 0) {
            return $baseUrl . '/' . $imagePath;
        }
        
        return $baseUrl . '/storage/' . $imagePath;
    }
    
    /**
     * Truncate description to appropriate length for sharing
     * 
     * @param string $description
     * @return string
     */
    protected function truncateDescription($description)
    {
        $description = strip_tags($description);
        
        if (strlen($description) <= 200) {
            return $description;
        }
        
        return substr($description, 0, 197) . '...';
    }
    
    /**
     * Check if a string is valid JSON
     * 
     * @param string $string
     * @return bool
     */
    protected function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

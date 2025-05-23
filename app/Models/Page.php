<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use App\Models\MetaData;

class Page extends Model
{
    protected $fillable = ['title', 'meta_title', 'keywords', 'slug', 'desc', 'parent_id', 'type_id', 'sort', 'active'];

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id')
            ->with('translation')
            ->with(['children' => function ($query) {
                $query->orderBy('sort', 'asc');
            }]);
    }

    public function parent()
    {

        return $this->belongsTo(Page::class, 'parent_id');
    }

    public static function rearrange($array)
    {

        self::_rearrange($array, 0);

        \App\Models\Page::all()->each(function ($item) {

            $item->save();
        });
    }

    private static function _rearrange($array, $count, $parent = null)
    {

        foreach ($array as $a) {

            $count++;

            self::where('id', $a['id'])->update(['parent_id' => $parent, 'sort' => $count]);

            if (isset($a['children'])) {

                $count = self::_rearrange($a['children'], $count, $a['id']);
            }
        }

        return $count;
    }

    public function getFieldsAttribute()
    {

        return collect(Config::get('pageTypes'))->where('id', $this->type_id)->first()['fields'];
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('sort_order');
    }

    /**
     * Get the metadata associated with the page.
     */
    public function metadata()
    {
        return $this->morphOne(MetaData::class, 'metadatable');
    }

    /**
     * Update or create metadata for the page.
     */
    public function updateMetadata(array $metadata)
    {
        if ($this->metadata) {
            return $this->metadata->update($metadata);
        }

        return $this->metadata()->create($metadata);
    }
}

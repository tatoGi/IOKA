<?php
namespace App\Services\Frontend;

use App\Models\Offplan;

class FilterService
{
    public function filterOffplans(array $filters)
    {
        $query = Offplan::query();

        if (!empty($filters['property_type'])) {
            $query->where('property_type', $filters['property_type']);
        }

        if (!empty($filters['price_min'])) {
            $query->where('amount', '>=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('amount', '<=', $filters['price_max']);
        }

        if (!empty($filters['bedrooms'])) {
            $query->where('bedroom', $filters['bedrooms']);
        }

        if (!empty($filters['bathrooms'])) {
            $query->where('bathroom', $filters['bathrooms']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'LIKE', '%' . $filters['location'] . '%');
        }

        return $query->get();
    }
}

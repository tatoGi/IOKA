<?php
namespace App\Services\Frontend;

use App\Models\Offplan;
use Illuminate\Pagination\LengthAwarePaginator;

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

        if (!empty($filters['location'])) {
            $searchTerm = '%' . $filters['location'] . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', $searchTerm)
                  ->orWhere('subtitle', 'LIKE', $searchTerm)
                  ->orWhere('location', 'LIKE', $searchTerm)
                  ->orWhere('description', 'LIKE', $searchTerm)
                  ->orWhere('amenities', 'LIKE', $searchTerm)
                  ->orWhere('map_location', 'LIKE', $searchTerm)
                  ->orWhere('qr_title', 'LIKE', $searchTerm)
                  ->orWhere('qr_text', 'LIKE', $searchTerm)
                  ->orWhere('agent_title', 'LIKE', $searchTerm)
                  ->orWhere('agent_status', 'LIKE', $searchTerm)
                  ->orWhere('slug', 'LIKE', $searchTerm);
            });
        }

        // Get pagination parameters
        $page = $filters['page'] ?? 1;
        $perPage = 12; // You can adjust this number based on your needs

        // Execute the query with pagination
        $results = $query->paginate($perPage, ['*'], 'page', $page);

        // Return the paginated results
        return [
            'data' => $results->items(),
            'current_page' => $results->currentPage(),
            'last_page' => $results->lastPage(),
            'per_page' => $results->perPage(),
            'total' => $results->total(),
        ];
    }
}

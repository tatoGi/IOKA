<?php
namespace App\Services\Frontend;

use App\Models\Offplan;
use App\Models\RentalResale;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class FilterService
{
    public function filterRentals($filters)
    {
        $query = RentalResale::with(['amount']);

        // Property Type Filter
        if (!empty($filters['property_type'])) {
            $query->where('property_type', $filters['property_type']);
        }

        // Price Range Filter
        if (!empty($filters['price_min'])) {
            $query->where('amount', '>=', (float)$filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $query->where('amount', '<=', (float)$filters['price_max']);
        }

        // Bedrooms Filter
        if (!empty($filters['bedrooms'])) {
            $query->where('bedroom', $filters['bedrooms']);
        }

        // Bathrooms Filter - Updated to handle NULL values
        if (!empty($filters['bathrooms'])) {

            $query->where(function($q) use ($filters) {
                $q->where('bathroom', '=', (int)$filters['bathrooms'])
                  ->orWhereNull('bathroom');
            });
        }

        // Area (sq_ft) Filter
        if (!empty($filters['sq_ft_min'])) {
            $query->where('sq_ft', '>=', (float)$filters['sq_ft_min']);
        }
        if (!empty($filters['sq_ft_max'])) {
            $query->where('sq_ft', '<=', (float)$filters['sq_ft_max']);
        }

        // Location Search Filter
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

        // Debug the final SQL query


        // Get pagination parameters
        $page = $filters['page'] ?? 1;
        $perPage = 12;

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

    public function filterOffplans(array $filters)
    {

        $query = Offplan::query();

        // Property Type Filter
        if (!empty($filters['property_type'])) {
            $query->where('property_type', $filters['property_type']);
        }

        // Price Range Filter
        if (!empty($filters['price_min'])) {
            $query->where('amount', '>=', (float)$filters['price_min']);
        }
        if (!empty($filters['price_max'])) {
            $query->where('amount', '<=', (float)$filters['price_max']);
        }

        // Bedrooms Filter
        if (!empty($filters['bedrooms'])) {
            $query->where('bedroom', $filters['bedrooms']);
        }

        // Bathrooms Filter - Updated to handle NULL values
        if (!empty($filters['bathrooms'])) {

            $query->where(function($q) use ($filters) {
                $q->where('bathroom', '=', (int)$filters['bathrooms'])
                  ->orWhereNull('bathroom');
            });
        }

        // Area (sq_ft) Filter
        if (!empty($filters['sq_ft_min'])) {
            $query->where('sq_ft', '>=', (float)$filters['sq_ft_min']);
        }
        if (!empty($filters['sq_ft_max'])) {
            $query->where('sq_ft', '<=', (float)$filters['sq_ft_max']);
        }

        // Location Search Filter
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

        // Debug the final SQL query


        // Get pagination parameters
        $page = $filters['page'] ?? 1;
        $perPage = 12;

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

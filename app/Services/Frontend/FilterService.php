<?php
namespace App\Services\Frontend;

use App\Models\Offplan;
use App\Models\RentalResale;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use App\Models\Location;
use Illuminate\Http\Request;

class FilterService
{
    public function filterRentals($filters)
    {
        $query = RentalResale::with(['amount']);

        // Property Type Filter
        if (!empty($filters['property_type'])) {
            $query->where('property_type', $filters['property_type']);
        }

        // Price Range Filter - Updated to use amount relationship
        if (!empty($filters['price_min'])) {
            $query->whereHas('amount', function($q) use ($filters) {
                $q->where('amount', '>=', (float)$filters['price_min']);
            });
        }
        if (!empty($filters['price_max'])) {
            $query->whereHas('amount', function($q) use ($filters) {
                $q->where('amount', '<=', (float)$filters['price_max']);
            });
        }

        // Bedrooms Filter
        if (!empty($filters['bedrooms'])) {
            $query->where('bedroom', (int)$filters['bedrooms']);
        } elseif (!empty($filters['bedrooms_min'])) {
            $query->where('bedroom', '>=', (int)$filters['bedrooms_min']);
        }

        // Bathrooms Filter - Exact match
        if (!empty($filters['bathrooms'])) {
            $query->where('bathroom', (int)$filters['bathrooms']);
        } elseif (!empty($filters['bathrooms_min'])) {
            $query->where('bathroom', '>=', (int)$filters['bathrooms_min']);
        }
     
        // Area (sq_ft) Filter - Updated to handle type conversion
        if (!empty($filters['sq_ft_min'])) {
            $query->where('sq_ft', '>=', (float)$filters['sq_ft_min']);
        }
        if (!empty($filters['sq_ft_max'])) {
            $query->where('sq_ft', '<=', (float)$filters['sq_ft_max']);
        }

        // Location Search Filter
        if (!empty($filters['location'])) {
            $searchTerm = '%' . $filters['location'] . '%';
            $query->whereHas('locations', function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', $searchTerm)
                  ->orWhere('subtitle', 'LIKE', $searchTerm)
                  ->orWhere('description', 'LIKE', $searchTerm)
                  ->orWhere('amenities', 'LIKE', $searchTerm)
                  ->orWhere('agent_title', 'LIKE', $searchTerm)
                  ->orWhere('agent_status', 'LIKE', $searchTerm)
                  ->orWhere('slug', 'LIKE', $searchTerm);
            });
        }

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
        
        // Price Range Filter - Handle both price_min/price_max and price=min-max formats
        if (!empty($filters['price'])) {
            $priceRange = explode('-', $filters['price']);
            if (count($priceRange) === 2) {
                $priceMin = (float)trim($priceRange[0]);
                $priceMax = (float)trim($priceRange[1]);
                
                $query->where('amount', '>=', $priceMin)
                      ->where('amount', '<=', $priceMax);
            }
        } else {
            // Fallback to individual min/max parameters if 'price' parameter is not present
            if (!empty($filters['price_min'])) {
                $query->where('amount', '>=', (float)$filters['price_min']);
            }
            if (!empty($filters['price_max'])) {
                $query->where('amount', '<=', (float)$filters['price_max']);
            }
        }

        // Bedrooms Filter
        if (!empty($filters['bedrooms'])) {
            $query->where('bedroom', (int)$filters['bedrooms']);
        } elseif (!empty($filters['bedrooms_min'])) {
            $query->where('bedroom', '>=', (int)$filters['bedrooms_min']);
        }

        // Bathrooms Filter - Exact match
        if (!empty($filters['bathrooms'])) {
            $query->where('bathroom', (int)$filters['bathrooms']);
        } elseif (!empty($filters['bathrooms_min'])) {
            $query->where('bathroom', '>=', (int)$filters['bathrooms_min']);
        }

        // Parse sqFt range if present
        if (!empty($filters['sqFt'])) {
            $sqFtRange = explode('-', $filters['sqFt']);
            if (count($sqFtRange) === 2) {
                $sqFtMin = (float)trim($sqFtRange[0]);
                $sqFtMax = (float)trim($sqFtRange[1]);
                
                $query->where('sq_ft', '>=', $sqFtMin)
                      ->where('sq_ft', '<=', $sqFtMax);
            }
        }

        // Location Search Filter
        if (!empty($filters['location'])) {
            $searchTerm = '%' . $filters['location'] . '%';
            $query->whereHas('locations', function($q) use ($searchTerm) {
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

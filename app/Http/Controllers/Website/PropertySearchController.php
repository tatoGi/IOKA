<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offplan;
use App\Models\RentalResale;
use App\Models\Developer;
use Illuminate\Support\Facades\DB;

class PropertySearchController extends Controller
{
    public function search(Request $request)
    {

        // Validate request parameters
        $request->validate([
            'type' => 'sometimes|string',
            'location' => 'sometimes|string',
            'sq_ft_min' => 'sometimes|numeric',
            'sq_ft_max' => 'sometimes|numeric',
            'priceMin' => 'sometimes|numeric',
            'priceMax' => 'sometimes|numeric',
            'bathMin' => 'sometimes|integer',
            'bathMax' => 'sometimes|integer',
            'currency' => 'sometimes|in:AED,USD',
            'page' => 'sometimes|integer|min:1'
        ]);
        // Handle comma-separated types or single type
        $types = $request->input('type', 'OFFPLAN');
        $types = is_array($types) ? $types : explode(',', $types);

        // Normalize types to uppercase and remove duplicates
        $types = array_unique(array_map('strtoupper', $types));

        $response = [
            'OFFPLAN' => [],
            'RENTAL' => [],
            'RESALE' => [],
            'developers' => [],
            'all' => [],
            'message' => ''
        ];

        $allResults = collect();
        $developerIds = collect();

        // Get location from either singular or plural parameter
       // Get location from request and convert to array if it's a comma-separated string
            $location = $request->filled('location')
            ? explode(',', $request->location)
            : null;

        // Offplan search
        if (in_array('OFFPLAN', $types)) {
            $offplanQuery = $this->buildOffplanQuery($request, $location);
            $offplanResults = $offplanQuery->get();

            if ($offplanResults->isNotEmpty()) {
                $response['OFFPLAN'] = $offplanResults;
                $allResults = $allResults->merge($offplanResults);

                // Get developers who have matching offplan properties
                $offplanDeveloperIds = DB::table('developer_offplan')
                    ->whereIn('offplan_id', $offplanResults->pluck('id'))
                    ->pluck('developer_id')
                    ->unique();
                $developerIds = $developerIds->merge($offplanDeveloperIds);
            }
        }

        // Rental search
        if (in_array('RENTAL', $types)) {
            $rentalQuery = $this->buildRentalResaleQuery($request, 'rental', $location);
            $rentalResults = $rentalQuery->get();

            if ($rentalResults->isNotEmpty()) {
                $response['RENTAL'] = $rentalResults;
                $allResults = $allResults->merge($rentalResults);

                // Get developers who have matching rental properties
                $rentalDeveloperIds = DB::table('developer_rental_resale')
                    ->whereIn('rental_resale_id', $rentalResults->pluck('id'))
                    ->pluck('developer_id')
                    ->unique();
                $developerIds = $developerIds->merge($rentalDeveloperIds);
            }
        }

        // Resale search
        if (in_array('RESALE', $types)) {
            $resaleQuery = $this->buildRentalResaleQuery($request, 'resale', $location);
            $resaleResults = $resaleQuery->get();

            if ($resaleResults->isNotEmpty()) {
                $response['RESALE'] = $resaleResults;
                $allResults = $allResults->merge($resaleResults);

                // Get developers who have matching resale properties
                $resaleDeveloperIds = DB::table('developer_rental_resale')
                    ->whereIn('rental_resale_id', $resaleResults->pluck('id'))
                    ->pluck('developer_id')
                    ->unique();
                $developerIds = $developerIds->merge($resaleDeveloperIds);
            }
        }

        // Get unique developers with their relationships
        if ($developerIds->isNotEmpty()) {
            $response['developers'] = Developer::with([
                'offplanListings' => function($query) use ($types, $request, $location) {
                    if (in_array('OFFPLAN', $types)) {
                        $this->applyOffplanFilters($query, $request, $location)
                            ->with('locations');
                    }
                },
                'rentalResaleListings' => function($query) use ($types, $request, $location) {
                    $query->where(function($q) use ($types) {
                        if (in_array('RENTAL', $types)) {
                            $q->orWhereJsonContains('tags', '5');
                        }
                        if (in_array('RESALE', $types)) {
                            $q->orWhereJsonContains('tags', '6');
                        }
                    });
                    $this->applyRentalResaleFilters($query, $request, $location)
                        ->with('locations', 'amount');
                }
            ])->whereIn('id', $developerIds->unique())->get();
        }

        // Return empty response if no results
        if ($allResults->isEmpty()) {
            $response['message'] = 'No properties found matching your criteria';
            return response()->json(['data' => $response]);
        }

        // Paginate combined results
        $page = $request->input('page', 1);
        $perPage = 10;
        $paginatedResults = new \Illuminate\Pagination\LengthAwarePaginator(
            $allResults->forPage($page, $perPage),
            $allResults->count(),
            $perPage,
            $page,
            ['path' => $request->url()]
        );

        $response['all'] = $paginatedResults;
        return response()->json(['data' => $response]);
    }

    private function buildOffplanQuery(Request $request, $location = null)
    {
        return $this->applyOffplanFilters(Offplan::query(), $request, $location)
            ->with(['locations', 'developer']);
    }

    private function buildRentalResaleQuery(Request $request, $type, $location = null)
    {
        $query = RentalResale::query()
            ->whereJsonContains('tags', $type === 'rental' ? '5' : '6');

        return $this->applyRentalResaleFilters($query, $request, $location)
            ->with(['locations', 'developer', 'amount']);
    }

    private function applyOffplanFilters($query, Request $request, $location = null)
    {
        $matchAnyFilter = $request->input('matchAnyFilter', false);

        if ($matchAnyFilter) {
            // Use OR conditions for filters
            $query->where(function($q) use ($request, $location) {
                // Location filter
                if ($location) {
                    $q->orWhereHas('locations', function($q) use ($location) {
                        $q->whereIn('locations.id', $location);
                    });
                }

                // Size filter
                if ($request->filled('sizeMin')) {
                    $q->orWhere('sq_ft', '>=', (float)$request->sizeMin);
                }
                if ($request->filled('sizeMax')) {
                    $q->orWhere('sq_ft', '<=', (float)$request->sizeMax);
                }

                // Price filter
                $currency = $request->input('currency', 'USD');
                if ($currency === 'AED') {
                    if ($request->filled('priceMin')) {
                        $q->orWhere('amount_dirhams', '>=', (float)$request->priceMin);
                    }
                    if ($request->filled('priceMax')) {
                        $q->orWhere('amount_dirhams', '<=', (float)$request->priceMax);
                    }
                } else {
                    if ($request->filled('priceMin')) {
                        $q->orWhere('amount', '>=', (float)$request->priceMin);
                    }
                    if ($request->filled('priceMax')) {
                        $q->orWhere('amount', '<=', (float)$request->priceMax);
                    }
                }

                // Bedrooms filter
                if ($request->filled('bathMin')) {
                    $q->orWhere('bedroom', '>=', (int)$request->bathMin);
                }
                if ($request->filled('bathMax')) {
                    $q->orWhere('bedroom', '<=', (int)$request->bathMax);
                }
            });
        } else {
            // Use AND conditions for filters (original logic)
            // Location filter
            if ($location) {
                $query->whereHas('locations', function($q) use ($location) {
                    $q->whereIn('locations.id', $location);
                });
            }

            // Size filter
            if ($request->filled('sizeMin')) {
                $query->where('sq_ft', '>=', (float)$request->sizeMin);
            }
            if ($request->filled('sizeMax')) {
                $query->where('sq_ft', '<=', (float)$request->sizeMax);
            }

            // Price filter
            $currency = $request->input('currency', 'USD');
            if ($currency === 'AED') {
                if ($request->filled('priceMin')) {
                    $query->where('amount_dirhams', '>=', (float)$request->priceMin);
                }
                if ($request->filled('priceMax')) {
                    $query->where('amount_dirhams', '<=', (float)$request->priceMax);
                }
            } else {
                if ($request->filled('priceMin')) {
                    $query->where('amount', '>=', (float)$request->priceMin);
                }
                if ($request->filled('priceMax')) {
                    $query->where('amount', '<=', (float)$request->priceMax);
                }
            }

            // Bedrooms filter
            if ($request->filled('bathMin')) {
                $query->where('bedroom', '>=', (int)$request->bathMin);
            }
            if ($request->filled('bathMax')) {
                $query->where('bedroom', '<=', (int)$request->bathMax);
            }
        }

        return $query;
    }

    private function applyRentalResaleFilters($query, Request $request, $location = null)
    {
        $matchAnyFilter = $request->input('matchAnyFilter', false);

        if ($matchAnyFilter) {
            // Use OR conditions for filters
            $query->where(function($q) use ($request, $location) {
                // Location filter
                if ($location) {
                    $q->orWhereHas('locations', function($q) use ($location) {
                        $q->whereIn('locations.id', $location);
                    });
                }

                // Size filter
                if ($request->filled('sizeMin')) {
                    $q->orWhere('sq_ft', '>=', (float)$request->sizeMin);
                }
                if ($request->filled('sizeMax')) {
                    $q->orWhere('sq_ft', '<=', (float)$request->sizeMax);
                }

                // Price filter
                $currency = $request->input('currency', 'USD');
                if ($currency === 'AED') {
                    if ($request->filled('priceMin')) {
                        $q->orWhereHas('amount', function($q) use ($request) {
                            $q->where('amount_dirhams', '>=', (float)$request->priceMin);
                        });
                    }
                    if ($request->filled('priceMax')) {
                        $q->orWhereHas('amount', function($q) use ($request) {
                            $q->where('amount_dirhams', '<=', (float)$request->priceMax);
                        });
                    }
                } else {
                    if ($request->filled('priceMin')) {
                        $q->orWhereHas('amount', function($q) use ($request) {
                            $q->where('amount', '>=', (float)$request->priceMin);
                        });
                    }
                    if ($request->filled('priceMax')) {
                        $q->orWhereHas('amount', function($q) use ($request) {
                            $q->where('amount', '<=', (float)$request->priceMax);
                        });
                    }
                }

                // Bedrooms filter
                if ($request->filled('bathMin')) {
                    $q->orWhere('bedroom', '>=', (int)$request->bathMin);
                }
                if ($request->filled('bathMax')) {
                    $q->orWhere('bedroom', '<=', (int)$request->bathMax);
                }
            });
        } else {
            // Use AND conditions for filters (original logic)
            // ... (keep the existing AND logic here)
        }

        return $query;
    }
}

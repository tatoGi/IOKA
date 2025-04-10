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
        // Handle comma-separated types or single type
        $types = $request->input('type', 'OFFPLAN');
        $types = is_array($types) ? $types : explode(',', $types);

        // Normalize types to uppercase and remove duplicates
        $types = array_unique(array_map('strtoupper', $types));

        $response = [
            'OFFPLAN' => null,
            'RENTAL' => null,
            'RESALE' => null,
            'developers' => [],
            'all' => []
        ];

        $allResults = collect();
        $developerIds = collect();

        if (in_array('OFFPLAN', $types)) {
            $offplanQuery = $this->buildOffplanQuery($request);
            $offplanResults = $offplanQuery->get();
            $response['OFFPLAN'] = $offplanResults;
            $allResults = $allResults->merge($offplanResults);

            // Get developers who have offplan properties matching the filters
            $offplanDeveloperIds = DB::table('developer_offplan')
                ->whereIn('offplan_id', $offplanResults->pluck('id'))
                ->pluck('developer_id')
                ->unique();

            $developerIds = $developerIds->merge($offplanDeveloperIds);
        }

        if (in_array('RENTAL', $types)) {
            $rentalQuery = $this->buildRentalResaleQuery($request, 'rental');
            $rentalResults = $rentalQuery->get();
            $response['RENTAL'] = $rentalResults;
            $allResults = $allResults->merge($rentalResults);

            // Get developers who have rental properties matching the filters
            $rentalDeveloperIds = DB::table('developer_rental_resale')
                ->whereIn('rental_resale_id', $rentalResults->pluck('id'))
                ->pluck('developer_id')
                ->unique();
            $developerIds = $developerIds->merge($rentalDeveloperIds);
            $developer = Developer::whereIn('id', $developerIds)->first();
        }

        if (in_array('RESALE', $types)) {
            $resaleQuery = $this->buildRentalResaleQuery($request, 'resale');
            $resaleResults = $resaleQuery->get();
            $response['RESALE'] = $resaleResults;
            $allResults = $allResults->merge($resaleResults);

            // Get developers who have resale properties matching the filters
            $resaleDeveloperIds = DB::table('developer_rental_resale')
                ->whereIn('rental_resale_id', $resaleResults->pluck('id'))
                ->pluck('developer_id')
                ->unique();
            $developerIds = $developerIds->merge($resaleDeveloperIds);
            $developer = Developer::whereIn('id', $developerIds)->first();
        }

        // Get unique developers with their relationships
        if ($developerIds->isNotEmpty()) {
            $response['developers'] = Developer::with([
                'offplanListings' => function($query) use ($types) {
                    if (in_array('OFFPLAN', $types)) {
                        $query->with('locations');
                    }
                },
                'rentalResaleListings' => function($query) use ($types) {
                    $query->where(function($q) use ($types) {
                        if (in_array('RENTAL', $types)) {
                            $q->orWhereJsonContains('tags', '5');
                        }
                        if (in_array('RESALE', $types)) {
                            $q->orWhereJsonContains('tags', '6');
                        }
                    })->with('locations', 'amount');
                }
            ])->whereIn('id', $developerIds->unique())->get();
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

    private function buildOffplanQuery(Request $request)
    {
        $query = Offplan::query();

        // Location filter (optional)
        if ($request->filled('location')) {
            $location = trim($request->location);
            $query->where(function($q) use ($location) {
                $q->where('location', 'like', $location . '%')
                  ->orWhere('location', 'like', '%' . $location . '%')
                  ->orWhereHas('locations', function($q) use ($location) {
                      $q->where('title', 'like', $location . '%')
                        ->orWhere('title', 'like', '%' . $location . '%');
                  });
            });
        }

        // Size filter (optional)
        if ($request->filled('sizeMin')) {
            $query->where('sq_ft', '>=', (float)$request->sizeMin);
        }
        if ($request->filled('sizeMax')) {
            $query->where('sq_ft', '<=', (float)$request->sizeMax);
        }

        // Price filter (optional)
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

        // Bedrooms filter (optional)
        if ($request->filled('bathMin')) {
            $query->where('bedroom', '>=', (int)$request->bathMin);
        }
        if ($request->filled('bathMax')) {
            $query->where('bedroom', '<=', (int)$request->bathMax);
        }

        return $query->with(['locations', 'developer']);
    }

    private function buildRentalResaleQuery(Request $request, $type)
    {
        $query = RentalResale::query();

        // Always filter by type
        $query->whereJsonContains('tags', $type === 'rental' ? '5' : '6');

        // Location filter (optional)
        if ($request->filled('location')) {
            $location = trim($request->location);
            $query->whereHas('locations', function($q) use ($location) {
                $q->where('title', 'like', $location . '%')
                  ->orWhere('title', 'like', '%' . $location . '%');
            });
        }

        // Size filter (optional)
        if ($request->filled('sizeMin')) {
            $query->where('sq_ft', '>=', (float)$request->sizeMin);
        }
        if ($request->filled('sizeMax')) {
            $query->where('sq_ft', '<=', (float)$request->sizeMax);
        }

        // Price filter (optional)
        $currency = $request->input('currency', 'USD');
        if ($currency === 'AED') {
            if ($request->filled('priceMin')) {
                $query->whereHas('amount', function($q) use ($request) {
                    $q->where('amount_dirhams', '>=', (float)$request->priceMin);
                });
            }
            if ($request->filled('priceMax')) {
                $query->whereHas('amount', function($q) use ($request) {
                    $q->where('amount_dirhams', '<=', (float)$request->priceMax);
                });
            }
        } else {
            if ($request->filled('priceMin')) {
                $query->whereHas('amount', function($q) use ($request) {
                    $q->where('amount', '>=', (float)$request->priceMin);
                });
            }
            if ($request->filled('priceMax')) {
                $query->whereHas('amount', function($q) use ($request) {
                    $q->where('amount', '<=', (float)$request->priceMax);
                });
            }
        }

        // Bedrooms filter (optional)
        if ($request->filled('bathMin')) {
            $query->where('bedroom', '>=', (int)$request->bathMin);
        }
        if ($request->filled('bathMax')) {
            $query->where('bedroom', '<=', (int)$request->bathMax);
        }

        return $query->with(['locations', 'developer', 'amount']);
    }
}

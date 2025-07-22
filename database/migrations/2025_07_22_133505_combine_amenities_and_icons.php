<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update the amenities column to store both name and icon
        Schema::table('offplans', function (Blueprint $table) {
            // Convert existing amenities to the new format
            $offplans = DB::table('offplans')->get();
            
            foreach ($offplans as $offplan) {
                $amenities = json_decode($offplan->amenities, true) ?? [];
                $amenitiesIcons = json_decode($offplan->amenities_icons, true) ?? [];
                
                $combinedAmenities = [];
                
                // Combine amenities and their icons
                foreach ($amenities as $index => $name) {
                    $icon = $amenitiesIcons[$index]['icon'] ?? '';
                    $combinedAmenities[] = [
                        'name' => $name,
                        'icon' => $icon
                    ];
                }
                
                // Update the amenities column with the combined data
                DB::table('offplans')
                    ->where('id', $offplan->id)
                    ->update([
                        'amenities' => json_encode($combinedAmenities)
                    ]);
            }
            
            // Drop the amenities_icons column
            $table->dropColumn('amenities_icons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offplans', function (Blueprint $table) {
            // Add the amenities_icons column back
            $table->json('amenities_icons')->nullable()->after('amenities');
            
            // Convert the data back to the old format
            $offplans = DB::table('offplans')->get();
            
            foreach ($offplans as $offplan) {
                $amenities = json_decode($offplan->amenities, true) ?? [];
                $amenityNames = [];
                $amenityIcons = [];
                
                // Split the combined data back into separate arrays
                foreach ($amenities as $amenity) {
                    if (is_array($amenity)) {
                        $amenityNames[] = $amenity['name'] ?? '';
                        $amenityIcons[] = [
                            'name' => $amenity['name'] ?? '',
                            'icon' => $amenity['icon'] ?? ''
                        ];
                    } else {
                        $amenityNames[] = $amenity;
                        $amenityIcons[] = [
                            'name' => $amenity,
                            'icon' => ''
                        ];
                    }
                }
                
                // Update the columns with the split data
                DB::table('offplans')
                    ->where('id', $offplan->id)
                    ->update([
                        'amenities' => json_encode($amenityNames),
                        'amenities_icons' => json_encode($amenityIcons)
                    ]);
            }
        });
    }
};

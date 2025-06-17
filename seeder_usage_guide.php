<?php

/**
 * Simple Guide: How to Use OffplanSeeder and Remove Data
 *
 * Step-by-step instructions for using the enhanced OffplanSeeder
 */

require_once 'vendor/autoload.php';

use Database\Seeders\OffplanSeeder;
use App\Models\Offplan;

// ============================================================================
// STEP 1: CREATE 10K RECORDS (RUN THE SEEDER)
// ============================================================================

echo "=== STEP 1: Creating 10,000 offplan records ===\n";

// Method 1: Using Artisan Command (Recommended)
echo "Run this command in your terminal:\n";
echo "php artisan db:seed --class=OffplanSeeder\n\n";

// Method 2: Using the seeder directly in code
$seeder = new OffplanSeeder();
$seeder->run();

echo "✅ 10,000 offplan records created successfully!\n\n";

// ============================================================================
// STEP 2: VERIFY THE RECORDS WERE CREATED
// ============================================================================

echo "=== STEP 2: Verifying records ===\n";
$totalRecords = Offplan::count();
echo "Total offplans in database: {$totalRecords}\n";

// Show some sample records
$sampleRecords = Offplan::limit(3)->get();
echo "Sample records:\n";
foreach ($sampleRecords as $record) {
    echo "- {$record->title} ({$record->property_type}) - {$record->amount}\n";
}
echo "\n";

// ============================================================================
// STEP 3: REMOVE ALL 10K RECORDS
// ============================================================================

echo "=== STEP 3: Removing all 10k records ===\n";

// Method 1: Remove all records at once
$seeder->removeSeederRecords();

// Method 2: Remove specific count
// $seeder->removeSpecificCount(1000); // Remove first 1000

// Method 3: Remove by criteria
// $seeder->removeByCriteria(['property_type' => 'Apartment']); // Remove all apartments

echo "✅ All offplan records removed successfully!\n\n";

// ============================================================================
// STEP 4: VERIFY RECORDS WERE REMOVED
// ============================================================================

echo "=== STEP 4: Verifying removal ===\n";
$remainingRecords = Offplan::count();
echo "Remaining offplans in database: {$remainingRecords}\n";

if ($remainingRecords == 0) {
    echo "✅ All records successfully removed!\n";
} else {
    echo "⚠️  Some records still remain\n";
}

echo "\n=== Process Complete ===\n";

/**
 * QUICK COMMAND REFERENCE:
 *
 * 1. Create 10k records:
 *    php artisan db:seed --class=OffplanSeeder
 *
 * 2. Remove all records (using Artisan):
 *    php artisan tinker
 *    >>> $seeder = new Database\Seeders\OffplanSeeder();
 *    >>> $seeder->removeSeederRecords();
 *
 * 3. Check record count:
 *    php artisan tinker
 *    >>> App\Models\Offplan::count();
 *
 * 4. View sample records:
 *    php artisan tinker
 *    >>> App\Models\Offplan::limit(5)->get();
 */

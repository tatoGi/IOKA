<?php

/**
 * Quick Test for Fixed Seeders
 */

require_once 'vendor/autoload.php';

use Database\Seeders\DeveloperSeeder;
use Database\Seeders\RentalResaleSeeder;

echo "=== Quick Test for Fixed Seeders ===\n\n";

// Test Developer Seeder
echo "Testing Developer Seeder...\n";
try {
    $developerSeeder = new DeveloperSeeder();
    $developerSeeder->setCount(5);
    $developerSeeder->run();
    echo "✅ Developer seeder works!\n";
    $developerSeeder->removeSeederRecords();
    echo "✅ Developer removal works!\n\n";
} catch (Exception $e) {
    echo "❌ Developer seeder error: " . $e->getMessage() . "\n\n";
}

// Test Rental Resale Seeder
echo "Testing Rental Resale Seeder...\n";
try {
    $rentalSeeder = new RentalResaleSeeder();
    $rentalSeeder->setCount(5);
    $rentalSeeder->run();
    echo "✅ Rental Resale seeder works!\n";
    $rentalSeeder->removeSeederRecords();
    echo "✅ Rental Resale removal works!\n\n";
} catch (Exception $e) {
    echo "❌ Rental Resale seeder error: " . $e->getMessage() . "\n\n";
}

echo "=== Test Complete ===\n";

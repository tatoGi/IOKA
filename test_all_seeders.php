<?php

/**
 * Test All 10k Seeders
 *
 * This script tests all the enhanced seeders to ensure they work correctly
 */

require_once 'vendor/autoload.php';

use Database\Seeders\BlogPostSeeder;
use Database\Seeders\DeveloperSeeder;
use Database\Seeders\RentalResaleSeeder;
use Database\Seeders\OffplanSeeder;

echo "=== Testing All 10k Seeders ===\n\n";

$seeders = [
    'Blog Posts' => new BlogPostSeeder(),
    'Developers' => new DeveloperSeeder(),
    'Rental Resale' => new RentalResaleSeeder(),
    'Offplans' => new OffplanSeeder(),
];

foreach ($seeders as $name => $seeder) {
    echo "Testing {$name} Seeder...\n";

    try {
        // Test with 10 records first
        $seeder->setCount(10);
        $seeder->run();

        echo "✅ {$name} seeder works correctly!\n";

        // Remove test records
        $seeder->removeSeederRecords();
        echo "✅ {$name} removal works correctly!\n\n";

    } catch (Exception $e) {
        echo "❌ Error with {$name} seeder: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "\n\n";
    }
}

echo "=== All Tests Complete ===\n";
echo "If all tests passed, you can now use:\n";
echo "php artisan bulk:seed blog --count=10000\n";
echo "php artisan bulk:seed developer --count=10000\n";
echo "php artisan bulk:seed rental --count=10000\n";
echo "php artisan bulk:seed offplan --count=10000\n";
echo "php artisan bulk:seed all --count=10000\n";

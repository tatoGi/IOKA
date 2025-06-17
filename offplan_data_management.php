<?php

/**
 * Offplan Data Management Examples
 *
 * This file shows how to use the enhanced OffplanSeeder to manage your data
 */

require_once 'vendor/autoload.php';

use Database\Seeders\OffplanSeeder;
use App\Models\Offplan;

// Initialize the seeder
$seeder = new OffplanSeeder();

// ============================================================================
// EXAMPLE 1: Remove all 10k seeder records
// ============================================================================
echo "=== Removing all seeder records ===\n";
$seeder->removeSeederRecords();

// ============================================================================
// EXAMPLE 2: Set your own custom data (replaces all existing data)
// ============================================================================
echo "\n=== Setting custom data ===\n";

$myCustomData = [
    [
        'title' => 'Luxury Downtown Apartment',
        'subtitle' => 'Premium 2-bedroom apartment in city center',
        'amount' => 750000.00,
        'amount_dirhams' => 2752500.00,
        'description' => 'Beautiful luxury apartment with stunning city views',
        'features' => json_encode(['Balcony', 'Gym', 'Pool', 'Security']),
        'near_by' => json_encode([
            ['title' => 'Dubai Mall', 'distance' => 2.5],
            ['title' => 'Metro Station', 'distance' => 0.8],
        ]),
        'amenities' => json_encode(['Swimming Pool', 'Gym', 'Parking', 'Security']),
        'map_location' => 'Downtown Dubai',
        'property_type' => 'Apartment',
        'bathroom' => 2,
        'bedroom' => 2,
        'garage' => 1,
        'sq_ft' => 1200,
        'qr_title' => 'Scan for Details',
        'qr_text' => 'Scan this QR code to get more information about this property',
        'download_brochure' => 'https://example.com/brochure1.pdf',
        'agent_title' => 'John Smith',
        'agent_status' => 'Available',
        'agent_telephone' => '+971501234567',
        'agent_whatsapp' => '+971501234567',
        'agent_linkedin' => 'https://linkedin.com/in/johnsmith',
        'agent_email' => 'john.smith@example.com',
        'agent_languages' => json_encode(['English', 'Arabic']),
        'location' => 'Dubai',
        'alt_texts' => json_encode([
            'main_photo' => 'Luxury apartment exterior view',
            'banner_photo' => 'Apartment building facade',
            'qr_photo' => 'QR code for property details',
            'agent_image' => 'Professional photo of John Smith',
        ]),
    ],
    [
        'title' => 'Beachfront Villa',
        'subtitle' => 'Exclusive 4-bedroom villa with private beach access',
        'amount' => 2500000.00,
        'amount_dirhams' => 9175000.00,
        'description' => 'Stunning beachfront villa with panoramic sea views',
        'features' => json_encode(['Private Beach', 'Garden', 'Pool', 'BBQ Area']),
        'near_by' => json_encode([
            ['title' => 'Beach', 'distance' => 0.1],
            ['title' => 'Restaurant', 'distance' => 1.2],
        ]),
        'amenities' => json_encode(['Private Pool', 'Garden', 'BBQ Area', 'Beach Access']),
        'map_location' => 'Palm Jumeirah',
        'property_type' => 'Villa',
        'bathroom' => 4,
        'bedroom' => 4,
        'garage' => 3,
        'sq_ft' => 3500,
        'qr_title' => 'Villa Details',
        'qr_text' => 'Get detailed information about this beachfront villa',
        'download_brochure' => 'https://example.com/villa-brochure.pdf',
        'agent_title' => 'Sarah Johnson',
        'agent_status' => 'Online',
        'agent_telephone' => '+971507654321',
        'agent_whatsapp' => '+971507654321',
        'agent_linkedin' => 'https://linkedin.com/in/sarahjohnson',
        'agent_email' => 'sarah.johnson@example.com',
        'agent_languages' => json_encode(['English', 'French']),
        'location' => 'Dubai',
        'alt_texts' => json_encode([
            'main_photo' => 'Beachfront villa exterior',
            'banner_photo' => 'Villa with sea view',
            'qr_photo' => 'QR code for villa information',
            'agent_image' => 'Professional photo of Sarah Johnson',
        ]),
    ],
];

$seeder->setCustomData($myCustomData);

// ============================================================================
// EXAMPLE 3: Add more data (keep existing and add new)
// ============================================================================
echo "\n=== Adding more custom data ===\n";

$additionalData = [
    [
        'title' => 'Modern Studio Apartment',
        'subtitle' => 'Contemporary studio in trendy neighborhood',
        'amount' => 350000.00,
        'amount_dirhams' => 1284500.00,
        'description' => 'Modern studio apartment perfect for young professionals',
        'property_type' => 'Studio',
        'bathroom' => 1,
        'bedroom' => 0,
        'garage' => 1,
        'sq_ft' => 600,
        'location' => 'Dubai Marina',
        // Other fields will use defaults or be auto-generated
    ],
];

$seeder->addCustomData($additionalData);

// ============================================================================
// EXAMPLE 4: Update existing records
// ============================================================================
echo "\n=== Updating existing records ===\n";

// Update all apartments to have a new feature
$seeder->updateWithCustomData(
    ['features' => json_encode(['Balcony', 'Gym', 'Pool', 'Security', 'New Feature'])],
    ['property_type' => 'Apartment']
);

// ============================================================================
// EXAMPLE 5: Remove specific records
// ============================================================================
echo "\n=== Removing specific records ===\n";

// Remove all studios
$seeder->removeByCriteria(['property_type' => 'Studio']);

// Remove first 2 records
$seeder->removeSpecificCount(2);

// ============================================================================
// EXAMPLE 6: Check current data
// ============================================================================
echo "\n=== Current data summary ===\n";
$totalOffplans = Offplan::count();
echo "Total offplans: {$totalOffplans}\n";

$byType = Offplan::selectRaw('property_type, count(*) as count')
    ->groupBy('property_type')
    ->get();

foreach ($byType as $type) {
    echo "{$type->property_type}: {$type->count}\n";
}

echo "\n=== Data management complete ===\n";

/**
 * USAGE SUMMARY:
 *
 * 1. Remove all seeder data:
 *    $seeder->removeSeederRecords();
 *
 * 2. Set your own data (replaces everything):
 *    $seeder->setCustomData($yourDataArray);
 *
 * 3. Add more data (keeps existing):
 *    $seeder->addCustomData($additionalDataArray);
 *
 * 4. Update existing records:
 *    $seeder->updateWithCustomData($updates, $criteria);
 *
 * 5. Remove specific records:
 *    $seeder->removeByCriteria(['property_type' => 'Apartment']);
 *    $seeder->removeSpecificCount(10);
 */

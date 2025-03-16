<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RentalResale;
use App\Models\Amount;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class RentalResaleSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Ensure the storage directories exist
        Storage::disk('public')->makeDirectory('qr_photos');
        Storage::disk('public')->makeDirectory('gallery_images');

        for ($i = 0; $i < 10; $i++) {
            // Generate fake QR photo
            $qrPhoto = $faker->image(storage_path('app/public/qr_photos'), 200, 200, 'business', false);
            $qrPhotoPath = 'qr_photos/' . $qrPhoto;

            // Generate fake gallery images
            $galleryImages = [];
            for ($j = 0; $j < 3; $j++) { // Generate 3 gallery images per record
                $galleryImage = $faker->image(storage_path('app/public/gallery_images'), 800, 600, 'city', false);
                $galleryImages[] = 'gallery_images/' . $galleryImage;
            }

            // Create RentalResale record
            $rentalResale = RentalResale::create([
                'property_type' => $faker->randomElement(['Apartment', 'Villa', 'Townhouse']),
                'title' => $faker->sentence(3),
                'top' => $faker->boolean,
                'bathroom' => $faker->numberBetween(1, 5),
                'bedroom' => $faker->numberBetween(1, 5),
                'sq_ft' => $faker->numberBetween(1000, 5000),
                'garage' => $faker->numberBetween(1, 3),
                'description' => $faker->paragraph,
                'details' => json_encode(['detail1' => $faker->sentence, 'detail2' => $faker->sentence]),
                'amenities' => json_encode(['amenity1' => $faker->word, 'amenity2' => $faker->word]),
                'agent_title' => $faker->name,
                'agent_status' => $faker->randomElement(['Available', 'Busy']),
                'agent_languages' => $faker->randomElement(['English', 'Arabic', 'French']),
                'agent_call' => $faker->phoneNumber,
                'agent_whatsapp' => $faker->phoneNumber,
                'location_link' => $faker->url,
                'qr_photo' => $qrPhotoPath,
                'reference' => $faker->uuid,
                'dld_permit_number' => $faker->uuid,
                'addresses' => json_encode(['address1' => $faker->address, 'address2' => $faker->address]),
                'gallery_images' => json_encode($galleryImages),
                'tags' => json_encode(['tag1' => $faker->word, 'tag2' => $faker->word]),
            ]);

            // Create Amount record
            Amount::create([
                'rental_resale_id' => $rentalResale->id,
                'amount' => $faker->randomFloat(2, 1000, 100000),
                'amount_dirhams' => $faker->randomFloat(2, 1000, 100000),
            ]);
        }
    }
}

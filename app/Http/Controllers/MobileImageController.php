<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MobileImageController extends Controller
{
    /**
     * Handle mobile image upload with server-side optimization
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|string',
            'quality' => 'required|integer|min:10|max:100',
            'maxWidth' => 'required|integer|min:0|max:5000',
        ]);

        try {
            // Get the base64 image data
            $imageData = $request->input('image');
            $quality = (int)$request->input('quality');
            $maxWidth = (int)$request->input('maxWidth');

            // Extract the image data from the base64 string
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $imageType = strtolower($matches[1]);
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);

                if ($imageData === false) {
                    throw new \Exception('Failed to decode image data');
                }
            } else {
                throw new \Exception('Invalid image data format');
            }

            // Create an image from the data
            $manager = new ImageManager(new Driver());
            $img = $manager->read($imageData);

            // Apply server-side optimization
            if ($maxWidth > 0 && $img->width() > $maxWidth) {
                $img = $img->resize($maxWidth);
            }

            // Convert to JPEG with specified quality
            $img = $img->toJpeg($quality);

            // Ensure the uploads directory exists in public/storage
            $uploadDir = public_path('storage/uploads');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate a unique filename
            $filename = 'mobile_' . Str::uuid() . '.jpg';
            $fullPath = $uploadDir . '/' . $filename;
            $relativePath = 'uploads/' . $filename;
            
            // Save the image directly to the public storage path
            file_put_contents($fullPath, (string) $img);
            
            // Create the URL that will be accessible from the browser
            $url = '/storage/' . $relativePath;
            
            // Log the paths for debugging
            Log::info('Generated image URL: ' . $url);
            Log::info('Full image path: ' . $fullPath);
            
            return response()->json([
                'success' => true,
                'url' => $url,
                'path' => $relativePath,
                'message' => 'Image optimized and uploaded successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing image: ' . $e->getMessage()
            ], 422);
        }
    }
}

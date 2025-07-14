<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
            'image' => 'required',
            'quality' => 'required|integer|min:10|max:100',
            'maxWidth' => 'required|integer|min:0|max:5000',
        ]);

        try {
            $quality = (int)$request->input('quality');
            $maxWidth = (int)$request->input('maxWidth');
            $imageData = null;

            // Handle file upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imageData = file_get_contents($file->getRealPath());
            } 
            // Handle base64 string
            elseif (is_string($request->input('image'))) {
                $imageData = $request->input('image');
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                    $imageType = strtolower($matches[1]);
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $imageData = base64_decode($imageData);

                    if ($imageData === false) {
                        throw new \Exception('Failed to decode base64 image data');
                    }
                } else {
                    throw new \Exception('Invalid image data format');
                }
            } else {
                throw new \Exception('No valid image data provided');
            }

            // Create an image from the data
            try {
                // Create image manager with desired driver
                $manager = new ImageManager(new Driver());
                
                // Create image instance
                $image = $manager->read($imageData);
                
                // Resize if needed
                if ($maxWidth > 0) {
                    $image->scaleDown($maxWidth);
                }
                
                // Define the storage path
                $storagePath = 'sections';
                $filename = 'mobile_' . Str::uuid() . '.jpg';
                $relativePath = $storagePath . '/' . $filename;
                $fullPath = storage_path('app/public/' . $relativePath);
                
                // Ensure the directory exists with proper permissions
                $directory = dirname($fullPath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                    chmod($directory, 0755);
                }
                
                // Ensure the directory exists with proper permissions
                $directory = dirname($fullPath);
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Convert the image to a string and save to storage
                $imageData = (string) $image->toJpeg(quality: $quality);
                Storage::disk('public')->put($relativePath, $imageData);
                
                // Get the full path to the saved file
                $savedPath = Storage::disk('public')->path($relativePath);
                
                // Ensure the file has the correct permissions
                chmod($savedPath, 0644);
                
                // Generate the public URL using the Storage facade
                $url = Storage::url($relativePath);
                
                // Log the paths for debugging
                Log::info('Generated image URL: ' . $url);
                Log::info('Full image path: ' . $fullPath);
                
                return response()->json([
                    'success' => true,
                    'url' => $url,
                    'path' => $relativePath,  // Return relative path without storage prefix
                    'message' => 'Image optimized and uploaded successfully'
                ]);
            } catch (\Exception $e) {
                Log::error('Image processing error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process image: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing image: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Handle mobile image deletion
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'image_path' => 'required|string',
        ]);

        try {
            $imagePath = $request->input('image_path');
            
            // Remove the 'storage/' prefix if present to get the correct path
            $storagePath = str_replace('storage/', '', $imagePath);
            
            // Check if file exists
            if (Storage::disk('public')->exists($storagePath)) {
                // Delete the file
                Storage::disk('public')->delete($storagePath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error deleting mobile image: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }
}

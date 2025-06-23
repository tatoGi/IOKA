<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Carbon\Carbon;

class CKEditorController extends Controller
{
    /**
     * Handle CKEditor image uploads
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        if (!$request->hasFile('upload')) {
            return response()->json([
                'error' => [
                    'message' => 'No file was uploaded.'
                ]
            ], 400);
        }

        $file = $request->file('upload');
        $validator = validator()->make(['upload' => $file], [
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => [
                    'message' => 'Invalid file format or size. Please upload an image (jpeg, png, jpg, gif, webp) under 2MB.'
                ]
            ], 422);
        }

        try {
            $fileName = Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store directly in public directory instead of using Laravel's storage
            $uploadPath = public_path('uploads/editor');
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Move the file to the public directory
            $file->move($uploadPath, $fileName);
            
            // Generate a full absolute URL that CKEditor can use to display the image
            $url = url('uploads/editor/' . $fileName);

            return response()->json([
                'uploaded' => true,
                'url' => $url,
                'fileName' => $fileName,
                'default' => $url
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => 'Failed to upload image. Please try again.'
                ]
            ], 500);
        }
    }

    /**
     * Generate a token for CKBox authentication.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateToken()
    {
        // IMPORTANT: Replace with your CKBox credentials from your .env file.
        $environmentId = env('CKBOX_ENVIRONMENT_ID');
        $secretKey = env('CKBOX_SECRET_KEY');

        if (!$environmentId || !$secretKey) {
            return response()->json(['error' => 'CKBox credentials are not configured.'], 500);
        }

        $payload = [
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->addHour()->timestamp, // Token valid for 1 hour
            'aud' => $environmentId,
            'auth' => [
                'ckbox' => [
                    'role' => 'user',
                ]
            ]
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        return response()->json(['token' => $jwt]);
    }

    /**
     * Generate a token for mobile CKBox authentication.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateMobileToken()
    {
        // IMPORTANT: Replace with your CKBox credentials from your .env file.
        $environmentId = env('CKBOX_ENVIRONMENT_ID');
        $secretKey = env('CKBOX_SECRET_KEY');

        if (!$environmentId || !$secretKey) {
            return response()->json(['error' => 'CKBox credentials are not configured.'], 500);
        }

        $payload = [
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->addHour()->timestamp, // Token valid for 1 hour
            'aud' => $environmentId,
            'auth' => [
                'ckbox' => [
                    'role' => 'user',
                ]
            ]
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        return response()->json(['token' => $jwt]);
    }
}

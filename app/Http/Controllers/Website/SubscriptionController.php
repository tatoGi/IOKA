<?php

namespace App\Http\Controllers\WEbsite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $token = Str::random(60);

        $subscriber = Subscriber::create([
            'email' => $request->email,
            'token' => $token,
            'is_active' => false
        ]);

        // Here you would typically send a verification email
        // Mail::to($subscriber->email)->send(new SubscriptionVerification($token));

        return response()->json([
            'success' => true,
            'message' => 'Subscription successful! Please check your email to verify.',
            'data' => $subscriber
        ]);
    }

    public function verify($token)
    {
        $subscriber = Subscriber::where('token', $token)->firstOrFail();

        $subscriber->update([
            'email_verified_at' => now(),
            'token' => null,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully!'
        ]);
    }

    public function unsubscribe($token)
    {
        $subscriber = Subscriber::where('token', $token)->firstOrFail();

        $subscriber->update([
            'is_active' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Unsubscribed successfully!'
        ]);
    }
}

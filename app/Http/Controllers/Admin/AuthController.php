<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Models\AdminLoginActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }
    public function login(AdminLoginRequest $request)
    {
        // Get the validated data
        $validated = $request->validated();

        // Verify the reCAPTCHA response
        $secretKey = env('RECAPTCHA_SECRET');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey
        ]);

        $recaptchaData = $response->json();

        // Log failed login attempt for invalid CAPTCHA
        if (!$recaptchaData['success']) {
            $this->logLoginActivity($validated['name'], 'failed');
            return redirect()->route('admin.login')
                ->withErrors(['g-recaptcha-response' => 'CAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        // Check user credentials
        $user = User::where('name', $validated['name'])->first();

        // Log successful or failed login attempt
        if ($user && Auth::attempt(['name' => $validated['name'], 'password' => $validated['password']])) {
            Auth::logoutOtherDevices($validated['password']);  // Invalidate other devices
            $this->logLoginActivity($validated['name'], 'success');  // Log success
            return redirect()->route('admin.dashboard');
        }

        $this->logLoginActivity($validated['name'], 'failed');  // Log failed login
        return redirect()->route('admin.login')
            ->withErrors(['username' => 'Invalid credentials'])
            ->withInput();
    }

    private function logLoginActivity($username, $status)
    {
        $agent = new Agent();

        AdminLoginActivity::create([
            'admin_username' => $username,
            'ip_address' => Request::ip(),
            'device_details' => $agent->device() . ' - ' . $agent->platform() . ' - ' . $agent->browser(), // More detailed device info
            'status' => $status,
        ]);
    }
}

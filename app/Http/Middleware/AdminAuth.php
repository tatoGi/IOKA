<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is logged in and is an admin
        if (Auth::check()) {
            return $next($request);
        }

        // Redirect to login page if not authenticated or not an admin
        return redirect()->route('admin.login');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginActivity;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function activity()
    {
        $loginActivities = AdminLoginActivity::paginate(10);

        return view('admin.activity', compact('loginActivities'));
    }
}

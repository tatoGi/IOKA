<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLoginActivity extends Model
{
    protected $fillable = [
        'admin_username',
        'ip_address',
        'device_details',
        'login_time',
        'status',
    ];
}
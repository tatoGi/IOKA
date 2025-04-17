<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'content',
        // or if using separate columns:
        // 'privacy_policy', 'cookie_policy', 'terms_agreement'
    ];
}

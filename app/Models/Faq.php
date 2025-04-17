<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs'; // only if your table is singular
    protected $fillable = ['question', 'answer', 'is_active'];
}

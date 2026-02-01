<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'logo',
        'favicon',
        'enable_2fa', // Yeni əlavə edilən sahə
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    // enable_2fa sahəsini boolean (true/false) kimi oxumaq üçün
    protected $casts = [
        'enable_2fa' => 'boolean',
    ];
}

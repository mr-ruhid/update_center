<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    use HasFactory;

    protected $fillable = [
        'version', 'changelog', 'is_active', 'allow_download',
        'has_update_file', 'update_file_path', 'price_update',
        'has_full_file', 'full_file_path', 'price_full',
        'gallery_images'
    ];

    // gallery_images sütununu avtomatik array kimi oxu
    protected $casts = [
        'gallery_images' => 'array',
        'is_active' => 'boolean',
        'allow_download' => 'boolean',
        'has_update_file' => 'boolean',
        'has_full_file' => 'boolean',
    ];
}

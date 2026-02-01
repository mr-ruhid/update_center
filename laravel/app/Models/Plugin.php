<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description', // Yeni əlavə edilən açıqlama sahəsi
        'version',
        'image',
        'file_path',
        'is_free',
        'price',
        'payment_link'
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];
}

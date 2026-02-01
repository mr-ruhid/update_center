<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title_1',
        'hero_title_2',
        'hero_subtext',
        'hero_btn_text',
        'hero_btn_url',
        'hero_gallery'
    ];

    protected $casts = [
        'hero_title_1' => 'array',
        'hero_title_2' => 'array',
        'hero_subtext' => 'array',
        'hero_btn_text' => 'array',
        'hero_gallery' => 'array',
    ];
}

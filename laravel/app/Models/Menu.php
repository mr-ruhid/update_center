<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    // 'key' sütunu bura əlavə edilməli idi
    protected $fillable = ['title', 'url', 'type', 'order', 'key'];

    // Əgər artıq titulları birbaşa Translation modelindən çəkirsinizsə,
    // title sütununu 'array' kimi cast etməyə ehtiyac qalmaya bilər.
    // Amma köhnə datalar varsa, qala bilər.
    protected $casts = [
        'title' => 'array',
    ];
}

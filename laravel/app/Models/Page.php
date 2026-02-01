<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = ['slug', 'title', 'content', 'image'];

    // BU HİSSƏ ÇOX VACİBDİR:
    // Bu, Laravelə deyir ki, bazadakı title və content sütunlarını avtomatik olaraq Array-ə çevir.
    protected $casts = [
        'title' => 'array',
        'content' => 'array',
    ];
}

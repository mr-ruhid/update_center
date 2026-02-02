<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'message', 'url', 'version', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

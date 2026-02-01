<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_1', 'phone_2', 'email_receiver',
        'facebook', 'instagram', 'twitter', 'linkedin',
        'github', 'behance', 'tiktok', 'threads'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'plugin_id', 'amount', 'currency',
        'status', 'payment_method', 'customer_email'
    ];

    public function plugin()
    {
        return $this->belongsTo(Plugin::class);
    }
}

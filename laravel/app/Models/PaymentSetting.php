<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency',
        'currency_symbol',
        'stripe_public_key',
        'stripe_secret_key',
        'bank_account_info',
        'cryptomus_merchant_id',
        'cryptomus_payment_key',
        // YENİ: Statuslar
        'is_cryptomus_active',
        'is_stripe_active',
        'is_bank_active'
    ];

    // Boolean (true/false) kimi oxunması üçün
    protected $casts = [
        'is_cryptomus_active' => 'boolean',
        'is_stripe_active' => 'boolean',
        'is_bank_active' => 'boolean',
    ];
}

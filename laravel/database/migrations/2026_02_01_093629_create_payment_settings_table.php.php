<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();

            // Valyuta
            $table->string('currency')->default('AZN');
            $table->string('currency_symbol')->default('₼');

            // Stripe (Online Ödəniş)
            $table->string('stripe_public_key')->nullable();
            $table->string('stripe_secret_key')->nullable();

            // Bank Transfer
            $table->text('bank_account_info')->nullable(); // Bank adı, hesab nömrəsi və s.

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_settings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->boolean('is_cryptomus_active')->default(false);
            $table->boolean('is_stripe_active')->default(false);
            $table->boolean('is_bank_active')->default(true);
        });
    }

    public function down()
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn(['is_cryptomus_active', 'is_stripe_active', 'is_bank_active']);
        });
    }
};

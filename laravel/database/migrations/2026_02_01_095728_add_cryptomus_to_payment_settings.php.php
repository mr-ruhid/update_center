<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->string('cryptomus_merchant_id')->nullable();
            $table->string('cryptomus_payment_key')->nullable();
        });
    }

    public function down()
    {
        Schema::table('payment_settings', function (Blueprint $table) {
            $table->dropColumn(['cryptomus_merchant_id', 'cryptomus_payment_key']);
        });
    }
};

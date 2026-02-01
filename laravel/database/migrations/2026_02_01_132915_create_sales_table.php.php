<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // Unikal sifariş nömrəsi
            $table->foreignId('plugin_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10);
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->string('payment_method')->default('cryptomus');
            $table->string('customer_email')->nullable(); // Əgər login varsa user_id, yoxdursa email
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};

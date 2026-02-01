<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // Email təkrarlana bilməz
            $table->string('ip_address')->nullable(); // Hansı IP-dən qoşulub
            $table->boolean('is_active')->default(true); // Aktivlik statusu
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscribers');
    }
};

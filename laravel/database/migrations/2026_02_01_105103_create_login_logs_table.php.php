<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable(); // Brauzer/Cihaz
            $table->timestamp('login_at'); // Giriş vaxtı
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_logs');
    }
};

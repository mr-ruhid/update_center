<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->json('title'); // Çoxdilli başlıq ({"az": "Ana Səhifə", ...})
            $table->string('url');
            $table->string('type')->default('custom'); // 'page' və ya 'custom'
            $table->integer('order')->default(0); // Sıralama
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};

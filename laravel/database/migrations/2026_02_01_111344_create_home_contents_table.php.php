<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('home_contents', function (Blueprint $table) {
            $table->id();
            // 3 Hissəli Mətn (Çoxdilli JSON)
            $table->json('hero_title_1')->nullable();       // Başlıq 1 (Məs: RJ Pos Updater)
            $table->json('hero_title_2')->nullable();       // Başlıq 2 (Məs: Sistem İdarəetməsi)
            $table->json('hero_subtext')->nullable();       // Alt Mətn (Description)

            // Düymə (Çoxdilli Text + Link)
            $table->json('hero_btn_text')->nullable();
            $table->string('hero_btn_url')->nullable();

            // Qalereya
            $table->json('hero_gallery')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('home_contents');
    }
};

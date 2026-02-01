<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();
            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('email_receiver')->nullable(); // Mesajın gedəcəyi email

            // Sosial Media
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable(); // X.com
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->string('behance')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('threads')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_settings');
    }
};

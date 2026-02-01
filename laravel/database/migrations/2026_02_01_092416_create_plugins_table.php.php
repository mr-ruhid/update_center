<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('version');
            $table->string('image')->nullable();
            $table->string('file_path'); // Plugin faylı (.zip)

            // Ödəniş Sistemi
            $table->boolean('is_free')->default(true); // Pulsuz?
            $table->decimal('price', 10, 2)->nullable(); // Qiymət
            $table->string('payment_link')->nullable(); // Ödəniş linki (Stripe, Bank və s.)

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plugins');
    }
};

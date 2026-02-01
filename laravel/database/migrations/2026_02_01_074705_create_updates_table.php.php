<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('updates', function (Blueprint $table) {
            $table->id();
            $table->string('version'); // v1.2.0
            $table->text('changelog')->nullable(); // Yeniliklər mətni

            // Konfiqurasiya
            $table->boolean('is_active')->default(true); // Saytda görünsünmü?
            $table->boolean('allow_download')->default(true); // Yükləmə aktivdirmi?

            // Update Paketi (Mövcud müştərilər üçün)
            $table->boolean('has_update_file')->default(false);
            $table->string('update_file_path')->nullable();
            $table->decimal('price_update', 10, 2)->nullable(); // Update qiyməti

            // Full Paket (Yeni müştərilər üçün)
            $table->boolean('has_full_file')->default(false);
            $table->string('full_file_path')->nullable();
            $table->decimal('price_full', 10, 2)->nullable(); // Tam qiymət

            // Qalereya
            $table->json('gallery_images')->nullable(); // Şəkillərin yolları (Array)

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('updates');
    }
};

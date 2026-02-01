<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('translations', function (Blueprint $table) {
            // Add the 'tr' column after 'ru'
            if (!Schema::hasColumn('translations', 'tr')) {
                $table->text('tr')->nullable()->after('ru');
            }
        });
    }

    public function down()
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->dropColumn('tr');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('plugins', function (Blueprint $table) {
            // Name sütunundan sonra description sütunu artırırıq
            $table->text('description')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Daxil ediləcək menyu elementləri və düzgün tərcümələr
        $menus = [
            [
                'key' => 'menu_home',
                'title' => json_encode(['az' => 'Ana Səhifə', 'en' => 'Home', 'ru' => 'Главная', 'tr' => 'Ana Sayfa']),
                'url' => '/',
                'type' => 'page',
                'order' => 1
            ],
            [
                'key' => 'menu_updates',
                'title' => json_encode(['az' => 'Yeniliklər', 'en' => 'Updates', 'ru' => 'Обновления', 'tr' => 'Güncellemeler']),
                'url' => '/updates',
                'type' => 'page',
                'order' => 2
            ],
            [
                'key' => 'menu_plugins',
                'title' => json_encode(['az' => 'Pluginlər', 'en' => 'Plugins', 'ru' => 'Плагины', 'tr' => 'Eklentiler']),
                'url' => '/plugins',
                'type' => 'page',
                'order' => 3
            ],
            [
                'key' => 'menu_about',
                'title' => json_encode(['az' => 'Haqqımızda', 'en' => 'About', 'ru' => 'О нас', 'tr' => 'Hakkımızda']),
                'url' => '/about',
                'type' => 'page',
                'order' => 4
            ],
            [
                'key' => 'menu_contact',
                'title' => json_encode(['az' => 'Əlaqə', 'en' => 'Contact', 'ru' => 'Контакты', 'tr' => 'İletişim']),
                'url' => '/contact',
                'type' => 'page',
                'order' => 5
            ],
        ];

        foreach ($menus as $menu) {
            // 1. Menyu cədvəlini yenilə və ya əlavə et (updateOrInsert)
            DB::table('menus')->updateOrInsert(
                ['key' => $menu['key']], // Bu key varsa yenilə, yoxdursa yarat
                [
                    'url' => $menu['url'],
                    'type' => $menu['type'],
                    'order' => $menu['order'],
                    'title' => $menu['title'],
                    'updated_at' => now(),
                    // created_at üçün insert zamanı baxmaq lazımdır, amma updateOrInsert bunu avtomatik etmir.
                    // Əgər tamamilə yeni yazılırsa created_at null ola bilər, ona görə aşağıdakı kimi idarə edə bilərik:
                ]
            );

            // 2. Translations cədvəlini MƏCBURİ yenilə (Səhv tərcümələri düzəltmək üçün)
            $titles = json_decode($menu['title'], true);

            $translation = DB::table('translations')->where('key', $menu['key'])->first();

            if ($translation) {
                // Əgər varsa, düzgün dillərlə yeniləyirik
                DB::table('translations')->where('key', $menu['key'])->update([
                    'az' => $titles['az'],
                    'en' => $titles['en'],
                    'ru' => $titles['ru'],
                    'tr' => $titles['tr'],
                    'updated_at' => now(),
                ]);
            } else {
                // Yoxdursa yaradırıq
                DB::table('translations')->insert([
                    'key' => $menu['key'],
                    'az' => $titles['az'],
                    'en' => $titles['en'],
                    'ru' => $titles['ru'],
                    'tr' => $titles['tr'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $keys = ['menu_home', 'menu_updates', 'menu_plugins', 'menu_about', 'menu_contact'];
        DB::table('menus')->whereIn('key', $keys)->delete();
        DB::table('translations')->whereIn('key', $keys)->delete();
    }
};

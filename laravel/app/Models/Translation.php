<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Translation extends Model
{
    use HasFactory;

    // Bazaya yazıla biləcək sahələr
    protected $fillable = ['key', 'az', 'en', 'ru', 'tr'];

    /**
     * Verilmiş açar sözə (key) görə cari dildəki tərcüməni gətirir.
     * İstifadəsi: \App\Models\Translation::get('about_subtitle')
     */
    public static function get($key, $default = null)
    {
        // Cari dili alırıq (az, en, ru, tr)
        $locale = App::getLocale();

        // Bazadan həmin açarı axtarırıq
        $translation = self::where('key', $key)->first();

        if ($translation) {
            // Əgər həmin dildə tərcümə varsa onu qaytar, yoxdursa ana dili (az) qaytar
            return $translation->{$locale} ?? $translation->az ?? ($default ?? $key);
        }

        // Bazada tapılmasa, Laravel-in standart lang fayllarına bax, o da yoxdursa key-in özünü qaytar
        return __($key) !== $key ? __($key) : ($default ?? $key);
    }
}

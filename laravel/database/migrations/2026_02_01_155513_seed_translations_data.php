<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $translations = [
            // --- GLOBAL (Ümumi) ---
            [
                'key' => 'global_free',
                'az' => 'PULSUZ',
                'ru' => 'БЕСПЛАТНО',
                'en' => 'FREE',
                'tr' => 'ÜCRETSİZ'
            ],
            [
                'key' => 'global_read_more',
                'az' => 'Ətraflı Bax',
                'ru' => 'Подробнее',
                'en' => 'Read More',
                'tr' => 'Daha Fazla'
            ],
            [
                'key' => 'global_download',
                'az' => 'Yüklə',
                'ru' => 'Скачать',
                'en' => 'Download',
                'tr' => 'İndir'
            ],
            [
                'key' => 'global_buy',
                'az' => 'Satın Al',
                'ru' => 'Купить',
                'en' => 'Buy',
                'tr' => 'Satın Al'
            ],
            [
                'key' => 'global_loading',
                'az' => 'Yüklənir...',
                'ru' => 'Загрузка...',
                'en' => 'Loading...',
                'tr' => 'Yükleniyor...'
            ],
            [
                'key' => 'global_started',
                'az' => 'Başladı',
                'ru' => 'Началось',
                'en' => 'Started',
                'tr' => 'Başladı'
            ],
            [
                'key' => 'global_full_version',
                'az' => 'Tam Versiya',
                'ru' => 'Полная версия',
                'en' => 'Full Version',
                'tr' => 'Tam Sürüm'
            ],
            [
                'key' => 'global_update_package',
                'az' => 'Update Paketi',
                'ru' => 'Пакет обновлений',
                'en' => 'Update Package',
                'tr' => 'Güncelleme Paketi'
            ],

            // --- HOME (Ana Səhifə) ---
            [
                'key' => 'home_page_title',
                'az' => 'Ana Səhifə',
                'ru' => 'Главная',
                'en' => 'Home Page',
                'tr' => 'Ana Sayfa'
            ],
            [
                'key' => 'hero_default_title_1',
                'az' => 'RJ Pos Updater',
                'ru' => 'RJ Pos Updater',
                'en' => 'RJ Pos Updater',
                'tr' => 'RJ Pos Updater'
            ],
            [
                'key' => 'hero_default_title_2',
                'az' => 'Sistem İdarəetməsi',
                'ru' => 'Управление системой',
                'en' => 'System Management',
                'tr' => 'Sistem Yönetimi'
            ],
            [
                'key' => 'hero_default_subtext',
                'az' => 'Sistem haqqında məlumat...',
                'ru' => 'Информация о системе...',
                'en' => 'Information about the system...',
                'tr' => 'Sistem hakkında bilgi...'
            ],
            [
                'key' => 'hero_default_btn_text',
                'az' => 'Başla',
                'ru' => 'Начать',
                'en' => 'Get Started',
                'tr' => 'Başla'
            ],
            [
                'key' => 'home_latest_update_heading',
                'az' => 'Son Sistem Yeniləməsi',
                'ru' => 'Последнее обновление',
                'en' => 'Latest System Update',
                'tr' => 'Son Sistem Güncellemesi'
            ],
            [
                'key' => 'home_download_disabled',
                'az' => 'Yükləmə Deaktivdir',
                'ru' => 'Загрузка отключена',
                'en' => 'Download Disabled',
                'tr' => 'İndirme Devre Dışı'
            ],
            [
                'key' => 'home_no_updates_found',
                'az' => 'Hələ heç bir yenilik yoxdur.',
                'ru' => 'Обновлений пока нет.',
                'en' => 'No updates yet.',
                'tr' => 'Henüz güncelleme yok.'
            ],
            [
                'key' => 'home_popular_plugins_heading',
                'az' => 'Populyar Pluginlər',
                'ru' => 'Популярные плагины',
                'en' => 'Popular Plugins',
                'tr' => 'Popüler Eklentiler'
            ],
            [
                'key' => 'home_no_plugins_found',
                'az' => 'Plugin tapılmadı.',
                'ru' => 'Плагины не найдены.',
                'en' => 'No plugins found.',
                'tr' => 'Eklenti bulunamadı.'
            ],

            // --- UPDATES (Yeniliklər Səhifəsi) ---
            [
                'key' => 'updates_page_title',
                'az' => 'Yeniliklər',
                'ru' => 'Обновления',
                'en' => 'Updates',
                'tr' => 'Güncellemeler'
            ],
            [
                'key' => 'updates_hero_title',
                'az' => 'Yeniliklər',
                'ru' => 'Обновления',
                'en' => 'Updates',
                'tr' => 'Güncellemeler'
            ],
            [
                'key' => 'updates_hero_subtitle',
                'az' => 'Sistemin inkişaf tarixi və son versiyalar.',
                'ru' => 'История развития и последние версии.',
                'en' => 'Development history and latest versions.',
                'tr' => 'Sistemin gelişim geçmişi ve son sürümler.'
            ],
            [
                'key' => 'updates_latest_badge',
                'az' => 'Son Versiya',
                'ru' => 'Последняя версия',
                'en' => 'Latest Version',
                'tr' => 'Son Sürüm'
            ],
            [
                'key' => 'updates_btn_full_short',
                'az' => 'Tam',
                'ru' => 'Полная',
                'en' => 'Full',
                'tr' => 'Tam'
            ],
            [
                'key' => 'updates_btn_update_short',
                'az' => 'Update',
                'ru' => 'Обновление',
                'en' => 'Update',
                'tr' => 'Güncelleme'
            ],
            [
                'key' => 'updates_empty_title',
                'az' => 'Hələ heç bir yenilik yoxdur',
                'ru' => 'Обновлений пока нет',
                'en' => 'No updates yet',
                'tr' => 'Henüz güncelleme yok'
            ],
            [
                'key' => 'updates_empty_text',
                'az' => 'Tezliklə ilk versiya əlavə ediləcək.',
                'ru' => 'Первая версия скоро будет добавлена.',
                'en' => 'The first version will be added soon.',
                'tr' => 'Yakında ilk sürüm eklenecek.'
            ],
            [
                'key' => 'updates_screenshots_label',
                'az' => 'Ekran Görüntüləri',
                'ru' => 'Скриншоты',
                'en' => 'Screenshots',
                'tr' => 'Ekran Görüntüleri'
            ],
            [
                'key' => 'updates_download_disabled_msg',
                'az' => 'Yükləmə bu versiya üçün deaktivdir.',
                'ru' => 'Скачивание недоступно для этой версии.',
                'en' => 'Download is disabled for this version.',
                'tr' => 'Bu sürüm için indirme devre dışı.'
            ],

            // --- PLUGINS (Pluginlər Səhifəsi) ---
            [
                'key' => 'plugins_page_title',
                'az' => 'Pluginlər',
                'ru' => 'Плагины',
                'en' => 'Plugins',
                'tr' => 'Eklentiler'
            ],
            [
                'key' => 'plugins_hero_title',
                'az' => 'Plugin Mərkəzi',
                'ru' => 'Центр плагинов',
                'en' => 'Plugin Center',
                'tr' => 'Eklenti Merkezi'
            ],
            [
                'key' => 'plugins_hero_subtitle',
                'az' => 'Sisteminizi gücləndirmək üçün əlavələr.',
                'ru' => 'Дополнения для улучшения вашей системы.',
                'en' => 'Add-ons to enhance your system.',
                'tr' => 'Sisteminizi güçlendirmek için eklentiler.'
            ],
            [
                'key' => 'plugins_default_desc',
                'az' => 'Bu plugin sistemə yeni funksiyalar əlavə edir.',
                'ru' => 'Этот плагин добавляет новые функции.',
                'en' => 'This plugin adds new features.',
                'tr' => 'Bu eklenti sisteme yeni özellikler katar.'
            ],
            [
                'key' => 'plugins_not_found_title',
                'az' => 'Plugin tapılmadı',
                'ru' => 'Плагин не найден',
                'en' => 'No plugins found',
                'tr' => 'Eklenti bulunamadı'
            ],
            [
                'key' => 'plugins_not_found_text',
                'az' => 'Hazırda sistemdə heç bir əlavə yoxdur.',
                'ru' => 'На данный момент дополнений нет.',
                'en' => 'There are currently no add-ons.',
                'tr' => 'Şu anda sistemde eklenti yok.'
            ],

            // --- CONTACT (Əlaqə Səhifəsi) ---
            [
                'key' => 'contact_page_title',
                'az' => 'Əlaqə',
                'ru' => 'Контакты',
                'en' => 'Contact',
                'tr' => 'İletişim'
            ],
            [
                'key' => 'contact_hero_title',
                'az' => 'Bizimlə Əlaqə',
                'ru' => 'Связаться с нами',
                'en' => 'Contact Us',
                'tr' => 'Bizimle İletişime Geçin'
            ],
            [
                'key' => 'contact_hero_subtitle',
                'az' => 'Sualınız var? Bizimlə əlaqə saxlaya bilərsiniz.',
                'ru' => 'Есть вопросы? Свяжитесь с нами.',
                'en' => 'Have questions? You can contact us.',
                'tr' => 'Sorunuz mu var? Bizimle iletişime geçebilirsiniz.'
            ],
            [
                'key' => 'contact_info_title',
                'az' => 'Əlaqə Məlumatları',
                'ru' => 'Контактная информация',
                'en' => 'Contact Information',
                'tr' => 'İletişim Bilgileri'
            ],
            [
                'key' => 'contact_phone_label',
                'az' => 'Telefon',
                'ru' => 'Телефон',
                'en' => 'Phone',
                'tr' => 'Telefon'
            ],
            [
                'key' => 'contact_email_label',
                'az' => 'Email',
                'ru' => 'Email',
                'en' => 'Email',
                'tr' => 'E-posta'
            ],
            [
                'key' => 'contact_social_label',
                'az' => 'Sosial Media',
                'ru' => 'Социальные сети',
                'en' => 'Social Media',
                'tr' => 'Sosyal Medya'
            ],
            [
                'key' => 'contact_form_title',
                'az' => 'Mesaj Göndər',
                'ru' => 'Отправить сообщение',
                'en' => 'Send Message',
                'tr' => 'Mesaj Gönder'
            ],
            [
                'key' => 'form_name_label',
                'az' => 'Adınız',
                'ru' => 'Ваше имя',
                'en' => 'Your Name',
                'tr' => 'Adınız'
            ],
            [
                'key' => 'form_email_label',
                'az' => 'Email',
                'ru' => 'Email',
                'en' => 'Email',
                'tr' => 'E-posta'
            ],
            [
                'key' => 'form_subject_label',
                'az' => 'Mövzu',
                'ru' => 'Тема',
                'en' => 'Subject',
                'tr' => 'Konu'
            ],
            [
                'key' => 'form_message_label',
                'az' => 'Mesaj',
                'ru' => 'Сообщение',
                'en' => 'Message',
                'tr' => 'Mesaj'
            ],
            [
                'key' => 'form_send_button',
                'az' => 'Göndər',
                'ru' => 'Отправить',
                'en' => 'Send',
                'tr' => 'Gönder'
            ],
        ];

        foreach ($translations as $trans) {
            DB::table('translations')->updateOrInsert(
                ['key' => $trans['key']], // Açar sözə görə yoxlayır
                [
                    'az' => $trans['az'],
                    'ru' => $trans['ru'],
                    'en' => $trans['en'],
                    'tr' => $trans['tr'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // İstəyə görə rollback zamanı silmək olar, amma data itməməsi üçün boş saxlamaq daha yaxşıdır.
        // DB::table('translations')->truncate();
    }
};

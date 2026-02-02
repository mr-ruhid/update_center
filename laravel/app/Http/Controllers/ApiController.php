<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Update;
use App\Models\Plugin;
use App\Models\SiteSetting;
use App\Models\Notification; // YENİ: Bildiriş modeli

class ApiController extends Controller
{
    /**
     * Əsas Sistem Versiyasını Yoxla
     * Endpoint: POST /api/v1/check
     */
    public function checkUpdate(Request $request)
    {
        $clientVersion = $request->input('version');
        $apiKey = $request->input('api_key');
        // $domain = $request->input('domain'); // Lazım olarsa istifadə edilə bilər

        // 1. API Key Yoxlanışı (Dinamik)
        $settings = SiteSetting::first();
        // Bazada api_key yoxdursa default açarı yoxlayırıq
        $systemApiKey = $settings->api_key ?? 'rj_live_982348729384729384';

        if($apiKey !== $systemApiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key yanlışdır'
            ], 403);
        }

        // 2. YENİ: Aktiv Bildirişi Tap (Ən sonuncu)
        // Admin paneldən "Bildiriş Göndər" bölməsindən yazılan mesajlar
        $activeNotification = Notification::where('is_active', true)
                                ->latest()
                                ->first();

        $notificationData = null;
        if ($activeNotification) {
            // Əgər bildiriş konkret bir versiya üçün deyilsə və ya versiya uyğun gəlirsə
            if (!$activeNotification->version || $activeNotification->version === $clientVersion) {
                $notificationData = [
                    'id' => $activeNotification->id,
                    'title' => $activeNotification->title,
                    'message' => $activeNotification->message,
                    'url' => $activeNotification->url,
                    'created_at' => $activeNotification->created_at
                ];
            }
        }

        // 3. Son Versiyanı Tap
        $latestUpdate = Update::where('is_active', true)
                              ->orderBy('created_at', 'desc')
                              ->first();

        // 4. Versiya Müqayisəsi (Update varmı?)
        if ($latestUpdate && version_compare($clientVersion, $latestUpdate->version, '<')) {

            // Yükləmə linki məntiqi: Əvvəlcə Update paketi, yoxdursa Full paket
            $downloadUrl = null;
            if ($latestUpdate->allow_download) {
                if ($latestUpdate->has_update_file) {
                    $downloadUrl = asset('uploads/updates/'.$latestUpdate->update_file_path);
                } elseif ($latestUpdate->has_full_file) {
                    $downloadUrl = asset('uploads/updates/'.$latestUpdate->full_file_path);
                }
            }

            return response()->json([
                'update_available' => true,
                'new_version' => $latestUpdate->version,

                // Admin Paneldən gələn Xüsusi Bildiriş
                'global_notification' => $notificationData,

                // Update haqqında standart bildiriş
                'notification' => [
                    'message' => "Yeni versiya (" . $latestUpdate->version . ") mövcuddur!\n" . $latestUpdate->changelog
                ],

                'data' => [
                    'version'       => $latestUpdate->version,
                    'release_date'  => $latestUpdate->created_at->format('Y-m-d'),
                    'download_url'  => $downloadUrl,
                    'action_url'    => route('page', 'updates'),
                    'title'         => 'Sistem Yeniləməsi',
                    'notes'         => $latestUpdate->changelog,
                ]
            ]);
        }

        // 5. Update Yoxdur (Amma bildiriş varsa yenə də göndəririk)
        return response()->json([
            'update_available' => false,
            'message'          => 'Siz ən son versiyanı istifadə edirsiniz.',
            'global_notification' => $notificationData // YENİ
        ]);
    }

    /**
     * Plugin Versiyasını Yoxla
     * Endpoint: POST /api/v1/check-plugin
     */
    public function checkPlugin(Request $request)
    {
        $pluginName = $request->input('name'); // Məs: "WhatsApp Modulu"
        $currentVersion = $request->input('version');
        $apiKey = $request->input('api_key');

        // API Key Yoxla
        $settings = SiteSetting::first();
        $systemApiKey = $settings->api_key ?? 'rj_live_982348729384729384';

        if($apiKey !== $systemApiKey) {
            return response()->json(['error' => 'Invalid API Key'], 403);
        }

        // Plugini bazadan tap
        $plugin = Plugin::where('name', $pluginName)->first();

        if ($plugin && version_compare($currentVersion, $plugin->version, '<')) {
            return response()->json([
                'update_available' => true,
                'new_version' => $plugin->version,
                'download_url' => asset('uploads/plugins/'.$plugin->file_path),
                'is_free' => $plugin->is_free,
                'price' => $plugin->price
            ]);
        }

        return response()->json(['update_available' => false]);
    }
}

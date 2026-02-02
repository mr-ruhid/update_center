<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Update;
use App\Models\SiteSetting;

class ApiController extends Controller
{
    /**
     * Müştəri proqramı bu metoda sorğu göndərir.
     */
    public function checkUpdate(Request $request)
    {
        // 1. Müştəridən gələn məlumatlar
        // Müştəri kodunda 'version' göndərilir
        $clientVersion = $request->input('version');
        $apiKey = $request->input('api_key');
        $domain = $request->input('domain');

        // 2. API Key Yoxlanışı
        // Sizin müştəri kodundakı statik açar
        if($apiKey !== 'rj_live_982348729384729384') {
            return response()->json([
                'success' => false,
                'message' => 'API Key yanlışdır'
            ], 403);
        }

        // 3. Bazadan Son Versiyanı Tapırıq
        // Yalnız aktiv olan ən son versiyanı götürürük
        $latestUpdate = Update::where('is_active', true)
                              ->orderBy('created_at', 'desc')
                              ->first();

        if (!$latestUpdate) {
            return response()->json([
                'update_available' => false,
                'message' => 'Hələ heç bir versiya yoxdur.'
            ]);
        }

        // 4. MƏNTİQ: Versiya Müqayisəsi
        // Əgər müştəri versiyası serverdən kiçikdirsə (<), deməli update var.
        if (version_compare($clientVersion, $latestUpdate->version, '<')) {

            return response()->json([
                'update_available' => true,
                'new_version' => $latestUpdate->version,

                // Müştəri tərəfindəki JS 'notification' obyektini gözləyir
                'notification' => [
                    'message' => "Yeni versiya (" . $latestUpdate->version . ") mövcuddur! \n\nYeniliklər:\n" . $latestUpdate->changelog
                ],

                // Əlavə məlumatlar (istərsə istifadə edə bilər)
                'data' => [
                    'version'       => $latestUpdate->version,
                    'release_date'  => $latestUpdate->created_at->format('Y-m-d'),
                    'download_url'  => $latestUpdate->has_update_file ? asset('uploads/updates/'.$latestUpdate->update_file_path) : null,
                    'action_url'    => route('page', 'updates'), // Yeniliklər səhifəsinə yönləndirmə
                    'title'         => 'Sistem Yeniləməsi',
                    'notes'         => $latestUpdate->changelog,
                ]
            ]);

        }

        // 5. Update Yoxdur
        else {
            return response()->json([
                'update_available' => false,
                'message'          => 'Siz ən son versiyanı istifadə edirsiniz.'
            ]);
        }
    }
}

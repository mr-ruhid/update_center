<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Müştəri proqramı bu metoda sorğu göndərir.
     * Göndərilən parametr: 'current_version' (Məs: 2.0)
     */
    public function checkUpdate(Request $request)
    {
        // 1. Müştəridən gələn məlumatlar
        $clientVersion = $request->input('current_version'); // Müştərinin hazırki versiyası
        $apiKey = $request->input('api_key');

        // 2. Bizim Baza/Sistemdəki Son Versiya (Bunu Admin Paneldən idarə edəcəyik)
        // Hələlik statik yazırıq, gələcəkdə bazadan çəkəcəyik.
        $latestVersion = "3.0.0";

        // Admin paneldə yazdığınız başlıq və qeydlər
        $releaseData = [
            'version'       => $latestVersion,
            'release_date'  => '2024-05-20',

            // Diqqət: 'action_url' parametri istifadəçini sizin təyin etdiyiniz TAM URL-ə yönləndirəcək.
            // Admin paneldə bura 'https://ruhidjavadov.site/app/rjpos/' və ya başqa bir link yazıla bilər.
            'action_url'    => 'https://istenilen-sayt.com/update-page',

            'title'         => 'Sistem Yeniləməsi', // Adminin yazdığı başlıq
            'notes'         => 'Bu versiyada təhlükəsizlik boşluqları doldurulub və sürət artırılıb.', // Adminin qeydləri
            'force_update'  => false // Məcburi yeniləmədirmi?
        ];

        // 3. Təhlükəsizlik Yoxlaması
        if($apiKey !== 'rj_live_982348729384729384') {
            return response()->json([
                'success' => false,
                'message' => 'API Key yanlışdır'
            ], 403);
        }

        // 4. MƏNTİQ: Versiya Müqayisəsi
        // Əgər müştəri versiyası (v2) < son versiya (v3) isə -> Update Var
        if (version_compare($clientVersion, $latestVersion, '<')) {

            return response()->json([
                'update_available' => true,  // Bayraq: Update var!
                'data'             => $releaseData // Müştərinin istifadə edəcəyi xam məlumatlar
            ]);

        }

        // 5. Əgər versiya eynidirsə və ya müştəri daha yenidirsə -> Update Yoxdur
        else {
            return response()->json([
                'update_available' => false, // Bayraq: Sakit dur
                'message'          => 'Siz ən son versiyanı istifadə edirsiniz.'
            ]);
        }
    }
}

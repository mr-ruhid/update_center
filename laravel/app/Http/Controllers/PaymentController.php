<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Plugin;
use App\Models\PaymentSetting;
use App\Models\Sale;

class PaymentController extends Controller
{
    // Ödəniş Başlatma (Checkout)
    public function checkout($pluginId)
    {
        $plugin = Plugin::findOrFail($pluginId);
        $settings = PaymentSetting::first();

        // 1. Ayarları Yoxla
        if (!$settings || !$settings->is_cryptomus_active) {
            dd("Xəta: Cryptomus aktiv deyil və ya ayarlar yoxdur."); // DEBUG
        }

        if (!$settings->cryptomus_merchant_id || !$settings->cryptomus_payment_key) {
            dd("Xəta: API Key və ya Merchant ID daxil edilməyib."); // DEBUG
        }

        // 2. Sifariş Məlumatlarını Hazırla
        $orderId = time() . '-' . $plugin->id . '-' . rand(100, 999);
        $amount = (string)$plugin->price;
        $currency = $settings->currency ?? 'USD';

        // 3. Bazada İlkin Sifariş Yarat
        try {
            Sale::create([
                'order_id' => $orderId,
                'plugin_id' => $plugin->id,
                'amount' => $plugin->price,
                'currency' => $currency,
                'status' => 'pending',
                'payment_method' => 'cryptomus'
            ]);
        } catch (\Exception $e) {
            dd("Baza Xətası: " . $e->getMessage()); // DEBUG
        }

        // 4. Cryptomus Payload
        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'order_id' => $orderId,
            'url_return' => route('payment.success'),
            'url_callback' => route('payment.callback'),
            'is_payment_multiple' => false,
            'lifetime' => 3600,
            'to_currency' => 'USDT'
        ];

        // 5. İmza (Sign) Yaratmaq
        $payload = base64_encode(json_encode($data));
        $sign = md5($payload . $settings->cryptomus_payment_key);

        // 6. API Sorğusu Göndər
        try {
            $response = Http::withHeaders([
                'merchant' => $settings->cryptomus_merchant_id,
                'sign' => $sign
            ])->post('https://api.cryptomus.com/v1/payment', $data);

            $result = $response->json();

            // DEBUG: Gələn cavabı ekrana basırıq
            if (!isset($result['result']['url'])) {
                dd("Cryptomus API Xətası:", $result, "Göndərilən Data:", $data);
            }

            // Uğurludursa Yönləndir
            return redirect($result['result']['url']);

        } catch (\Exception $e) {
            dd("Sistem Xətası: " . $e->getMessage()); // DEBUG
        }
    }

    public function success()
    {
        return redirect()->route('home')->with('success', 'Ödənişiniz qeydə alındı! Təsdiqləndikdən sonra yükləmə aktiv olacaq.');
    }

    public function callback(Request $request)
    {
        Log::info('Cryptomus Callback:', $request->all());

        // Buraya gələcəkdə status yeniləmə kodu yazılacaq

        return response()->json(['status' => 'ok']);
    }
}

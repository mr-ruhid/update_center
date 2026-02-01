<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->subject('⚠️ Yeni Giriş Təsbit Edildi - RJ Site Updater')
                    ->html("
                        <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                            <h2 style='color: #d97706;'>Yeni Giriş Bildirişi</h2>
                            <p>Salam, Admin hesabınıza yeni giriş edildi.</p>

                            <table style='width: 100%; margin-top: 20px; border-collapse: collapse;'>
                                <tr>
                                    <td style='padding: 8px; border-bottom: 1px solid #eee; color: #666;'>IP Ünvanı:</td>
                                    <td style='padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;'>{$this->details['ip']}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px; border-bottom: 1px solid #eee; color: #666;'>Cihaz / Brauzer:</td>
                                    <td style='padding: 8px; border-bottom: 1px solid #eee;'>{$this->details['user_agent']}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 8px; border-bottom: 1px solid #eee; color: #666;'>Tarix:</td>
                                    <td style='padding: 8px; border-bottom: 1px solid #eee;'>{$this->details['time']}</td>
                                </tr>
                            </table>

                            <p style='margin-top: 20px; font-size: 12px; color: #999;'>Əgər bu siz deyilsinizsə, dərhal şifrənizi dəyişin.</p>
                        </div>
                    ");
    }
}

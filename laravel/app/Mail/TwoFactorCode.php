<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorCode extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('Giriş Kodu - RJ Site Updater')
                    ->html("
                        <h1>Giriş Təsdiqi</h1>
                        <p>Admin panelə daxil olmaq üçün təsdiq kodunuz:</p>
                        <h2 style='color: #2563eb; letter-spacing: 5px;'>{$this->code}</h2>
                        <p>Bu kod 10 dəqiqə ərzində keçərlidir.</p>
                    ");
    }
}

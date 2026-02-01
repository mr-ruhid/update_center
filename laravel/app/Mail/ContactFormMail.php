<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Yeni Əlaqə Mesajı: ' . $this->data['subject'])
                    ->replyTo($this->data['email'], $this->data['name'])
                    ->html("
                        <h2>Saytdan Yeni Mesaj</h2>
                        <p><strong>Ad:</strong> {$this->data['name']}</p>
                        <p><strong>Email:</strong> {$this->data['email']}</p>
                        <p><strong>Mövzu:</strong> {$this->data['subject']}</p>
                        <p><strong>Mesaj:</strong></p>
                        <blockquote style='border-left: 4px solid #ddd; padding-left: 10px; color: #555;'>
                            " . nl2br(e($this->data['message'])) . "
                        </blockquote>
                    ");
    }
}

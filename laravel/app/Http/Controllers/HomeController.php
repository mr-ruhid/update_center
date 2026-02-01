<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Models\Menu;
use App\Models\SiteSetting;
use App\Models\Plugin;
use App\Models\Update;
use App\Models\Page;
use App\Models\ContactSetting;
use App\Models\SmtpSetting;
use App\Mail\ContactFormMail;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('order', 'asc')->get();
        $settings = SiteSetting::first() ?? new SiteSetting();
        $latestUpdate = Update::where('is_active', true)->latest()->first();
        $plugins = Plugin::take(3)->latest()->get();
        $contact = ContactSetting::first();

        if (View::exists('public.home')) {
            return view('public.home', compact('menus', 'settings', 'latestUpdate', 'plugins', 'contact'));
        }
        return view('welcome', compact('menus', 'settings', 'latestUpdate', 'plugins', 'contact'));
    }

    public function page($slug)
    {
        $menus = Menu::orderBy('order', 'asc')->get();
        $settings = SiteSetting::first() ?? new SiteSetting();
        $contact = ContactSetting::first();

        if (View::exists("public.{$slug}")) {
            $data = compact('menus', 'settings', 'contact');

            // Haqqımızda
            if ($slug === 'about') {
                $data['pageData'] = \App\Models\Page::where('slug', 'about')->first();
            }

            // Yeniliklər
            if ($slug === 'updates') {
                $data['updates'] = \App\Models\Update::where('is_active', true)
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(10);
            }

            // YENİ: Pluginlər səhifəsi
            if ($slug === 'plugins') {
                $data['plugins'] = \App\Models\Plugin::orderBy('created_at', 'desc')->paginate(12);
            }

            return view("public.{$slug}", $data);
        }

        $dbPage = \App\Models\Page::where('slug', $slug)->first();
        if ($dbPage) {
            return view('public.dynamic', compact('menus', 'settings', 'dbPage', 'contact'));
        }

        abort(404);
    }

    public function changeLanguage($locale)
    {
        if (in_array($locale, ['az', 'en', 'ru', 'tr'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }
        return redirect()->back();
    }

    public function sendContactMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contactSettings = ContactSetting::first();
        $recipientEmail = $contactSettings->email_receiver ?? 'admin@example.com';

        $smtp = SmtpSetting::first();
        if ($smtp && $smtp->mail_host) {
            Config::set('mail.mailers.smtp.host', $smtp->mail_host);
            Config::set('mail.mailers.smtp.port', $smtp->mail_port);
            Config::set('mail.mailers.smtp.username', $smtp->mail_username);
            Config::set('mail.mailers.smtp.password', $smtp->mail_password);
            Config::set('mail.mailers.smtp.encryption', $smtp->mail_encryption);
            Config::set('mail.from.address', $smtp->mail_from_address);
            Config::set('mail.from.name', $smtp->mail_from_name);
            Config::set('mail.default', 'smtp');
        }

        try {
            Mail::to($recipientEmail)->send(new ContactFormMail($request->all()));
            return redirect()->back()->with('success', 'Mesajınız uğurla göndərildi!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Contact Form Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Xəta baş verdi.');
        }
    }
}

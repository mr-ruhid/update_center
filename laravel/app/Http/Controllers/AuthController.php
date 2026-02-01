<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config; // Konfiqurasiya dəyişimi üçün
use Illuminate\Support\Facades\Log;    // Xətaları loglamaq üçün
use App\Models\User;
use App\Models\SiteSetting;
use App\Models\SmtpSetting;
use App\Models\LoginLog;    // YENİ: Giriş tarixçəsi modeli
use App\Mail\TwoFactorCode; // 2FA Maili
use App\Mail\LoginAlert;    // Giriş Xəbərdarlığı Maili
use Carbon\Carbon;

class AuthController extends Controller
{
    // 1. Giriş Səhifəsi
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    // 2. Giriş Məntiqi (POST)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // İstifadəçini tapırıq (amma hələ login etmirik)
        $user = User::where('email', $request->email)->first();

        // Şifrəni yoxlayırıq
        if ($user && Hash::check($request->password, $user->password)) {

            // SMTP-ni dinamik konfiqurasiya et (Əgər bazada varsa)
            $this->configureMailer();

            $settings = SiteSetting::first();

            // --- SENARYO 1: 2FA Aktivdir ---
            if ($settings && $settings->enable_2fa) {
                // Kodu yaradib bazaya yazırıq
                $code = rand(100000, 999999);
                $user->two_factor_code = $code;
                $user->two_factor_expires_at = Carbon::now()->addMinutes(10);
                $user->save();

                // Mail göndəririk
                try {
                    Mail::to($user->email)->send(new TwoFactorCode($code));
                } catch (\Exception $e) {
                    // Mail getməsə belə xətanı loga yazırıq
                    Log::error('SMTP Xətası (2FA): ' . $e->getMessage());
                    return back()->withErrors(['email' => 'SMTP Xətası: Mail göndərilə bilmədi. Adminlə əlaqə saxlayın.']);
                }

                // İstifadəçi ID-sini sessiyada saxlayırıq (Login etmirik!)
                $request->session()->put('2fa:user_id', $user->id);
                $request->session()->put('2fa:remember', $request->boolean('remember'));

                return redirect()->route('2fa.index');
            }

            // --- SENARYO 2: Birbaşa Giriş (2FA Yoxdur) ---
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            // Ağıllı Sistem: Log yaz və Bildiriş göndər
            $this->logLogin($user, $request);
            $this->sendLoginNotification($user, $request);

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Daxil etdiyiniz məlumatlar yanlışdır.',
        ])->onlyInput('email');
    }

    // 3. 2FA Səhifəsi (View)
    public function showTwoFactor()
    {
        // Sessiyada ID yoxdursa, girişə at
        if (!session()->has('2fa:user_id')) {
            return redirect()->route('login');
        }
        return view('admin.two_factor');
    }

    // 4. Kodu Yoxlamaq (POST)
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        // Sessiyadan istifadəçi ID-sini alırıq
        $userId = session()->get('2fa:user_id');

        // Əgər sessiya bitibsə, girişə qaytar
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Sessiya vaxtı bitdi, zəhmət olmasa yenidən daxil olun.']);
        }

        $user = User::find($userId);

        // İstifadəçi tapılmadısa
        if (!$user) {
            session()->forget('2fa:user_id');
            return redirect()->route('login');
        }

        // Kod düzgündürmü və vaxtı keçməyibmi?
        if ($request->code == $user->two_factor_code && $user->two_factor_expires_at && $user->two_factor_expires_at->gt(Carbon::now())) {

            // Kodu sıfırla (Təhlükəsizlik üçün)
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();

            // Rəsmi Giriş
            Auth::login($user, session()->get('2fa:remember'));

            // Sessiyanı təmizlə
            session()->forget('2fa:user_id');
            session()->forget('2fa:remember');
            session()->regenerate();

            // Ağıllı Sistem: Log yaz və Bildiriş göndər (SMTP yenidən konfiqurasiya olunmalıdır)
            $this->configureMailer();
            $this->logLogin($user, $request);
            $this->sendLoginNotification($user, $request);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['code' => 'Kod yanlışdır və ya müddəti bitib.']);
    }

    // 5. Kodu Yenidən Göndər
    public function resendTwoFactor()
    {
        $userId = session()->get('2fa:user_id');

        if (!$userId) return redirect()->route('login');
        $user = User::find($userId);
        if (!$user) return redirect()->route('login');

        // SMTP ayarlarını yüklə
        $this->configureMailer();

        // Yeni kod yarat
        $code = rand(100000, 999999);
        $user->two_factor_code = $code;
        $user->two_factor_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        try {
            Mail::to($user->email)->send(new TwoFactorCode($code));
            return back()->with('success', 'Yeni kod email ünvanınıza göndərildi.');
        } catch (\Exception $e) {
            Log::error('SMTP Xətası (Resend): ' . $e->getMessage());
            return back()->withErrors(['code' => 'Kod göndərilə bilmədi. Zəhmət olmasa biraz sonra yenidən cəhd edin.']);
        }
    }

    // 6. Çıxış
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // --- KÖMƏKÇİ METODLAR (AĞILLI SİSTEM) ---

    /**
     * Bazadakı SMTP ayarlarını Laravel-in Mail konfiqurasiyasına yükləyir.
     */
    private function configureMailer()
    {
        $smtp = SmtpSetting::first();

        // Yalnız SMTP məlumatları doludursa konfiqurasiya et
        if ($smtp && $smtp->mail_host) {

            // 1. Mövcud ayarları yeniləyirik
            Config::set('mail.mailers.smtp.host', $smtp->mail_host);
            Config::set('mail.mailers.smtp.port', $smtp->mail_port);
            Config::set('mail.mailers.smtp.username', $smtp->mail_username);
            Config::set('mail.mailers.smtp.password', $smtp->mail_password);
            Config::set('mail.mailers.smtp.encryption', $smtp->mail_encryption);
            Config::set('mail.from.address', $smtp->mail_from_address);
            Config::set('mail.from.name', $smtp->mail_from_name);

            // 2. VACİB: Mail göndərmə sistemini "log"-dan "smtp"-yə keçiririk
            Config::set('mail.default', 'smtp');
        }
    }

    /**
     * Giriş edildiyi an IP və Cihaz məlumatlarını maile göndərir.
     */
    private function sendLoginNotification($user, Request $request)
    {
        // Yalnız SMTP qurulubsa göndər
        $smtp = SmtpSetting::first();
        if ($smtp && $smtp->mail_host) {
            try {
                $details = [
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'time' => Carbon::now()->format('d.m.Y H:i:s')
                ];

                Mail::to($user->email)->send(new LoginAlert($details));
            } catch (\Exception $e) {
                // Xətanı log faylına yazırıq, amma girişə mane olmuruq
                Log::error('Giriş bildirişi mail xətası: ' . $e->getMessage());
            }
        }
    }

    /**
     * Girişi bazaya (login_logs) qeyd edir.
     */
    private function logLogin($user, Request $request)
    {
        LoginLog::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'login_at' => now(),
        ]);
    }
}

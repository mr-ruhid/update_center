<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;        // Məhsul modeli
use App\Models\Update;         // Versiya/Update modeli
use App\Models\Subscriber;     // Abonə modeli
use App\Models\Page;           // Səhifə modeli (Haqqımızda)
use App\Models\ContactSetting; // Əlaqə ayarları modeli
use App\Models\SiteSetting;    // Sayt ayarları modeli
use App\Models\SmtpSetting;    // SMTP ayarları modeli
use App\Models\Translation;    // Tərcümə modeli
use App\Models\Plugin;         // Plugin modeli
use App\Models\PaymentSetting; // Ödəmə ayarları modeli
use App\Models\LoginLog;       // Giriş Logları modeli
use App\Models\HomeContent;    // Ana Səhifə Məzmunu modeli
use App\Models\Menu;           // Menyu modeli
use App\Models\Sale;           // Satış modeli
use Illuminate\Support\Facades\File; // Fayl əməliyyatları
use Illuminate\Support\Facades\Hash; // Şifrə şifrələmə
use Illuminate\Support\Facades\Auth; // İstifadəçi identifikasiyası
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // --- ANA SƏHİFƏ (DASHBOARD) ---
    public function dashboard()
    {
        // Statistikalar
        $stats = [
            'updates' => Update::count(),
            'plugins' => Plugin::count(),
            'subscribers' => Subscriber::count(),
            'products' => Product::count(),
        ];

        // Son Məlumatlar
        $recentUpdates = Update::latest()->take(5)->get();
        $recentPlugins = Plugin::latest()->take(5)->get();

        // Son Girişlər (Əgər cədvəl yoxdursa xəta verməsin - Try/Catch)
        try {
            $loginLogs = LoginLog::with('user')->latest('login_at')->take(7)->get();
        } catch (\Exception $e) {
            $loginLogs = collect([]);
        }

        return view('admin.dashboard', compact('stats', 'recentUpdates', 'recentPlugins', 'loginLogs'));
    }

    // --- MARKETİNQ & SATIŞ ---

    // 1. Ödəniş Tarixçəsi
    public function sales()
    {
        $sales = Sale::with('plugin')->latest()->paginate(20);
        $totalSales = Sale::where('status', 'paid')->count();
        return view('admin.sales', compact('sales', 'totalSales'));
    }

    // 2. Bildirişlər
    public function notification()
    {
        return view('admin.notification');
    }

    public function storeNotification(Request $request)
    {
        // Bildiriş göndərmə məntiqi (API/Firebase)
        return redirect()->back()->with('success', 'Bildiriş uğurla göndərildi (Demo).');
    }

    // 3. Abonələr
    public function subscribers(Request $request)
    {
        $query = Subscriber::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('email', 'like', "%{$request->search}%");
        }

        $subscribers = $query->latest()->paginate(20);
        $totalSubscribers = Subscriber::count();

        return view('admin.subscribers', compact('subscribers', 'totalSubscribers'));
    }

    public function deleteSubscriber($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();
        return redirect()->back()->with('success', 'Abonə silindi.');
    }

    // --- MƏZMUN İDARƏETMƏSİ ---

    // 1. Ana Səhifə Məzmunu (Hero)
    public function homeContent()
    {
        $content = HomeContent::first() ?? new HomeContent();
        return view('admin.home_content', compact('content'));
    }

    public function updateHomeContent(Request $request)
    {
        $request->validate([
            'hero_btn_url' => 'nullable|string',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $content = HomeContent::first();
        if (!$content) {
            $content = new HomeContent();
        }

        // Çoxdilli Məlumatlar
        $content->hero_title_1 = [
            'az' => $request->hero_title_1_az,
            'en' => $request->hero_title_1_en,
            'ru' => $request->hero_title_1_ru,
            'tr' => $request->hero_title_1_tr,
        ];
        $content->hero_title_2 = [
            'az' => $request->hero_title_2_az,
            'en' => $request->hero_title_2_en,
            'ru' => $request->hero_title_2_ru,
            'tr' => $request->hero_title_2_tr,
        ];
        $content->hero_subtext = [
            'az' => $request->hero_subtext_az,
            'en' => $request->hero_subtext_en,
            'ru' => $request->hero_subtext_ru,
            'tr' => $request->hero_subtext_tr,
        ];
        $content->hero_btn_text = [
            'az' => $request->hero_btn_text_az,
            'en' => $request->hero_btn_text_en,
            'ru' => $request->hero_btn_text_ru,
            'tr' => $request->hero_btn_text_tr,
        ];
        $content->hero_btn_url = $request->hero_btn_url;

        // Qalereya
        $currentGallery = $content->hero_gallery ?? [];
        if ($request->hasFile('gallery')) {
            foreach($request->file('gallery') as $image) {
                $imgName = 'hero_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/home'), $imgName);
                $currentGallery[] = $imgName;
            }
        }

        // Silinəcək şəkillər
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $img) {
                if (($key = array_search($img, $currentGallery)) !== false) {
                    if (File::exists(public_path('uploads/home/'.$img))) {
                        File::delete(public_path('uploads/home/'.$img));
                    }
                    unset($currentGallery[$key]);
                }
            }
        }

        $content->hero_gallery = array_values($currentGallery);
        $content->save();

        return redirect()->back()->with('success', 'Ana səhifə məlumatları yeniləndi.');
    }

    // 2. Yenilik Paylaş (Update Center)
    public function update()
    {
        $updates = Update::latest()->paginate(5);
        return view('admin.update', compact('updates'));
    }

    public function storeUpdate(Request $request)
    {
        $request->validate([
            'version' => 'required|string|max:50',
            'changelog' => 'nullable|string',
        ]);

        $data = [
            'version' => $request->version,
            'changelog' => $request->changelog,
            'is_active' => $request->has('is_active'),
            'allow_download' => $request->has('allow_download'),
            'has_update_file' => $request->has('has_update_file'),
            'price_update' => $request->price_update,
            'has_full_file' => $request->has('has_full_file'),
            'price_full' => $request->price_full,
        ];

        if ($request->hasFile('update_file') && $request->has('has_update_file')) {
            $file = $request->file('update_file');
            $filename = 'update_' . preg_replace('/[^A-Za-z0-9\-]/', '', $request->version) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/updates'), $filename);
            $data['update_file_path'] = $filename;
        }

        if ($request->hasFile('full_file') && $request->has('has_full_file')) {
            $file = $request->file('full_file');
            $filename = 'full_' . preg_replace('/[^A-Za-z0-9\-]/', '', $request->version) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/updates'), $filename);
            $data['full_file_path'] = $filename;
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach($request->file('gallery') as $image) {
                $imgName = 'gal_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/gallery'), $imgName);
                $galleryPaths[] = $imgName;
            }
            $data['gallery_images'] = $galleryPaths;
        }

        Update::create($data);
        return redirect()->back()->with('success', 'Versiya uğurla paylaşıldı.');
    }

    public function deleteUpdate($id)
    {
        $update = Update::findOrFail($id);

        if ($update->update_file_path && File::exists(public_path('uploads/updates/'.$update->update_file_path))) {
            File::delete(public_path('uploads/updates/'.$update->update_file_path));
        }
        if ($update->full_file_path && File::exists(public_path('uploads/updates/'.$update->full_file_path))) {
            File::delete(public_path('uploads/updates/'.$update->full_file_path));
        }
        if ($update->gallery_images) {
            foreach($update->gallery_images as $img) {
                if (File::exists(public_path('uploads/gallery/'.$img))) {
                    File::delete(public_path('uploads/gallery/'.$img));
                }
            }
        }

        $update->delete();
        return redirect()->back()->with('success', 'Versiya silindi.');
    }

    // 3. Pluginlər (REAL)
    public function plugins()
    {
        $plugins = Plugin::latest()->paginate(10);
        return view('admin.plugins', compact('plugins'));
    }

    public function storePlugin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string', // Açıqlama sahəsi
            'version' => 'required|string|max:50',
            'file' => 'required|file|mimes:zip|max:51200',
            'image' => 'nullable|image|max:2048',
            'is_free' => 'required|boolean',
            'price' => 'nullable|numeric|required_if:is_free,0',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'version' => $request->version,
            'is_free' => $request->boolean('is_free'),
            'price' => $request->boolean('is_free') ? null : $request->price,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'plugin_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/plugins'), $filename);
            $data['file_path'] = $filename;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imgName = 'pl_img_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/plugins'), $imgName);
            $data['image'] = $imgName;
        }

        Plugin::create($data);
        return redirect()->back()->with('success', 'Plugin uğurla əlavə edildi.');
    }

    public function deletePlugin($id)
    {
        $plugin = Plugin::findOrFail($id);
        if(File::exists(public_path('uploads/plugins/' . $plugin->file_path))) {
            File::delete(public_path('uploads/plugins/' . $plugin->file_path));
        }
        if($plugin->image && File::exists(public_path('uploads/plugins/' . $plugin->image))) {
            File::delete(public_path('uploads/plugins/' . $plugin->image));
        }
        $plugin->delete();
        return redirect()->back()->with('success', 'Plugin silindi.');
    }

    // 4. Məhsul Şəkilləri
    public function products(Request $request)
    {
        $query = Product::query();
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('barcode', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%");
        }
        $products = $query->latest()->paginate(12);
        $totalProducts = Product::count();
        return view('admin.products', compact('products', 'totalProducts'));
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:products,barcode',
            'image'   => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = $request->barcode . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            Product::create(['barcode' => $request->barcode, 'name' => $request->name, 'image_path' => $filename]);
            return redirect()->back()->with('success', 'Məhsul uğurla əlavə edildi.');
        }
        return redirect()->back()->with('error', 'Xəta baş verdi.');
    }

    public function productDelete($id)
    {
        $product = Product::findOrFail($id);
        $filePath = public_path('uploads/products/' . $product->image_path);
        if (File::exists($filePath)) File::delete($filePath);
        $product->delete();
        return redirect()->back()->with('success', 'Məhsul silindi.');
    }

    // 5. Haqqımızda (YENİLƏNDİ - Çoxdilli Array dəstəyi)
    public function about()
    {
        // 'about' slug-ı olan səhifəni gətiririk
        // Default dəyərlər array olmalıdır
        $page = Page::firstOrCreate(
            ['slug' => 'about'],
            ['title' => [], 'content' => []]
        );
        return view('admin.about', compact('page'));
    }

    public function updateAbout(Request $request)
    {
        // Validasiya
        $request->validate([
            'title' => 'required|array',
            'content' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
        ]);

        $page = Page::where('slug', 'about')->firstOrFail();

        $data = [
            'title' => $request->title,     // Massiv olaraq gəlir
            'content' => $request->content, // Massiv olaraq gəlir
        ];

        if ($request->hasFile('image')) {
            if ($page->image && File::exists(public_path('uploads/pages/'.$page->image))) {
                File::delete(public_path('uploads/pages/'.$page->image));
            }
            $file = $request->file('image');
            $filename = 'about_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pages'), $filename);
            $data['image'] = $filename;
        }

        $page->update($data);

        return redirect()->back()->with('success', 'Haqqımızda məlumatları uğurla yeniləndi.');
    }

    // 6. Əlaqə
    public function contact()
    {
        $contact = ContactSetting::first() ?? new ContactSetting();
        return view('admin.contact', compact('contact'));
    }

    public function updateContact(Request $request)
    {
        $data = $request->validate([
            'phone_1' => 'nullable|string', 'phone_2' => 'nullable|string', 'email_receiver' => 'nullable|email',
            'facebook' => 'nullable|url', 'instagram' => 'nullable|url', 'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url', 'github' => 'nullable|url', 'behance' => 'nullable|url',
            'tiktok' => 'nullable|url', 'threads' => 'nullable|url'
        ]);
        $contact = ContactSetting::first();
        if ($contact) $contact->update($data); else ContactSetting::create($data);
        return redirect()->back()->with('success', 'Əlaqə məlumatları yeniləndi.');
    }

    // --- SİSTEM ---

    public function accounts()
    {
        return view('admin.accounts');
    }

    public function accountUpdate(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password|nullable',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Cari şifrə yanlışdır.']);
            }
            $user->password = Hash::make($request->new_password);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return redirect()->back()->with('success', 'Hesab məlumatları yeniləndi.');
    }

    public function api()
    {
        return view('admin.api');
    }

    // --- TƏNZİMLƏMƏLƏR ---

    // 1. Sayt Ayarları
    public function siteSettings()
    {
        $settings = SiteSetting::first() ?? new SiteSetting();
        return view('admin.site_settings', compact('settings'));
    }

    public function updateSiteSettings(Request $request)
    {
        $request->validate(['site_name' => 'nullable|string', 'logo' => 'nullable|image', 'favicon' => 'nullable|image']);
        $settings = SiteSetting::first();
        if (!$settings) $settings = new SiteSetting();

        $data = $request->only(['site_name', 'seo_title', 'seo_description', 'seo_keywords']);
        $data['enable_2fa'] = $request->has('enable_2fa');

        if ($request->hasFile('logo')) {
            if ($settings->logo && File::exists(public_path('uploads/settings/'.$settings->logo))) File::delete(public_path('uploads/settings/'.$settings->logo));
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $data['logo'] = $filename;
        }
        if ($request->hasFile('favicon')) {
            if ($settings->favicon && File::exists(public_path('uploads/settings/'.$settings->favicon))) File::delete(public_path('uploads/settings/'.$settings->favicon));
            $file = $request->file('favicon');
            $filename = 'favicon_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $filename);
            $data['favicon'] = $filename;
        }

        if ($settings->exists) $settings->update($data); else SiteSetting::create($data);
        return redirect()->back()->with('success', 'Ayarlar yadda saxlanıldı.');
    }

    // 2. Menyu Ayarları
    public function menu()
    {
        $menus = Menu::orderBy('order')->get();
        $files = File::allFiles(resource_path('views/public')); // Frontend views
        $pages = [];
        foreach ($files as $file) {
            $filename = $file->getRelativePathname();
            $cleanName = str_replace('.blade.php', '', $filename);
            $url = $cleanName === 'home' ? '/' : '/' . $cleanName;
            $pages[] = ['name' => ucfirst($cleanName), 'url' => url($url), 'path' => $filename];
        }
        return view('admin.menu', compact('menus', 'pages'));
    }

    public function storeMenu(Request $request)
    {
        $request->validate([
            'url' => 'required',
            'type' => 'required',
            'key' => 'required',
            'title' => 'required'
        ]);

        $maxOrder = Menu::max('order') ?? 0;

        // Menyuya əlavə edirik (Key ilə birlikdə)
        Menu::create([
            'key' => $request->key,
            'url' => $request->url,
            'type' => $request->type,
            'order' => $maxOrder + 1,
            // Model array cast etdiyi üçün title-ı array kimi saxlayırıq
            'title' => ['az' => $request->title]
        ]);

        // Əgər bu açar (key) Tərcümə cədvəlində yoxdursa, yaradırıq
        $transCheck = Translation::where('key', $request->key)->first();
        if (!$transCheck) {
            Translation::create([
                'key' => $request->key,
                'az' => $request->title,
                'en' => $request->title, // İngilis dili üçün də eyni adı yazırıq
                'ru' => $request->title,
                'tr' => $request->title,
            ]);
        }

        return redirect()->back()->with('success', 'Menyu və tərcümə açarı uğurla yaradıldı.');
    }

    public function deleteMenu($id)
    {
        Menu::destroy($id);
        return redirect()->back()->with('success', 'Silindi.');
    }

    public function sortMenu(Request $request)
    {
        if($request->has('order')) {
            foreach($request->order as $index => $id) {
                Menu::where('id', $id)->update(['order' => $index + 1]);
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function updateMenu(Request $request) { return redirect()->back(); }

    // 3. Ödəmə Ayarları
    public function paymentSettings()
    {
        $payment = PaymentSetting::first() ?? new PaymentSetting();
        return view('admin.payment_settings', compact('payment'));
    }

    public function updatePaymentSettings(Request $request)
    {
        $data = $request->validate([
            'currency' => 'required', 'currency_symbol' => 'required',
            'cryptomus_merchant_id' => 'nullable', 'cryptomus_payment_key' => 'nullable',
            'stripe_public_key' => 'nullable', 'stripe_secret_key' => 'nullable', 'bank_account_info' => 'nullable'
        ]);
        $data['is_cryptomus_active'] = $request->has('is_cryptomus_active');
        $data['is_stripe_active'] = $request->has('is_stripe_active');
        $data['is_bank_active'] = $request->has('is_bank_active');

        $payment = PaymentSetting::first();
        if ($payment) $payment->update($data); else PaymentSetting::create($data);
        return redirect()->back()->with('success', 'Ödəmə ayarları yeniləndi.');
    }

    // 4. SMTP
    public function smtp()
    {
        $smtp = SmtpSetting::first() ?? new SmtpSetting();
        return view('admin.smtp', compact('smtp'));
    }

    public function updateSmtp(Request $request)
    {
        $data = $request->validate([
            'mail_host' => 'required', 'mail_port' => 'required', 'mail_username' => 'required',
            'mail_password' => 'required', 'mail_encryption' => 'nullable', 'mail_from_address' => 'required', 'mail_from_name' => 'required'
        ]);
        $data['mail_mailer'] = 'smtp';
        $smtp = SmtpSetting::first();
        if ($smtp) $smtp->update($data); else SmtpSetting::create($data);
        return redirect()->back()->with('success', 'SMTP yeniləndi.');
    }

    // 5. Tərcümə
    public function translation(Request $request)
    {
        $query = Translation::query();
        if ($request->has('search') && !empty($request->search)) {
            $s = $request->search;
            $query->where('key', 'like', "%{$s}%")->orWhere('az', 'like', "%{$s}%")->orWhere('en', 'like', "%{$s}%")->orWhere('tr', 'like', "%{$s}%");
        }
        $translations = $query->latest()->paginate(20);
        return view('admin.translation', compact('translations'));
    }

    public function storeTranslation(Request $request)
    {
        $request->validate(['key' => 'required|unique:translations,key']);
        Translation::create([
            'key' => strtolower(str_replace(' ', '_', $request->key)),
            'az' => $request->az, 'en' => $request->en, 'ru' => $request->ru, 'tr' => $request->tr
        ]);
        return redirect()->back()->with('success', 'Açar əlavə edildi.');
    }

    public function updateTranslation(Request $request)
    {
        if ($request->has('translations')) {
            foreach ($request->translations as $id => $data) {
                Translation::where('id', $id)->update([
                    'az' => $data['az'], 'en' => $data['en'], 'ru' => $data['ru'], 'tr' => $data['tr']
                ]);
            }
        }
        return redirect()->back()->with('success', 'Tərcümələr yeniləndi.');
    }

    public function deleteTranslation($id)
    {
        Translation::destroy($id);
        return redirect()->back()->with('success', 'Silindi.');
    }
}

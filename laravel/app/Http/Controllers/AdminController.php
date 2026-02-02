<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Update;
use App\Models\Subscriber;
use App\Models\Page;
use App\Models\ContactSetting;
use App\Models\SiteSetting;
use App\Models\SmtpSetting;
use App\Models\Translation;
use App\Models\Plugin;
use App\Models\PaymentSetting;
use App\Models\LoginLog;
use App\Models\HomeContent;
use App\Models\Menu;
use App\Models\Sale;
use App\Models\Notification;   // YENİ: Unudulmuş model əlavə edildi
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // --- ANA SƏHİFƏ (DASHBOARD) ---
    public function dashboard()
    {
        $stats = [
            'updates' => Update::count(),
            'plugins' => Plugin::count(),
            'subscribers' => Subscriber::count(),
            'products' => Product::count(),
        ];

        $recentUpdates = Update::latest()->take(5)->get();
        $recentPlugins = Plugin::latest()->take(5)->get();

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

    // 2. Bildirişlər (DÜZƏLDİLDİ)
    public function notification()
    {
        // Əgər Notification modeli yoxdursa və ya boşdursa xəta verməsin
        try {
            $notifications = Notification::latest()->paginate(10);
        } catch (\Exception $e) {
            $notifications = collect([]);
        }

        return view('admin.notification', compact('notifications'));
    }

    public function storeNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
            'url' => 'nullable|url',
            'version' => 'nullable|string',
        ]);

        Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'url' => $request->url,
            'version' => $request->version,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Bildiriş yayımlandı.');
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
        Subscriber::destroy($id);
        return redirect()->back()->with('success', 'Abonə silindi.');
    }

    // --- MƏZMUN İDARƏETMƏSİ ---

    // 1. Ana Səhifə Məzmunu
    public function homeContent()
    {
        $content = HomeContent::first() ?? new HomeContent();
        return view('admin.home_content', compact('content'));
    }

    public function updateHomeContent(Request $request)
    {
        $request->validate([ 'hero_btn_url' => 'nullable|string', 'gallery.*' => 'image|max:2048' ]);
        $content = HomeContent::first() ?? new HomeContent();

        $content->hero_title_1 = ['az' => $request->hero_title_1_az, 'en' => $request->hero_title_1_en, 'ru' => $request->hero_title_1_ru, 'tr' => $request->hero_title_1_tr];
        $content->hero_title_2 = ['az' => $request->hero_title_2_az, 'en' => $request->hero_title_2_en, 'ru' => $request->hero_title_2_ru, 'tr' => $request->hero_title_2_tr];
        $content->hero_subtext = ['az' => $request->hero_subtext_az, 'en' => $request->hero_subtext_en, 'ru' => $request->hero_subtext_ru, 'tr' => $request->hero_subtext_tr];
        $content->hero_btn_text = ['az' => $request->hero_btn_text_az, 'en' => $request->hero_btn_text_en, 'ru' => $request->hero_btn_text_ru, 'tr' => $request->hero_btn_text_tr];
        $content->hero_btn_url = $request->hero_btn_url;

        $currentGallery = $content->hero_gallery ?? [];
        if ($request->hasFile('gallery')) {
            foreach($request->file('gallery') as $image) {
                $imgName = 'hero_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/home'), $imgName);
                $currentGallery[] = $imgName;
            }
        }
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $img) {
                if (($key = array_search($img, $currentGallery)) !== false) {
                    if (File::exists(public_path('uploads/home/'.$img))) File::delete(public_path('uploads/home/'.$img));
                    unset($currentGallery[$key]);
                }
            }
        }
        $content->hero_gallery = array_values($currentGallery);
        $content->save();
        return redirect()->back()->with('success', 'Yeniləndi.');
    }

    // 2. Yenilik Paylaş
    public function update()
    {
        $updates = Update::latest()->paginate(5);
        return view('admin.update', compact('updates'));
    }

    public function storeUpdate(Request $request)
    {
        $request->validate([ 'version' => 'required', 'changelog' => 'nullable' ]);
        $data = [
            'version' => $request->version, 'changelog' => $request->changelog,
            'is_active' => $request->has('is_active'), 'allow_download' => $request->has('allow_download'),
            'has_update_file' => $request->has('has_update_file'), 'price_update' => $request->price_update,
            'has_full_file' => $request->has('has_full_file'), 'price_full' => $request->price_full,
        ];
        if ($request->hasFile('update_file')) {
            $file = $request->file('update_file');
            $filename = 'upd_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/updates'), $filename);
            $data['update_file_path'] = $filename;
        }
        if ($request->hasFile('full_file')) {
            $file = $request->file('full_file');
            $filename = 'full_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/updates'), $filename);
            $data['full_file_path'] = $filename;
        }
        if ($request->hasFile('gallery')) {
            $paths = [];
            foreach($request->file('gallery') as $img) {
                $name = 'g_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(public_path('uploads/gallery'), $name);
                $paths[] = $name;
            }
            $data['gallery_images'] = $paths;
        }
        Update::create($data);
        return redirect()->back()->with('success', 'Yenilik paylaşıldı.');
    }

    public function deleteUpdate($id)
    {
        $upd = Update::findOrFail($id);
        if($upd->update_file_path && File::exists(public_path('uploads/updates/'.$upd->update_file_path))) File::delete(public_path('uploads/updates/'.$upd->update_file_path));
        if($upd->full_file_path && File::exists(public_path('uploads/updates/'.$upd->full_file_path))) File::delete(public_path('uploads/updates/'.$upd->full_file_path));
        $upd->delete();
        return redirect()->back()->with('success', 'Silindi.');
    }

    // 3. Pluginlər
    public function plugins()
    {
        $plugins = Plugin::latest()->paginate(10);
        return view('admin.plugins', compact('plugins'));
    }

    public function storePlugin(Request $request)
    {
        $request->validate(['name' => 'required', 'version' => 'required', 'file' => 'required']);
        $data = [
            'name' => $request->name, 'description' => $request->description, 'version' => $request->version,
            'is_free' => $request->boolean('is_free'), 'price' => $request->price
        ];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = 'pl_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/plugins'), $name);
            $data['file_path'] = $name;
        }
        if ($request->hasFile('image')) {
            $img = $request->file('image');
            $name = 'pl_img_' . time() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('uploads/plugins'), $name);
            $data['image'] = $name;
        }
        Plugin::create($data);
        return redirect()->back()->with('success', 'Plugin yükləndi.');
    }

    public function deletePlugin($id)
    {
        $pl = Plugin::findOrFail($id);
        if(File::exists(public_path('uploads/plugins/'.$pl->file_path))) File::delete(public_path('uploads/plugins/'.$pl->file_path));
        if($pl->image && File::exists(public_path('uploads/plugins/'.$pl->image))) File::delete(public_path('uploads/plugins/'.$pl->image));
        $pl->delete();
        return redirect()->back()->with('success', 'Silindi.');
    }

    // 4. Məhsul Şəkilləri
    public function products(Request $request)
    {
        $query = Product::query();
        if ($request->has('search')) $query->where('barcode', 'like', "%{$request->search}%");
        return view('admin.products', ['products' => $query->paginate(12), 'totalProducts' => Product::count()]);
    }

    public function productStore(Request $request)
    {
        $request->validate(['barcode' => 'required|unique:products', 'image' => 'required|image']);
        $file = $request->file('image');
        $name = $request->barcode . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/products'), $name);
        Product::create(['barcode' => $request->barcode, 'name' => $request->name, 'image_path' => $name]);
        return redirect()->back()->with('success', 'Əlavə edildi.');
    }

    public function productDelete($id)
    {
        $p = Product::findOrFail($id);
        if(File::exists(public_path('uploads/products/'.$p->image_path))) File::delete(public_path('uploads/products/'.$p->image_path));
        $p->delete();
        return redirect()->back()->with('success', 'Silindi.');
    }

    // 5. Haqqımızda
    public function about() { $page = Page::firstOrCreate(['slug'=>'about'], ['title'=>[], 'content'=>[]]); return view('admin.about', compact('page')); }
    public function updateAbout(Request $request) {
        $page = Page::where('slug', 'about')->first();
        $data = ['title' => $request->title, 'content' => $request->content];
        if($request->hasFile('image')) {
            $file = $request->file('image');
            $name = 'about_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/pages'), $name);
            $data['image'] = $name;
        }
        $page->update($data);
        return redirect()->back()->with('success', 'Yeniləndi.');
    }

    // 6. Əlaqə
    public function contact() { $contact = ContactSetting::first() ?? new ContactSetting(); return view('admin.contact', compact('contact')); }
    public function updateContact(Request $request) {
        $contact = ContactSetting::first();
        if($contact) $contact->update($request->all()); else ContactSetting::create($request->all());
        return redirect()->back()->with('success', 'Yeniləndi.');
    }

    // Sistem və Tənzimləmələr
    public function accounts() { return view('admin.accounts'); }
    public function accountUpdate(Request $request) {
        $user = Auth::user();
        if($request->filled('new_password')) $user->password = Hash::make($request->new_password);
        $user->update($request->only('name', 'email'));
        return redirect()->back()->with('success', 'Yeniləndi.');
    }
    public function api() { return view('admin.api'); }

    public function siteSettings() { $settings = SiteSetting::first() ?? new SiteSetting(); return view('admin.site_settings', compact('settings')); }
    public function updateSiteSettings(Request $request) {
        $settings = SiteSetting::first();
        $data = $request->all();
        $data['enable_2fa'] = $request->has('enable_2fa');
        if($request->hasFile('logo')) {
            $file = $request->file('logo');
            $name = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $name);
            $data['logo'] = $name;
        }
        if($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            $name = 'fav_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/settings'), $name);
            $data['favicon'] = $name;
        }
        if($settings) $settings->update($data); else SiteSetting::create($data);
        return redirect()->back()->with('success', 'Yeniləndi.');
    }

    public function menu() {
        $menus = Menu::orderBy('order')->get();
        $files = File::allFiles(resource_path('views/public'));
        $pages = [];
        foreach ($files as $file) {
            $name = str_replace('.blade.php', '', $file->getRelativePathname());
            $pages[] = ['name' => ucfirst($name), 'url' => url($name == 'home' ? '/' : $name), 'path' => $name];
        }
        return view('admin.menu', compact('menus', 'pages'));
    }
    public function storeMenu(Request $request) {
        Menu::create(['title' => ['az'=>$request->title_az, 'en'=>$request->title_en], 'url'=>$request->url, 'type'=>$request->type, 'order'=>99]);
        return redirect()->back()->with('success', 'Əlavə edildi.');
    }
    public function deleteMenu($id) { Menu::destroy($id); return redirect()->back(); }
    public function sortMenu(Request $request) { return response()->json(); } // Update logic here if needed
    public function updateMenu() { return redirect()->back(); }

    public function paymentSettings() { $payment = PaymentSetting::first() ?? new PaymentSetting(); return view('admin.payment_settings', compact('payment')); }
    public function updatePaymentSettings(Request $request) {
        $payment = PaymentSetting::first();
        $data = $request->all();
        $data['is_cryptomus_active'] = $request->has('is_cryptomus_active');
        $data['is_stripe_active'] = $request->has('is_stripe_active');
        $data['is_bank_active'] = $request->has('is_bank_active');
        if($payment) $payment->update($data); else PaymentSetting::create($data);
        return redirect()->back()->with('success', 'Yeniləndi.');
    }

    public function smtp() { $smtp = SmtpSetting::first() ?? new SmtpSetting(); return view('admin.smtp', compact('smtp')); }
    public function updateSmtp(Request $request) {
        $smtp = SmtpSetting::first();
        $request->merge(['mail_mailer' => 'smtp']);
        if($smtp) $smtp->update($request->all()); else SmtpSetting::create($request->all());
        return redirect()->back()->with('success', 'Yeniləndi.');
    }

    public function translation(Request $request) {
        $q = Translation::query();
        if($request->has('search')) $q->where('key', 'like', "%{$request->search}%");
        return view('admin.translation', ['translations'=>$q->latest()->paginate(20)]);
    }
    public function storeTranslation(Request $request) {
        Translation::create(['key'=>$request->key, 'az'=>$request->az, 'en'=>$request->en, 'ru'=>$request->ru, 'tr'=>$request->tr]);
        return redirect()->back()->with('success', 'Əlavə edildi.');
    }
    public function updateTranslation(Request $request) {
        if($request->has('translations')) {
            foreach($request->translations as $id => $data) Translation::where('id', $id)->update($data);
        }
        return redirect()->back()->with('success', 'Yeniləndi.');
    }
    public function deleteTranslation($id) { Translation::destroy($id); return redirect()->back(); }
}

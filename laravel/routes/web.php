<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- FRONTEND (Ön Tərəf) ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lang/{locale}', [HomeController::class, 'changeLanguage'])->name('lang.switch');
// Əlaqə formu göndərmək üçün POST marşrutu
Route::post('/contact-submit', [HomeController::class, 'sendContactMessage'])->name('contact.send');

// --- GİRİŞ SİSTEMİ ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- 2FA (İki Faktorlu Doğrulama) ---
Route::get('/2fa', [AuthController::class, 'showTwoFactor'])->name('2fa.index');
Route::post('/2fa', [AuthController::class, 'verifyTwoFactor'])->name('2fa.verify');
Route::post('/2fa/resend', [AuthController::class, 'resendTwoFactor'])->name('2fa.resend');

// --- ÖDƏNİŞ SİSTEMİ (Publik) ---
Route::post('/payment/checkout/{plugin}', [PaymentController::class, 'checkout'])->name('payment.checkout');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback'); // CSRF istisnası tələb edə bilər

// --- ADMIN PANELİ (Qorunan Bölmə) ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Marketinq & Satış
    Route::get('/sales', [AdminController::class, 'sales'])->name('sales'); // Ödənişlər
    Route::get('/notification', [AdminController::class, 'notification'])->name('notification');
    Route::post('/notification', [AdminController::class, 'storeNotification'])->name('notification.store');

    Route::get('/subscribers', [AdminController::class, 'subscribers'])->name('subscribers');
    Route::delete('/subscribers/{id}', [AdminController::class, 'deleteSubscriber'])->name('subscribers.delete');

    // Məzmun İdarəetməsi
    // Ana Səhifə Redaktəsi
    Route::get('/home-content', [AdminController::class, 'homeContent'])->name('home_content');
    Route::post('/home-content', [AdminController::class, 'updateHomeContent'])->name('home_content.update');

    Route::get('/update', [AdminController::class, 'update'])->name('update');
    Route::post('/update', [AdminController::class, 'storeUpdate'])->name('update.store');
    Route::delete('/update/{id}', [AdminController::class, 'deleteUpdate'])->name('update.delete');

    Route::get('/plugins', [AdminController::class, 'plugins'])->name('plugins');
    Route::post('/plugins', [AdminController::class, 'storePlugin'])->name('plugins.store');
    Route::delete('/plugins/{id}', [AdminController::class, 'deletePlugin'])->name('plugins.delete');

    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products', [AdminController::class, 'productStore'])->name('products.store');
    Route::delete('/products/{id}', [AdminController::class, 'productDelete'])->name('products.delete');

    Route::get('/about', [AdminController::class, 'about'])->name('about');
    Route::post('/about', [AdminController::class, 'updateAbout'])->name('about.update');

    Route::get('/contact', [AdminController::class, 'contact'])->name('contact');
    Route::post('/contact', [AdminController::class, 'updateContact'])->name('contact.update');

    // Sistem
    Route::get('/accounts', [AdminController::class, 'accounts'])->name('accounts');
    Route::post('/accounts', [AdminController::class, 'accountUpdate'])->name('accounts.update');
    Route::get('/api', [AdminController::class, 'api'])->name('api');

    // Tənzimləmələr
    Route::get('/settings/site', [AdminController::class, 'siteSettings'])->name('site_settings');
    Route::post('/settings/site', [AdminController::class, 'updateSiteSettings'])->name('site_settings.update');

    Route::get('/settings/menu', [AdminController::class, 'menu'])->name('menu');
    Route::post('/settings/menu', [AdminController::class, 'updateMenu'])->name('menu.update');
    Route::post('/settings/menu/store', [AdminController::class, 'storeMenu'])->name('menu.store');
    Route::delete('/settings/menu/{id}', [AdminController::class, 'deleteMenu'])->name('menu.delete');
    Route::post('/settings/menu/sort', [AdminController::class, 'sortMenu'])->name('menu.sort');

    Route::get('/settings/payment', [AdminController::class, 'paymentSettings'])->name('payment');
    Route::post('/settings/payment', [AdminController::class, 'updatePaymentSettings'])->name('payment.update');

    Route::get('/settings/smtp', [AdminController::class, 'smtp'])->name('smtp');
    Route::post('/settings/smtp', [AdminController::class, 'updateSmtp'])->name('smtp.update');

    Route::get('/settings/translation', [AdminController::class, 'translation'])->name('translation');
    Route::post('/settings/translation/store', [AdminController::class, 'storeTranslation'])->name('translation.store');
    Route::post('/settings/translation/update', [AdminController::class, 'updateTranslation'])->name('translation.update');
    Route::delete('/settings/translation/{id}', [AdminController::class, 'deleteTranslation'])->name('translation.delete');

    // Köhnə Settings linki yönləndirmə
    Route::get('/settings', function() { return redirect()->route('admin.site_settings'); })->name('settings');
});

// Dinamik Səhifələr (Ən sonda olmalıdır ki, digər routeları əzməsin)
// Məsələn: /about, /contact, /updates, /plugins (frontend) və s.
Route::get('/{slug}', [HomeController::class, 'page'])->name('page');

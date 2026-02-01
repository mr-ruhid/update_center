@extends('admin.layout')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Sayt Ayarları</h1>
            <p class="text-sm text-gray-500">Logo, favicon, SEO və Təhlükəsizlik tənzimləmələri.</p>
        </div>
    </div>

    <!-- Mesajlar -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <i class="fa-solid fa-check mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.site_settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- SOL TƏRƏF: ÜMUMİ VƏ ŞƏKİLLƏR -->
            <div class="md:col-span-1 space-y-6">

                <!-- Logo -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
                    <h3 class="font-semibold text-gray-800 mb-4 text-left">Sayt Logosu</h3>

                    <div class="bg-gray-100 rounded-lg p-4 mb-4 flex items-center justify-center border border-dashed border-gray-300 min-h-[120px]">
                        @if($settings->logo)
                            <img src="{{ asset('uploads/settings/'.$settings->logo) }}" alt="Logo" class="max-h-20 max-w-full">
                        @else
                            <span class="text-gray-400 text-sm">Logo Yüklənməyib</span>
                        @endif
                    </div>

                    <label class="block">
                        <span class="sr-only">Logo seç</span>
                        <input type="file" name="logo" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    </label>
                </div>

                <!-- Favicon -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
                    <h3 class="font-semibold text-gray-800 mb-4 text-left">Favicon (İkon)</h3>

                    <div class="bg-gray-100 rounded-lg p-4 mb-4 flex items-center justify-center border border-dashed border-gray-300 min-h-[80px]">
                        @if($settings->favicon)
                            <img src="{{ asset('uploads/settings/'.$settings->favicon) }}" alt="Favicon" class="h-10 w-10">
                        @else
                            <span class="text-gray-400 text-sm">Yoxdur</span>
                        @endif
                    </div>

                    <label class="block">
                        <span class="sr-only">Favicon seç</span>
                        <input type="file" name="favicon" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    </label>
                </div>

            </div>

            <!-- SAĞ TƏRƏF: MƏLUMATLAR VƏ SEO -->
            <div class="md:col-span-2 space-y-6">

                <!-- Sayt Adı -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sayt Adı</label>
                        <input type="text" name="site_name" value="{{ $settings->site_name }}" placeholder="RJ Site Updater" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                        <p class="text-xs text-gray-400 mt-1">Browser başlığında və footer-də görünür.</p>
                    </div>
                </div>

                <!-- 2FA Aktivləşdirmə (YENİ) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-800">İki Faktorlu Doğrulama (2FA)</h3>
                            <p class="text-sm text-gray-500">Admin girişində emailə kod göndərilməsini aktivləşdir.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_2fa" value="1" class="sr-only peer" {{ ($settings->enable_2fa ?? false) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- SMTP Xəbərdarlığı -->
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-start gap-3">
                        <div class="text-yellow-600 mt-0.5"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <p class="text-xs text-yellow-700 leading-relaxed">
                            <strong>Vacib Qeyd:</strong> Bu funksiyanı aktivləşdirməzdən əvvəl <a href="{{ route('admin.smtp') }}" class="font-bold underline hover:text-yellow-900">SMTP Mail Ayarlarının</a> düzgün quraşdırıldığından əmin olun. Əks halda sistem kod göndərə bilməyəcək və giriş edə bilməyəcəksiniz.
                        </p>
                    </div>
                </div>

                <!-- SEO Ayarları -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                        <div class="bg-purple-100 p-1.5 rounded text-purple-600">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">SEO Tənzimləmələri</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SEO Başlığı (Title)</label>
                            <input type="text" name="seo_title" value="{{ $settings->seo_title }}" placeholder="Əsas Səhifə - RJ Site Updater" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SEO Açıqlama (Description)</label>
                            <textarea name="seo_description" rows="3" placeholder="Sayt haqqında qısa məlumat..." class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none text-sm">{{ $settings->seo_description }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Açar Sözlər (Keywords)</label>
                            <input type="text" name="seo_keywords" value="{{ $settings->seo_keywords }}" placeholder="software, update, admin panel, laravel" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none text-sm">
                            <p class="text-xs text-gray-400 mt-1">Sözləri vergül ilə ayırın.</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 text-right">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors flex items-center justify-center ml-auto">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Yadda Saxla
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection

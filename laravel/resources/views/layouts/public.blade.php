<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Cryptomus Domain Verification -->
    <meta name="cryptomus" content="0f1c989e" />

    <title>@yield('title', $settings->site_name ?? 'RJ Site Updater')</title>
    <meta name="description" content="{{ $settings->seo_description ?? '' }}">
    <meta name="keywords" content="{{ $settings->seo_keywords ?? '' }}">

    <!-- Favicon -->
    @if(isset($settings->favicon) && $settings->favicon)
        <link rel="icon" href="{{ asset('uploads/settings/'.$settings->favicon) }}">
    @endif

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        /* CKEditor content styles */
        .prose h1 { font-size: 2rem; font-weight: 800; margin-bottom: 1rem; color: #1f2937; }
        .prose h2 { font-size: 1.5rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 1rem; color: #374151; }
        .prose p { margin-bottom: 1rem; color: #4b5563; line-height: 1.7; }
        .prose ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
        .prose ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
        .prose a { color: #2563eb; text-decoration: underline; }
        .prose blockquote { border-left: 4px solid #e5e7eb; padding-left: 1rem; font-style: italic; color: #6b7280; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- HEADER (Ortaq) -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        @if(isset($settings->logo) && $settings->logo)
                            <img class="h-10 w-auto" src="{{ asset('uploads/settings/'.$settings->logo) }}" alt="Logo">
                        @else
                            <span class="text-2xl font-bold text-blue-600">{{ $settings->site_name ?? 'RJ UPDATER' }}</span>
                        @endif
                    </a>
                </div>

                <!-- Dinamik Menyu -->
                <nav class="hidden md:flex space-x-8">
                    @if(isset($menus))
                        @foreach($menus as $menu)
                            <a href="{{ $menu->url }}" class="text-sm font-medium {{ request()->url() == $menu->url ? 'text-blue-600' : 'text-gray-500 hover:text-blue-600' }} transition-colors">
                                <!-- Tərcüməni yoxlayır, tapmasa fallback dəyərləri göstərir -->
                                {{ \App\Models\Translation::get($menu->key) ?? $menu->title[app()->getLocale()] ?? 'Menu' }}
                            </a>
                        @endforeach
                    @endif
                </nav>

                <!-- Sağ Tərəf: Dil və Login -->
                <div class="flex items-center space-x-4">

                    <!-- Dil Seçimi (KLİKLƏ AÇILAN) -->
                    <div class="relative">
                        <button id="lang-btn" onclick="document.getElementById('lang-dropdown').classList.toggle('hidden')" class="flex items-center bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-full transition-all duration-300 border border-gray-200 focus:outline-none select-none">
                            @php
                                $flags = [
                                    'az' => 'https://flagcdn.com/w40/az.png',
                                    'en' => 'https://flagcdn.com/w40/gb.png',
                                    'ru' => 'https://flagcdn.com/w40/ru.png',
                                    'tr' => 'https://flagcdn.com/w40/tr.png'
                                ];
                                $currentLang = app()->getLocale();
                            @endphp
                            <img src="{{ $flags[$currentLang] ?? $flags['az'] }}" alt="{{ $currentLang }}" class="w-5 h-5 rounded-full object-cover mr-2 shadow-sm">
                            <span class="uppercase font-bold text-xs text-gray-600">{{ $currentLang }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] ml-2 text-gray-400"></i>
                        </button>

                        <!-- Dropdown (group-hover silindi, id əlavə edildi) -->
                        <div id="lang-dropdown" class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl py-2 border border-gray-100 hidden z-50">
                            <div class="px-4 py-2 border-b border-gray-50 text-xs text-gray-400 font-semibold uppercase tracking-wider">
                                {{ \App\Models\Translation::get('select_language') ?? 'Dil seçin' }}
                            </div>
                            <a href="{{ route('lang.switch', 'az') }}" class="flex items-center px-4 py-3 hover:bg-blue-50 transition-colors {{ $currentLang == 'az' ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                                <img src="{{ $flags['az'] }}" class="w-5 h-5 rounded-full mr-3 shadow-sm"> Azərbaycan
                            </a>
                            <a href="{{ route('lang.switch', 'en') }}" class="flex items-center px-4 py-3 hover:bg-blue-50 transition-colors {{ $currentLang == 'en' ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                                <img src="{{ $flags['en'] }}" class="w-5 h-5 rounded-full mr-3 shadow-sm"> English
                            </a>
                            <a href="{{ route('lang.switch', 'ru') }}" class="flex items-center px-4 py-3 hover:bg-blue-50 transition-colors {{ $currentLang == 'ru' ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                                <img src="{{ $flags['ru'] }}" class="w-5 h-5 rounded-full mr-3 shadow-sm"> Русский
                            </a>
                            <a href="{{ route('lang.switch', 'tr') }}" class="flex items-center px-4 py-3 hover:bg-blue-50 transition-colors {{ $currentLang == 'tr' ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                                <img src="{{ $flags['tr'] }}" class="w-5 h-5 rounded-full mr-3 shadow-sm"> Türkçe
                            </a>
                        </div>
                    </div>

                    @guest
                        <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            {{ \App\Models\Translation::get('login_btn') ?? 'Daxil Ol' }}
                        </a>
                    @endguest
                </div>

                <!-- Mobil Menyu Düyməsi -->
                <div class="md:hidden flex items-center">
                    <button class="text-gray-500 hover:text-gray-900 focus:outline-none" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobil Menyu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                @if(isset($menus))
                    @foreach($menus as $menu)
                        <a href="{{ $menu->url }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                            {{ \App\Models\Translation::get($menu->key) ?? $menu->title[app()->getLocale()] ?? 'Menu' }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </header>

    <!-- CONTENT (Səhifəyə özəl məzmun) -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER (Ortaq) -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-400 text-sm mb-4 md:mb-0">&copy; {{ date('Y') }} {{ $settings->site_name ?? 'RJ Site Updater' }}. Bütün hüquqlar qorunur.</p>
            <div class="flex space-x-6 text-xl">
                @if(isset($contact))
                    @if($contact->facebook) <a href="{{ $contact->facebook }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors"><i class="fa-brands fa-facebook"></i></a> @endif
                    @if($contact->instagram) <a href="{{ $contact->instagram }}" target="_blank" class="text-gray-400 hover:text-pink-600 transition-colors"><i class="fa-brands fa-instagram"></i></a> @endif
                    @if($contact->twitter) <a href="{{ $contact->twitter }}" target="_blank" class="text-gray-400 hover:text-black transition-colors"><i class="fa-brands fa-x-twitter"></i></a> @endif
                    @if($contact->linkedin) <a href="{{ $contact->linkedin }}" target="_blank" class="text-gray-400 hover:text-blue-700 transition-colors"><i class="fa-brands fa-linkedin"></i></a> @endif
                    @if($contact->github) <a href="{{ $contact->github }}" target="_blank" class="text-gray-400 hover:text-gray-900 transition-colors"><i class="fa-brands fa-github"></i></a> @endif
                    @if($contact->behance) <a href="{{ $contact->behance }}" target="_blank" class="text-gray-400 hover:text-blue-500 transition-colors"><i class="fa-brands fa-behance"></i></a> @endif
                    @if($contact->tiktok) <a href="{{ $contact->tiktok }}" target="_blank" class="text-gray-400 hover:text-black transition-colors"><i class="fa-brands fa-tiktok"></i></a> @endif
                    @if($contact->threads) <a href="{{ $contact->threads }}" target="_blank" class="text-gray-400 hover:text-black transition-colors"><i class="fa-brands fa-threads"></i></a> @endif
                @endif
            </div>
        </div>
    </footer>

    <!-- Kənara kliklədikdə dil menyusunu bağlamaq üçün script -->
    <script>
        document.addEventListener('click', function(e) {
            const btn = document.getElementById('lang-btn');
            const dropdown = document.getElementById('lang-dropdown');
            // Əgər kliklənən yer nə düymə, nə də menyu deyilsə, menyunu bağla
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

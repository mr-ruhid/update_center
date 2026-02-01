<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RJ Site Updater - Admin Panel</title>
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col shadow-xl transition-all duration-300 z-20 hidden md:flex">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b border-slate-700 bg-slate-950">
                <div class="flex items-center gap-2 font-bold text-xl tracking-wider text-blue-400">
                    <i class="fa-solid fa-rotate"></i>
                    <span>RJ SITE <span class="text-white">UPDATER</span></span>
                </div>
            </div>

            <!-- Menyu -->
            <nav class="flex-1 overflow-y-auto no-scrollbar py-4 px-3 space-y-1">

                <!-- Sayta Bax -->
                <a href="{{ url('/') }}" target="_blank" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md text-emerald-400 hover:bg-slate-800 hover:text-white transition-colors mb-4 border border-slate-800 bg-slate-800/30">
                    <i class="fa-solid fa-globe w-6 text-center text-lg mr-2 group-hover:animate-pulse"></i>
                    Sayta Bax
                </a>

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors mb-4 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high w-6 text-center text-lg mr-2"></i>
                    Ana Səhifə
                </a>

                <!-- BÖLMƏ: MARKETİNQ -->
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-2">Marketinq & Satış</p>

                <!-- Ödənişlər (YENİ) -->
                <a href="{{ route('admin.sales') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.sales') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-6 text-center text-lg mr-2"></i>
                    Ödəniş Tarixçəsi
                </a>

                <a href="{{ route('admin.notification') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.notification') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-bullhorn w-6 text-center text-lg mr-2"></i>
                    Bildiriş Göndər
                </a>

                <a href="{{ route('admin.subscribers') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.subscribers') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users w-6 text-center text-lg mr-2"></i>
                    Abonələr
                </a>

                <!-- BÖLMƏ: MƏZMUN İDARƏETMƏSİ -->
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Məzmun İdarəetməsi</p>

                <a href="{{ route('admin.home_content') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.home_content') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-house-chimney w-6 text-center text-lg mr-2"></i>
                    Ana Səhifə
                </a>

                <a href="{{ route('admin.update') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.update') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-cloud-arrow-up w-6 text-center text-lg mr-2"></i>
                    Yenilik Paylaş
                </a>

                <a href="{{ route('admin.plugins') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.plugins') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-puzzle-piece w-6 text-center text-lg mr-2"></i>
                    Pluginlər
                </a>

                <a href="{{ route('admin.products') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.products') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-regular fa-images w-6 text-center text-lg mr-2"></i>
                    Məhsul Şəkilləri
                </a>

                <a href="{{ route('admin.about') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.about') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-address-card w-6 text-center text-lg mr-2"></i>
                    Haqqımızda
                </a>

                <a href="{{ route('admin.contact') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.contact') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-envelope w-6 text-center text-lg mr-2"></i>
                    Əlaqə Bölməsi
                </a>

                <!-- BÖLMƏ: SİSTEM -->
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Sistem</p>

                <a href="{{ route('admin.accounts') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.accounts') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-shield w-6 text-center text-lg mr-2"></i>
                    Hesablar
                </a>

                <a href="{{ route('admin.api') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.api') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-code w-6 text-center text-lg mr-2"></i>
                    API İnteqrasiya
                </a>

                <!-- BÖLMƏ: TƏNZİMLƏMƏLƏR -->
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Tənzimləmələr</p>

                <a href="{{ route('admin.site_settings') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.site_settings') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-sliders w-6 text-center text-lg mr-2"></i>
                    Sayt Ayarları
                </a>

                <a href="{{ route('admin.menu') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.menu') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-bars w-6 text-center text-lg mr-2"></i>
                    Menyu
                </a>

                <a href="{{ route('admin.payment') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.payment') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-regular fa-credit-card w-6 text-center text-lg mr-2"></i>
                    Ödəmə Ayarları
                </a>

                <a href="{{ route('admin.smtp') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.smtp') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-server w-6 text-center text-lg mr-2"></i>
                    SMTP (Mail)
                </a>

                <a href="{{ route('admin.translation') }}"
                   class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors {{ request()->routeIs('admin.translation') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-language w-6 text-center text-lg mr-2"></i>
                    Tərcümə
                </a>

            </nav>

            <!-- User Info -->
            <div class="border-t border-slate-700 p-4 bg-slate-950">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8 rounded-full border border-slate-600" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=3b82f6&color=fff" alt="Admin">
                    </div>
                    <div class="ml-3 overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-medium text-slate-400 hover:text-white focus:outline-none">Çıxış et</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT WRAPPER -->
        <main class="flex-1 flex flex-col overflow-hidden relative">

            <!-- Mobile Header -->
            <header class="md:hidden flex items-center justify-between bg-slate-900 text-white h-16 px-4 shadow-md z-10">
                <div class="font-bold text-lg">RJ Site Updater</div>
                <button id="mobileMenuBtn" class="text-gray-300 hover:text-white focus:outline-none">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </header>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto bg-gray-50 p-6 relative">
                @yield('content')
            </div>
        </main>

        <!-- Mobile Overlay -->
        <div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>
    </div>

    <!-- Toggle Script -->
    <script>
        const btn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('aside');
        const overlay = document.getElementById('mobileMenuOverlay');

        function toggleMenu() {
            if (sidebar) {
                sidebar.classList.toggle('hidden');
                sidebar.classList.toggle('absolute');
                sidebar.classList.toggle('inset-y-0');
                sidebar.classList.toggle('left-0');
                sidebar.classList.toggle('flex');
                overlay.classList.toggle('hidden');
            }
        }

        if(btn) btn.addEventListener('click', toggleMenu);
        if(overlay) overlay.addEventListener('click', toggleMenu);
    </script>
</body>
</html>

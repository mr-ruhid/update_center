@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Mesajlar -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Hesab Tənzimləmələri</h1>
            <p class="text-sm text-gray-500">Admin giriş məlumatlarını buradan yeniləyə bilərsiniz.</p>
        </div>
        <div class="bg-purple-50 text-purple-700 px-4 py-2 rounded-lg text-sm font-medium border border-purple-100">
            <i class="fa-solid fa-user-shield mr-2"></i>
            Rol: <span class="font-bold">Super Admin</span>
        </div>
    </div>

    <form action="{{ route('admin.accounts.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- SOL TƏRƏF: PROFİL KARTI -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center sticky top-6">
                    <div class="w-24 h-24 bg-slate-100 rounded-full mx-auto mb-4 flex items-center justify-center text-slate-400 text-4xl border-4 border-white shadow-sm">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">{{ auth()->user()->name ?? 'Admin' }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ auth()->user()->email ?? 'admin@example.com' }}</p>

                    <div class="border-t border-gray-100 pt-4 mt-4 text-left">
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-2">Təhlükəsizlik</p>
                        <div class="flex items-center text-sm text-green-600 mb-2">
                            <i class="fa-solid fa-shield-halved w-6"></i> Hesab Aktivdir
                        </div>
                        <div class="flex items-center text-sm text-blue-600">
                            <i class="fa-solid fa-clock w-6"></i> Son Giriş: İndi
                        </div>
                    </div>
                </div>
            </div>

            <!-- SAĞ TƏRƏF: FORMA -->
            <div class="md:col-span-2 space-y-6">

                <!-- 1. Ümumi Məlumatlar -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 font-semibold text-gray-800">
                        Profil Məlumatları
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ad Soyad</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-regular fa-user"></i></span>
                                <input type="text" name="name" value="{{ auth()->user()->name ?? '' }}" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Ünvanı</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" name="email" value="{{ auth()->user()->email ?? '' }}" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Şifrə Dəyişimi -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 font-semibold text-gray-800 flex items-center justify-between">
                        <span>Şifrəni Yenilə</span>
                        <span class="text-xs font-normal text-gray-500 bg-gray-200 px-2 py-0.5 rounded">Dəyişmək istəmirsinizsə boş buraxın</span>
                    </div>
                    <div class="p-6 space-y-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Şifrə (Təsdiq üçün)</label>
                            <input type="password" name="current_password" placeholder="********" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                            <p class="text-xs text-gray-400 mt-1">Məlumatları dəyişmək üçün hazırki şifrənizi yazmalısınız.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Yeni Şifrə</label>
                                <input type="password" name="new_password" placeholder="Yeni şifrə" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Yeni Şifrə (Təkrar)</label>
                                <input type="password" name="new_password_confirmation" placeholder="Təkrar yazın" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                            </div>
                        </div>

                    </div>

                    <!-- Footer Button -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors shadow-sm flex items-center">
                            <i class="fa-solid fa-floppy-disk mr-2"></i>
                            Yadda Saxla
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

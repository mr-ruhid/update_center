@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">SMTP Mail Ayarları</h1>
            <p class="text-sm text-gray-500">Saytın email göndərmə sistemini (Server) konfiqurasiya edin.</p>
        </div>

        <!-- Status Badge (Vizual) -->
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium border border-blue-100 flex items-center">
            <i class="fa-solid fa-server mr-2"></i> Status: <span class="font-bold ml-1">Konfiqurasiya</span>
        </div>
    </div>

    <!-- Mesajlar -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative flex items-center">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.smtp.update') }}" method="POST">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                <div class="bg-indigo-100 p-1.5 rounded text-indigo-600">
                    <i class="fa-solid fa-envelope-circle-check text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Server Məlumatları</h3>
            </div>

            <div class="p-6 space-y-6">

                <!-- Host & Port -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mail Host</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-globe"></i></span>
                            <input type="text" name="mail_host" value="{{ $smtp->mail_host ?? '' }}" placeholder="smtp.gmail.com" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none transition-all font-mono text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-door-open"></i></span>
                            <input type="number" name="mail_port" value="{{ $smtp->mail_port ?? '587' }}" placeholder="587" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none transition-all font-mono text-sm">
                        </div>
                    </div>
                </div>

                <!-- Username & Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username (Email)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="mail_username" value="{{ $smtp->mail_username ?? '' }}" placeholder="email@example.com" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password (App Password)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-key"></i></span>
                            <input type="password" name="mail_password" value="{{ $smtp->mail_password ?? '' }}" placeholder="********" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100">

                <!-- Encryption & Sender Info -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                        <select name="mail_encryption" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none bg-white">
                            <option value="tls" {{ ($smtp->mail_encryption ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($smtp->mail_encryption ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="null" {{ ($smtp->mail_encryption ?? '') == 'null' ? 'selected' : '' }}>Yoxdur</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Address (Göndərən Email)</label>
                        <input type="email" name="mail_from_address" value="{{ $smtp->mail_from_address ?? '' }}" placeholder="no-reply@sayt.com" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Name (Göndərən Adı)</label>
                    <input type="text" name="mail_from_name" value="{{ $smtp->mail_from_name ?? config('app.name') }}" placeholder="RJ Site Updater" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                </div>

            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                <p class="text-xs text-gray-500">
                    <i class="fa-solid fa-circle-info mr-1 text-blue-500"></i>
                    Gmail istifadə edirsinizsə, "App Password" yaratmalısınız.
                </p>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition-colors flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Yadda Saxla
                </button>
            </div>

        </div>
    </form>
</div>
@endsection

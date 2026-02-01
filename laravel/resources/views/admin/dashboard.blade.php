@extends('admin.layout')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <!-- Başlıq -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ana Səhifə</h1>
            <p class="text-sm text-gray-500">Sisteminizin ümumi vəziyyəti və son hərəkətlər.</p>
        </div>
        <div class="text-right">
            <span class="block text-xs text-gray-400">Server Vaxtı</span>
            <span class="text-sm font-mono font-medium text-gray-700">{{ now()->format('d.m.Y H:i') }}</span>
        </div>
    </div>

    <!-- 1. Statistik Kartlar -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Updates -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm text-gray-500 font-medium">Yeniliklər</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['updates'] ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-rocket"></i>
            </div>
        </div>

        <!-- Plugins -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm text-gray-500 font-medium">Pluginlər</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['plugins'] ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-puzzle-piece"></i>
            </div>
        </div>

        <!-- Subscribers -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm text-gray-500 font-medium">Abonələr</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['subscribers'] ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Products -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-sm text-gray-500 font-medium">Məhsullar</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['products'] ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xl">
                <i class="fa-regular fa-images"></i>
            </div>
        </div>

    </div>

    <!-- 2. Son Hərəkətlər (Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- SOL: Son Yeniliklər və Pluginlər -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Son Yeniliklər -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Son Yüklənən Yeniliklər</h3>
                    <a href="{{ route('admin.update') }}" class="text-xs text-blue-600 hover:underline">Hamısına bax</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentUpdates as $upd)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <span class="font-mono text-xs font-bold bg-blue-100 text-blue-700 px-2 py-1 rounded">{{ $upd->version }}</span>
                            <div>
                                <p class="text-sm font-medium text-gray-900 truncate w-64" title="{{ $upd->changelog }}">{{ Str::limit($upd->changelog, 50) }}</p>
                                <p class="text-xs text-gray-400">{{ $upd->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($upd->is_active)
                                <span class="text-[10px] text-green-600 bg-green-50 px-2 py-1 rounded border border-green-100">Aktiv</span>
                            @else
                                <span class="text-[10px] text-gray-500 bg-gray-100 px-2 py-1 rounded border border-gray-200">Gizli</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-400 text-sm">Hələ heç bir yenilik yoxdur.</div>
                    @endforelse
                </div>
            </div>

            <!-- Son Pluginlər -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Yeni Pluginlər</h3>
                    <a href="{{ route('admin.plugins') }}" class="text-xs text-purple-600 hover:underline">Hamısına bax</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentPlugins as $pl)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded bg-purple-50 flex items-center justify-center text-purple-500 border border-purple-100">
                                @if($pl->image)
                                    <img src="{{ asset('uploads/plugins/'.$pl->image) }}" class="w-full h-full object-cover rounded">
                                @else
                                    <i class="fa-solid fa-cube"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $pl->name }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $pl->version }}</p>
                            </div>
                        </div>
                        <div>
                            @if($pl->is_free)
                                <span class="text-xs font-bold text-green-600 border border-green-200 bg-green-50 px-2 py-0.5 rounded">FREE</span>
                            @else
                                <span class="text-xs font-bold text-yellow-600 border border-yellow-200 bg-yellow-50 px-2 py-0.5 rounded">${{ $pl->price }}</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-400 text-sm">Hələ heç bir plugin yüklənməyib.</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- SAĞ: GİRİŞ TARİXÇƏSİ -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-6">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <div class="bg-orange-100 p-1.5 rounded text-orange-600">
                        <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">Son Girişlər</h3>
                </div>

                <div class="divide-y divide-gray-100 max-h-[500px] overflow-y-auto custom-scrollbar">
                    @forelse($loginLogs as $log)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start mb-1">
                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1">
                                <i class="fa-regular fa-user text-gray-400"></i>
                                {{ $log->user->name ?? 'Naməlum' }}
                            </span>
                            <span class="text-[10px] text-gray-400">{{ $log->login_at->format('d M H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500 font-mono mb-1">
                            <i class="fa-solid fa-network-wired text-[10px] text-blue-400"></i> {{ $log->ip_address }}
                        </div>
                        <div class="text-[10px] text-gray-400 truncate" title="{{ $log->user_agent }}">
                            {{ Str::limit($log->user_agent, 40) }}
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400 text-sm">
                        <i class="fa-solid fa-shield-cat text-3xl mb-2 opacity-30 block text-orange-400"></i>
                        Giriş qeydi yoxdur.
                    </div>
                    @endforelse
                </div>

                <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.accounts') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium hover:underline">Hesab Təhlükəsizliyi</a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Custom Scrollbar for Logs */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #ccc;
    }
</style>
@endsection

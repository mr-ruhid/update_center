@extends('admin.layout')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Bildiriş Mərkəzi</h1>
            <p class="text-sm text-gray-500">Müştərilərə anlıq mesajlar göndərin.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <i class="fa-solid fa-check mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- SOL: FORM -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <div class="bg-orange-100 p-1.5 rounded text-orange-600">
                        <i class="fa-solid fa-bullhorn text-sm"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">Yeni Bildiriş</h3>
                </div>

                <form action="{{ route('admin.notification.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Başlıq</label>
                        <input type="text" name="title" required placeholder="Məs: Sistem Baxışı" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Mesaj</label>
                        <textarea name="message" rows="4" placeholder="Bildiriş mətni..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-orange-500 outline-none transition-all text-sm"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Yönləndirmə Linki (Opsional)</label>
                        <input type="url" name="url" placeholder="https://..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-orange-500 outline-none text-blue-600">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Versiya (Opsional)</label>
                        <input type="text" name="version" placeholder="Məs: v1.0.2" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-orange-500 outline-none font-mono text-sm">
                        <p class="text-[10px] text-gray-400 mt-1">Boş buraxsanız bütün versiyalara gedəcək.</p>
                    </div>

                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2.5 rounded-lg font-medium transition-colors shadow-sm flex justify-center items-center">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Yayımla
                    </button>
                </form>
            </div>
        </div>

        <!-- SAĞ: TARİXÇƏ -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-800">Son Göndərilənlər</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($notifications as $note)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold text-gray-800">{{ $note->title }}</h4>
                                <span class="text-xs text-gray-400">{{ $note->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $note->message }}</p>
                            <div class="flex items-center gap-3">
                                @if($note->url)
                                    <a href="{{ $note->url }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center">
                                        <i class="fa-solid fa-link mr-1"></i> Linkə keç
                                    </a>
                                @endif
                                @if($note->version)
                                    <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded text-gray-500 font-mono">{{ $note->version }}</span>
                                @else
                                    <span class="text-[10px] bg-green-50 px-2 py-0.5 rounded text-green-600 border border-green-100">Hamı</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-400">
                            <i class="fa-regular fa-bell-slash text-3xl mb-2 opacity-50 block"></i>
                            Hələ heç bir bildiriş göndərilməyib.
                        </div>
                    @endforelse
                </div>

                <div class="px-5 py-3 border-t border-gray-100">
                    {{ $notifications->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

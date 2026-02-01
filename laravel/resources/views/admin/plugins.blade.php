@extends('admin.layout')

@section('content')
<div class="max-w-6xl mx-auto">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Plugin Mərkəzi</h1>
            <p class="text-sm text-gray-500">Sisteminizi genişləndirmək üçün əlavələr (addons) yükləyin.</p>
        </div>
        <div class="bg-purple-50 text-purple-700 px-4 py-2 rounded-lg text-sm font-medium border border-purple-100">
            <i class="fa-solid fa-puzzle-piece mr-2"></i>
            Aktiv Pluginlər: <span class="font-bold">{{ $plugins->total() ?? 0 }}</span>
        </div>
    </div>

    <!-- Mesajlar -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <i class="fa-solid fa-check mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- SOL: YÜKLƏMƏ FORMASI -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <div class="bg-purple-100 p-1.5 rounded text-purple-600">
                        <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">Yeni Plugin Yüklə</h3>
                </div>

                <form action="{{ route('admin.plugins.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf

                    <!-- Ad -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Plugin Adı <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Məs: WhatsApp Modulu" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>

                    <!-- Açıqlama (YENİ) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Açıqlama</label>
                        <textarea name="description" rows="3" placeholder="Plugin haqqında qısa məlumat..." class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all text-sm"></textarea>
                    </div>

                    <!-- Versiya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Versiya</label>
                        <input type="text" name="version" required placeholder="v1.0.0" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-purple-500 outline-none font-mono text-sm">
                    </div>

                    <!-- Ödəniş Seçimi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ödəniş Növü</label>
                        <div class="flex gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_free" value="1" checked class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500" onchange="togglePrice(false)">
                                <span class="ml-2 text-sm text-gray-700">Pulsuz (Free)</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="is_free" value="0" class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500" onchange="togglePrice(true)">
                                <span class="ml-2 text-sm text-gray-700">Ödənişli (Paid)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Qiymət (Gizli) -->
                    <div id="price_section" class="hidden space-y-4 border-l-2 border-purple-200 pl-3 mt-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Qiymət (USD - $)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" step="0.01" name="price" placeholder="10.00" class="w-full pl-6 pr-3 py-2 rounded-lg border border-gray-300 focus:ring-purple-500 outline-none font-mono">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Cryptomus ödənişləri üçün qiymət dollarla hesablanır.</p>
                        </div>
                    </div>

                    <!-- Şəkil (Icon/Cover) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">İkon / Şəkil</label>
                        <label class="flex items-center justify-center w-full h-20 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-2">
                                <i class="fa-regular fa-image text-gray-400"></i>
                                <span class="text-xs text-gray-500">Şəkil seçin (PNG/JPG)</span>
                            </div>
                            <input name="image" type="file" class="hidden" accept="image/*" />
                        </label>
                    </div>

                    <!-- Fayl (Zip) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Plugin Faylı (.zip) <span class="text-red-500">*</span></label>
                        <input type="file" name="file" required accept=".zip" class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                        <p class="text-[10px] text-gray-400 mt-1">Maksimum ölçü: 50MB</p>
                    </div>

                    <!-- Düymə -->
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2.5 rounded-lg font-medium transition-colors shadow-sm flex justify-center items-center">
                        <i class="fa-solid fa-upload mr-2"></i> Yüklə
                    </button>
                </form>
            </div>
        </div>

        <!-- SAĞ: PLUGİN SİYAHISI -->
        <div class="lg:col-span-2">

            @if(isset($plugins) && $plugins->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                @foreach($plugins as $plugin)
                <div class="bg-white rounded-xl border border-gray-200 p-4 flex gap-4 hover:shadow-md transition-shadow relative group">

                    <!-- Şəkil -->
                    <div class="w-16 h-16 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden border border-gray-100 flex items-center justify-center">
                        @if($plugin->image)
                            <img src="{{ asset('uploads/plugins/'.$plugin->image) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-cube text-2xl text-gray-300"></i>
                        @endif
                    </div>

                    <!-- Məlumat -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-gray-800 truncate pr-6">{{ $plugin->name }}</h4>

                            <!-- Silmə -->
                            <form action="{{ route('admin.plugins.delete', $plugin->id) }}" method="POST" onsubmit="return confirm('Bu plugini silmək istədiyinizə əminsiniz?');" class="absolute top-4 right-4">
                                @csrf @method('DELETE')
                                <button class="text-gray-300 hover:text-red-500 transition-colors">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>

                        <div class="flex items-center gap-2 mt-1">
                            <span class="bg-blue-50 text-blue-700 text-xs px-2 py-0.5 rounded font-mono border border-blue-100">{{ $plugin->version }}</span>

                            @if($plugin->is_free)
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded font-bold border border-green-200">FREE</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded font-bold border border-yellow-200">$ {{ $plugin->price }}</span>
                            @endif
                        </div>

                        <!-- Açıqlama (Qısa) -->
                        @if($plugin->description)
                            <p class="text-xs text-gray-500 mt-2 line-clamp-2" title="{{ $plugin->description }}">{{ $plugin->description }}</p>
                        @endif

                        <!-- Link Kopyala -->
                        <div class="mt-3">
                            <div class="flex items-center gap-2">
                                <input type="text" readonly value="{{ asset('uploads/plugins/'.$plugin->file_path) }}" class="w-full text-[10px] bg-gray-50 border border-gray-200 rounded px-2 py-1 text-gray-500 truncate select-all">
                                <button onclick="copyToClipboard('{{ asset('uploads/plugins/'.$plugin->file_path) }}')" class="text-gray-400 hover:text-blue-600" title="Yükləmə Linkini Kopyala">
                                    <i class="fa-solid fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $plugins->links('pagination::tailwind') }}
            </div>

            @else
                <!-- Boş Yer Tutucu -->
                <div class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-12 text-center text-gray-400 h-64">
                    <i class="fa-solid fa-box-open text-4xl mb-3 opacity-50"></i>
                    <h3 class="text-lg font-medium text-gray-600">Plugin yoxdur</h3>
                    <p class="text-sm">Sisteme yeni funksionallıq əlavə etmək üçün sol tərəfdən yükləyin.</p>
                </div>
            @endif

        </div>

    </div>
</div>

<script>
    function togglePrice(show) {
        const section = document.getElementById('price_section');
        if(show) {
            section.classList.remove('hidden');
        } else {
            section.classList.add('hidden');
        }
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        alert('Link kopyalandı: ' + text);
    }
</script>
@endsection

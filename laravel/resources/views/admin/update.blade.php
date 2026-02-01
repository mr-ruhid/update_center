@extends('admin.layout')

@section('content')
<div class="max-w-7xl mx-auto pb-10">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Versiya və Yenilik Paylaşımı</h1>
            <p class="text-sm text-gray-500">Proqram təminatı üçün yeni versiyaları, faylları və qiymətləri buradan idarə edin.</p>
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <!-- SOL: YENİ VERSİYA YARATMA FORMASI -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <div class="bg-blue-100 p-1.5 rounded text-blue-600">
                        <i class="fa-solid fa-rocket text-sm"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">Yeni Versiya Yayımla</h3>
                </div>

                <form action="{{ route('admin.update.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-5">
                    @csrf

                    <!-- 1. Əsas Məlumatlar -->
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Versiya Kodu <span class="text-red-500">*</span></label>
                            <input type="text" name="version" placeholder="v2.5.0" required class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 font-mono text-blue-600 font-bold">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bu yenilikdə nələr olacaq?</label>
                            <textarea name="changelog" rows="3" placeholder="- Xətalar düzəldildi&#10;- Yeni dizayn əlavə edildi" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 text-sm"></textarea>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- 2. Fayl Konfiqurasiyası (Açılıb/Bağlanan) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Paket Seçimləri</label>

                        <!-- Update Paketi Seçimi -->
                        <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="flex items-center cursor-pointer mb-2">
                                <input type="checkbox" name="has_update_file" id="chk_update" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" onchange="toggleSection('sec_update')">
                                <span class="ml-2 text-sm font-medium text-gray-800">Yalnız Update Faylı (Mövcud müştərilər)</span>
                            </label>

                            <div id="sec_update" class="hidden space-y-3 pl-6 mt-2 border-l-2 border-blue-200">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Update Faylı (.zip)</label>
                                    <input type="file" name="update_file" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Update Qiyməti (AZN)</label>
                                    <input type="number" step="0.01" name="price_update" placeholder="0.00" class="w-full px-2 py-1 text-sm rounded border border-gray-300">
                                </div>
                            </div>
                        </div>

                        <!-- Full Paket Seçimi -->
                        <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <label class="flex items-center cursor-pointer mb-2">
                                <input type="checkbox" name="has_full_file" id="chk_full" class="w-4 h-4 text-green-600 rounded focus:ring-green-500" onchange="toggleSection('sec_full')">
                                <span class="ml-2 text-sm font-medium text-gray-800">Tam Sayt Skripti (Yeni müştərilər)</span>
                            </label>

                            <div id="sec_full" class="hidden space-y-3 pl-6 mt-2 border-l-2 border-green-200">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Tam Source Code (.zip)</label>
                                    <input type="file" name="full_file" class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Satış Qiyməti (AZN)</label>
                                    <input type="number" step="0.01" name="price_full" placeholder="0.00" class="w-full px-2 py-1 text-sm rounded border border-gray-300">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- 3. Qalereya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Versiya Qalereyası</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-20 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-2 pb-3">
                                    <i class="fa-regular fa-images text-gray-400 mb-1"></i>
                                    <p class="text-xs text-gray-500">Çoxlu şəkil seçə bilərsiniz</p>
                                </div>
                                <input type="file" name="gallery[]" multiple class="hidden" />
                            </label>
                        </div>
                    </div>

                    <!-- 4. Status -->
                    <div class="flex items-center justify-between bg-yellow-50 p-3 rounded-lg border border-yellow-100">
                        <div>
                            <span class="text-sm font-medium text-gray-800 block">Saytda görünsün?</span>
                            <span class="text-xs text-gray-500">Müştərilər yeniliyi görəcək.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" class="sr-only peer" checked>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between bg-purple-50 p-3 rounded-lg border border-purple-100">
                        <div>
                            <span class="text-sm font-medium text-gray-800 block">Yükləməyə icazə ver?</span>
                            <span class="text-xs text-gray-500">Deaktiv olsa "Fayl mövcud deyil" yazılacaq.</span>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="allow_download" class="sr-only peer" checked>
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium shadow-lg shadow-blue-500/30 transition-all flex justify-center items-center">
                        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Yayımla
                    </button>
                </form>
            </div>
        </div>

        <!-- SAĞ: VERSİYA SİYAHISI -->
        <div class="xl:col-span-2">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Paylaşılan Versiyalar</h3>

            <div class="space-y-4">
                @forelse($updates as $update)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col md:flex-row gap-5 relative group">

                    <!-- Versiya Sol Panel -->
                    <div class="flex-shrink-0 flex flex-col items-center justify-center w-full md:w-24 bg-slate-50 rounded-lg border border-slate-100 p-2 text-center">
                        <span class="text-xs text-slate-400 font-mono">{{ $update->created_at->format('d M Y') }}</span>
                        <span class="text-xl font-bold text-blue-600 my-1">{{ $update->version }}</span>
                        @if($update->is_active)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">AKTİV</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500">GİZLİ</span>
                        @endif
                    </div>

                    <!-- Məlumatlar -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="font-bold text-gray-800 mb-1">Dəyişikliklər</h4>
                            <!-- Silmə Butonu -->
                            <form action="{{ route('admin.update.delete', $update->id) }}" method="POST" onsubmit="return confirm('Bu versiyanı və bütün fayllarını silmək istədiyinizə əminsiniz?');">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-600 p-1 opacity-0 group-hover:opacity-100 transition-opacity" title="Sil">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        <p class="text-sm text-gray-600 mb-3 whitespace-pre-line">{{ $update->changelog }}</p>

                        <!-- Paketlər -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            @if($update->has_update_file)
                                <span class="inline-flex items-center px-2.5 py-1 rounded border border-blue-200 bg-blue-50 text-blue-700 text-xs font-medium">
                                    <i class="fa-solid fa-file-zipper mr-1.5"></i> Update Paketi ({{ $update->price_update ?? '0' }} ₼)
                                </span>
                            @endif
                            @if($update->has_full_file)
                                <span class="inline-flex items-center px-2.5 py-1 rounded border border-green-200 bg-green-50 text-green-700 text-xs font-medium">
                                    <i class="fa-solid fa-box-open mr-1.5"></i> Tam Versiya ({{ $update->price_full ?? '0' }} ₼)
                                </span>
                            @endif

                            @if(!$update->allow_download)
                                <span class="inline-flex items-center px-2.5 py-1 rounded border border-red-200 bg-red-50 text-red-600 text-xs font-medium">
                                    <i class="fa-solid fa-ban mr-1.5"></i> Fayl Mövcud Deyil
                                </span>
                            @endif
                        </div>

                        <!-- Qalereya Preview -->
                        @if($update->gallery_images)
                        <div class="flex gap-2 overflow-x-auto pb-2">
                            @foreach($update->gallery_images as $img)
                                <a href="{{ asset('uploads/gallery/'.$img) }}" target="_blank" class="block w-12 h-12 flex-shrink-0 rounded overflow-hidden border border-gray-200 hover:opacity-80">
                                    <img src="{{ asset('uploads/gallery/'.$img) }}" class="w-full h-full object-cover">
                                </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400">Hələ heç bir versiya paylaşılmayıb.</p>
                </div>
                @endforelse

                {{ $updates->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<script>
    // Checkbox-a basanda müvafiq bölməni aç/bağla
    function toggleSection(id) {
        const el = document.getElementById(id);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
</script>
@endsection

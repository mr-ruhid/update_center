@extends('admin.layout')

@section('content')
<div class="max-w-6xl mx-auto">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Menyu və Tərcümə İdarəetməsi</h1>
            <p class="text-sm text-gray-500">Menyu elementlərini əlavə edin və sıralayın.</p>
        </div>
        <button id="saveOrderBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm hidden">
            <i class="fa-solid fa-save mr-2"></i> Sıralamanı Yadda Saxla
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <i class="fa-solid fa-check mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- SOL TƏRƏF: ƏLAVƏ ETMƏ FORMASI -->
        <div class="lg:col-span-1 space-y-6">

            <!-- 1. Səhifələrdən Seçim -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center cursor-pointer" onclick="toggleAccordion('pagesAccordion')">
                    <h3 class="font-semibold text-gray-800 text-sm">Səhifələr</h3>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-500 transition-transform" id="pagesAccordionIcon"></i>
                </div>
                <div id="pagesAccordion" class="p-4 space-y-3">
                    <p class="text-xs text-gray-400 mb-2">Səhifəni menyuya əlavə et (Key avtomatik yaranacaq):</p>

                    @foreach($pages as $page)
                    <div class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0">
                        <div>
                            <span class="text-sm text-gray-700 block">{{ $page['name'] }}</span>
                            <span class="text-[10px] text-gray-400 font-mono">{{ $page['path'] }}</span>
                        </div>
                        <form action="{{ route('admin.menu.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="page">
                            <input type="hidden" name="url" value="{{ $page['url'] }}">
                            <!-- Avtomatik Key generasiyası -->
                            <input type="hidden" name="key" value="menu_{{ Str::slug($page['name'], '_') }}">
                            <input type="hidden" name="title" value="{{ $page['name'] }}">

                            <button type="submit" class="bg-gray-100 hover:bg-blue-50 text-gray-600 hover:text-blue-600 p-1.5 rounded border border-gray-200 transition-colors" title="Əlavə et">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Yeni Link (Custom) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-800 text-sm">Yeni Link</h3>
                </div>

                <form action="{{ route('admin.menu.store') }}" method="POST" class="p-4 space-y-4" id="customLinkForm">
                    @csrf
                    <input type="hidden" name="type" value="custom">

                    <!-- URL -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">URL / Link</label>
                        <input type="text" name="url" placeholder="https://" required class="w-full px-3 py-2 rounded border border-gray-300 focus:border-blue-500 outline-none text-sm font-mono text-gray-600">
                    </div>

                    <!-- Title (Simple) -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Ad (Başlıq)</label>
                        <input type="text" name="title" placeholder="Məs: Haqqımızda" required class="w-full px-3 py-2 rounded border border-gray-300 focus:border-blue-500 outline-none text-sm">
                    </div>

                    <!-- Translation Key -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tərcümə Açarı (Key)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-xs select-none">menu_</span>
                            <!-- İstifadəçinin yazdığı hissə (sadəcə suffix) -->
                            <input type="text" id="customKeySuffix" placeholder="home" required class="w-full pl-12 pr-3 py-2 rounded border border-gray-300 focus:border-blue-500 outline-none text-sm font-mono">
                        </div>
                        <!-- Serverə gedən əsl tam açar -->
                        <input type="hidden" name="key" id="realKeyInput">

                        <p class="text-[10px] text-gray-400 mt-1">Bu açar 'translations' cədvəlində saxlanılacaq. (Nəticə: <span id="keyPreview" class="font-bold">menu_...</span>)</p>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-bold transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus"></i> Əlavə Et
                    </button>
                </form>
            </div>
        </div>

        <!-- SAĞ TƏRƏF: MENYU SİYAHISI -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">Aktiv Menyu</h3>
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded">Cəmi: {{ count($menus) }}</span>
                </div>

                <ul id="sortableMenu" class="space-y-3">
                    @forelse($menus as $menu)
                        <!-- Menyu Elementi -->
                        <li class="bg-white border border-gray-200 rounded-lg p-3 flex justify-between items-center group hover:border-blue-400 hover:shadow-sm transition-all" data-id="{{ $menu->id }}">
                            <div class="flex items-center gap-4 flex-1">
                                <!-- Drag Handle -->
                                <div class="cursor-move text-gray-300 hover:text-gray-500 px-1">
                                    <i class="fa-solid fa-grip-vertical"></i>
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <!-- Əsas Başlıq (AZ) -->
                                        <span class="font-bold text-gray-800">
                                            {{ \App\Models\Translation::get($menu->key) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-400">
                                        <!-- İSTƏYİNİZƏ UYĞUN OLARAQ BURADAKI 'TYPE' (page) ƏVƏZİNƏ 'KEY' YAZILDI -->
                                        <span class="bg-yellow-100 text-yellow-800 border border-yellow-200 px-1.5 rounded font-mono" title="Translation Key">
                                            {{ $menu->key }}
                                        </span>
                                        <a href="{{ $menu->url }}" target="_blank" class="hover:text-blue-600 truncate max-w-[250px]">{{ $menu->url }}</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Əməliyyatlar -->
                            <div class="flex items-center gap-2 border-l border-gray-100 pl-3 ml-2">
                                <!-- Delete Button -->
                                <form action="{{ route('admin.menu.delete', $menu->id) }}" method="POST" onsubmit="return confirm('Silmək istədiyinizə əminsiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-600 hover:text-white flex items-center justify-center transition-colors">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="text-center py-12 flex flex-col items-center justify-center text-gray-400 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                            <i class="fa-solid fa-layer-group text-3xl mb-3 opacity-50"></i>
                            <span class="text-sm">Menyu siyahısı boşdur.</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

</div>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    // Accordion
    function toggleAccordion(id) {
        const el = document.getElementById(id);
        const icon = document.getElementById(id + 'Icon');
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            el.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Custom Link Key Generator
    const suffixInput = document.getElementById('customKeySuffix');
    const realKeyInput = document.getElementById('realKeyInput');
    const keyPreview = document.getElementById('keyPreview');

    if(suffixInput) {
        suffixInput.addEventListener('input', function() {
            // Slug yarat: boşluqları _ ilə əvəz et, kiçik hərfə sal
            let val = this.value.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');

            // Gizli inputu yenilə
            realKeyInput.value = 'menu_' + val;
            keyPreview.textContent = 'menu_' + val;
        });

        // İlkin yüklənmədə boşdursa yenilə
        if(suffixInput.value) {
            suffixInput.dispatchEvent(new Event('input'));
        }
    }

    // Sortable (Sıralama)
    var el = document.getElementById('sortableMenu');
    var saveBtn = document.getElementById('saveOrderBtn');

    if(el) {
        var sortable = Sortable.create(el, {
            animation: 150,
            handle: '.cursor-move',
            onEnd: function (evt) {
                saveBtn.classList.remove('hidden'); // Sıralama dəyişəndə Yadda saxla düyməsini göstər
            }
        });

        // Yadda saxla düyməsi
        saveBtn.addEventListener('click', function() {
            var order = sortable.toArray();

            // Loading effekti
            saveBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Yadda saxlanılır...';
            saveBtn.disabled = true;

            fetch('{{ route("admin.menu.sort") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: order })
            }).then(response => {
                if(response.ok) {
                    saveBtn.innerHTML = '<i class="fa-solid fa-check mr-2"></i> Yadda Saxlandı';
                    saveBtn.classList.replace('bg-green-600', 'bg-gray-400');
                    setTimeout(() => {
                        saveBtn.classList.add('hidden');
                        saveBtn.innerHTML = '<i class="fa-solid fa-save mr-2"></i> Sıralamanı Yadda Saxla';
                        saveBtn.classList.replace('bg-gray-400', 'bg-green-600');
                        saveBtn.disabled = false;
                    }, 2000);
                }
            });
        });
    }
</script>
@endsection

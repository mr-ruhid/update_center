@extends('admin.layout')

@section('content')
<div class="max-w-6xl mx-auto">

    <!-- Mesajlar (Uğurlu/Xəta) -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
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
            <h1 class="text-2xl font-bold text-gray-800">Məhsul Şəkilləri</h1>
            <p class="text-sm text-gray-500">POS və ya Veb tətbiqinizdə görünəcək məhsul şəkillərini idarə edin.</p>
        </div>
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium border border-blue-100">
            <i class="fa-solid fa-server mr-2"></i>
            Cəmi şəkil: <span class="font-bold">{{ $totalProducts ?? 0 }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- SOL: YÜKLƏMƏ FORMASI -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <div class="bg-green-100 p-1.5 rounded text-green-600">
                        <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">Yeni Şəkil Yüklə</h3>
                </div>

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
                    @csrf
                    <!-- Barkod Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Məhsul Barkodu / ID <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-barcode"></i></span>
                            <input type="text" name="barcode" required placeholder="Məs: 8690504033" class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all font-mono text-gray-700">
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Fayl adı bu barkodla eyni olacaq.</p>
                    </div>

                    <!-- Məhsul Adı (Opsional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Məhsul Adı</label>
                        <input type="text" name="name" placeholder="Məs: Coca Cola 0.5L" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none transition-all text-sm">
                    </div>

                    <!-- Şəkil Seçimi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Şəkil Faylı</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fa-solid fa-image text-gray-400 text-2xl mb-2"></i>
                                    <p class="text-xs text-gray-500">PNG, JPG və ya WEBP</p>
                                </div>
                                <input id="dropzone-file" name="image" type="file" class="hidden" required />
                            </label>
                        </div>
                    </div>

                    <!-- Düymə -->
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-medium transition-colors shadow-sm flex justify-center items-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Yadda Saxla
                    </button>
                </form>
            </div>
        </div>

        <!-- SAĞ: ŞƏKİL QALEREYASI -->
        <div class="lg:col-span-2">

            <!-- Axtarış Paneli -->
            <form action="{{ route('admin.products') }}" method="GET" class="mb-4 flex gap-2">
                <div class="relative flex-1">
                    <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Barkod və ya ad ilə axtar..." class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                </div>
                <button type="submit" class="bg-white border border-gray-300 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </form>

            <!-- Grid List -->
            @if(isset($products) && $products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                @foreach($products as $product)
                <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow relative">
                    <div class="aspect-square bg-gray-100 flex items-center justify-center relative overflow-hidden">
                        <!-- Şəkil -->
                        <img src="{{ asset('uploads/products/' . $product->image_path) }}" alt="{{ $product->name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">

                        <!-- Hover Actions -->
                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity gap-2">
                            <a href="{{ asset('uploads/products/' . $product->image_path) }}" target="_blank" class="bg-white p-2 rounded-full text-blue-600 hover:text-blue-700" title="Bax">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Bu şəkli silmək istədiyinizə əminsiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-white p-2 rounded-full text-red-600 hover:text-red-700" title="Sil">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="p-3">
                        <p class="text-xs text-gray-500 font-mono mb-0.5 truncate">{{ $product->barcode }}</p>
                        <h4 class="text-sm font-semibold text-gray-800 truncate">{{ $product->name ?? 'Adsız' }}</h4>

                        <!-- Link Kopyala (Dinamik) -->
                        <button onclick="copyLink('{{ asset('uploads/products/' . $product->image_path) }}')" class="mt-2 text-xs w-full py-1 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded text-gray-600 flex items-center justify-center gap-1 active:bg-green-100 active:text-green-700">
                            <i class="fa-regular fa-copy"></i> Linki Kopyala
                        </button>
                    </div>
                </div>
                @endforeach

            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $products->appends(['search' => request('search')])->links('pagination::tailwind') }}
            </div>

            @else
                <!-- Boş Yer Tutucu -->
                <div class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-12 text-center text-gray-400">
                    <i class="fa-regular fa-image text-4xl mb-3 opacity-50"></i>
                    <h3 class="text-lg font-medium text-gray-600">Hələ heç bir şəkil yoxdur</h3>
                    <p class="text-sm">Yeni məhsul şəkli əlavə etmək üçün sol tərəfdən yükləyin.</p>
                </div>
            @endif

        </div>

    </div>
</div>

<!-- Copy Script -->
<script>
    function copyLink(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Sadə vizual feedback
            alert('Link kopyalandı!');
        }, function(err) {
            console.error('Kopyalama xətası: ', err);
            // Fallback (iFrame mühitində kopyalama bəzən bloklana bilər)
            alert('Kopyalama dəstəklənmir, zəhmət olmasa manual seçin: ' + text);
        });
    }
</script>
@endsection

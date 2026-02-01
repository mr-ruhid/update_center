@extends('admin.layout')

@section('content')
<div class="max-w-6xl mx-auto">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Haqqımızda Bölməsi</h1>
            <p class="text-sm text-gray-500">Saytın "Haqqımızda" səhifəsinin məzmununu buradan idarə edin.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('page', 'about') }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                <i class="fa-solid fa-external-link-alt mr-1"></i> Saytda bax
            </a>
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

    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            <!-- SOL: MƏZMUN (MULTI-LANGUAGE) -->
            <div class="xl:col-span-2 space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                    <!-- Dil Tabları -->
                    <div class="bg-gray-50 border-b border-gray-200 px-4 pt-3 flex gap-2" x-data="{ lang: 'az' }">
                        @foreach(['az', 'en', 'ru', 'tr'] as $lang)
                            <button type="button" @click="lang = '{{ $lang }}'"
                                    :class="lang === '{{ $lang }}' ? 'bg-white border-gray-200 text-blue-600 border-b-white' : 'bg-transparent border-transparent text-gray-500 hover:text-gray-700'"
                                    class="px-4 py-2 text-sm font-medium border rounded-t-lg focus:outline-none -mb-px relative top-px uppercase">
                                {{ $lang }}
                            </button>
                        @endforeach
                    </div>

                    <div class="p-6" x-data="{ lang: 'az' }">

                        <!-- Dillər üçün loop -->
                        @foreach(['az', 'en', 'ru', 'tr'] as $lang)
                        <div id="tab_{{ $lang }}" class="lang-tab {{ $lang != 'az' ? 'hidden' : '' }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Səhifə Başlığı ({{ strtoupper($lang) }})</label>
                                    <!-- Input name massiv formatında olmalıdır: title[az] -->
                                    <input type="text" name="title[{{ $lang }}]" value="{{ $page->title[$lang] ?? '' }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none font-bold text-lg">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Məzmun ({{ strtoupper($lang) }})</label>
                                    <!-- Textarea name massiv formatında olmalıdır: content[az] -->
                                    <textarea name="content[{{ $lang }}]" id="editor_{{ $lang }}">{{ $page->content[$lang] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>

            </div>

            <!-- SAĞ: ŞƏKİL VƏ YADDA SAXLA -->
            <div class="xl:col-span-1 space-y-6">

                <!-- Əsas Şəkil -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fa-regular fa-image mr-2 text-blue-500"></i> Əsas Şəkil
                    </h3>

                    @if($page->image)
                        <div class="mb-4 rounded-lg overflow-hidden border border-gray-200">
                            <img src="{{ asset('uploads/pages/'.$page->image) }}" class="w-full h-40 object-cover">
                        </div>
                    @endif

                    <label class="block">
                        <span class="sr-only">Şəkil seç</span>
                        <input type="file" name="image" class="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-sm file:font-semibold
                          file:bg-blue-50 file:text-blue-700
                          hover:file:bg-blue-100
                        "/>
                    </label>
                    <p class="text-xs text-gray-400 mt-2">Bu şəkil səhifənin yuxarısında və ya fonunda görünə bilər.</p>
                </div>

                <!-- Əməliyyat -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium shadow-lg shadow-blue-500/30 transition-all flex justify-center items-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Yadda Saxla
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>

<!-- AlpineJS -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
    // Tab düymələrinə klikləmə funksiyası
    const buttons = document.querySelectorAll('[x-data] button');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const lang = btn.innerText.toLowerCase();

            // Bütün düymələri resetlə
            buttons.forEach(b => b.className = 'bg-transparent border-transparent text-gray-500 hover:text-gray-700 px-4 py-2 text-sm font-medium border rounded-t-lg focus:outline-none -mb-px relative top-px uppercase');
            // Aktiv düymə
            btn.className = 'bg-white border-gray-200 text-blue-600 border-b-white px-4 py-2 text-sm font-medium border rounded-t-lg focus:outline-none -mb-px relative top-px uppercase';

            // Tabları dəyiş
            document.querySelectorAll('.lang-tab').forEach(tab => tab.classList.add('hidden'));
            document.getElementById('tab_' + lang).classList.remove('hidden');
        });
    });

    // CKEditor-ları hər dil üçün başlat
    ['az', 'en', 'ru', 'tr'].forEach(lang => {
        const editorEl = document.querySelector('#editor_' + lang);
        if(editorEl) {
            ClassicEditor
                .create(editorEl, {
                    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ],
                    height: '400px'
                })
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>

<style>
    /* Editor hündürlüyünü tənzimləmək üçün */
    .ck-editor__editable_inline {
        min-height: 400px;
    }
</style>
@endsection

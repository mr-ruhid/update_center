@extends('admin.layout')

@section('content')
<div class="max-w-6xl mx-auto pb-20">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Ana Səhifə Məzmunu</h1>
            <p class="text-sm text-gray-500">Saytın giriş (Hero) hissəsindəki yazıları və qalereyanı idarə edin.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <i class="fa-solid fa-check mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.home_content.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            <!-- SOL: MƏTN VƏ DÜYMƏLƏR -->
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
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Başlıq Hissə 1 (Üst) - {{ strtoupper($lang) }}</label>
                                    <input type="text" name="hero_title_1_{{ $lang }}" value="{{ $content->hero_title_1[$lang] ?? '' }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none font-bold text-lg" placeholder="Məs: RJ Pos Updater">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Başlıq Hissə 2 (Alt) - {{ strtoupper($lang) }}</label>
                                    <input type="text" name="hero_title_2_{{ $lang }}" value="{{ $content->hero_title_2[$lang] ?? '' }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none font-bold text-lg text-blue-600" placeholder="Məs: Sistem İdarəetməsi">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alt Mətn (Subtext) - {{ strtoupper($lang) }}</label>
                                    <textarea name="hero_subtext_{{ $lang }}" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none text-sm">{{ $content->hero_subtext[$lang] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Düymə Adı - {{ strtoupper($lang) }}</label>
                                    <input type="text" name="hero_btn_text_{{ $lang }}" value="{{ $content->hero_btn_text[$lang] ?? '' }}" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none" placeholder="Məs: Başla">
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>

                <!-- URL -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Düymə Linki (URL)</label>
                    <input type="text" name="hero_btn_url" value="{{ $content->hero_btn_url }}" placeholder="https://..." class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none text-blue-600">
                    <p class="text-xs text-gray-400 mt-1">İstifadəçi düyməyə basdıqda bu linkə yönləndiriləcək.</p>
                </div>
            </div>

            <!-- SAĞ: QALEREYA -->
            <div class="xl:col-span-1 space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fa-regular fa-images mr-2 text-purple-600"></i> Yan Qalereya
                    </h3>

                    <div class="flex items-center justify-center w-full mb-4">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-2xl mb-2"></i>
                                <p class="text-xs text-gray-500">Şəkilləri bura atın</p>
                            </div>
                            <input type="file" name="gallery[]" multiple class="hidden" accept="image/*" />
                        </label>
                    </div>

                    <!-- Mövcud Şəkillər -->
                    @if($content->hero_gallery)
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($content->hero_gallery as $img)
                                <div class="relative group rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('uploads/home/'.$img) }}" class="w-full h-20 object-cover">
                                    <label class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                        <input type="checkbox" name="delete_images[]" value="{{ $img }}" class="w-5 h-5 text-red-600">
                                        <span class="text-white text-xs ml-2">Sil</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 text-center">Silmək üçün işarələyin və yadda saxlayın.</p>
                    @endif
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium shadow-lg shadow-blue-500/30 transition-all flex justify-center items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Yadda Saxla
                </button>

            </div>

        </div>
    </form>
</div>

<script>
    const tabs = document.querySelectorAll('.lang-tab');
    const buttons = document.querySelectorAll('[x-data] button');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const lang = btn.innerText.toLowerCase();

            buttons.forEach(b => b.className = 'bg-transparent border-transparent text-gray-500 hover:text-gray-700 px-4 py-2 text-sm font-medium border rounded-t-lg focus:outline-none -mb-px relative top-px uppercase');
            btn.className = 'bg-white border-gray-200 text-blue-600 border-b-white px-4 py-2 text-sm font-medium border rounded-t-lg focus:outline-none -mb-px relative top-px uppercase';

            tabs.forEach(tab => tab.classList.add('hidden'));
            document.getElementById('tab_' + lang).classList.remove('hidden');
        });
    });
</script>
@endsection

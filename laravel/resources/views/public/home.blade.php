@extends('layouts.public')

@section('title', $settings->seo_title ?? \App\Models\Translation::get('home_page_title'))

@section('content')
    <!-- HERO SECTION (Əsas Hissə - Dinamik) -->
    @php
        $home = \App\Models\HomeContent::first();
        $lang = app()->getLocale();
    @endphp

    <section class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20 px-4 sm:px-6 lg:px-8">
                <main class="mt-10 mx-auto max-w-7xl sm:mt-12 md:mt-16 lg:mt-20 xl:mt-28">
                    <div class="sm:text-center lg:text-left">

                        <!-- Başlıqlar -->
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">
                                {{ $home->hero_title_1[$lang] ?? \App\Models\Translation::get('hero_default_title_1') }}
                            </span>
                            <span class="block text-blue-600 mt-2">
                                {{ $home->hero_title_2[$lang] ?? \App\Models\Translation::get('hero_default_title_2') }}
                            </span>
                        </h1>

                        <!-- Alt Mətn -->
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            {{ $home->hero_subtext[$lang] ?? \App\Models\Translation::get('hero_default_subtext') }}
                        </p>

                        <!-- Düymə -->
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ $home->hero_btn_url ?? '#' }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg transition-colors">
                                    {{ $home->hero_btn_text[$lang] ?? \App\Models\Translation::get('hero_default_btn_text') }}
                                </a>
                            </div>
                        </div>

                    </div>
                </main>
            </div>
        </div>

        <!-- Sağ Tərəf: Qalereya / Slider -->
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-gray-50 flex items-center justify-center overflow-hidden">
            @if($home && $home->hero_gallery && count($home->hero_gallery) > 0)
                <div class="relative w-full h-full">
                    @foreach($home->hero_gallery as $index => $img)
                        <img class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0' }} slider-img"
                             src="{{ asset('uploads/home/'.$img) }}" alt="Slide {{ $index }}">
                    @endforeach
                </div>
            @else
                <!-- Placeholder -->
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-200">
                    <i class="fa-solid fa-server text-9xl opacity-20"></i>
                </div>
            @endif
        </div>
    </section>

    <!-- SON YENİLİKLƏR VƏ PLUGINLƏR -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

                <!-- Son Yenilik -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        {{ \App\Models\Translation::get('home_latest_update_heading') }}
                    </h2>
                    @if(isset($latestUpdate) && $latestUpdate)
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-full flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-mono font-bold">{{ $latestUpdate->version }}</span>
                                    <span class="text-sm text-gray-400">{{ $latestUpdate->created_at->format('d M Y') }}</span>
                                </div>
                                <p class="text-gray-600 mb-4 whitespace-pre-line text-sm leading-relaxed">{{ Str::limit($latestUpdate->changelog, 150) }}</p>
                            </div>

                            @if($latestUpdate->allow_download)
                                <a href="#" class="text-blue-600 text-sm font-medium hover:underline flex items-center mt-2">
                                    {{ \App\Models\Translation::get('global_read_more') }} <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            @else
                                <span class="text-red-500 text-xs font-medium bg-red-50 px-2 py-1 rounded w-max">
                                    {{ \App\Models\Translation::get('home_download_disabled') }}
                                </span>
                            @endif
                        </div>
                    @else
                        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 text-center text-gray-400">
                            {{ \App\Models\Translation::get('home_no_updates_found') }}
                        </div>
                    @endif
                </div>

                <!-- Populyar Pluginlər -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        {{ \App\Models\Translation::get('home_popular_plugins_heading') }}
                    </h2>
                    <div class="space-y-4">
                        @forelse($plugins as $plugin)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600 flex-shrink-0 overflow-hidden">
                                        @if($plugin->image)
                                            <img src="{{ asset('uploads/plugins/'.$plugin->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-puzzle-piece text-xl"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-sm">{{ $plugin->name }}</h4>
                                        <span class="text-xs text-gray-500 font-mono">{{ $plugin->version }}</span>
                                    </div>
                                </div>
                                <span class="text-sm font-bold px-3 py-1 rounded {{ $plugin->is_free ? 'text-green-700 bg-green-50' : 'text-blue-700 bg-blue-50' }}">
                                    {{ $plugin->is_free ? \App\Models\Translation::get('global_free') : '$'.$plugin->price }}
                                </span>
                            </div>
                        @empty
                            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 text-center text-gray-400">
                                {{ \App\Models\Translation::get('home_no_plugins_found') }}
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Slider Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const slides = document.querySelectorAll('.slider-img');
            if (slides.length > 1) {
                let currentSlide = 0;
                setInterval(() => {
                    slides[currentSlide].classList.remove('opacity-100');
                    slides[currentSlide].classList.add('opacity-0');

                    currentSlide = (currentSlide + 1) % slides.length;

                    slides[currentSlide].classList.remove('opacity-0');
                    slides[currentSlide].classList.add('opacity-100');
                }, 4000); // 4 saniyədən bir dəyişir
            }
        });
    </script>
@endsection

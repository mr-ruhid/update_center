@extends('layouts.public')

@php
    // Dili alırıq
    $lang = app()->getLocale();

    // Title məlumatını yoxlayırıq
    $titleRaw = $pageData->title ?? 'Haqqımızda';

    // Əgər bazadan Array gəlirsə (köhnə sistem), dilə uyğun olanı seçirik
    if (is_array($titleRaw)) {
        $title = $titleRaw[$lang] ?? $titleRaw['az'] ?? 'Haqqımızda';
    } else {
        // Əgər String gəlirsə (yeni sistem - Key), tərcümə edirik
        $title = __($titleRaw);
    }
@endphp

@section('title', $title . ' - ' . ($settings->site_name ?? 'RJ Site Updater'))

@section('content')
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 text-center">

            <!-- Başlıq -->
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                {{ $title }}
            </h1>

            <!-- Alt Başlıq (Tərcümə oluna bilən statik açar) -->
            <div class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                <p>{{ \App\Models\Translation::get('about_subtitle') }}</p>
            </div>

        </div>
    </div>

    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-12 lg:items-start">

            <!-- Sol: Şəkil -->
            <div class="relative mb-8 lg:mb-0">
                @if(isset($pageData->image) && $pageData->image)
                    <div class="relative rounded-2xl overflow-hidden shadow-xl border border-gray-100">
                        <img class="w-full h-full object-cover" src="{{ asset('uploads/pages/'.$pageData->image) }}" alt="{{ $title }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    </div>
                @else
                    <!-- Placeholder -->
                    <div class="aspect-w-5 aspect-h-3 rounded-2xl bg-gray-100 flex items-center justify-center border border-gray-200 p-12">
                        <i class="fa-regular fa-image text-6xl text-gray-300"></i>
                    </div>
                @endif
            </div>

            <!-- Sağ: Məzmun -->
            <div class="prose prose-lg text-gray-500 mx-auto lg:max-w-none">
                @if(isset($pageData->content))
                    @php
                        $contentRaw = $pageData->content;
                        $finalContent = '';

                        if (is_array($contentRaw)) {
                            // Array-dirsə dilə uyğun seç
                            $finalContent = $contentRaw[$lang] ?? $contentRaw['az'] ?? '';
                        } else {
                            // String-dirsə yoxla: Qısa key-dir yoxsa HTML?
                            // Əgər boşluq yoxdursa və qısadırsa, tərcümə etməyə çalış
                            if (strlen(strip_tags($contentRaw)) < 50 && !str_contains(strip_tags($contentRaw), ' ')) {
                                $finalContent = __($contentRaw);
                            } else {
                                $finalContent = $contentRaw;
                            }
                        }
                    @endphp

                    {!! $finalContent !!}
                @else
                    <p>{{ __('Məlumat daxil edilməyib.') }}</p>
                @endif
            </div>

        </div>
    </div>
@endsection

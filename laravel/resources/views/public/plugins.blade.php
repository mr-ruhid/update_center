@extends('layouts.public')

@section('title', \App\Models\Translation::get('plugins_page_title') . ' - ' . ($settings->site_name ?? 'RJ Site Updater'))

@section('content')
    <!-- Header -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                {{ \App\Models\Translation::get('plugins_hero_title') }}
            </h1>
            <div class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                <p>{{ \App\Models\Translation::get('plugins_hero_subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        @if(isset($plugins) && $plugins->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($plugins as $plugin)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col h-full group">

                        <!-- Image Area -->
                        <div class="h-48 bg-gray-50 rounded-t-2xl overflow-hidden relative flex items-center justify-center border-b border-gray-50">
                            @if($plugin->image)
                                <img src="{{ asset('uploads/plugins/'.$plugin->image) }}" alt="{{ $plugin->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <i class="fa-solid fa-puzzle-piece text-5xl text-gray-300"></i>
                            @endif

                            <!-- Price Badge -->
                            <div class="absolute top-4 right-4">
                                @if($plugin->is_free)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 shadow-sm">
                                        {{ \App\Models\Translation::get('global_free') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white text-gray-900 shadow-sm border border-gray-100">
                                        ${{ $plugin->price }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Info Area -->
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-gray-900 line-clamp-1">{{ $plugin->name }}</h3>
                                <span class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $plugin->version }}</span>
                            </div>

                            <p class="text-sm text-gray-500 line-clamp-2 mb-6 flex-1">
                                {{ \App\Models\Translation::get('plugins_default_desc') }}
                            </p>

                            <!-- Action Button -->
                            <div class="mt-auto">
                                @if($plugin->is_free)
                                    <button onclick="startDownload('{{ $plugin->name }}', '{{ asset('uploads/plugins/'.$plugin->file_path) }}')" class="w-full flex items-center justify-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition-all shadow-blue-500/20 shadow-lg">
                                        <i class="fa-solid fa-download mr-2"></i> {{ \App\Models\Translation::get('global_download') }}
                                    </button>
                                @else
                                    <!-- Ödənişli Pluginlər üçün Form -->
                                    <form action="{{ route('payment.checkout', $plugin->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 border border-gray-200 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all">
                                            <i class="fa-regular fa-credit-card mr-2 text-gray-400"></i> {{ \App\Models\Translation::get('global_buy') }} (${{ $plugin->price }})
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $plugins->links() }}
            </div>
        @else
            <div class="text-center py-24">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-6">
                    <i class="fa-solid fa-box-open text-3xl text-blue-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">
                    {{ \App\Models\Translation::get('plugins_not_found_title') }}
                </h3>
                <p class="mt-2 text-gray-500">
                    {{ \App\Models\Translation::get('plugins_not_found_text') }}
                </p>
            </div>
        @endif

    </div>

    <script>
        function startDownload(name, url) {
            const btn = event.target.closest('button');
            const originalContent = btn.innerHTML;

            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');

            setTimeout(() => {
                const link = document.createElement('a');
                link.href = url;
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // JavaScript daxilində PHP-dən gələn tərcümə
                btn.innerHTML = '<i class="fa-solid fa-check"></i> {{ \App\Models\Translation::get('global_started') }}';

                setTimeout(() => {
                    btn.innerHTML = originalContent;
                    btn.disabled = false;
                    btn.classList.remove('opacity-75', 'cursor-wait');
                }, 2000);
            }, 800);
        }
    </script>
@endsection

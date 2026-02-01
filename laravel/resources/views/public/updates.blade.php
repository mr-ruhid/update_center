@extends('layouts.public')

@section('title', \App\Models\Translation::get('updates_page_title') . ' - ' . ($settings->site_name ?? 'RJ Site Updater'))

@section('content')
    <!-- Header -->
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                {{ \App\Models\Translation::get('updates_hero_title') }}
            </h1>
            <div class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                <p>{{ \App\Models\Translation::get('updates_hero_subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Timeline Content -->
    <div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        @if(isset($updates) && $updates->count() > 0)
            <div class="relative border-l-2 border-gray-200 ml-4 md:ml-6 space-y-12">
                @foreach($updates as $update)
                    <div class="relative pl-8 md:pl-12 group">
                        <!-- Dot -->
                        <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-white border-4 {{ $loop->first ? 'border-blue-600' : 'border-gray-300' }} group-hover:border-blue-400 transition-colors"></div>

                        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between mb-2">
                            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                                {{ $update->version }}
                                @if($loop->first)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 animate-pulse">
                                        {{ \App\Models\Translation::get('updates_latest_badge') }}
                                    </span>
                                @endif
                            </h2>
                            <time class="text-sm text-gray-500 font-mono mt-1 sm:mt-0">{{ $update->created_at->format('d F Y, H:i') }}</time>
                        </div>

                        <!-- Card -->
                        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all duration-300">

                            <!-- Qısa Məzmun -->
                            <div class="prose prose-blue max-w-none text-gray-600 mb-4">
                                <p class="line-clamp-3 leading-relaxed">
                                    {{ Str::limit($update->changelog, 200) }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-50">

                                <!-- Detallar Butonu -->
                                <button onclick="openDetailsModal({{ $update->id }})" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center transition-colors">
                                    {{ \App\Models\Translation::get('global_read_more') }} <i class="fa-solid fa-arrow-right-long ml-2"></i>
                                </button>

                                <!-- Yükləmə Butonları (Mini) -->
                                <div class="flex gap-2">
                                    @if($update->allow_download)
                                        @if($update->has_full_file)
                                            <button onclick="startDownload('{{ \App\Models\Translation::get('global_full_version') }}', '{{ asset('uploads/updates/'.$update->full_file_path) }}')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 shadow-sm transition-transform active:scale-95">
                                                <i class="fa-solid fa-download mr-1.5"></i> {{ \App\Models\Translation::get('updates_btn_full_short') }}
                                            </button>
                                        @endif
                                        @if($update->has_update_file)
                                            <button onclick="startDownload('{{ \App\Models\Translation::get('global_update_package') }}', '{{ asset('uploads/updates/'.$update->update_file_path) }}')" class="inline-flex items-center px-3 py-1.5 border border-gray-200 text-xs font-medium rounded-md text-gray-700 bg-gray-50 hover:bg-gray-100 shadow-sm transition-transform active:scale-95">
                                                <i class="fa-solid fa-file-zipper mr-1.5"></i> {{ \App\Models\Translation::get('updates_btn_update_short') }}
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded"><i class="fa-solid fa-lock mr-1"></i></span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Data for Modal (JSON) -->
                        <script id="update-data-{{ $update->id }}" type="application/json">
                            {
                                "version": "{{ $update->version }}",
                                "date": "{{ $update->created_at->format('d F Y') }}",
                                "changelog": {!! json_encode(nl2br(e($update->changelog))) !!},
                                "gallery": {!! json_encode($update->gallery_images ?? []) !!},
                                "gallery_path": "{{ asset('uploads/gallery/') }}/",
                                "allow_download": {{ $update->allow_download ? 'true' : 'false' }},
                                "has_full": {{ $update->has_full_file ? 'true' : 'false' }},
                                "full_url": "{{ asset('uploads/updates/'.$update->full_file_path) }}",
                                "has_update": {{ $update->has_update_file ? 'true' : 'false' }},
                                "update_url": "{{ asset('uploads/updates/'.$update->update_file_path) }}",
                                "price_full": "{{ $update->price_full }}",
                                "price_update": "{{ $update->price_update }}"
                            }
                        </script>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $updates->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fa-solid fa-code-branch text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">
                    {{ \App\Models\Translation::get('updates_empty_title') }}
                </h3>
                <p class="mt-1 text-gray-500">
                    {{ \App\Models\Translation::get('updates_empty_text') }}
                </p>
            </div>
        @endif

    </div>

    <!-- DETAILS MODAL -->
    <div id="detailsModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeDetailsModal()"></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal Panel -->
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-200">

                    <!-- Header -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-gray-100">
                        <div>
                            <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-version">v1.0.0</h3>
                            <p class="text-xs text-gray-500 mt-0.5" id="modal-date">01 Jan 2024</p>
                        </div>
                        <button type="button" onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-4 py-5 sm:p-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                        <!-- Mətn -->
                        <div class="prose prose-sm max-w-none text-gray-600 mb-6" id="modal-changelog">
                            <!-- Content goes here -->
                        </div>

                        <!-- Qalereya -->
                        <div id="modal-gallery-container" class="hidden">
                            <h4 class="text-sm font-bold text-gray-900 mb-3 border-b border-gray-100 pb-2">
                                {{ \App\Models\Translation::get('updates_screenshots_label') }}
                            </h4>
                            <div class="grid grid-cols-2 gap-3" id="modal-gallery">
                                <!-- Images go here -->
                            </div>
                        </div>

                    </div>

                    <!-- Footer / Actions -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2" id="modal-actions">
                        <!-- Buttons injected by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Logic -->
    <script>
        // Modal Elementləri
        const modal = document.getElementById('detailsModal');
        const mVersion = document.getElementById('modal-version');
        const mDate = document.getElementById('modal-date');
        const mChangelog = document.getElementById('modal-changelog');
        const mGalleryContainer = document.getElementById('modal-gallery-container');
        const mGallery = document.getElementById('modal-gallery');
        const mActions = document.getElementById('modal-actions');

        function openDetailsModal(id) {
            // Datanı oxu
            const dataScript = document.getElementById('update-data-' + id);
            if(!dataScript) return;
            const data = JSON.parse(dataScript.textContent);

            // Məlumatları doldur
            mVersion.textContent = data.version;
            mDate.textContent = data.date;
            mChangelog.innerHTML = data.changelog;

            // Qalereya
            mGallery.innerHTML = '';
            if (data.gallery && data.gallery.length > 0) {
                mGalleryContainer.classList.remove('hidden');
                data.gallery.forEach(img => {
                    const a = document.createElement('a');
                    a.href = data.gallery_path + img;
                    a.target = '_blank';
                    a.className = 'block rounded-lg overflow-hidden border border-gray-200 hover:opacity-90 transition-opacity';
                    a.innerHTML = `<img src="${data.gallery_path + img}" class="w-full h-32 object-cover">`;
                    mGallery.appendChild(a);
                });
            } else {
                mGalleryContainer.classList.add('hidden');
            }

            // Düymələr (Modal üçün)
            let buttonsHtml = '';
            // Tərcümələri JS dəyişənlərinə mənimsədirik (PHP tərəfindən render olunur)
            const txtFullVersion = "{{ \App\Models\Translation::get('global_full_version') }}";
            const txtUpdatePackage = "{{ \App\Models\Translation::get('global_update_package') }}";
            const txtFree = "{{ \App\Models\Translation::get('global_free') }}";
            const txtDownloadDisabled = "{{ \App\Models\Translation::get('updates_download_disabled_msg') }}";

            if (data.allow_download) {
                if (data.has_full) {
                    const priceText = data.price_full > 0 ? data.price_full + ' ₼' : txtFree;
                    buttonsHtml += `<button onclick="startDownload('${txtFullVersion}', '${data.full_url}')" class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto">${txtFullVersion} (${priceText})</button>`;
                }
                if (data.has_update) {
                    const priceText = data.price_update > 0 ? data.price_update + ' ₼' : txtFree;
                    buttonsHtml += `<button onclick="startDownload('${txtUpdatePackage}', '${data.update_url}')" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">${txtUpdatePackage} (${priceText})</button>`;
                }
            } else {
                buttonsHtml = `<span class="text-sm text-red-500 font-medium py-2">${txtDownloadDisabled}</span>`;
            }
            mActions.innerHTML = buttonsHtml;

            // Modalı aç
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Scrollu kilidlə
        }

        function closeDetailsModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Scrollu aç
        }

        // YENİLƏNMİŞ YÜKLƏMƏ FUNKSİYASI
        function startDownload(type, url) {
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            // Tərcümə
            const txtLoading = "{{ \App\Models\Translation::get('global_loading') }}";

            // Animasiya başlat
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> ${txtLoading}`;
            btn.disabled = true;

            setTimeout(() => {
                const link = document.createElement('a');
                link.href = url;
                link.target = '_blank'; // Yeni pəncərədə aç
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Düyməni bərpa et
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 800);
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
@endsection

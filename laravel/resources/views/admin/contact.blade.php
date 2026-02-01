@extends('admin.layout')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Əlaqə Bölməsi</h1>
            <p class="text-sm text-gray-500">Saytın footer və əlaqə səhifəsindəki məlumatları idarə edin.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="#" target="_blank" class="text-sm text-blue-600 hover:underline">
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

    <form action="{{ route('admin.contact.update') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- SOL TƏRƏF: ƏLAQƏ VƏ FORM -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 1. Telefon və Email -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                        <div class="bg-blue-100 p-1.5 rounded text-blue-600">
                            <i class="fa-solid fa-phone text-sm"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Əlaqə Vasitələri</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefon Nömrəsi 1</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-mobile-screen"></i></span>
                                <input type="text" name="phone_1" value="{{ $contact->phone_1 ?? '' }}" placeholder="+994 50 000 00 00" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefon Nömrəsi 2</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" name="phone_2" value="{{ $contact->phone_2 ?? '' }}" placeholder="+994 12 000 00 00" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Form Ayarları -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                        <div class="bg-orange-100 p-1.5 rounded text-orange-600">
                            <i class="fa-solid fa-envelope-open-text text-sm"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Müraciət Formu Ayarları</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mesajlar hansı emailə göndərilsin?</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-at"></i></span>
                                <input type="email" name="email_receiver" value="{{ $contact->email_receiver ?? '' }}" placeholder="info@ruhidjavadov.site" class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Saytın əlaqə formundan yazılan mesajlar bu ünvana yönləndiriləcək.</p>
                        </div>

                        <!-- SMTP Xəbərdarlığı -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex gap-3">
                            <div class="text-yellow-600 mt-0.5"><i class="fa-solid fa-circle-exclamation"></i></div>
                            <div>
                                <h4 class="text-sm font-bold text-yellow-800">Vacib Qeyd:</h4>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Əlaqə formunun düzgün işləməsi və mesajların emailinizə gəlməsi üçün <a href="{{ route('admin.smtp') }}" class="underline font-medium hover:text-yellow-900">SMTP Ayarlarını</a> mütləq quraşdırmalısınız.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- SAĞ TƏRƏF: SOSİAL MEDİA -->
            <div class="lg:col-span-1 space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 font-semibold text-gray-800">
                        Sosial Media Hesabları
                    </div>
                    <div class="p-5 space-y-4">

                        <!-- Facebook -->
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Facebook</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-blue-600"><i class="fa-brands fa-facebook"></i></span>
                                <input type="url" name="facebook" value="{{ $contact->facebook ?? '' }}" class="w-full pl-9 pr-3 py-2 rounded border border-gray-300 text-sm focus:border-blue-500 outline-none" placeholder="https://facebook.com/...">
                            </div>
                        </div>

                        <!-- Instagram -->
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Instagram</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-pink-600"><i class="fa-brands fa-instagram"></i></span>
                                <input type="url" name="instagram" value="{{ $contact->instagram ?? '' }}" class="w-full pl-9 pr-3 py-2 rounded border border-gray-300 text-sm focus:border-blue-500 outline-none" placeholder="https://instagram.com/...">
                            </div>
                        </div>

                        <!-- X (Twitter) -->
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">X.com (Twitter)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-black"><i class="fa-brands fa-x-twitter"></i></span>
                                <input type="url" name="twitter" value="{{ $contact->twitter ?? '' }}" class="w-full pl-9 pr-3 py-2 rounded border border-gray-300 text-sm focus:border-blue-500 outline-none" placeholder="https://x.com/...">
                            </div>
                        </div>

                        <!-- LinkedIn -->
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">LinkedIn</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-blue-700"><i class="fa-brands fa-linkedin"></i></span>
                                <input type="url" name="linkedin" value="{{ $contact->linkedin ?? '' }}" class="w-full pl-9 pr-3 py-2 rounded border border-gray-300 text-sm focus:border-blue-500 outline-none" placeholder="https://linkedin.com/in/...">
                            </div>
                        </div>

                        <!-- GitHub -->
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">GitHub</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-800"><i class="fa-brands fa-github"></i></span>
                                <input type="url" name="github" value="{{ $contact->github ?? '' }}" class="w-full pl-9 pr-3 py-2 rounded border border-gray-300 text-sm focus:border-blue-500 outline-none" placeholder="https://github.com/...">
                            </div>
                        </div>

                        <!-- Behance -->
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Behance</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-blue-500"><i class="fa-brands fa-behance"></i></span>
                                <input type="url" name="behance" value="{{ $contact->behance ?? '' }}" class="w-full pl-9 pr-3 py-2 rounded border border-gray-300 text-sm focus:border-blue-500 outline-none" placeholder="https://behance.net/...">
                            </div>
                        </div>

                        <!-- TikTok -->
                        <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">TikTok</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-black"><i class="fa-brands fa-tiktok"></i></span>
                                <input type="url" name="tiktok" value="{{ $contact->tiktok ?? '' }}" class="w-full pl-9 pr-3 py-2 rounded border border-gray-300 text-sm focus:border-blue-500 outline-none" placeholder="https://tiktok.com/...">
                            </div>
                        </div>

                         <!-- Threads -->
                         <div>
                            <label class="text-xs font-medium text-gray-600 mb-1 block">Threads</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-black"><i class="fa-brands fa-threads"></i></span>
                                <input type="url" name="threads" value="{{ $contact->threads ?? '' }}" class="w-full pl-9 pr-3 py-2 rounded border border-gray-300 text-sm focus:border-blue-500 outline-none" placeholder="https://threads.net/...">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium shadow-lg shadow-blue-500/30 transition-all flex justify-center items-center">
                                <i class="fa-solid fa-floppy-disk mr-2"></i> Yadda Saxla
                            </button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection

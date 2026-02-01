@extends('admin.layout')

@section('content')
<div class="max-w-6xl mx-auto">

    <!-- Üst Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Versiya Buraxılışı (Release)</h1>
            <p class="text-sm text-gray-500">Müştəri sistemləri üçün yeni versiya parametrlərini təyin edin.</p>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center">
            <i class="fa-solid fa-rocket mr-2"></i>
            Yeniləməni Yayımla
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- SOL TƏRƏF: ADMIN GİRİŞ FORMASI -->
        <div class="space-y-6">

            <!-- Əsas Məlumatlar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <div class="bg-blue-100 p-1.5 rounded text-blue-600">
                        <i class="fa-solid fa-server text-sm"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800">Server Tərəfi (Sizin Daxil Etdikləriniz)</h3>
                </div>

                <div class="p-6 space-y-5">

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Versiya Kodu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Yeni Versiya (Server)</label>
                            <input type="text" value="3.0.0" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 font-mono font-bold text-blue-600">
                            <p class="text-xs text-gray-400 mt-1">Müştəri v3.0.0-dan aşağıdırsa, bildiriş alacaq.</p>
                        </div>
                        <!-- URL - Tam Manual -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Yönləndirmə Linki (Tam URL)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-link"></i></span>
                                <input type="url" placeholder="https://istenilen-sayt.com/update" class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 text-blue-600">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Bura istənilən saytın linkini yaza bilərsiniz.</p>
                        </div>
                    </div>

                    <!-- Başlıq -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Bildiriş Başlığı (API Title)</label>
                        <input type="text" placeholder="Vacib Təhlükəsizlik Yeniləməsi" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>

                    <!-- Qeydlər -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Dəyişiklik Qeydləri (API Body)</label>
                        <textarea rows="4" placeholder="- Xətalar aradan qaldırıldı&#10;- Yeni modul əlavə edildi" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
                    </div>

                </div>
            </div>
        </div>

        <!-- SAĞ TƏRƏF: QARŞI TƏRƏFİN GÖRƏCƏYİ (DATA) -->
        <div class="space-y-4">

            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Müştəriyə Gedən Xam Məlumat (API Response)</h4>

            <!-- JSON Preview Box -->
            <div class="bg-slate-900 rounded-xl shadow-lg border border-slate-700 overflow-hidden text-left">
                <div class="bg-slate-950 px-4 py-2 border-b border-slate-800 flex justify-between items-center">
                    <span class="text-xs font-mono text-green-400">JSON Response</span>
                    <span class="text-xs text-slate-500">Status: 200 OK</span>
                </div>
                <div class="p-5 overflow-x-auto">
<pre class="font-mono text-sm leading-relaxed">
<span class="text-purple-400">{</span>
  <span class="text-blue-400">"update_available"</span>: <span class="text-orange-400">true</span>,
  <span class="text-blue-400">"data"</span>: <span class="text-purple-400">{</span>
    <span class="text-blue-400">"version"</span>: <span class="text-green-400">"3.0.0"</span>,
    <span class="text-blue-400">"title"</span>: <span class="text-green-400">"Vacib Təhlükəsizlik Yeniləməsi"</span>,
    <span class="text-blue-400">"notes"</span>: <span class="text-green-400">"- Xətalar aradan qaldırıldı\n- Yeni modul əlavə edildi"</span>,
    <span class="text-blue-400">"action_url"</span>: <span class="text-green-400">"https://istenilen-sayt.com/update"</span>,
    <span class="text-blue-400">"release_date"</span>: <span class="text-green-400">"2024-05-20"</span>
  <span class="text-purple-400">}</span>
<span class="text-purple-400">}</span>
</pre>
                </div>
            </div>

            <!-- İzahlı Note -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex gap-3">
                    <div class="text-yellow-600 mt-0.5"><i class="fa-solid fa-circle-info"></i></div>
                    <div>
                        <h4 class="text-sm font-bold text-yellow-800">Necə işləyir?</h4>
                        <p class="text-sm text-yellow-700 mt-1 leading-relaxed">
                            Müştəri proqramı API-yə qoşulur.
                            Siz <b>action_url</b> hissəsində hansı linki yazmısınızsa (bu istənilən sayt ola bilər),
                            müştəri "Yenilə" düyməsinə basdıqda ora yönləndiriləcək.
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

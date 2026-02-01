@extends('admin.layout')

@section('content')
<div class="space-y-6">

    <!-- Başlıq və Açıqlama -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">API İnteqrasiya</h1>
            <p class="text-sm text-gray-500 mt-1">Digər sistemlərinizi bu mərkəzə bağlamaq üçün lazımi məlumatlar.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                API Xidməti Aktivdir
            </span>
        </div>
    </div>

    <!-- API Məlumatları Kartı -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fa-solid fa-key text-blue-500 mr-2"></i>
                Qoşulma Məlumatları
            </h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- API Endpoint -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">API Endpoint (URL)</label>
                <div class="flex rounded-md shadow-sm">
                    <!-- Düzəliş: Dinamik URL -->
                    <input type="text" readonly value="{{ url('/api/v1/check') }}" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-gray-500 sm:text-sm focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" onclick="alert('Kopyalandı!')" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-400">Müştəri tərəf bu ünvana sorğu göndərəcək.</p>
            </div>

            <!-- API Key -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sistem API Key</label>
                <div class="flex rounded-md shadow-sm">
                    <div class="relative flex-grow focus-within:z-10">
                        <input type="text" readonly value="rj_live_982348729384729384" class="block w-full px-3 py-2 rounded-l-md border border-gray-300 bg-gray-50 font-mono text-gray-600 sm:text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="button" class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 bg-gray-100 text-sm font-medium text-gray-600 hover:bg-gray-200">
                        <i class="fa-solid fa-arrows-rotate mr-2"></i> Yenilə
                    </button>
                    <button type="button" onclick="alert('Kopyalandı!')" class="-ml-px relative inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
                <p class="mt-1 text-xs text-red-400">Bu açarı gizli saxlayın!</p>
            </div>

        </div>
    </div>

    <!-- İnteqrasiya Nümunəsi -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fa-solid fa-code text-purple-500 mr-2"></i>
                Müştəri Tərəfi (Client-Side) İnteqrasiya Kodu
            </h3>
            <span class="text-xs font-mono bg-purple-100 text-purple-700 px-2 py-1 rounded">PHP / Laravel</span>
        </div>

        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">
                Aşağıdakı kodu müştəri saytının <code class="bg-gray-100 px-1 py-0.5 rounded text-red-500">index.php</code> və ya əsas controller faylına əlavə edin. Bu kod avtomatik olaraq versiyanı yoxlayacaq və bildirişləri göstərəcək.
            </p>

            <div class="relative group">
                <div class="absolute right-4 top-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-3 py-1 rounded text-sm backdrop-blur-sm">
                        <i class="fa-regular fa-copy mr-1"></i> Kodu Kopyala
                    </button>
                </div>
<pre class="bg-slate-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm font-mono leading-relaxed">
<span class="text-pink-400">&lt;?php</span>

<span class="text-gray-400">// --- RJ Updater Client Code ---</span>

<span class="text-gray-400">// Düzəliş: Dinamik URL avtomatik buraya düşəcək</span>
<span class="text-blue-300">$serverUrl</span> = <span class="text-green-300">"{{ url('/api/v1/check') }}"</span>;
<span class="text-blue-300">$apiKey</span>    = <span class="text-green-300">"rj_live_982348729384729384"</span>;
<span class="text-blue-300">$currentVer</span> = <span class="text-green-300">"1.0.0"</span>; <span class="text-gray-400">// Saytın hazırki versiyası</span>

<span class="text-gray-400">// Sorğu göndəririk</span>
<span class="text-blue-300">$ch</span> = <span class="text-yellow-300">curl_init</span>();
<span class="text-yellow-300">curl_setopt</span>(<span class="text-blue-300">$ch</span>, CURLOPT_URL, <span class="text-blue-300">$serverUrl</span>);
<span class="text-yellow-300">curl_setopt</span>(<span class="text-blue-300">$ch</span>, CURLOPT_POST, <span class="text-purple-300">true</span>);
<span class="text-yellow-300">curl_setopt</span>(<span class="text-blue-300">$ch</span>, CURLOPT_POSTFIELDS, [
    <span class="text-green-300">'api_key'</span> => <span class="text-blue-300">$apiKey</span>,
    <span class="text-green-300">'version'</span> => <span class="text-blue-300">$currentVer</span>,
    <span class="text-green-300">'domain'</span>  => <span class="text-blue-300">$_SERVER</span>[<span class="text-green-300">'HTTP_HOST'</span>]
]);
<span class="text-yellow-300">curl_setopt</span>(<span class="text-blue-300">$ch</span>, CURLOPT_RETURNTRANSFER, <span class="text-purple-300">true</span>);
<span class="text-blue-300">$response</span> = <span class="text-yellow-300">json_decode</span>(<span class="text-yellow-300">curl_exec</span>(<span class="text-blue-300">$ch</span>), <span class="text-purple-300">true</span>);
<span class="text-yellow-300">curl_close</span>(<span class="text-blue-300">$ch</span>);

<span class="text-gray-400">// Cavabı emal edirik</span>
<span class="text-purple-300">if</span> (<span class="text-blue-300">$response</span> && <span class="text-blue-300">$response</span>[<span class="text-green-300">'update_available'</span>]) {
    <span class="text-gray-400">// Yeni versiya var!</span>
    echo <span class="text-green-300">"&lt;div class='alert'&gt;Yeni versiya mövcuddur: "</span> . <span class="text-blue-300">$response</span>[<span class="text-green-300">'new_version'</span>] . <span class="text-green-300">"&lt;/div&gt;"</span>;
}

<span class="text-purple-300">if</span> (<span class="text-blue-300">$response</span> && !empty(<span class="text-blue-300">$response</span>[<span class="text-green-300">'notification'</span>])) {
    <span class="text-gray-400">// Admin bildirişi var!</span>
    echo <span class="text-green-300">"&lt;script&gt;alert('"</span> . <span class="text-blue-300">$response</span>[<span class="text-green-300">'notification'</span>][<span class="text-green-300">'message'</span>] . <span class="text-green-300">"');&lt;/script&gt;"</span>;
}
<span class="text-pink-400">?&gt;</span>
</pre>
            </div>
        </div>
    </div>
</div>
@endsection

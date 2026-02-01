@extends('admin.layout')

@section('content')
<div class="max-w-7xl mx-auto pb-20">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tərcümə Mərkəzi</h1>
            <p class="text-sm text-gray-500">Frontend üçün dil paketlərini (JSON Keys) buradan idarə edin.</p>
        </div>

        <!-- Yeni Key Button -->
        <button onclick="document.getElementById('newKeyModal').classList.remove('hidden')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Yeni Açar (Key)
        </button>
    </div>

    <!-- Mesajlar -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative flex items-center">
            <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded relative">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <!-- Axtarış -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
        <form action="{{ route('admin.translation') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative">
                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Açar sözü və ya tərcüməni axtar..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 outline-none">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                Axtar
            </button>
        </form>
    </div>

    <!-- Tərcümə Formu (Hamısını birdən yadda saxlamaq üçün) -->
    <form action="{{ route('admin.translation.update') }}" method="POST">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Açar (Key)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AZ (Azərbaycan)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">EN (English)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RU (Русский)</th>
                            <!-- Yeni TR sütunu -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TR (Türkçe)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-10">Sil</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($translations as $tr)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded select-all block truncate" title="{{ $tr->key }}">
                                    {{ $tr->key }}
                                </span>
                            </td>
                            <td class="px-6 py-2">
                                <textarea name="translations[{{ $tr->id }}][az]" rows="1" class="w-full text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 resize-none overflow-hidden" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ $tr->az }}</textarea>
                            </td>
                            <td class="px-6 py-2">
                                <textarea name="translations[{{ $tr->id }}][en]" rows="1" class="w-full text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 resize-none overflow-hidden" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ $tr->en }}</textarea>
                            </td>
                            <td class="px-6 py-2">
                                <textarea name="translations[{{ $tr->id }}][ru]" rows="1" class="w-full text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 resize-none overflow-hidden" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ $tr->ru }}</textarea>
                            </td>
                            <!-- TR Mətn Sahəsi -->
                            <td class="px-6 py-2">
                                <textarea name="translations[{{ $tr->id }}][tr]" rows="1" class="w-full text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 resize-none overflow-hidden" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ $tr->tr }}</textarea>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button type="button" onclick="deleteTranslation({{ $tr->id }})" class="text-red-400 hover:text-red-600">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-language text-3xl mb-3 opacity-50"></i>
                                <p>Hələ heç bir tərcümə əlavə edilməyib.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sticky Footer Action -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-lg z-10 flex justify-end md:pl-64">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-lg font-bold shadow-md transition-transform active:scale-95 flex items-center">
                <i class="fa-solid fa-save mr-2"></i> Dəyişiklikləri Yadda Saxla
            </button>
        </div>
    </form>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $translations->appends(['search' => request('search')])->links('pagination::tailwind') }}
    </div>

</div>

<!-- Modal: Yeni Key -->
<div id="newKeyModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 m-4 relative">
        <button onclick="document.getElementById('newKeyModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <i class="fa-solid fa-times text-lg"></i>
        </button>

        <h3 class="text-lg font-bold text-gray-800 mb-4">Yeni Açar (Key) Əlavə Et</h3>

        <form action="{{ route('admin.translation.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Key (Məs: home_title)</label>
                    <input type="text" name="key" required class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500 font-mono text-sm" placeholder="home_title">
                    <p class="text-xs text-gray-400 mt-1">Yalnız ingilis hərfləri və alt xətt (_)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">AZ Qarşılığı</label>
                    <input type="text" name="az" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">EN Qarşılığı</label>
                    <input type="text" name="en" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RU Qarşılığı</label>
                    <input type="text" name="ru" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500">
                </div>
                <!-- Yeni TR Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">TR Qarşılığı</label>
                    <input type="text" name="tr" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-indigo-500">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-medium mt-2">
                    Əlavə Et
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function deleteTranslation(id) {
        if(confirm('Bu açarı silmək istədiyinizə əminsiniz?')) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ url('admin/settings/translation') }}/" + id;
            form.submit();
        }
    }
</script>
@endsection

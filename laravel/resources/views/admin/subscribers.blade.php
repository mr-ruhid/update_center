@extends('admin.layout')

@section('content')
<div class="max-w-6xl mx-auto">

    <!-- Başlıq -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Abonəliklər</h1>
            <p class="text-sm text-gray-500">Saytınızdan qeydiyyatdan keçən izləyicilərin siyahısı.</p>
        </div>
        <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg text-sm font-medium border border-indigo-100 flex items-center">
            <i class="fa-solid fa-envelope-open-text mr-2"></i>
            Toplam: <span class="font-bold ml-1">{{ $totalSubscribers }}</span>
        </div>
    </div>

    <!-- Mesajlar -->
    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded relative">
            <i class="fa-solid fa-check mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Axtarış -->
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
        <form action="{{ route('admin.subscribers') }}" method="GET" class="flex gap-4">
            <div class="flex-1 relative">
                <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Email ünvanı axtar..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                Axtar
            </button>
            @if(request('search'))
                <a href="{{ route('admin.subscribers') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                    <i class="fa-solid fa-times mr-2"></i> Təmizlə
                </a>
            @endif
        </form>
    </div>

    <!-- Cədvəl -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        @if($subscribers->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Ünvanı</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qeydiyyat Tarixi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Əməliyyat</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($subscribers as $sub)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="fa-regular fa-envelope"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $sub->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-500 font-mono bg-gray-100 px-2 py-1 rounded">{{ $sub->ip_address ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $sub->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($sub->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aktiv
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Bloklanmış
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <form action="{{ route('admin.subscribers.delete', $sub->id) }}" method="POST" onsubmit="return confirm('Bu abonəni silmək istədiyinizə əminsiniz?');" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Sil">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $subscribers->appends(['search' => request('search')])->links('pagination::tailwind') }}
        </div>

        @else
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                    <i class="fa-regular fa-folder-open text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Məlumat tapılmadı</h3>
                <p class="text-gray-500 mt-1">Hələ heç kim abunə olmayıb və ya axtarışa uyğun nəticə yoxdur.</p>
            </div>
        @endif
    </div>
</div>
@endsection

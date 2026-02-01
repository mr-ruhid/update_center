<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doğrulama Kodu - RJ Site Updater</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center">

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">

        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4 animate-pulse">
            <i class="fa-solid fa-envelope-circle-check text-3xl"></i>
        </div>

        <h2 class="text-2xl font-bold text-gray-800">Təhlükəsizlik Kodu</h2>
        <p class="text-sm text-gray-500 mt-2">Emailinizə 6 rəqəmli kod göndərildi. Zəhmət olmasa daxil edin.</p>

        <!-- Uğurlu Mesaj (Kodu yenidən göndərəndə) -->
        @if(session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-600 px-4 py-2 rounded text-sm text-left">
                <i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Xəta Mesajları -->
        @if($errors->any())
            <div class="mt-4 bg-red-50 border border-red-200 text-red-600 px-4 py-2 rounded text-sm text-left">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('2fa.verify') }}" method="POST" class="mt-6 space-y-5">
            @csrf

            <div>
                <input type="text" name="code" required maxlength="6" autofocus
                       class="w-full text-center text-2xl tracking-[10px] font-bold pl-4 py-3 rounded-lg border border-gray-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all placeholder-gray-300"
                       placeholder="000000">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg shadow-sm transition-colors flex justify-center items-center">
                Təsdiqlə və Daxil Ol
            </button>
        </form>

        <div class="mt-6 border-t border-gray-100 pt-4 flex justify-between items-center text-sm">
            <a href="{{ route('login') }}" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-arrow-left mr-1"></i> Çıxış
            </a>

            <!-- Kodu Yenidən Göndər Düyməsi -->
            <form action="{{ route('2fa.resend') }}" method="POST">
                @csrf
                <button type="submit" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                    Kodu yenidən göndər
                </button>
            </form>
        </div>

    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş - RJ Site Updater</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg border border-gray-200 p-8">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4">
                <i class="fa-solid fa-rotate text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Admin Girişi</h2>
            <p class="text-sm text-gray-500 mt-1">Davam etmək üçün hesabınıza daxil olun.</p>
        </div>

        <!-- Xəta Mesajları -->
        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Ünvanı</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="admin@example.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Şifrə</label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-gray-400"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" required class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Məni xatırla</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg shadow-sm transition-colors flex justify-center items-center">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Daxil ol
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-400 border-t border-gray-100 pt-4">
            RJ Site Updater &copy; {{ date('Y') }}
        </div>

    </div>

</body>
</html>
